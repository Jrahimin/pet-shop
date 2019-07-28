<?php

use App\Model\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedExtraItems = ['dpd_courier_price' => 1.99, 'omniva_courier_price' => 1.99,
            'free_shipping_from' => 25, 'store_pickup_discount' => 3, 'dpd_pay_on_delivery' => 1.29, 'stock_enable' => 0];

        foreach ($seedExtraItems as $key=>$value) {
            $module = "discounts";
            if ($key == 'stock_enable')
                $module = 'stock';

            $dbItem = Setting::where('module', 'discounts')->where('key', $key)->first();
            if ($dbItem)
                continue;

            Setting::create([
                'module' => $module,
                'key' => $key,
                'value' => $value,
            ]);

        }
    }
}
