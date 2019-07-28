<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageColorController extends Controller
{
    public function index()
    {
        return view('admin.package_attribute.color');
    }

    public function addColor(Request $request)
    {
        $rules = [
            'name'=>'required',
            'hex_code'=>'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $colorId = DB::table('package_colors')->insertGetId($request->all());

        if(!$colorId)
            return response()->json(["success"=>false, "message"=>"data is not inserted"]);

        return response()->json(["success"=>true, "data"=>$colorId]);
    }


    public function getColors()
    {
        $colors = DB::table('package_colors')->paginate(20);

        return response()->json($colors) ;
    }

    public function deleteColors($id)
    {
        DB::table('package_colors')->where('id',$id)->delete();
    }

    public function editColor(Request $request)
    {

        $rules = [
            'name'=>'required',
            'hex_code'=>'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        DB::table('package_colors')->where('id',$request->id)->update($request->all());
    }

    public function getColor($id)
    {
        $color = DB::table('package_colors')->find($id) ;
        return response()->json($color) ;
    }
}
