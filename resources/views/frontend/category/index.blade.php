@extends('frontend.layouts.master')

@section('content')

   <div id="categoryPage">

            <div class="container">
               <div class="row pb-2">
                  <div class="col-sm-12 col-md-12 col-lg-12">

                  </div>
               </div>
            </div>

            <div class="container">
               <div class="row pb-4">
                  <div class="col-sm-7 col-md-7 col-lg-7">
                     <div class="d-inline-flex pagination_filter_text">
                     Rodyti puslapyje:
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">15</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">30</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">90</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter_last" href="#">viską</a>
                     </div>
                  </div>

                  <div class="col-sm-5 col-md-5 col-lg-5 text-sm-left text-md-right text-lg-right">
                     <div class="d-inline-flex pagination_filter_text">
                     Puslapis
                     </div>

                     <div class="d-inline-flex mr-2">
                        <button class="btn pagination_filter_dropdown" type="button" id="productPgnDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          1
                        <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                        <div class="dropdown-menu pagination_filter_drop_show" aria-labelledby="productPgnDropdownButton">
                          <a class="dropdown-item" href="#">2</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">3</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">4</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">5</a>
                        </div>
                     </div>

                     <div class="d-inline-flex mr-1">
                        <a class="" href="index.php"><img class="img-fluid" src="{{ asset('images/prev_arr.png') }}" alt="yzipet" /></a>
                     </div>
                     <div class="d-inline-flex ml-1">
                        <a class="" href="index.php"><img class="img-fluid" src="{{ asset('images/next_arr.png') }}" alt="yzipet" /></a>
                     </div>
                  </div>
               </div>
            </div>



            <div class="container">
               <div class="row pb-5">


                  <div class="col-sm-3 col-md-3 col-lg-3">
                     <div id="product_filterbox_frame">
                        <div class="d-sm-flex d-md-flex d-lg-flex mb-3 filter_title_text">
                           Gamintojas
                        </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck1">
                             <label class="form-check-label filter_item_text" for="defaultCheck1">
                              Default checkbox 1
                             </label>
                           </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck2">
                             <label class="form-check-label filter_item_text" for="defaultCheck2">
                              Default checkbox 2
                             </label>
                           </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck3">
                             <label class="form-check-label filter_item_text" for="defaultCheck3">
                              Default checkbox 3
                             </label>
                           </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck4">
                             <label class="form-check-label filter_item_text" for="defaultCheck4">
                              Default checkbox 4
                             </label>
                           </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck5">
                             <label class="form-check-label filter_item_text" for="defaultCheck5">
                              Default checkbox 5
                             </label>
                           </div>
                           <div class="form-check mb-2">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" value="" id="defaultCheck6">
                             <label class="form-check-label filter_item_text" for="defaultCheck6">
                              Default checkbox 6
                             </label>
                           </div>
                     </div>
                  </div>

                        <div class="col-sm-9">
                           <div class="row">
                              <div class="col-sm-4 col-md- col-lg-4">
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

                              <div class="col-sm-4 col-md-4 col-lg-4">
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

                              <div class="col-sm-4 col-md-4 col-lg-4">
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

                              <div class="col-sm-4 col-md- col-lg-4">
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

                              <div class="col-sm-4 col-md- col-lg-4">
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

                              <div class="col-sm-4 col-md- col-lg-4">
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

               </div>
            </div>

            <div class="container">
               <div class="row pb-2">
                  <div class="col">
                     <div class="home_prd_category_divider">
                     </div>
                  </div>
               </div>
            </div>

            <div class="container">
               <div class="row pb-4">
                  <div class="col-sm-4 col-md-4 col-lg-4">
                     <div class="d-flex flex-row">
                       <a class="del_info_link1" href="index.php"><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</a>
                     </div>
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4">
                     <div class="d-inline-flex pagination_filter_text">
                     Rodyti puslapyje:
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">15</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">30</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#">90</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter_last" href="#">viską</a>
                     </div>
                  </div>
                  <div class="col-sm-4 col-md-4 col-lg-4 text-sm-left text-md-right text-lg-right">
                     <div class="d-inline-flex pagination_filter_text">
                     Puslapis
                     </div>
                     <div class="d-inline-flex mr-2">
                        <button class="btn pagination_filter_dropdown" type="button" id="productPgnDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          1
                        <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                        <div class="dropdown-menu pagination_filter_drop_show" aria-labelledby="productPgnDropdownButton">
                          <a class="dropdown-item" href="#">2</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">3</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">4</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="#">5</a>
                        </div>
                     </div>
                     <div class="d-inline-flex mr-1">
                        <a class="" href="index.php"><img class="img-fluid" src="{{ asset('images/prev_arr.png') }}" alt="yzipet" /></a>
                     </div>
                     <div class="d-inline-flex ml-1">
                        <a class="" href="index.php"><img class="img-fluid" src="{{ asset('images/next_arr.png') }}" alt="yzipet" /></a>
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
