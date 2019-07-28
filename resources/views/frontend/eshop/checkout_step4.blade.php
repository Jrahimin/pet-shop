@extends('frontend.layouts.master')

@section('content')

    <div id="eshop-page">

      <div class="container">
         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12">


            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-4">
            <div class="col-sm-12 col-md-5 col-lg-6">
               <div class="d-flex">
                  <div class="product_title_text1">
                     Apmokėjimas
                  </div>
               </div>
               <div class="d-flex mb-3">
                  <div class="product_desc_text1">
                     Peržiūrėję ir pasitikrinę išsirinktas prekes, apmokėkite užsakymą.
                  </div>
               </div>
            </div>

            <div class="col-sm-12 col-md-7 col-lg-6 checkout_cart_NON_mobile">
               <div class="d-flex">
                  <div class="checkout_circle_step1">1</div>
                  <div class="checkout_circle_line1"></div>
                  <div class="checkout_circle_step1">2</div>
                  <div class="checkout_circle_line1"></div>
                  <div class="checkout_circle_step1">3</div>
                  <div class="checkout_circle_line1"></div>
                  <div class="checkout_circle_step1">4</div>
               </div>

               <div class="d-flex">
                  <div class="py-2 w-25 checkout_step_text1">Krepšelis</div>
                  <div class="py-2 w-25 checkout_step_text1">Jūsų duomenys</div>
                  <div class="py-2 w-25 text-center checkout_step_text1">Pristatymas</div>
                  <div class="py-2 w-25 text-right checkout_step_text1">Apmokėjimas</div>
               </div>

            </div>

<!-- CART SECTION FOR MOBILE DEVICES -->

            <div class="col-sm-12 checkout_cart_mobile">
               <div class="d-flex mb-3 justify-content-center">
                  <div class="mr-2 checkout_circle_step1">1</div>
                  <div class="mr-4 pt-3 w-25 checkout_step_text1">Krepšelis</div>
                  <div class="mr-2 checkout_circle_step1">2</div>
                  <div class="pt-3 w-25 checkout_step_text1">Jūsų duomenys</div>
               </div>
               <div class="d-flex justify-content-center">
                  <div class="mr-2 checkout_circle_step1">3</div>
                  <div class="mr-4 pt-3 w-25 checkout_step_text1">Pristatymas</div>
                  <div class="mr-2 checkout_circle_step1">4</div>
                  <div class="pt-3 w-25 checkout_step_text1">Apmokėjimas</div>
               </div>
            </div>
