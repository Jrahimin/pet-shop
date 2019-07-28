<?php

namespace App\Http\Controllers\FrontEnd;

use App\Libraries\Functions;
use App\Model\Cart;
use App\Model\CartProduct;
use App\Model\CatalogCategory;
use App\Model\Discount;
use App\Model\Promotion;
use App\Model\Setting;
use App\Model\Stock;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cartIndex()
    {
        return view('frontend.cart.cart_index');
    }

    public function getCartItem()
    {
        $sessionId = session()->getId();
        $cart = Cart::where('session_id',$sessionId)->first();
        if(!empty($cart)) {
            $cartItems = $cart->cartProducts;
            $total = 0;
            $totalWeight = 0;
            $totalDiscount = 0;
            $storeDiscountedPrice = 0;
            $promotion = null ;
            if(session()->has('discountCode'))
            {

                $promotion = session('discountCode') ;
            }


            foreach ($cartItems as $item)
            {

                $item->title = $item->product->pavadinimas_lt;
                $item->manufacturer = $item->product->manufacturer->title;
                $item->image = url('/') . "/storage/images/katalogas/s3_" . $item->product->foto;
                $item->packageTitle = $item->package->pavadinimas;


                $item->unitPrice = $item->package->kaina;
                $item->itemSum = $item->unitPrice * $item->quantity;
                $item->itemSum = number_format($item->itemSum, 2, '.', '');
                $item->dicountedPrice = 0;
                $promotionDiscountedPrice = 0 ;
                if($promotion != null)
                {

                    if($promotion->all_product ==1 )
                    {
                        $applyDiscount = true ;
                        if($promotion->manufacturer_check==1)
                        {
                          $manufacturers = $promotion->manufacturers;
                          if($manufacturers->contains($item->product->manufacturer))
                              $applyDiscount = false;
                        }

                        if($promotion->category_check==1 && $applyDiscount==true)
                        {
                           $promotionCategories = $promotion->categories ;
                           $productCategories = $item->product->categories ;
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
                                                 ($item->itemSum - ($item->itemSum*$promotion->amount_percent)/100)
                                                 :($item->itemSum - $promotion->amount_fixed) ;

                            $promotionDiscountedPrice = number_format($promotionDiscountedPrice,2,'.','') ;


                        }
                    }

                    else{

                        $promotionProducts = $promotion->products ;
                        if($promotionProducts->contains($item->product))
                        {
                            $promotionDiscountedPrice = $promotion->amount_percent != null ?
                                ($item->itemSum - ($item->itemSum*$promotion->amount_percent)/100)
                                :($item->itemSum - $promotion->amount_fixed) ;

                            $promotionDiscountedPrice = number_format($promotionDiscountedPrice,2,'.','') ;
                        }

                    }


                }

                $allDiscount = $this->getAllDiscounts($item) ;


                // calculation for discounted price if any discounts
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
                                    $price = $item->itemSum - ($item->itemSum * $categoryDiscount->amount) / 100;
                                    $price = number_format($price, '2', '.', '');
                                    $prices[] = $price;
                                } else {
                                    $price = $item->itemSum - $categoryDiscount->amount_fixed;
                                    $price = number_format($price, '2', '.', '');
                                    $prices[] = $price;
                                }
                            }
                        }
                        else{
                            if($itemDiscount==false) continue;
                            if ($itemDiscount->amount != null) // amount is percent discount
                            {
                                $price = $item->itemSum - ($item->itemSum * $itemDiscount->amount) / 100;
                                $price = number_format($price, '2', '.', '');
                                $prices[] = $price;
                            } else {
                                $price = $item->itemSum - $itemDiscount->amount_fixed;
                                $price = number_format($price, '2', '.', '');
                                $prices[] = $price;
                            }
                        }
                    }



                    if(count($prices)>0)
                      $item->dicountedPrice = min($prices);

                    if($promotion != null && $promotionDiscountedPrice!=0)
                    {
                        $item->dicountedPrice = $item->dicountedPrice> $promotionDiscountedPrice ? $promotionDiscountedPrice : $item->dicountedPrice ;
                    }

                    $totalDiscount += ($item->itemSum - $item->dicountedPrice);

                }

                else{
                    if($promotion != null && $promotionDiscountedPrice!= 0)
                    {
                        $item->dicountedPrice =  $promotionDiscountedPrice  ;

                        $totalDiscount += ($item->itemSum - $item->dicountedPrice);


                    }
                }

                $total += $item->itemSum;

                $item->weight = $item->package->svoris;
                $totalWeight += $item->weight;
            }


            $totalDiscount = number_format($totalDiscount, 2, '.', '');
            $total = number_format($total,2,'.','');

            return response()->json(['items'=>$cartItems,'total'=>$total,'totalWeight'=>$totalWeight,'totalDiscount'=>$totalDiscount,'storeDiscountedPrice'=>$storeDiscountedPrice]);

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


    public function changeItemQuantity(Request $request)
    {
        $checkStock = Setting::where('module','stock')->where('key','stock_enable')->first()->value ;
        if($checkStock == '1')
        {
            $cartProduct = CartProduct::where('id',$request->id)->first();
            $currentTime = date('h:i:s',time());
            $productStock = Stock::where('product_id',$cartProduct->product_id)->where('package_id',$cartProduct->package_id)->first();
            $existingCartProducts = CartProduct::where('package_id',$request->package)->get();
            $sumOfExistingCarts = 0;
            foreach ($existingCartProducts as $existingCartProduct)
            {
                $existingCart = $existingCartProduct->cart ;
                $timeDifference = (strtotime($currentTime) - strtotime($existingCart->last_active))/60 ;
                if($timeDifference<30)
                    $sumOfExistingCarts += $existingCartProduct->quantity;
            }


            $finalQuantity = $productStock->quantity - $sumOfExistingCarts ;
            if($productStock!=null && $productStock->quantity!=null && $finalQuantity >= $request->quantity)
            {
                $sessionId = session()->getId();
                $cart = Cart::where('session_id',$sessionId)->first() ;
                $cart->update(['last_active'=>$currentTime]);
                $cart->cartProducts()->where('id',$request->id)->update(['quantity'=>$request->quantity]);
            }
            else{
                return response()->json(['message'=>"Not enough items in the stock"]);
            }
        }
        else{
            $sessionId = session()->getId();
            $cart = Cart::where('session_id',$sessionId)->first() ;
            $cart->update(['last_active'=>date('h:i:s',time())]);
            $cart->cartProducts()->where('id',$request->id)->update(['quantity'=>$request->quantity]);
        }



    }

    public function addItemQuantity(Request $request)
    {
        $checkStock = Setting::where('module','stock')->where('key','stock_enable')->first()->value ;
        if($checkStock == '1') {
            $currentTime = date('h:i:s', time());
            $productStock = Stock::where('product_id', $request->productId)->where('package_id', $request->package)->first();
            $existingCartProducts = CartProduct::where('package_id', $request->package)->get();
            $sumOfExistingCarts = 0;
            foreach ($existingCartProducts as $existingCartProduct) {
                $existingCart = $existingCartProduct->cart;
                $timeDifference = (strtotime($currentTime) - strtotime($existingCart->last_active)) / 60;
                if ($timeDifference < 30)
                    $sumOfExistingCarts += $existingCartProduct->quantity;
            }


            $finalQuantity = $productStock->quantity - $sumOfExistingCarts;
            if ($productStock != null && $productStock->quantity != null ) {

                $sessionId = session()->getId();
                $cart = Cart::where('session_id',$sessionId)->first() ;
                $cart->update(['last_active'=>$currentTime]);
                $cartProduct =$cart->cartProducts()->where('product_id',$request->productId)->where('package_id',$request->package)->first();
                $oldQuantity = $cartProduct->quantity ;
                $newQuantity = $oldQuantity + $request->quantity ;
                if($finalQuantity >= $newQuantity)
                {
                    $cartProduct->quantity = $newQuantity ;
                    $cartProduct->save() ;
                }
                else{
                    return response()->json(['message'=>"Not enough items in the stock"]);
                }

            }
        }
        else{
            $sessionId = session()->getId();
            $cart = Cart::where('session_id',$sessionId)->first() ;
            $cart->update(['last_active'=>date('h:i:s',time())]);
            $cartProduct =$cart->cartProducts()->where('product_id',$request->productId)->where('package_id',$request->package)->first();
            $oldQuantity = $cartProduct->quantity ;
            $newQuantity = $oldQuantity + $request->quantity ;
            $cartProduct->quantity = $newQuantity ;
            $cartProduct->save() ;
        }


    }

    public function checkIfCartProductExists(Request $request)
    {
        $sessionId = session()->getId();
        $cart = Cart::where('session_id',$sessionId)->first() ;
        if(!empty($cart))
        {
            $cartProduct = $cart->cartProducts()->where('product_id',$request->productId)->where('package_id',$request->package)->first();

            if(empty($cartProduct))
                return response()->json(false);
            else
                return response()->json(true);
        }
        else{
            return response()->json(false);
        }

    }


    public function getCartItemNumber()
    {
        $sessionId =session()->getId();
        $cart = Cart::where('session_id', $sessionId)->first() ;
        if(!empty($cart))

        {
            $cartProducts = $cart->cartProducts;
            $productCount = 0 ;
            foreach ($cartProducts as $cartProduct)
            {
                $productCount += $cartProduct->quantity ;
            }
        }

        else $productCount = 0 ;
        return response()->json($productCount);
    }
    public function addToCart(Request $request)
    {

        $checkStock = Setting::where('module','stock')->where('key','stock_enable')->first()->value ;
        if($checkStock == '1')
        {
            $currentTime = date('h:i:s',time());
            $productStock = Stock::where('product_id',$request->productId)->where('package_id',$request->package)->first();
            $existingCartProducts = CartProduct::where('package_id',$request->package)->get();
            $sumOfExistingCarts = 0;
            foreach ($existingCartProducts as $existingCartProduct)
            {
                $existingCart = $existingCartProduct->cart ;
                $timeDifference = (strtotime($currentTime) - strtotime($existingCart->last_active))/60 ;
                if($timeDifference<30)
                  $sumOfExistingCarts += $existingCartProduct->quantity;
            }


            $finalQuantity = $productStock->quantity - $sumOfExistingCarts ;
            if($productStock!=null && $productStock->quantity!=null && $finalQuantity >= $request->quantity)
            {
                $sessionId =session()->getId();
                $cart = Cart::where('session_id',$sessionId)->first();
                if(empty($cart))
                {
                    $cart = Cart::create([
                        'ip_address'=>$request->ip(),
                        'session_id'=>$sessionId,
                        'last_active'=>$currentTime]) ;
                }
                else{
                    $cart->update(['last_active'=>$currentTime]);
                }

                CartProduct::create([
                    'product_id'=>$request->productId,
                    'package_id'=>$request->package,
                    'quantity' => $request->quantity,
                    'cart_id'  => $cart->id
                ]);
            }

            else{
                return response()->json(['message'=>"Not enough items in the stock"]);
            }


        }
        else{

            $sessionId =session()->getId();
            $cart = Cart::where('session_id',$sessionId)->first();
            if(empty($cart))
            {
                $cart = Cart::create([
                    'ip_address'=>$request->ip(),
                    'session_id'=>$sessionId,
                    'last_active'=>date('h:i:s',time())]) ;
            }
            else{
                $cart->update(['last_active'=>date('h:i:s',time())]);
            }

            CartProduct::create([
                'product_id'=>$request->productId,
                'package_id'=>$request->package,
                'quantity' => $request->quantity,
                'cart_id'  => $cart->id
            ]);

        }


    }

    public function removeFromCart(Request $request)
    {

        $sessionId =session()->getId();
        $cart = Cart::where('session_id',$sessionId)->first() ;
        $cart->update(['last_active'=>date('h:i:s',time())]);
        if($request->has('productId'))
          {
                CartProduct::where('cart_id',$cart->id)->where('product_id',$request->productId)->delete() ;
          }
       else
          {

               CartProduct::where('id',$request->id)->delete();
          }





    }

    public function applyDiscountCode(Request $request)
    {

      $date = time();
      $promotion = Promotion::where('code',$request->code)->where('datefrom','<=',$date)->where('datetill','>=',$date)->first();

      if(!empty($promotion))
      {
        session(['discountCode'=>$promotion]);
      }
      else{
          return response()->json(['message'=>'Invalid or expired code']);
      }

    }


    public function getCodeDiscountAmount()
    {
        return response()->json(session('cartData.codeDiscountAmount'));
    }

    public function getDeliveryPrice(Request $request)
    {

        $total = $request->total ;
        if($request->deliveryType=='venipak')
        {
            $dpdShippingFee = Setting::where('key','dpd_courier_price')->first()->value ;
            $freeShiiping = Setting::where('key','free_shipping_from')->first()->value ;

            $deliveryPrice = $total>$freeShiiping ? 0 : $dpdShippingFee ;
            if($request->paymentMethod != null && $request->paymentMethod == 'payondel')
            {
                $dpdPayOnDelPrice = Setting::where('key','dpd_pay_on_delivery')->first()->value ;
                $deliveryPrice += $dpdPayOnDelPrice ;
                $deliveryPrice = number_format($deliveryPrice,2,'.','');
            }

        }

        elseif ($request->deliveryType =="omniva")
        {
            $omnivaDeliveryPrice = Setting::where('key','omniva_courier_price')->first()->value ;
            $deliveryPrice = $omnivaDeliveryPrice ;
        }

        else{
            $deliveryPrice = 0 ;

        }

        session(['deliveryPrice'=>$deliveryPrice]);
        return response()->json($deliveryPrice) ;
    }

    public function getPickupDiscount()
    {
        $pickupDiscount = Setting::where('key','store_pickup_discount')->first()->value ;
        return response()->json($pickupDiscount) ;
    }


    public function getOmnivaTerminals()
    {
        $terminals_json_file_dir = public_path() .'/'. "locations.json";
        $terminals_file = fopen($terminals_json_file_dir, "r");
        $allTerminals = fread($terminals_file, filesize($terminals_json_file_dir) + 10);
        fclose($terminals_file);
        $allTerminals = json_decode($allTerminals, true);
        $terminals = array();
        foreach ($allTerminals as $terminal)
        {
            if($terminal['TYPE']==0 && $terminal['A0_NAME']=='LT')
            {
                $terminals[] = $terminal;
            }
        }
        return response()->json($terminals);
    }

    public function getCourierPrices()
    {
        $couriePrices['omniva_courier_price'] = Setting::where('key','omniva_courier_price')->first()->value ;
        $couriePrices['dpd_courier_price'] = Setting::where('key','dpd_courier_price')->first()->value ;
        $couriePrices['free_shipping_from'] = Setting::where('key','free_shipping_from')->first()->value ;
        $couriePrices['store_picup_discount'] = Setting::where('key','store_pickup_discount')->first()->value ;
        $couriePrices['dpd_pay_on_delivery'] = Setting::where('key','dpd_pay_on_delivery')->first()->value ;


        return response()->json($couriePrices);

    }






}
