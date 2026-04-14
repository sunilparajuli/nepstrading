<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateWordPressCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:wp-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate customers from legacy wp_users table to Laravel users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of WordPress customers...');

        $wpUsers = \DB::table('wp_users')->get();
        
        if ($wpUsers->isEmpty()) {
            $this->error('No users found in wp_users table.');
            return;
        }

        $bar = $this->output->createProgressBar(count($wpUsers));
        $count = 0;
        $skipped = 0;

        foreach ($wpUsers as $wpUser) {
            // Check if user already exists in Laravel
            if (\App\Models\User::where('email', $wpUser->user_email)->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Get First and Last Name from WP metadata
            $firstName = \DB::table('wp_usermeta')->where('user_id', $wpUser->ID)->where('meta_key', 'first_name')->value('meta_value');
            $lastName = \DB::table('wp_usermeta')->where('user_id', $wpUser->ID)->where('meta_key', 'last_name')->value('meta_value');
            
            $name = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));
            if (!$name) {
                $name = $wpUser->display_name ?: $wpUser->user_login;
            }

            // Get Role from WP metadata (wp_capabilities)
            $caps = \DB::table('wp_usermeta')->where('user_id', $wpUser->ID)->where('meta_key', 'wp_capabilities')->value('meta_value');
            $isAdmin = false;
            
            if ($caps) {
                $unserialized = @unserialize($caps);
                if (is_array($unserialized) && isset($unserialized['administrator'])) {
                    $isAdmin = true;
                }
            }

            \DB::table('users')->insert([
                'name' => $name,
                'email' => $wpUser->user_email,
                'password' => $wpUser->user_pass, // WP Hash
                'is_admin' => $isAdmin,
                'created_at' => $wpUser->user_registered,
                'updated_at' => $wpUser->user_registered,
            ]);

            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migration completed! $count customers migrated, $skipped skipped (duplicates).");
        $this->warn('NOTE: WordPress passwords migrated as-is. Users may need to reset passwords due to hashing differences.');
    }
}
