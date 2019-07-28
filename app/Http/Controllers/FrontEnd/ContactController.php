<?php

namespace App\Http\Controllers\FrontEnd;

use App\Libraries\DPDFunctions;
use App\Model\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function getContacts()
    {
        $contacts = Contact::where('active', 1)->orderBy('pozicija')->get();

        $baseDir = asset('storage/images').'/kontaktai/s1_';

        foreach ($contacts as $contact)
        {
            $contact->mapImage = $baseDir.$contact->img2;
            $contact->contactImage = $baseDir.$contact->img;
            $contact->work_hours = nl2br($contact->work_hours);
            $contact->rekvizitai = nl2br($contact->rekvizitai);

            //for mail object
            $contact->name = "";
            $contact->mail_from = "";
            $contact->contact_no = "";
            $contact->comment = "";
        }

        return response()->json($contacts);
    }

    public function sendMail(Request $request)
    {
        $rules = [
            'name'=>'required',
            'mail_from'=>'required|email',
            'comment'=>'required',
            'contact_no'=>'required',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $request['name'] = htmlspecialchars($request->name, ENT_QUOTES);
        $request['contact_no'] = htmlspecialchars($request->contact_no, ENT_QUOTES);
        $request['comment'] = nl2br(htmlspecialchars($request->comment, ENT_QUOTES));

        $infoAll = $request->all();

        Mail::send('frontend.mail.contact', ['content' => $infoAll], function ($message) use($infoAll) {
            $message->from($infoAll['mail_from']);
            $message->to("rahiminmyself@gmail.com"); // $infoAll['form_email'] will be replaced

            $message->subject('Gauta užklausa YziPet.lt');
        });

        return response()->json("mail sent");
    }

}
