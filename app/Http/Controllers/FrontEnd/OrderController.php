<?php

namespace App\Http\Controllers\FrontEnd;

use App\Libraries\PaySera\WebToPay;
use App\Libraries\PaySera\WebToPayException;
use App\Model\Cart;
use App\Model\CatalogCategory;
use App\Model\Order;
use App\Model\Stock;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{

    public function getIfUserLoggedIn()
    {
        return response()->json(['loggedIn'=>Auth::check(),'user'=>Auth::user()->id]);
    }



    public function storeBuyerInfo(Request $request)
    {
        $rules = [
            'name'=>'required',
            'surname'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'city'=>'required',
            'address'=>'required',
            'zip_code'=>'required',
            'needvat'=>'required',

        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if(Auth::check())
        {
            $request['userid'] = Auth::user()->id;
        }
        else{
            $request['userid'] = null;
        }
        session(['cartData.buyer'=>$request->all()]);
        $buyer = session('cartData.buyer');
        //$buyerId = DB::table('orders')->insertGetId($request->all());
        return response()->json($buyer);
    }
    public function storeDeliveryInfo(Request $request)
    {
        $rules = [
            'delivery_city'=>'required',
            'delivery_address'=>'required',
            'delivery_zip_code'=>'required',
            'delivery_type'=>'required',
        ];
        if($request->delivery_type=="omniva")
        {
            $rules['pickupMethod'] = 'required';
        }
        if($request->delivery_type=="venipak")
        {
            $rules['payondel'] = 'required';
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if($request->delivery_type=='venipak')
        {
            $sum = (float)session('cartData.total');
            $request['pickupMethod']=null ;
            $request['terminal'] =null ;
            if($sum < 25)
            {
                $this->storeDeliveryPrice(1.99);
                // $sum = $sum + 1.99 ;

            }
            if($request['payondel']==1)
            {
                $payOnDeliveryPrice = 1.29 ;
                $request['payondel_price'] = $payOnDeliveryPrice;
                session(['cartData.payOndeliverPrice'=>$payOnDeliveryPrice]);

            }
            else{
                $request['payondel_price'] = null;
            }
            //session(['cartData.totalSum'=>$sum]);

        }
        elseif ($request->delivery_type=='shop')
        {
            $request['pickupMethod'] = null ;
            $request['terminal'] =null ;
            $request['payondel_price'] =null;
        }
        else{
            $request['payondel_price'] =null;
        }

        session(['cartData.delivery'=>$request->all()]);
        $delivery = session('cartData.delivery');
        return response()->json($delivery);
    }

    public function storeDeliveryPrice($price)
    {
        session(['cartData.deliverPrice'=>$price]);
        return response()->json(session('cartData.deliverPrice'));
    }



    public function getTotalSum()
    {

        $sum = session('cartData.total');

        $total = $sum ;
        if(session()->has('cartData.deliverPrice'))
        {
            $deliveryPrice = session('cartData.deliverPrice');
            $total += $deliveryPrice;
        }
        //dd($total);
        $delivery = session('cartData.delivery');

        if($delivery['delivery_type']=="omniva")
        {
            $charge = 2.99;
            $total +=$charge ;
        }

        if($delivery['delivery_type']=="shop")
        {
            $discount = ($total*3)/100;
            $total -=$discount ;
        }

        if($delivery['payondel']==1)
        {
            $payOnDelPrice = $delivery['payondel_price'];
            $total += $payOnDelPrice;
        }

        if(session()->has('cartData.codeDiscountAmount'))
        {
            $discount = session('cartData.codeDiscountAmount');
            $total -= $discount;
        }
        $total = number_format($total,2,'.','');

         session(['cartData.totalSum'=>$total]);
        $total = session('cartData.totalSum');
        return response()->json($total);
    }
    public function getBuyerInfo()
    {
       // $order = DB::table('orders')->where('id',$id)->select('name','surname','email','phone','address','city','zip_code')->first();
        $buyer = session('cartData.buyer');
        return response()->json($buyer);
    }


    public function getUserInfo($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function paymentRequest($orderId)
    {
        $webToPay = new WebToPay();

       // $total = session('cartData.totalSum');
        $order = Order::find($orderId);

        try {

            $request = $webToPay->redirectToPayment(array(
                'projectid'     => env('PAYSERA_PROJECT_ID'),
                'sign_password' => env('PAYSERA_PASSWORD'),
                'orderid'       => $orderId,
                'amount'        => $order->final_sum*100,
                'currency'      => 'EUR',
                'country'       => 'LT',
                'accepturl'     => route('accepted_payment'),
                'cancelurl'     => route('cancelled_payment'),
                'callbackurl'   => route('callback'),
                'test'          => 1,
            ));
            session()->forget('deliveryPrice');
            session()->forget('discountCode');
            session()->forget('store_pickup_discount');
        } catch (WebToPayException $e) {
            // handle exception
        }
    }

    public function acceptedPayment()
    {

        return view('frontend.paysera.accept');
    }

    public function cancelledPayment()
    {
        return view('frontend.paysera.cancel');
    }

    public function callBackPayment()
    {
        $webToPay = new WebToPay();
        try {
            $response = $webToPay->checkResponse($_GET, array(
                'projectid'     => 0,
                'sign_password' => 'd41d8cd98f00b204e9800998ecf8427e',
            ));

            $orderId = $response['orderid'];
            $amount = $response['amount'];
            $currency = $response['currency'];

            $order = Order::find($orderId);
            $order->final_sum = $amount;
            $order->paymentcurrency = $currency;
            $order->paymentbank = $response['payment'];
            $order->paymentstatus = $response['status'];
            $order->response = serialize($_GET);
            $order->paid = 1;
            $order->save();

          /*  if ($response['test'] !== '0') {
                throw new Exception('Testing, real payment was not made');
            }
            if ($response['type'] !== 'macro') {
                throw new Exception('Only macro payment callbacks are accepted');
            }
            echo 'OK';*/
        } catch (Exception $e) {
            echo get_class($e) . ': ' . $e->getMessage();
        }
    }

    public function saveOrder(Request $request)
    {

        $rules = [

            'buyer.name'=>'required',
            'buyer.surname'=>'required',
            'buyer.email'=>'required',
            'buyer.phone'=>'required',
            'buyer.city'=>'required',
            'buyer.address'=>'required',
            'buyer.zip_code'=>'required',
            'buyer.needvat'=>'required',
           /* 'buyer.company_title'=>'required',
            'buyer.company_code'=>'required',
            'buyer.company_vatcode'=>'required',*/
            'deliveryType'=>'required',
            'paymentMethod'=>'required',
            'total'=>'required',
            'totalPrice'=>'required',

        ];
        if($request->buyer['needvat'] == 1)
        {
            $rules['buyer.company_title'] = 'required' ;
            $rules['buyer.company_code'] = 'required' ;
            $rules['buyer.company_vatcode'] = 'required' ;

        }
        if($request->deliveryType == 'omniva')
        {
            $rules['terminal'] = 'required' ;
            $rules['pickupMethod'] = 'required' ;
        }

        $messages = [
            'buyer.name.required'=>__('buyer.name.required'),
            'buyer.surname.required'=>__('buyer.surname.required'),
            'buyer.email.required'=>__('buyer.email.required'),
            'buyer.phone.required'=> __('buyer.phone.required'),
            'buyer.city.required'=>__('buyer.city.required'),
            'buyer.address.required'=>__('buyer.address.required'),
            'buyer.zip_code.required'=>__('buyer.zip_code.required'),
            'buyer.needvat.required'=>__('buyer.needvat.required'),
            'buyer.company_title.required'=>__('buyer.company_title.required'),
            'buyer.company_code.required'=>__('buyer.company_code.required'),
            'buyer.company_vatcode.required'=>  __('buyer.company_vatcode.required'),
            'deliveryType.required'=> __('deliveryType.required'),
            'paymentMethod.required'=> __('paymentMethod.required'),
            'total.required'=> __('total.required'),
            'totalPrice.required'=> __('totalPrice.required'),
                   ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $buyer = $request->buyer;
        $date = time();
        if($request->paymentMethod == 'paysera')
        {
            $payondel = 0 ;
        }
        elseif($request->paymentMethod == 'payondel')
        {
            $payondel = 1 ;
        }
        else
        {
            $payondel = 1 ;
        }

        $payondel_price = $payondel >0 ? $request->total : null ;

        $delivery_price = session('deliveryPrice');

        $orderCode = md5($date.$this->generateCode(10));

        $orderId = DB::table('orders')
            ->insertGetId(['name'=>$buyer['name'],
                              'surname'=>$buyer['surname'],
                              'phone'=>$buyer['phone'],
                              'email'=>$buyer['email'],
                              'address'=>$buyer['address'],
                              'zip_code'=>$buyer['zip_code'],
                              'city'=>$buyer['city'],
                              'needvat'=>$buyer['needvat'],
                              'company_title'=>$buyer['company_title'],
                              'company_code'=>$buyer['company_code'],
                              'company_vatcode'=>$buyer['company_vatcode'],
                              'delivery_address'=>$buyer['address'],
                              'delivery_zip_code'=>$buyer['zip_code'],
                              'userid' => $buyer['userid'] ,
                              'delivery_city'=>$buyer['city'],
                              'delivery_type'=>$request->deliveryType,
                              'payondel'=>$payondel,
                              'pickup_method'=>$request->pickupMethod,
                              'delivery_price'=>$delivery_price,
                              'payondel_price'=>$payondel_price,
                              'terminal'=>$request->terminal,
                              'ordercode'=>$orderCode,
                              'items_sum'=>$request->totalPrice,
                              'final_sum'=>$request->total ,
                              'date'=>$date]);
       $totalWeight =  $this->saveOrderItems($orderId);

       DB::table('orders')->where('id',$orderId)->update(['totalweight'=>$totalWeight]);


        return response()->json($orderId);

    }

    public function saveOrderItems($orderId)
    {
        $sessionId = session()->getId();
        $cart = Cart::where('session_id',$sessionId)->first();
          if(!empty($cart))
          {
              $cartProducts = $cart->cartProducts ;
              $totalWeight = 0;
              $promotion = null ;

              if(session()->has('discountCode'))
              {

                  $promotion = session('discountCode') ;
              }

              foreach ($cartProducts as $cartProduct)
              {
                  $allDiscount = $this->getAllDiscounts($cartProduct) ;
                  $unitPrice = $cartProduct->package->kaina ;
                  $sum = $unitPrice*$cartProduct->quantity ;
                  $promotionDiscountedPrice = 0 ;

                  if($promotion != null)
                  {

                      if($promotion->all_product ==1 )
                      {
                          $applyDiscount = true ;
                          if($promotion->manufacturer_check==1)
                          {
                              $manufacturers = $promotion->manufacturers;
                              if($manufacturers->contains($cartProduct->product->manufacturer))
                                  $applyDiscount = false;
                          }

                          if($promotion->category_check==1 && $applyDiscount==true)
                          {
                              $promotionCategories = $promotion->categories ;
                              $productCategories = $cartProduct->product->categories ;
                              $catalog = new CatalogCategory();
                              foreach ($promotionCategories as $promotionCategory)
                              {
                                  foreach ($productCategories as $productCategory)
                                  {
                                      $child = $catalog->categoryMatch($promotionCategory->id, $productCategory->id) ;
                                      if($child)
                                          $applyDiscount =false ;
                                      if($applyDiscount == false)
                                          break 2;
                                  }

                              }
                          }
                          if($applyDiscount==true){
                              $promotionDiscountedPrice = $promotion->amount_percent != null ?
                                  ($sum - ($sum*$promotion->amount_percent)/100)
                                  :($sum - $promotion->amount_fixed) ;

                              $promotionDiscountedPrice = number_format($promotionDiscountedPrice,2,'.','') ;


                          }
                      }

                      else{

                          $promotionProducts = $promotion->products ;
                          if($promotionProducts->contains($cartProduct->product))
                          {
                              $promotionDiscountedPrice = $promotion->amount_percent != null ?
                                  ($sum - ($sum*$promotion->amount_percent)/100)
                                  :($sum - $promotion->amount_fixed) ;

                              $promotionDiscountedPrice = number_format($promotionDiscountedPrice,2,'.','') ;
                          }

                      }


                  }



                  if (count($allDiscount) > 0 && $allDiscount[0]!==false && count($allDiscount[0])>0)
                  {
                    
                      $prices = [];
                      foreach ($allDiscount as $itemDiscount)
                      {
                          if(gettype($itemDiscount)=='array')
                          {
                              foreach ($itemDiscount as $categoryDiscount)
                              {
                                  if($categoryDiscount==false) continue;
                                  if ($categoryDiscount->amount != null) // amount is percent discount
                                  {
                                      $price = $sum - ($sum * $categoryDiscount->amount) / 100;
                                      $price = number_format($price, '2', '.', '');
                                      $prices[] = $price;
                                  } else {
                                      $price = $sum - $categoryDiscount->amount_fixed;
                                      $price = number_format($price, '2', '.', '');
                                      $prices[] = $price;
                                  }
                              }
                          }
                          else{
                              if($itemDiscount==false) continue;
                              if ($itemDiscount->amount != null) // amount is percent discount
                              {
                                  $price = $sum - ($sum* $itemDiscount->amount) / 100;
                                  $price = number_format($price, '2', '.', '');
                                  $prices[] = $price;
                              } else {
                                  $price = $sum - $itemDiscount->amount_fixed;
                                  $price = number_format($price, '2', '.', '');
                                  $prices[] = $price;
                              }
                          }
                      }



                      $sum = min($prices);


                  }

                  if($promotionDiscountedPrice != 0)
                  {
                      $sum = $sum > $promotionDiscountedPrice ? $promotionDiscountedPrice :$sum ;
                  }

                  DB::table('order_items')->insert([
                     'order_id' => $orderId ,
                     'item_id'  => $cartProduct->product_id ,
                     'package' => $cartProduct->package_id ,
                     'color'      => 0 ,
                     'price'      => $unitPrice ,
                     'quantity'  => $cartProduct->quantity,
                     'sum'       =>$sum  ,
                  ]);

                /*  $productStock = Stock::where('product_id', $cartProduct->product_id)->where('package_id',$cartProduct->package_id)->first();
                  $updatedQuantity = $productStock->quantity - $cartProduct->quantity ;
                  $productStock->quantity = $updatedQuantity ;
                  $productStock->save();*/

                  $totalWeight += $cartProduct->package->svoris ;
              }

              $cart->cartProducts()->delete();
              $cart->delete();
              return $totalWeight ;
          }


    }


    private function getAllDiscounts($item)
    {
        $catalogCategory = new CatalogCategory();
        $discountCategoryTree = $catalogCategory->catagoryDiscountTree();
        $categoryTree = $discountCategoryTree;
        $date = time();
        //fetching discount for items starts here
        $allDiscount = [];
        $manufacturerDiscounts = $item->product->manufacturer->discounts()->where('datefrom', '<=', $date)->where('datetill', '>=', $date)->where('for_all_user', 1)->get();
        $productDiscounts = $item->package->discounts()->where('datefrom', '<=', $date)->where('datetill', '>=', $date)->where('for_all_user', 1)->get();
        $categoryDiscounts = [];
        $itemCategories = $item->product->categories;
        foreach ($itemCategories as $category) {
            $categoryDiscounts[] = $this->getCategoryDiscount($categoryTree, $category->id);
        }

        $allDiscount = array_merge($allDiscount,$categoryDiscounts);



        foreach ($manufacturerDiscounts as $manufacturerDiscount) {
            $allDiscount[] = $manufacturerDiscount;
        }

        foreach ($productDiscounts as $productDiscount) {
            $allDiscount[] = $productDiscount;
        }

        //discounts for logged in user
        if (Auth::check()) {
            $user = Auth::user();
            $userDiscounts = $user->discounts()->where('datefrom', '<=', $date)
                ->where('datetill', '>=', $date)
                ->where('for_all_user', 0)->get();
            $userCategoryDiscounts = [];

            foreach ($categoryDiscounts as $categoryDiscount)
            {
                if ($userDiscounts->contains($categoryDiscount))
                {
                    $userCategoryDiscounts[] = $categoryDiscount;
                }
            }


            foreach ($userDiscounts as $key => $userDiscount) {
                $manufacturers = $userDiscount->manufacturers;
                $products = $userDiscount->packages;


                if (!$manufacturers->isEmpty()) {
                    foreach ($manufacturers as $manufacturer) {
                        if ($manufacturer->id != $item->product->manufacturer->id) {
                            unset($userDiscounts[$key]);
                        }
                    }
                }

                if (!$products->isEmpty()) {

                    foreach ($products as $product) {
                        if ($product->id != $item->package->id) {
                            unset($userDiscounts[$key]);
                        }
                    }
                }

            }
            foreach ($userDiscounts as $userDiscount) {
                $allDiscount[] = $userDiscount;
            }
            /*dd($userDiscounts) ;*/


        }

        return $allDiscount ;

    }

    private function getCategoryDiscount($categoryTreeArr, $category,$discounts=[])
    {


        foreach( $categoryTreeArr as $aCategoryTree )
        {
            $newChildDiscount = array_merge( $discountArr = $aCategoryTree->catDiscount,  $discounts);

            if( $aCategoryTree->id==$category)
                return $newChildDiscount;
            if( $aCategoryTree->children && count( $aCategoryTree->children )>0 )
            {
                $retData = $this->getCategoryDiscount($aCategoryTree->children, $category, $newChildDiscount);
                if(!empty($retData))
                    return $retData;

            }
        }
        return false;
    }

    public function generateCode($length) {
        $str = 'QWERTYUPASDFGHJKLZXCVBNM'.
            'qwertyuipasdfghjkzxcvbnm'.
            '23456789'.
            '23456789';
        $kodas = "";
        for ($i=0; $i<$length; $i++) {
            $sk = rand (0, strlen($str)-1);
            $kodas .= $str[$sk];
        }
        return $kodas;
    }

    public function orderConfirmedIndex($orderId)
    {
        return view('frontend.order_confirmed_message',compact('orderId'));
    }


}
