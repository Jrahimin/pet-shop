<?php
namespace App\Libraries;
use App\Enumerations\SettingType;
use App\Model\Setting;

class SettingsSingleton
{
    private static $settingsData;

    public static function get($settingType)
    {
        if (!isset(self::$settingsData)) {
            $items = Setting::all();

            if($settingType === SettingType::$MAIN)
            {
                $selectedItemList = array(
                    'emailas', 'uzsakymu_mailas', 'gimtadieniu_mailas', 'adresas',
                    'phone_cont', 'darbo_laikas', 'twitterlink', 'fblink',
                    'tit_youtube', 'meta_description_lt', 'meta_keywords_lt',
                    'dpd_courier_price', 'omniva_courier_price', 'free_shipping_from',
                    'store_pickup_discount', 'dpd_pay_on_delivery','stock_enable'
                );
            }
            else
                $selectedItemList = array('pickupdiscount', 'deliveryprice', 'nodelpricefrom', 'payondelprice');


            $settings = array();
            foreach($items as $item) {
                if(in_array($item->key, $selectedItemList))
                {
                    $settings[$item->key] = $item->value;
                }
            }

            self::$settingsData = $settings;
        }
        return self::$settingsData;
    }

    public static function set($key, $value)
    {
        $settings = Setting::where('key', $key)->first();
        $settings->key = $key;
        $settings->value = $value;
        $settings->save();
    }
}