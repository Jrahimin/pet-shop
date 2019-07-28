<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\SettingType;
use App\Libraries\SettingsSingleton;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.setting.setting_index');
    }

    public function getSettings()
    {
        $settings = SettingsSingleton::get(SettingType::$MAIN);

        return response()->json($settings);
    }

    public function changeSettings(Request $request)
    {
        $rules = [
            'emailas'=>'required|email',
            'uzsakymu_mailas'=>'required|email',
            'gimtadieniu_mailas'=>'required|email',
            'adresas'=>'required',
            'phone_cont'=>'required',
            'darbo_laikas'=>'required',
            'twitterlink'=>'required',
            'fblink'=>'required',
            'tit_youtube'=>'required',
            'meta_description_lt'=>'required',
            'meta_keywords_lt'=>'required',
            'dpd_courier_price' => 'required',
            'omniva_courier_price' => 'required',
            'free_shipping_from' => 'required',
            'store_pickup_discount' => 'required',
            'dpd_pay_on_delivery' => 'required',
            'stock_enable'  => 'required'
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $settingsChange = $request->except('_token');

        foreach($settingsChange as $key=>$value)
        {
            SettingsSingleton::set($key,$value);
        }
    }
}
