@extends('frontend.layouts.master')

@section('content')
 <div id="homePage">
   <div class="container">
      <div class="row">
         <div class="col-sm-12 col-md-12 col-lg-12">


         Slider place

         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pb-5">
         <div class="col-sm-12 col-md-12 col-lg-12">

         </div>
      </div>
   </div>



   <div class="container">
      <div class="row pb-3">
         <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="d-flex justify-content-center">
               <div class="headliner1"></div>
                  <span class="head_product_title_text"><h3 class="head_product_text">Naujos prekės</h3></span>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pb-5">
         <div class="col-sm-3 col-md-3 col-lg-3">
            <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
            <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
            <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
            <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>


 <!--                     -->



    <div class="container">
       <div class="row pb-5">
         <div class="col-sm-3 col-md-3 col-lg-3">
             <div id="home_product_item_frame">
                <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                   <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                   <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                   <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
                </div>
                <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                   <span class="home_product_price">19.99</span>
                   <span class="home_product_currency">eur</span>
                </div>
                <div class="dropdown mb-2">
                  <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pasirinkimas
                  <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                  <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                    <a class="dropdown-item" href="#">Žalios</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Mėlynos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Geltonos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Raudonos</a>
                  </div>
                </div>
                <div class="d-flex">
                   <div class="prod_qty_field">
                      <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                   </div>
                   <div class="prod_qty_text mr-5">
                      vnt.
                   </div>
                   <div class="">
                      <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                   </div>
                </div>
             </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
             <div id="home_product_item_frame">
                <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                   <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                   <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                   <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
                </div>
                <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                   <span class="home_product_price">19.99</span>
                   <span class="home_product_currency">eur</span>
                </div>
                <div class="dropdown mb-2">
                  <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pasirinkimas
                  <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                  <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                    <a class="dropdown-item" href="#">Žalios</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Mėlynos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Geltonos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Raudonos</a>
                  </div>
                </div>
                <div class="d-flex">
                   <div class="prod_qty_field">
                      <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                   </div>
                   <div class="prod_qty_text mr-5">
                      vnt.
                   </div>
                   <div class="">
                      <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                   </div>
                </div>
             </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
             <div id="home_product_item_frame">
                <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                   <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                   <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                   <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
                </div>
                <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                   <span class="home_product_price">19.99</span>
                   <span class="home_product_currency">eur</span>
                </div>
                <div class="dropdown mb-2">
                  <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pasirinkimas
                  <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                  <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                    <a class="dropdown-item" href="#">Žalios</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Mėlynos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Geltonos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Raudonos</a>
                  </div>
                </div>
                <div class="d-flex">
                   <div class="prod_qty_field">
                      <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                   </div>
                   <div class="prod_qty_text mr-5">
                      vnt.
                   </div>
                   <div class="">
                      <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                   </div>
                </div>
             </div>
         </div>

         <div class="col-sm-3 col-md-3 col-lg-3">
             <div id="home_product_item_frame">
                <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                   <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                   <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
                </div>
                <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                   <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
                </div>
                <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                   <span class="home_product_price">19.99</span>
                   <span class="home_product_currency">eur</span>
                </div>
                <div class="dropdown mb-2">
                  <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pasirinkimas
                  <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                  <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                    <a class="dropdown-item" href="#">Žalios</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Mėlynos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Geltonos</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Raudonos</a>
                  </div>
                </div>
                <div class="d-flex">
                   <div class="prod_qty_field">
                      <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                   </div>
                   <div class="prod_qty_text mr-5">
                      vnt.
                   </div>
                   <div class="">
                      <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                   </div>
                </div>
             </div>
         </div>

       </div>
   </div>

   <div class="container">
      <div class="row pb-3">
         <div class="col">
            <div class="home_prd_category_divider">
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pb-3">
         <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="card">
               <a class="" href="index.php"><img class="card-img-top" src="{{ asset('images/promo_banner_img1.jpg') }}" alt="yzipet" /></a>
            </div>
         </div>

         <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="card">
               <a class="" href="index.php"><img class="card-img-top" src="{{ asset('images/promo_banner_img2.jpg') }}" alt="yzipet" /></a>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pb-3">
         <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="d-flex justify-content-center">
               <div class="headliner1"></div>
                  <span class="head_product_title_text"><h3 class="head_product_text">Perkamiausios prekės</h3></span>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pb-3">
        <div class="col-sm-3 col-md-3 col-lg-3">
           <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
           </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3">
           <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
           </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3">
           <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
           </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3">
           <div id="home_product_item_frame">
               <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                  <a class="home_product_item_img" href="index.php"><img class="img-fluid home_product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                  <a class="" href="index.php"><img class="img-fluid home_product_com_logo" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
               </div>
               <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                  <p class="home_product_title">Papildas katėms CHONDROCAT BIOSOL</p>
               </div>
               <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                  <span class="home_product_price">19.99</span>
                  <span class="home_product_currency">eur</span>
               </div>
               <div class="dropdown mb-2">
                 <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Pasirinkimas
                 <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                 <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                   <a class="dropdown-item" href="#">Žalios</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Mėlynos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Geltonos</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="#">Raudonos</a>
                 </div>
               </div>
               <div class="d-flex">
                  <div class="prod_qty_field">
                     <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                  </div>
                  <div class="prod_qty_text mr-5">
                     vnt.
                  </div>
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
           </div>
        </div>

      </div>
   </div>



   <div class="container">
      <div class="row pb-5">
         <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="d-flex flex-row justify-content-center">
              <a class="del_info_link" href="index.php"><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</a>
                  <div class="px-4"><hr class="vertical_line"/></div>
              <a class="del_info_link" href="index.php"><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</a>
            </div>
         </div>
      </div>
   </div>


   {{-- <div class="container">
      <div class="row">
         <div class="col-sm-12 col-md-12 col-lg-12 mb-3 text-center">

            <div class="d-inline-flex flex-row">
              <a href="index.php"><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</a>
            </div>

              <div class="d-inline-flex flex-row"><hr class="vertical_line"/></div>

            <div class="d-inline-flex flex-row">
              <a href="index.php"><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija</a>
            </div>

         </div>
      </div>
   </div> --}}

   <div class="container">
      <div class="row pb-3">
         <div class="col">
            <div class="home_prd_category_divider">
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row py-3">
         <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="d-flex flex-row justify-content-center">
               <h5 class="feature_text_center">Kodėl verta rinktis Yzipet</h5>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row py-3">
         <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="d-flex flex-row justify-content-center">
               <a href="index.php"><img class="img-fluid" src="{{ asset('images/trust_img_icon.png') }}" alt="yzipet" /></a>
            </div>
            <div class="py-3 text-center">
               <h3 class="advantages_text_title">Natūralumas ir patikimumas</h3>
            </div>
            <div class="text-center">
               <p class="advantages_text">
                  Atrinkome produktus, kurie padėtų Jūsų augintinio gyvenimo sąlygas priartinti prie jo protėvių gyvenimo natūralioje aplinkoje.
                  Šie produktai išbandyti ir įvertinti profesionalų bei gyvūnų augintojų įvairiose šalyse.
                  Daugelis turi tarptautinių ar šalies gamintojų apdovanojimų, YZIpet komanda ir jų draugai patys išbando ir naudoja visas Jums siūlomas prekes.
               </p>
            </div>
         </div>
         <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="d-flex flex-row justify-content-center">
               <a href="index.php"><img class="img-fluid" src="{{ asset('images/quality_img_icon.png') }}" alt="yzipet" /></a>
            </div>
            <div class="py-3 text-center">
               <h3 class="advantages_text_title">Nepriekaištinga kokybė</h3>
            </div>
            <div class="text-center">
               <p class="advantages_text">
                  Atrenkant produktus - tai yra vienas iš svarbiausių rodiklių. Suteikiame gamintojo nurodytos trukmės garantinį laikotarpį.
                  Nemėgstame vienadienių dalykų, tad ir Jums tokių nesiūlome!
               </p>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row py-3">
         <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="d-flex flex-row justify-content-center">
               <a href="index.php"><img class="img-fluid" src="{{ asset('images/friendship_img_icon.png') }}" alt="yzipet" /></a>
            </div>
            <div class="py-3 text-center">
               <h3 class="advantages_text_title">Draugiška gamtai</h3>
            </div>
            <div class="text-center">
               <p class="advantages_text">
                  Tai dar vienas iš svarbiausių atrankos kriterijų.
                  Pasirinkdami prekės gamintoją, prašome jo įrodymų, kad gamyboje naudojamos medžiagos nesukels neigiamų padarinių nei Jums, nei Jūsų augintiniui ar aplinkai.
                  Taip pat vertiname, ar saugus pakuotės dizainas bei pati pakuotė.
               </p>
            </div>
         </div>
         <div class="col-sm-6 col-md-6 col-lg-6" >
            <div class="d-flex flex-row justify-content-center">
               <a ><img class="img-fluid" src="{{ asset('images/technologies_img_icon.png') }}" alt="yzipet" /></a>
            </div>
            <div class="py-3 text-center">
               <h3 class="advantages_text_title">Patirties ir technologijų derinys</h3>
            </div>
            <div class="text-center">
               <p class="advantages_text">
                  Rinkdamiesi prekės ženklus remiamės geriausia gamybos praktika, ją organiškai sujungiant su šiuolaikinėmis technologijomis.
               </p>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="row pt-2">
         <div class="col-sm-12 col-md-12 col-lg-12 py-3">
            <div class="d-flex flex-row">
               <div class="w-50 section_div_line"></div>

               <div class="mx-3">
                  <img class="img-fluid" src="{{ asset('images/yzipet_section_divider.png') }}" alt="yzipet" />
               </div>

               <div class="w-50 section_div_line"></div>
            </div>
         </div>
      </div>
   </div>

   <div id="footer">

      <div class="container">
         <div class="row pb-2">
            <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
               <table class="table table-borderless">
                  <tbody>
                     <tr>
                        <td class="footer_table_th_frame">
                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/map_spot_icon.png') }}" alt="yzipet" /></a>
                        </td>
                        <td class="footer_table_th_frame">
                           <div class="">
                              <span class="footer_info_txt1">Yzipet</span> <span class="footer_info_txt2">Vilniuje:</span>
                           </div>
                           <div class="footer_info_txt3">Antakalnio g. 38 LT-10305</div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
               <table class="table table-borderless">
                  <tbody>
                     <tr>
                        <td class="footer_table_th_frame">
                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                        </td>
                        <td class="footer_table_th_frame">
                           <div class="footer_info_txt3">
                              I-IV 10:00-19:00,
                              <br>
                              V 10:00-18:00,
                              <br>
                              VI 10:00 - 16:00.
                              <br>
                              VII nedirbame.
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
               <table class="table table-borderless">
                  <tbody>
                     <tr>
                        <td class="footer_table_th_frame">
                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                        </td>
                        <td class="footer_table_th_frame">
                           <div class="footer_info_txt3">
                              +370 656 93284
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>

            <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
               <table class="table table-borderless">
                  <tbody>
                     <tr>
                        <td class="footer_table_th_frame">
                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                        </td>
                        <td class="footer_table_th_frame">
                           <div class="footer_info_txt3">
                              sales@yzipet.com
                           </div>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row py-3">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="footer_divider_line"><br></div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pt-1">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="text-center">Socializuokimės</div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pt-2">
            <div class="col-sm-12 col-md-12 col-lg-12 pb-4 text-center">
               <a href="https://www.instagram.com/yzipet/" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/instagram.png') }}" alt="yzipet" /></a>
               <a href="https://www.facebook.com/YZIpet" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/fb.png') }}" alt="yzipet" /></a>
            </div>
         </div>
      </div>

   </div>


   </div>













     <router-view></router-view>
 </div>