<!-- CART SECTION FOR MOBILE DEVICES -->

         </div>
      </div>

      <div class="container">
         <div class="row pb-3">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div id="checkout_cart_frame">

                  <div class="row">
                     <div class="col-sm-2 col-md-2 col-lg-2">
                        <div class="d-flex">
                           <div class="checkout_img_container">
                               <a href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 col-md-3 col-lg-3">
                        <div class="d-flex flex-column">
                           <div class="ml-auto checkout_remove_btn">
                              <a type=""href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/remove_icon.png') }}" alt="yzipet" /></a>
                           </div>
                           <div class="checkout_title_txt1">
                              Beco Pets
                           </div>
                           <div class="checkout_txt2">
                              Beco Cod and Haddock maistas šunims su menke
                           </div>
                           <div class="pt-3 checkout_atr_txt3">
                              250 ml
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 col-md-3 col-lg-3 text-center align-self-center">
                        <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex">
                              <span class="home_product_price">19.99</span>
                              <span class="home_product_currency">eur</span>
                        </div>
                     </div>
                     <div class="col-sm-1 col-md-1 col-lg-1 align-self-center">
                        <div class="d-flex">
                           <div class="mx-auto">
                              <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-3 col-md-3 col-lg-3 text-center align-self-center">
                        <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex">
                              <span class="home_product_price">19.99</span>
                              <span class="home_product_currency">eur</span>
                        </div>
                     </div>
                  </div>
                  <div class="my-3 checkout_prod_divider"></div>

                 <div class="row">
                    <div class="col-sm-2 col-md-2 col-lg-2">
                       <div class="d-flex">
                          <div class="checkout_img_container">
                              <a href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                          </div>
                       </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                       <div class="d-flex flex-column">
                          <div class="ml-auto checkout_remove_btn">
                             <a type=""href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/remove_icon.png') }}" alt="yzipet" /></a>
                          </div>
                          <div class="checkout_title_txt1">
                             Beco Pets
                          </div>
                          <div class="checkout_txt2">
                             Beco Cod and Haddock maistas šunims su menke
                          </div>
                          <div class="pt-3 checkout_atr_txt3">
                             250 ml
                          </div>
                       </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 text-center align-self-center">
                       <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex">
                             <span class="home_product_price">19.99</span>
                             <span class="home_product_currency">eur</span>
                       </div>
                    </div>
                    <div class="col-sm-1 col-md-1 col-lg-1 align-self-center">
                       <div class="d-flex">
                          <div class="mx-auto">
                             <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" id="prod_qty_id">
                          </div>
                       </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 text-center align-self-center">
                       <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex">
                             <span class="home_product_price">19.99</span>
                             <span class="home_product_currency">eur</span>
                       </div>
                    </div>
                  </div>

               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-3">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="d-flex">
                  <div class="ml-auto text-right">
                     <span class="checkout_total_sum">Suma:</span>
                     <span class="checkout_total_sum">35897.57</span>
                     <span class="checkout_total_sum">eur</span>
                     <div class="discount_total_sum">
                        <span class="discount_total_sum">Lojalumo nuolaida:</span>
                        <span class="discount_total_sum">-321.67</span>
                        <span class="discount_total_sum">Eur</span>
                     </div>
                     <div class="discount_total_sum">
                        <span class="shipping_total_sum">Pristatymas:</span>
                        <span class="shipping_total_sum">2.99</span>
                        <span class="shipping_total_sum">Eur</span>
                     </div>
                     <span class="checkout_total_sum">Viso:</span>
                     <span class="checkout_total_sum">38964.33</span>
                     <span class="checkout_total_sum">eur</span>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-5">
            <div class="col-sm-4 col-md-4 col-lg-4 checkout_border_left_right1">

               <div class="d-flex pb-3">
                  <div class="checkout_title_txt3">
                     Pristatymo informacija
                  </div>
                  <div class="ml-auto">
                     <a type=""href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/editstep_icon.png') }}" alt="yzipet" /></a>
                  </div>
               </div>

               <div class="d-flex pb-2">
                  <div class="pr-1 del_info_txt">@lang('checkout_step_4.COURIER NAME DPD')</div>
                  <div class="pl-1 del_info_txt">siuntų pristatymas</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">@lang('checkout_step_4.Buyer Name')</div>
                  <div class="pl-1 del_info_txt">@lang('checkout_step_4.Buyer Last Name')</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Tel. Nr.: +370 000 000</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">@lang('checkout_step_4.Buyer City')</div>
                  <div class="pl-1 del_info_txt">@lang('checkout_step_4.Buyer Address')</div>
               </div>

            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
               <div class="d-flex pb-3">
                  <div class="checkout_title_txt3">
                     Pirkėjas
                  </div>
                  <div class="ml-auto">
                     <a type=""href="index.php"><img class="img-fluid checkout_img" src="{{ asset('images/editstep_icon.png') }}" alt="yzipet" /></a>
                  </div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">@lang('checkout_step_4.Buyer Name')</div>
                  <div class="pl-1 del_info_txt">@lang('checkout_step_4.Buyer Last Name')</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Tel. Nr.: +370 000 000</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Buyer City</div>
                  <div class="pl-1 del_info_txt">Buyer Address</div>
               </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4 checkout_border_left_right1">
               <div class="d-flex pb-3">
                  <div class="checkout_title_txt3">
                     Pardavėjas
                  </div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">UAB "BIOMEDIKA"</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Antakalnio g. 36, LT-10305 Vilnius</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Tel.: 85 270 90 55</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Įmonės kodas: 123501772</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">PVM mokėtojo kodas: LT235017716</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">A./s.: LT937300010073379464</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">Bankas: AB Swedbank</div>
               </div>
               <div class="d-flex">
                  <div class="pr-1 del_info_txt">SWIFT kodas: HABALT22</div>
               </div>
            </div>

         </div>
      </div>

      <div class="container">
         <div class="row pb-4">
            <div class="col-sm-4 col-md-2 col-lg-5 align-self-center">
               <div class="d-flex">
                  <a class="chekcout_buy_more_link" href="index.php"><img class="img-fluid chekcout_buy_more_img" src="{{ asset('images/prev_arr.png') }}" alt="yzipet" />Grįžti</a>
               </div>
            </div>

            <div class="col-sm-5 col-md-7 col-lg-4 align-self-center">
               <div class="d-flex">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="repCompanyCheck">
                    <label class="custom-control-label courrier_extra_small" for="repCompanyCheck">Patvirtinu, kad susipažinau ir sutinku su <a href="#">taisyklėmis</a></label>
                  </div>
               </div>
            </div>


            <div class="col-sm-3 col-md-3 col-lg-3">
               <div class="d-flex">
                  <div class="ml-auto">
                      <button type="submit" class="btn total_sum_btn">Patvirtinti užsakymą</button>
                  </div>
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

