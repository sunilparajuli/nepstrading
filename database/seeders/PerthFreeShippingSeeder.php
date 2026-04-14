<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\ShippingLocation;
use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class PerthFreeShippingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Zone
        $zone = ShippingZone::updateOrCreate(
            ['name' => 'Perth Free Delivery'],
            ['is_enabled' => true]
        );

        // 2. Add the Free Shipping Rate
        ShippingRate::updateOrCreate(
            ['shipping_zone_id' => $zone->id, 'name' => 'Free Delivery'],
            ['cost' => 0.00, 'type' => 'free_shipping']
        );

        // 3. Add the Postcodes
        $postcodes = [
            6000, 6001, 6003, 6004, 6005, 6006, 6007, 6008, 6009, 6010, 6011, 6012, 6014, 6015, 6016, 6017, 
            6018, 6019, 6020, 6021, 6022, 6023, 6024, 6025, 6026, 6027, 6029, 6032, 6036, 6050, 6051, 6052, 
            6053, 6054, 6055, 6056, 6057, 6058, 6059, 6060, 6061, 6062, 6063, 6064, 6065, 6066, 6067, 6068, 
            6076, 6077, 6079, 6090, 6100, 6101, 6102, 6103, 6104, 6105, 6106, 6107, 6108, 6109, 6110, 6111, 
            6112, 6147, 6148, 6149, 6150, 6151, 6152, 6153, 6154, 6155, 6156, 6157, 6158, 6159, 6160, 6161, 
            6162, 6163, 6164, 6165, 6166, 6800, 6831, 6832, 6840, 6841, 6842, 6843, 6844, 6845, 6846, 6848, 
            6849, 6850, 6865, 6872, 6892, 6900, 6901, 6902, 6904, 6905, 6906, 6907, 6910, 6911, 6912, 6913, 
            6914, 6915, 6916, 6917, 6918, 6919, 6920, 6921, 6922, 6923, 6924, 6926, 6929, 6931, 6932, 6933, 
            6934, 6935, 6936, 6937, 6939, 6941, 6942, 6943, 6944, 6945, 6946, 6947, 6951, 6952, 6953, 6954, 
            6955, 6956, 6957, 6959, 6960, 6961, 6963, 6964, 6970, 6979, 6980, 6981, 6982, 6983, 6984, 6985, 
            6986, 6987, 6988, 6989, 6990, 6991, 6992, 6997
        ];

        foreach ($postcodes as $code) {
            ShippingLocation::updateOrCreate(
                ['shipping_zone_id' => $zone->id, 'type' => 'postcode', 'code' => (string)$code]
            );
        }
    }
}
