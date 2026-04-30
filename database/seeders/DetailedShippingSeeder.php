<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\ShippingLocation;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\DB;

class DetailedShippingSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe existing shipping data
        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        ShippingLocation::truncate();
        ShippingRate::truncate();
        ShippingZone::truncate();
        
        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 1. Melbourne Metro
        $melbourneZone = ShippingZone::create(['name' => 'Melbourne Metro']);
        ShippingRate::create([
            'shipping_zone_id' => $melbourneZone->id,
            'name' => 'Melbourne Metro Delivery',
            'cost' => 9.50,
            'type' => 'flat_rate'
        ]);
        
        $melbournePostcodes = "3091, 3097, 3139, 3154, 3158-3160, 3211, 3213, 3221-3223, 3225-3228, 3230, 3240, 3321, 3328-3329, 3331-3338, 3340-3342, 3345, 3357-3358, 3427-3435, 3437-3438, 3440-3442, 3444, 3446-3447, 3458, 3522, 3658-3663, 3711-3712, 3714, 3717-3718, 3750, 3754-3764, 3766-3767, 3770, 3775, 3777-3779, 3781-3783, 3787-3788, 3791-3793, 3795-3797, 3799, 3806-3810, 3812-3816, 3818, 3820, 3831-3832, 3910-3913, 3915-3916, 3918-3923, 3925-3931, 3933-3934, 3936-3945, 3979-3981, 3984, 3987-3992, 3096*, 3099*, 3212*, 3214-3220*, 3224*, 3232-3234*, 3241*, 3350-3356*, 3363-3364*, 3370*, 3373*, 3448*, 3451*, 3460-3461*, 3523*, 3550*, 3664*, 3821*, 3833*, 3838*, 3870*, 3946*, 3977-3978*, 3995*";
        $this->addPostcodes($melbourneZone->id, $melbournePostcodes);

        // 2. Sydney Metro
        $sydneyZone = ShippingZone::create(['name' => 'Sydney Metro']);
        $this->addMetroRates($sydneyZone->id);
        $sydneyPostcodes = "1001, 1025, 1042, 1208-1209, 1215, 1225-1226, 1228-1231, 1235, 1240, 1300, 1335, 1350, 1355, 1360, 1363, 1401, 1405, 1420, 1430, 1435, 1440, 1445, 1450, 1455, 1460, 1465-1466, 1470, 1475, 1480-1481, 1484-1485, 1487, 1490, 1493, 1495, 1499, 1515, 1560, 1565, 1570, 1582, 1585, 1590, 1595, 1597, 1602, 1630, 1635, 1639-1640, 1655, 1658, 1660, 1670-1671, 1675, 1680, 1685, 1700-1701, 1710, 1715, 1730, 1740, 1750, 1755, 1765, 1781, 1790, 1800, 1805, 1811, 1825-1826, 1831, 1835, 1848, 1851, 1860, 1871, 1875, 1885, 1888, 1890-1891, 2000-2002, 2004, 2006-2012, 2015-2050, 2052, 2055, 2057-2077, 2079-2082, 2084-2097, 2099-2148, 2150-2168, 2170-2179, 2190-2200, 2203-2205, 2207-2214, 2216-2234, 2555-2559, 2564-2567, 2747-2748, 2750-2751, 2759-2764, 2766-2770, 2785, 2206*, 2560*, 2745*, 2752-2753*, 2765*";
        $this->addPostcodes($sydneyZone->id, $sydneyPostcodes);

        // 3. Brisbane
        $brisbaneZone = ShippingZone::create(['name' => 'Brisbane Metro']);
        $this->addMetroRates($brisbaneZone->id);
        $brisbanePostcodes = "4000-4015, 4017-4022, 4025, 4029-4032, 4034-4037, 4051-4055, 4059-4061, 4064-4070, 4072-4078, 4101-4124, 4128-4133, 4151-4161, 4163-4165, 4169, 4171-4174, 4177-4179, 4183, 4205, 4300-4301, 4303-4305, 4500-4502, 4508-4509, 9017, 9019, 9022, 9464, 9600, 4125*, 4127*, 4170*, 4207*, 4306*, 4314*, 4503*, 4520*";
        $this->addPostcodes($brisbaneZone->id, $brisbanePostcodes);

        // 4. Gold Coast
        $goldCoastZone = ShippingZone::create(['name' => 'Gold Coast']);
        $this->addMetroRates($goldCoastZone->id);
        $goldCoastPostcodes = "2485-2487*, 2490*, 4208-4231*, 9726*, 9729*";
        $this->addPostcodes($goldCoastZone->id, $goldCoastPostcodes);

        // 5. Sunshine Coast
        $sunshineCoastZone = ShippingZone::create(['name' => 'Sunshine Coast']);
        $this->addMetroRates($sunshineCoastZone->id);
        $sunshineCoastPostcodes = "4504-4505*, 4507*, 4510-4511*, 4517-4519*, 4550-4567*, 4572-4575*";
        $this->addPostcodes($sunshineCoastZone->id, $sunshineCoastPostcodes);

        // 6. Adelaide
        $adelaideZone = ShippingZone::create(['name' => 'Adelaide Metro']);
        $this->addMetroRates($adelaideZone->id);
        $adelaidePostcodes = "5000-5001, 5006-5025, 5031-5035, 5037-5052, 5061-5076, 5081-5094, 5096-5098, 5106-5117, 5121, 5127, 5131-5133, 5140, 5150, 5156, 5158-5161, 5163-5168, 5800, 5930, 5942, 5950, 5005*, 5095*, 5125-5126*, 5157*, 5162*";
        $this->addPostcodes($adelaideZone->id, $adelaidePostcodes);

        // 7. Perth
        $perthZone = ShippingZone::create(['name' => 'Perth Metro']);
        $this->addMetroRates($perthZone->id);
        $perthPostcodes = "6000-6001, 6003-6012, 6015-6027, 6029, 6032, 6036, 6050-6055, 6057-6068, 6077, 6079, 6090, 6100-6110, 6147-6162, 6800, 6831-6832, 6840-6846, 6848-6850, 6865, 6872, 6892, 6900-6902, 6904-6907, 6910-6924, 6926, 6929, 6931-6937, 6939, 6941-6947, 6951- 6957, 6959-6961, 6963-6964, 6970, 6979-6992, 6997, 6014*, 6056*, 6076*, 6111-6112*, 6163-6166*";
        $this->addPostcodes($perthZone->id, $perthPostcodes);

        // 8. Australia Wide (Default)
        $defaultZone = ShippingZone::create(['name' => 'Australia Wide']);
        ShippingLocation::create([
            'shipping_zone_id' => $defaultZone->id,
            'type' => 'country',
            'code' => 'AU'
        ]);
        ShippingRate::create([
            'shipping_zone_id' => $defaultZone->id,
            'name' => 'Standard Shipping',
            'cost' => 29.99,
            'type' => 'flat_rate'
        ]);
        ShippingRate::create([
            'shipping_zone_id' => $defaultZone->id,
            'name' => 'Standard Shipping (With Rice)',
            'cost' => 33.50,
            'type' => 'flat_rate'
        ]);
    }

    private function addMetroRates($zoneId)
    {
        ShippingRate::create([
            'shipping_zone_id' => $zoneId,
            'name' => 'Metro Delivery',
            'cost' => 29.99,
            'type' => 'flat_rate'
        ]);
        ShippingRate::create([
            'shipping_zone_id' => $zoneId,
            'name' => 'Metro Delivery (With Rice)',
            'cost' => 33.50,
            'type' => 'flat_rate'
        ]);
    }

    private function addPostcodes($zoneId, $postcodeString)
    {
        $codes = array_map('trim', explode(',', $postcodeString));
        foreach ($codes as $code) {
            if (empty($code)) continue;
            ShippingLocation::create([
                'shipping_zone_id' => $zoneId,
                'type' => 'postcode',
                'code' => $code
            ]);
        }
    }
}
