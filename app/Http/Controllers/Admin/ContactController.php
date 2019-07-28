<?php

namespace App\Http\Controllers\Admin;

use App\Model\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ContactController extends Controller
{
    public function index()
    {
        return view('admin.contact.contact_index');
    }

    public function getContacts()
    {
        $contacts = Contact::orderBy('pozicija')->paginate(20);

        $baseDir = asset('storage/images').'/kontaktai/';

        return response()->json(["contacts"=>$contacts, "base_dir"=>$baseDir]);
    }

    public function addContactPost(Request $request)
    {
        if($request->buildingImage == "undefined")
            $request['buildingImage'] = null;
        if($request->mapImage == "undefined")
            $request['mapImage'] = null;

        $rules = [
            'buildingImage'=>'required|max:5120',
            'mapImage'=>'required|max:5120',
            'title'=>'required',
            'work_hours'=>'required',
            'adresas'=>'required',
            'telefonas'=>'required',
            'email'=>'required|email',
            'form_email'=>'required|email',
            'rekvizitai'=>'required',
            'active'=>'required|integer',
            'showform'=>'required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $maxPosition = Contact::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;

        $request['pozicija'] = $maxPosition + 1;

        //return response()->json(["success"=>true, "data"=>$request->all()]);

        //generating data for building image
        $buildingFile = $request->file('buildingImage');
        $buildingFileName = $buildingFile->getClientOriginalName();
        $newBuildingFileName=time()."_".str_random(4)."_".$buildingFileName;
        $path = public_path()."/storage/images/kontaktai/";
        $request['img'] = $newBuildingFileName;

        $slider1 = Image::make($buildingFile)->resize(325,205)->save($path."s1_".$newBuildingFileName);
        $slider2 = Image::make($buildingFile)->resize(800,600)->save($path."s2_".$newBuildingFileName);
        $slider3 = Image::make($buildingFile)->resize(150,100)->save($path."s3_".$newBuildingFileName);

        //generating data for map image
        $mapFile = $request->file('mapImage');
        $mapFileName = $mapFile->getClientOriginalName();
        $newMapFileName=time()."_".str_random(4)."_".$mapFileName;
        $path = public_path()."/storage/images/kontaktai/";
        $request['img2'] = $newMapFileName;

        $slider1 = Image::make($mapFile)->resize(325,205)->save($path."s1_".$newMapFileName);
        $slider2 = Image::make($mapFile)->resize(800,600)->save($path."s2_".$newMapFileName);
        $slider3 = Image::make($mapFile)->resize(150,100)->save($path."s3_".$newMapFileName);

        $contact = Contact::create($request->all());


        if(!$contact)
            return response()->json(["success"=>false, "message"=>"contact is not created"]);

        return response()->json(["success"=>true, "data"=>$contact]);
    }

    public function getContact($id)
    {
        $contact = Contact::find($id);

        $contact->show_building_image = url('/')."/storage/images/kontaktai/s3_".$contact->img;
        $contact->show_map_image = url('/')."/storage/images/kontaktai/s3_".$contact->img2;

        return response()->json($contact);
    }

    public function contactUp($id)
    {
        $contact = Contact::find($id);
        $oldPosition = $contact->pozicija;

        $belowContact = Contact::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowContact->pozicija;

        $contact->pozicija = $newPosition;
        $contact->save();

        $belowContact->pozicija = $oldPosition;
        $belowContact->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function contactDown($id)
    {
        $contact = Contact::find($id);
        $oldPosition = $contact->pozicija;

        $belowContact = Contact::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowContact->pozicija;

        $contact->pozicija = $newPosition;
        $contact->save();

        $belowContact->pozicija = $oldPosition;
        $belowContact->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function editContact(Request $request)
    {
        $rules = [
            'buildingImage'=>'required|max:5120',
            'mapImage'=>'required|max:5120',
            'title'=>'required',
            'work_hours'=>'required',
            'adresas'=>'required',
            'telefonas'=>'required',
            'email'=>'required|email',
            'form_email'=>'required|email',
            'rekvizitai'=>'required',
            'active'=>'required|integer',
            'showform'=>'required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $contact = Contact::find($request->id);

        if($request->remove_building_image)
        {
            $path=public_path()."/storage/images/kontaktai/";
            File::delete($path."s1_".$contact->img);
            File::delete($path."s2_".$contact->img);
            File::delete($path."s3_".$contact->img);

            $request['img'] = "";
        }

        if($request->remove_map_image)
        {
            $path=public_path()."/storage/images/kontaktai/";
            File::delete($path."s1_".$contact->img2);
            File::delete($path."s2_".$contact->img2);
            File::delete($path."s3_".$contact->img2);

            $request['img2'] = "";
        }

        if($request->hasFile('buildingImage'))
        {
            //generating data for building image
            $buildingFile = $request->file('buildingImage');
            $buildingFileName = $buildingFile->getClientOriginalName();
            $newBuildingFileName=time()."_".str_random(4)."_".$buildingFileName;
            $path = public_path()."/storage/images/kontaktai/";
            $request['img'] = $newBuildingFileName;

            $slider1 = Image::make($buildingFile)->resize(325,205)->save($path."s1_".$newBuildingFileName);
            $slider2 = Image::make($buildingFile)->resize(800,600)->save($path."s2_".$newBuildingFileName);
            $slider3 = Image::make($buildingFile)->resize(150,100)->save($path."s3_".$newBuildingFileName);
        }

        if($request->hasFile('mapImage'))
        {
            //generating data for map image
            $mapFile = $request->file('mapImage');
            $mapFileName = $mapFile->getClientOriginalName();
            $newMapFileName=time()."_".str_random(4)."_".$mapFileName;
            $path = public_path()."/storage/images/kontaktai/";
            $request['img2'] = $newMapFileName;

            $slider1 = Image::make($mapFile)->resize(325,205)->save($path."s1_".$newMapFileName);
            $slider2 = Image::make($mapFile)->resize(800,600)->save($path."s2_".$newMapFileName);
            $slider3 = Image::make($mapFile)->resize(150,100)->save($path."s3_".$newMapFileName);
        }

        $contactUpdated = $contact->update($request->all());

        if(!$contactUpdated)
            return response()->json(["success"=>false, "message"=>"contact is not updated"],200);
    }

    public function deleteContact($id)
    {
        $contact = Contact::find($id);

        $path=public_path()."/storage/images/kontaktai/";

        File::delete($path."s1_".$contact->img);
        File::delete($path."s2_".$contact->img);
        File::delete($path."s3_".$contact->img);

        File::delete($path."s1_".$contact->img2);
        File::delete($path."s2_".$contact->img2);
        File::delete($path."s3_".$contact->img2);

        $contact->delete();
    }
}
