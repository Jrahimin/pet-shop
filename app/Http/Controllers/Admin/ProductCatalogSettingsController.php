<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\SettingType;
use App\Libraries\SettingsSingleton;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCatalogSettingsController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.settings_index');
    }

    public function getSettings()
    {
        $settings = SettingsSingleton::get(SettingType::$CATALOG);

        return response()->json($settings);
    }

    public function changeSettings(Request $request)
    {
        $rules = [
            'pickupdiscount'=>'required|numeric',
            'deliveryprice'=>'required|numeric',
            'nodelpricefrom'=>'required|numeric',
            'payondelprice'=>'required|numeric',
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