@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vue-carousel-0.10.0/dist/vue-carousel.min.js')}}"></script>

    <script>
        Vue.use(axios);

        var DefaultProductList = {
            template: `
         <div>

            <div class="card col-md-2">
              <div class="card-body">
                 <h4>Manufacturers</h4>
                 <div v-for="manufacturer in manufacturers">
                     <input type="checkbox"  :value="manufacturer.id" v-model="manufactureFilter" @change="filterProducts">  @{{ manufacturer.title }}

                 </div>
              </div>
            </div>



              <div id='newproducts' class="col-md-8 ">

                <div class="card col-md-3 " v-for="newProduct in newProducts">
                 <div class="card-body">
                  <div><img :src="newProduct.image"></div>
                    <p><img :src="newProduct.manufacturerPhoto"></p>
                    <h4 class="card-title"><a :href="newProduct.detailLink" >@{{ newProduct.title }}</a></h4>
                    <p class="card-text">Price @{{  newProduct.price}}</p>
                    <p class="card-text" v-if="newProduct.old_price!=0"> Old Price@{{ newProduct.old_price }}</p>

                    <div  v-if="newProduct.packageLength>0">
                                <select  v-model="cart.package" class="form-control">
                                <option v-for="newpackage in newProduct.packages" :value="newpackage.id">@{{ newpackage.pavadinimas }}</option>
                                </select>
                     </div>


                    <div class="form-group">
                       <label class="control-label col-md-4" for="">@lang('Quantity')</label>
                      <div class="col-md-8">
                           <input type="text" v-model="cart.quantity" class="form-control">
                       </div>
                     </div>

                       <div>
                           <button @click="addToCart(newProduct.id)" class="btn btn-primary ">@lang('Add to cart')</button>
                        </div>
                   </div>
                 </div>
               </div>



          </div>

`,
            data: function(){
                return {
                    newProducts:[],
                    manufacturers:[],
                    manufactureFilter:[],
                    cart:{
                        quantity:0,
                        package:'',
                        productId:''
                    },

                };
            },

            methods:
                {

                    getProducts()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('category');
                        var length = url_string.length;

                        var category_id = url_string.substr(index+9).replace('#/','');

                       axios.get('../../get-products-by-cat/'+category_id).then(response=>{

                            this.newProducts=response.data;
                            var that =this;

                           axios.post('../../get-products-manufacturer',{products:this.newProducts}).then(response=>{
                               console.log(response.data)
                               that.manufacturers = response.data;
                           })

                        })
                    },


                    addToCart(productId)
                    {
                        this.cart.productId = productId;
                        axios.post('add-to-cart',this.cart).then(response=>{
                            //console.log(response.data)
                        })
                    },
                    filterProducts()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('category');
                        var length = url_string.length;

                        var category_id = url_string.substr(index+9).replace('#/','');
                        if(this.manufactureFilter.length ==0)
                            this.getProducts();
                       axios.post('../../filter-products-by-manu',{manufacturers:this.manufactureFilter,category:category_id}).then(response=>{
                           this.newProducts =response.data;
                       })
                    }



                },
            created(){
                this.getProducts();

            }
        }




        const routes = [

            {
                path: '/',
                component: DefaultProductList,
                name: 'defaultProductList'
            },



        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router,

        }).$mount('#eshop-page')


    </script>
@endsection
