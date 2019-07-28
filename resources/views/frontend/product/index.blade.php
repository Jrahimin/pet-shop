@extends('frontend.layouts.master')

@section('content')

    <div id="productPage">
      <div class="container">
          <div class="row pb-2">
             <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="product_item_frame">
                   <div class="d-sm-flex d-md-flex d-lg-flex justify-content-md-center justify-content-lg-center">
                      <a href="index.php"><img class="img-fluid product_item_img" src="{{ asset('images/product_pic.jpg') }}" alt="yzipet" /></a>
                   </div>
                </div>
                <div class="text-center mt-3">
                   PHOTO SLIDER

                </div>
             </div>

             <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="d-flex mb-3">
                   <div class="product_brand_logos">
                      <a class="" href="index.php"><img class="img-fluid product_brand_logo_img" src="{{ asset('images/product_partner_pic.png') }}" alt="yzipet" /></a>
                   </div>
                </div>
                <div class="d-flex mb-3">
                   <div class="product_title_text1">
                      Pure Chicken Dinner maistas šunims su vištiena
                   </div>
                </div>
                <div class="d-flex mb-3">
                   <div class="product_title_text2">
                      Sausas begrūdis
                   </div>
                </div>

                <div class="d-flex mb-3">
                   <div class="discount_product_price"><div class="discount_price_linethrough">10.25</div></div>
                   <div class="discount_product_currency mr-3"><div class="discount_price_linethrough">Eur</div></div>
                   <div class="product_price">15.25</div>
                   <div class="product_currency">Eur</div>
                </div>

                <div class="d-flex mb-4">
                   <div class="w-50">
                      <div class="input-group product_qty_counter_frame" id="spinner">
                        <span class="input-group-btn btn-group-sm mr-2 product_counter_minus">
                           <button type="button" class="btn product_qty_btn1" data-action="decrement"><ion-icon ios="ios-remove" md="md-remove"></ion-icon></button>
                        </span>
                        <input name="qty" type="text" class="form-control text-center product_counter_input" value="1" min="1" max="999" maxlength="3" enabled>
                        <span class="input-group-btn btn-group-sm ml-2 product_counter_plus">
                           <button type="button" class="btn product_qty_btn2" data-action="increment"><ion-icon ios="ios-add" md="md-add"></ion-icon></button>
                        </span>
                        <div class="prod_qty_text">
                          vnt.
                        </div>
                      </div>
                   </div>

                  <div class="dropdown">
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
                </div>

               <div class="d-flex mb-3">
                  <div class="">
                     <button type="button" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                  </div>
               </div>
             </div>
          </div>
      </div>

      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 pb-4">
               <div class="content_divider1 py-4"></div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="d-flex mb-3">
                 <div class="pr-2"><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /></div>
                 <div class="product_content_title1">Informacija</div>
               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 px-5 pb-4">
               <div class="d-flex mb-3">
                  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="d-flex mb-3">
                 <div class="pr-2"><img class="img-fluid" src="{{ asset('images/comments_icon.png') }}" alt="yzipet" /></div>
                 <div class="product_content_title1">Atsiliepimai</div>
               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 px-5 pb-4">
               <div class="d-flex mb-3">
                  <div class="pr-4"><img class="img-fluid" src="{{ asset('images/comment_quotes_icon.png') }}" alt="yzipet" /></div>
                  <div class="px-3 comment_text">
                     Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
                  </div>
               </div>
               <div class="d-flex pl-5 mb-3">
                  <div class="pl-4 comment_author">
                     Lorem Ipsum
                  </div>
               </div>

            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12 pb-4">
               <div class="product_content_title2">Gali būti įdomu</div>
               <div class="content_divider1"></div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-4">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="text-center">BOTTOM SLIDER</div>
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
    <script src="{{asset('js/qty_counter.js')}}"></script>


    <script>

        let ProductList = {
            template: `
            <div>
                <div class="col-md-10 col-md-offset-1">
                      <div class="col-md-4" v-for="product in products">
                        <router-link style="text-decoration:none; color:black;" :to="{name:'productDetails',params:{id:product.id}}">
                          <div class="card">
                              <div class="card-body">
                                   <h4>@{{ product.pavadinimas_lt }}</h4>
                                   <img :src="product.manufactImage">
                                   <img :src="product.image">
                                   <p>@{{ product.description }}</p>
                               </div>
                           </div>
                      </router-link>
                      <br/>
                      </div>
                </div>
            </div>`,
            data(){
                return{
                    products: [], id: '', id_param: this.$route.params.id,
                }
            },
            created(){
                this.getProducts();
            },
            methods:{
                getProducts()
                {
                    let path = "";
                    console.log(this.id_param)
                    if(this.id_param != undefined)
                        path = 'get-products/'+this.id_param;
                    else
                        path = 'get-products';

                    axios.get(path).then(response=>{
                        this.products = response.data;
                        console.log(response.data)
                    });
                },
            }
        }

        let ProductDetails = {
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
                    product:{},
                    id: this.$route.params.id,
                }
            },
            created(){
                this.getProducts();
            },
            methods:{
                getProducts()
                {
                    axios.get('get-product-detail/'+this.id).then(response=>{
                        this.product = response.data;
                        console.log(this.product)
                    });
                },
            }
        }


        const routes = [
            { path: '/:id?', component: ProductList, name: 'productList' },
            {path: '/details/:id', component: ProductDetails, name: 'productDetails'}
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#productPage')

    </script>

@endsection
