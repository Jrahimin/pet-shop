<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageSizeController extends Controller
{
    public function index()
    {
        return view('admin.package_attribute.size');
    }

    public function addSize(Request $request)
    {
        $rules = [
            'name'=>'required',
            'details'=>'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $sizeId = DB::table('package_sizes')->insertGetId($request->all());

        if(!$sizeId)
            return response()->json(["success"=>false, "message"=>"data is not inserted"]);

        return response()->json(["success"=>true, "data"=>$sizeId]);
    }

    public function getSizes()
    {
        $sizes = DB::table('package_sizes')->paginate(20);
        return response()->json($sizes);
    }

    public function deleteSize($id)
    {
        DB::table('package_sizes')->where('id',$id)->delete() ;

    }

    public function editSize(Request $request)
    {
        $rules = [
            'name'=>'required',
            'details'=>'required'
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        DB::table('package_sizes')->where('id',$request->id)->update($request->all());

    }


    public function getSize($id)
    {
        $size = DB::table('package_sizes')->find($id);
        return response()->json($size) ;
    }
}
