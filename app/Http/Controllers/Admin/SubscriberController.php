<?php

namespace App\Http\Controllers\Admin;

use App\Model\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SubscriberController extends Controller
{
  public function index()
  {
      return view('admin.subscribers.index');
  }

  public function getSubscribers()
  {
      $subscribers = Subscriber::orderBy('reg_date','desc')->paginate(20);
      foreach ($subscribers as $subscriber)
      {
          $subscriber->reg_date=$subscriber->reg_date>0?date('Y-m-d',$subscriber->reg_date):"-";
      }
      return response()->json($subscribers);
  }

  public function saveSubscriber(Request $request)
  {
      $rules = [
          'email' => 'required|email',
      ];

      $validation = Validator::make($request->all(), $rules);
      if ($validation->fails()) {
          return response()->json(["success" => false,
              "message" => $validation->errors()->all()], 200);
      }

      $request['reg_date'] = time();
      $request['act'] = 1;
      $request['lang'] = "lt";
      $request['code'] = "-";
      $subscriber = Subscriber::create($request->all());

      return response()->json($subscriber);
  }

  public function deleteSubscriber($id)
  {
      $subscriber = Subscriber::find($id);
      $subscriber->delete();
  }

  public function getSubscriber($id)
  {
      $subscriber = Subscriber::find($id);
      return response()->json($subscriber);
  }
  public function editSubscriber(Request $request)
  {
      $rules = [
          'email' => 'required|email',
      ];

      $validation = Validator::make($request->all(), $rules);
      if ($validation->fails()) {
          return response()->json(["success" => false,
              "message" => $validation->errors()->all()], 200);
      }

      $subscriber = Subscriber::find($request->id);
      $subscriber->email = $request->email;
      $subscriber->save();
  }

  public function exportSubscriber()
  {
      $subscribers = Subscriber::orderBy('reg_date','desc')->get();
      foreach ($subscribers as $subscriber)
      {
          $subscriber->reg_date=$subscriber->reg_date>0?date('Y-m-d',$subscriber->reg_date):"-";
      }

      $data=array();
      foreach ($subscribers as $subscriber)
      {
          $data[] = array(
              $subscriber->reg_date,
              $subscriber->email,

          );
      }
      $currentDate = date("Y-m-d");
      Excel::create($currentDate, function($excel) use($data){
          $excel->sheet('raw_report', function($sheet) use($data){
              $sheet->fromArray($data);
              $sheet->row(1, array(
                  'Date',
                  'Email'
              ));
          });
      })->download('csv');
  }

}