<div style="clear: both;"></div>
@endsection


@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>

        let HomePage = {
            template: `
            <div>
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div v-for="slider in sliders">
                            <div class="col-md-6">
                                <a style="text-decoration:none; color:black;" :href="slider.link">
                                    <img style="border-radius: 50%;" :src="slider.imageSlider">
                                    <p>@{{ slider.description }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <br/><hr>

                    <div class="col-md-7 col-md-offset-2">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" :src="youtube_link" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <br/><hr>

                    <div class="col-md-9 col-md-offset-2">
                        <div class="col-md-6" v-for="advantage in advantages" style="height: 320px;">
                            <img style="border-radius: 50%;" :src="advantage.imageAdvantage">
                            <div v-html="advantage.title"></div>
                            <p>@{{ advantage.description }}</p>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <br/><hr>

                    <div class="col-md-offset-4"><h3>Atstovaujame prekiniams ženklams</h3></div>
                    <div style="clear: both;"></div>
                    <br/>

                    <div class="col-md-9 col-md-offset-2">
                        <div class="col-md-6" v-for="manufacturer in manufacturers" style="height: 200px;">
                            <img style="border-radius: 50%;" :src="manufacturer.imageManufacturer">
                            <p>
                                <b>@{{ manufacturer.title }}- </b>
                                @{{ manufacturer.description }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>`,
            data(){
                return{
                    sliders: [], advantages:[], manufacturers:[], youtube_link:'', id: '', id_param: this.$route.params.id,
                }
            },
            created(){
                this.getHomeData();
            },
            methods:{
                getHomeData()
                {
                    axios.get('get-home-data').then(response=>{
                        this.sliders = response.data.sliders;
                        this.advantages = response.data.advantages;
                        this.manufacturers = response.data.manufacturers;
                        this.youtube_link = response.data.youtubeLink;
                    });
                },
            }
        }

        let SliderDetails = {
            template: `
            <div>
            <div class="col-md-offset-2">
                <div>
                    <div class="card col-md-10">
                        <div class="card-body">
                            <h3>@{{ product.pavadinimas_lt }}</h3>
                            <img :src="product.manufactImage">
                            <hr>
                            <div v-html="product.tekstas_lt"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>`,
            data(){
                return{
                    slider:{},
                    id: this.$route.params.id,
                }
            },
            created(){
                this.getSlider();
            },
            methods:{
                getSlider()
                {
                    axios.get('slider/'+this.id).then(response=>{
                        this.slider = response.data;
                        console.log(this.slider)
                    });
                },
            }
        }


        const routes = [
            { path: '/', component: HomePage, name: 'homePage' },
            {path: '/details/:id', component: SliderDetails, name: 'sliderDetails'}
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#homePage')

    </script>

@endsection
