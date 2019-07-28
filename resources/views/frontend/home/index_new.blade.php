@extends('frontend.layouts.master')

@section('header')
    <link rel="stylesheet" href="{{asset('css/swiper/swiper.min.css')}}">

    <style>
        .swiper-container, .parallax-bg {
            width: 100%;
            height: {{$sliderOptionObj['height']}}px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 38px;
            font-weight: 700;
            background-color: #eee;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
    </style>
@endsection

@section('content')


    <div v-if="sliderImages.length>0" class="container" id="slider">
        <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <swiper :options="swiperOption">
                    <swiper-slide v-for="image in sliderImages">
                        <a :style="{'width': '100%', 'height': + swiperOption.height +'px'}" v-if="image.parallax" :href="image.link" target="_blank">
                            <div :style="{'background-image':'url(\'' + image.image + '\')', 'background-size': 'cover', 'width': '100%', 'height': + swiperOption.height +'px'}"><span v-html="image.parallax"></span></div>
                        </a>
                        <a :style="{'width': '100%', 'height': + swiperOption.height +'px'}" v-else :href="image.link" target="_blank">
                            <img :src="image.image"  :style="{'width': '100%', 'height': + swiperOption.height +'px'}">
                        </a>
                    </swiper-slide>

                    <!-- Optional controls -->
                    <div v-if="this.swiperOption.navigation">
                        <div class="swiper-pagination" slot="pagination"></div>
                        <div class="swiper-button-prev" slot="button-prev"></div>
                        <div class="swiper-button-next" slot="button-next"></div>
                    </div>
                </swiper>
            </div>
        </div>
    </div>

    <div id="eshop-page">

        <router-view></router-view>
    </div>


@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset("js/vue-carousel-0.10.0/dist/vue-carousel.min.js")}}"></script>

    <!--<script src="{{asset('js/swiper/swiper.js')}}"></script>
    <script src="{{asset('js/vue-awesome-swiper/vue-awesome-swiper.js')}}"></script>-->
	<!-- Include the Swiper library -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>
	<!-- Swiper JS Vue -->
	<script src="https://cdn.jsdelivr.net/npm/vue-awesome-swiper@3.1.2/dist/vue-awesome-swiper.js"></script>
    <script>
        Vue.use(VueAwesomeSwiper)
    </script>

    <script>
        Vue.use(axios);
        Vue.use(VueCarousel);
        Vue.use(VueCarousel.Carousel);
        Vue.use(VueCarousel.Slide);

        var ClipLoader = VueSpinner.ClipLoader;

        let DefaultProductList = {
            template: `
            <div>
                   <div id="scroll_nav_block">
                      <div class="container">
                         <div class="row py-2">
                            <div class="col-sm-6 col-md-6 col-lg-3">
                               <div class="d-flex">
                                  <div class="input-group input-group-sm my-2">
                                      <input class="form-control border-right-0 border" type="search" v-model="keyword" id="example-search-input" @keyup.enter="searchProduct">
                                      <span class="input-group-append"><div class="input-group-text search_field_icon"><i @click="searchProduct" style="cursor: pointer;" class="fa fa-search"></i></div></span>
                                  </div>
                               </div>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-5 d-none d-sm-block">
                               <div class="d-flex flex-row justify-content-center">
                                 <a class="del_info_link2" href="{{route('delivery_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</a>
                                    <div class="px-2"><hr class="vertical_line1"/></div>
                                 <a class="del_info_link2" href="{{route('buyer_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</a>
                               </div>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-3">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="top_call_td_frame">
                                           <div class="top_call_info_icon"><i class="fas fa-phone-square fa-2x"></i></div>
                                        </td>
                                        <td class="top_call_td_frame">
                                           <div class="top_call_info_txt1">
                                              @lang("global.You have any questions?")
                                           </div>
                                           <div class="top_call_info_txt2">
                                              +370 656 93284
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-1">
                               <div class="d-flex justify-content-sm-end justify-content-md-end justify-content-lg-start">
                                  <div class="cart_circle_container">
                                     {{-- <p>@{{ cartItemNumber }}</p> --}}
                                     <div class="cart_circle_shape"><a href="{{route('cart_index')}}"><div class="cart_circle_icon"><ion-icon name="cart"></ion-icon></div></a></div>
                                     <div class="cart_circle_counter_container">
                                       <div class="cart_circle_counter1" ref="totalItemNumber">@{{ cartItemNumber }}</div>
                                     </div>
                                  </div>
                               </div>
                            </div>

                         </div>
                      </div>
                   </div>

                   <div class="container">
                        <div class="row py-3">
                           <div v-for="newBanner in newBanners"  class="col-sm-6 col-md-6 col-lg-6">
                              <div class="card">
                                  <a :href="newBanner.link" @click="increaseClickCount(newBanner.id)"  class="" ><img class="card-img-top" :src="newBanner.image"  alt="yzipet" /></a>
                              </div>
                           </div>
                        </div>
                   </div>

                 <div class="container">
                      <div style="margin-top: 200px;" v-if="isLoading1">
                            <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                        </div>
                     <div class="row pb-3">


                        <div class="col-sm-12 col-md-12 col-lg-12">
                           <div class="d-flex justify-content-center">
                              <div class="headliner1"></div>
                                 <span class="head_product_title_text"><h3 class="head_product_text">@lang("global.New products")</h3></span>
                           </div>
                        </div>
                     </div>
                  </div>


                <div class="container">
                  <div class="row pb-4">
                     <div class="col-sm-6 col-md-4 col-lg-3 mb-3" v-for="(newProduct,index) in newProducts">
                        <div id="home_product_item_frame">
                           <div class="d-flex justify-content-center">
                              <a class="home_product_item_picframe" :href="newProduct.detailLink"><img class="img-fluid" :src="newProduct.image" alt="yzipet" /></a>
                           </div>
                           <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                              <a class="" :href="newProduct.detailLink"><img class="img-fluid home_product_com_logo" :src="newProduct.manufacturerPhoto" alt="yzipet" /></a>
                           </div>
                           <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                              <a class="home_product_title_link" :href="newProduct.detailLink" >@{{ newProduct.title }}</a>
                           </div>
                            <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3" v-if="newProduct.price!=0 && newProduct.selectedPackagePrice>newProduct.price">
                                <span class="home_product_price">
                                 <div class="discount_price_linethrough" > @{{ newProduct.selectedPackagePrice }}</div></span>
                                <span class="home_product_currency">
                                <div class="discount_price_linethrough" >   EUR</div></span>
                                <span class="home_product_price"  >@{{ newProduct.price }}   EUR</span>
                             </div>

                              <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3" v-if="newProduct.price!=0 && newProduct.selectedPackagePrice < newProduct.price">
                                <span class="home_product_price">
                                 @{{ newProduct.selectedPackagePrice }}</span>
                                <span class="home_product_currency">
                                  EUR</span>

                             </div>

                             <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3" v-if="newProduct.price ==0">
                                <span class="home_product_price">
                                 @{{ newProduct.selectedPackagePrice }}</span>
                                <span class="home_product_currency">
                                  EUR</span>


                             </div>




                        <div class="mb-2" v-if="newProduct.packageLength>0">
                            <div class="dropdown">
                                <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="overflow-wrap :break-word; word-wrap: break-word;white-space:normal;">
                                    @{{newProduct.selectedPackageName}}
                                    <i class="fas fa-angle-down fa-border"></i>
                                </button>

                                <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton" >
                                    <a v-for="(newpackage,pindex) in newProduct.packages" v-if="newpackage.pavadinimas !='default'" class="dropdown-item" href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,'new',newpackage.kaina)">@{{ newpackage.pavadinimas }}</a>

                                </div>

                            </div>
						</div>

                           <div class="d-flex">
                              <div class="prod_qty_field">
                                 <input type="text" class="form-control prod_qty_field_inner" value="1" :id="'quantity_'+newProduct.id" maxlength="3">
                              </div>
                              <div class="prod_qty_text mr-5">
                                 vnt.
                              </div>
                              <div class="">
                                <!-- <button v-if="added.includes(newProduct.id)==false" @click="addToCart(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">@lang("global.Add to cart")</button>-->
                                 <button @click="changeQuantityOrAdd(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">@lang("global.Add to cart")</button>
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
                             <div v-for="bestBanner in bestBanners"  class="col-sm-6 col-md-6 col-lg-6">
                                <div class="card">
                                   <a :href="bestBanner.link"  @click="increaseClickCount(bestBanner.id)" ><img  class="" ><img class="card-img-top" :src="bestBanner.img" alt="yzipet" /></a>
                                </div>
                             </div>
                          </div>
                    </div>
                  <div class="container">
                      <div class="row pb-3">
                         <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="d-flex justify-content-center">
                               <div class="headliner1"></div>
                                  <span class="head_product_title_text"><h3 class="head_product_text">@lang("global.The best bought")</h3></span>
                            </div>
                         </div>
                      </div>
                  </div>


                  <div class="container">
                     <div style="margin-top: 200px;" v-if="isLoading2">
                            <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                        </div>
                    <div class="row pb-4">
                       <div class="col-sm-6 col-md-4 col-lg-3 mb-2" v-for="(bestProduct,index) in bestProducts">
                           <div id="home_product_item_frame">
                             <div class="d-flex justify-content-center">
                                <a class="home_product_item_picframe" :href="bestProduct.detailLink"><img class="img-fluid" :src="bestProduct.image" alt="yzipet" /></a>
                             </div>
                             <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                                <a class="" :href="bestProduct.detailLink"><img class="img-fluid home_product_com_logo" :src="bestProduct.manufacturerPhoto" alt="yzipet" /></a>
                             </div>
                             <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                                <a class="home_product_title_link" :href="bestProduct.detailLink" >@{{ bestProduct.title }}</a>
                             </div>
                             <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3"v-if="bestProduct.price!=0 && bestProduct.selectedPackagePrice>bestProduct.price">
                                <span class="home_product_price">
                                 <div class="discount_price_linethrough" > @{{ bestProduct.selectedPackagePrice }}</div></span>
                                <span class="home_product_currency">
                                <div class="discount_price_linethrough" >  EUR</div></span>
                                <span class="home_product_price"  >@{{ bestProduct.price }}  EUR</span>
                             </div>

                              <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3"v-if="bestProduct.price!=0 && bestProduct.selectedPackagePrice<bestProduct.price">
                                <span class="home_product_price">
                                 @{{ bestProduct.selectedPackagePrice }}</span>
                                <span class="home_product_currency">EUR</span>
                                </div>

                                <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3"v-if="bestProduct.price==0">
                                <span class="home_product_price">
                                 @{{ bestProduct.selectedPackagePrice }}</span>
                                <span class="home_product_currency">EUR</span>
                                </div>







                               <div class="mb-2" v-if="bestProduct.packageLength>0 ">
                                    <div class="dropdown">
                                    <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="overflow-wrap :break-word; word-wrap: break-word;white-space:normal;" >
                                        @{{bestProduct.selectedPackageName}}
                                        <i class="fas fa-angle-down fa-border"></i>
                                    </button>



                                    <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                                        <a v-for="(newpackage,pindex) in bestProduct.packages" class="dropdown-item" href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,'best',newpackage.kaina)">@{{ newpackage.pavadinimas }}</a>
                                    </div>

                                </div>
                               </div>


                             <div class="d-flex">
                                <div class="prod_qty_field">
                                   <input type="text" class="form-control prod_qty_field_inner" value="1" :id="'quantity_'+bestProduct.id" maxlength="3">
                                </div>
                                <div class="prod_qty_text mr-5">
                                   vnt.
                                </div>
                                <div class="">
                                   <!--<button v-if="added.includes(bestProduct.id)==false" @click="addToCart(index, bestProduct.id, 'best_products')" class="btn btn-block add_to_cart_btn">@lang('Į krepšelį')</button>-->
                                   <button  @click="changeQuantityOrAdd(index, bestProduct.id, 'best_products')" class="btn btn-block add_to_cart_btn">@lang('Į krepšelį')</button>
                                </div>
                             </div>
                          </div>
                       </div></div>
                  </div>

                     <div class="container">
                          <div class="row pb-5">
                                 <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="d-flex flex-row justify-content-center">
                                          <router-link class="del_info_link" :to="{name:'deliveryInfo'}" ><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" />@lang("global.Delivery")</router-link>
                                              <div class="px-4"><hr class="vertical_line"/></div>
                                         <router-link class="del_info_link" :to="{name:'customerInfo'}" ><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> @lang("global.Information for buyer")</router-link>

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
                      <div class="row py-3">
                         <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="d-flex flex-row justify-content-center">
                               <h5 class="feature_text_center">@lang("global.Why choose Yzipet?")</h5>
                            </div>
                         </div>
                      </div>
                   </div>

                     <div class="container">
                          <div class="row py-3">
                              <div class="col-sm-6 col-md-6 col-lg-6" v-for="advantage in advantages">
                                  <div class="d-flex flex-row justify-content-center">
                                     <a href="index.php"><img class="img-fluid" :src="advantage.img" alt="yzipet" /></a>
                                  </div>
                                  <div class="py-3 text-center">
                                     <h3 class="advantages_text_title" v-html="advantage.title"></h3>
                                  </div>
                                  <div class="text-center">
                                     <p class="advantages_text">
                                        @{{ advantage.description }}
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
                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
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
                                           <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.work_hours }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                              @{{ contacts.telefonas }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.email }}
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
                               <div class="text-center">@lang("global.Socialize")</div>
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

               </div>`,
            data: function(){
                return {
                    newProducts:[],
                    bestProducts:[],
                    bestBanners :[],
                    newBanners :[],
                    cart:{
                        quantity:'',
                        package:'',
                        productId:''
                    },
                    added:[],
                    cartItemNumber:0,
                    keyword:'',
                    error:false,
                    advantages:[],
                    contacts :{},
                    packageName:'',
                    newManudiscount:[],
                    newProductDiscounts:[],
                    bestManudiscount:[],
                    bestProductDiscounts:[],
                    isLoading2:true,
                    isLoading1 :true,
                };
            },
            created(){
                this.getNewProducts();
                this.getBestProducts();
                this.getBestProductBanners();
                this.getNewProductBanners();
                this.getCartProductNumbers();
                this.getAdvantages();
                this.getContact();
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    setPackage(productIndex, packageIndex,id,name,type,price)
                    {
                        if(type== 'new')
                        {
                            this.newProducts[productIndex].selectedPackageName = name;
                            this.newProducts[productIndex].selectedPackageId = id;
                            this.newProducts[productIndex].selectedPackagePrice = price;
                            this.calculateDisocunPriceForOneProduct(this.newProducts[productIndex],'new') ;
                        }
                        else {
                            this.bestProducts[productIndex].selectedPackageName = name;
                            this.bestProducts[productIndex].selectedPackageId = id;
                            this.bestProducts[productIndex].selectedPackagePrice = price;
                            this.calculateDisocunPriceForOneProduct(this.bestProducts[productIndex],'new') ;
                        }

                    },

                    searchProduct()
                    {
                        if(this.keyword==''){
                            alert("Please provide a keyword");
                            return;
                        }
                        url = {!! json_encode(route('filtered_products_front', ["keyword"=>"@key"])) !!};
                        url = url.replace("@key", this.keyword);
                        location.replace(url);
                    },
                    getContact()
                    {
                        axios.get('get-contacts-home').then(response=>{
                            this.contacts = response.data;
                        })
                    },
                    getNewProducts()
                    {
                        axios.get('get-new-products').then(response=>{
                           var newproducts = response.data.products ;
                           var discounts = response.data.discounts ;
                           this.newManudiscount = discounts.manDiscount ;
                           this.newProductDiscounts = discounts.productDiscount;
                            this.isLoading1 = false ;
                            this.newProducts=this.calculatedDiscountedPrices(newproducts,discounts);


                        })
                    },
                    getBestProducts()
                    {
                        axios.get('get-best-products').then(response=>{
                            //console.log(response.data)
                            /*this.bestProducts*/ var bestproducts=response.data.products;
                            var discounts = response.data.discounts ;
                            this.bestManudiscount = discounts.manDiscount ;
                            this.bestProductDiscounts = discounts.productDiscount  ;
                            this.isLoading2 = false ;
                            this.bestProducts = this.calculatedDiscountedPrices(bestproducts,discounts) ;

                        })
                    },

                    calculatedDiscountedPrices(products, discounts)
                    {
                        var bestproducts = products ;
                        var productDiscount =discounts.productDiscount;
                        var manu_discounts = discounts.manDiscount;


                        for(var i = 0 ; i<bestproducts.length ; i++)
                        {
                            bestproducts[i].price = 0;
                           /* console.log(bestproducts[i].price);*/
                            var cat_discount = bestproducts[i].categoryDiscounts ;
                            var price = Number(bestproducts[i].selectedPackagePrice) ;
                           /* console.log(typeof price);*/
                           /* console.log(price);*/
                            var prices1 =[];
                            var prices2 =[] ;
                            var prices3 = [] ;
                            for(var j = 0; j<productDiscount.length ; j++)
                            {

                                if(productDiscount[j].productId == bestproducts[i].id && productDiscount[j].package_id == bestproducts[i].selectedPackageId )
                                {
                                    var discountAmount = productDiscount[j].amount != null ?
                                        (bestproducts[i].selectedPackagePrice * productDiscount[j].amount)/100 :  productDiscount[j].fixed_amount ;

                                    prices1.push(bestproducts[i].selectedPackagePrice - discountAmount) ;
                                }
                            }

                            if(prices1.length >0)
                            {
                                var disocuntedPrice1 = Math.min.apply(Math,prices1) ;

                                price = price > disocuntedPrice1 ? disocuntedPrice1 : price ;

                                bestproducts[i].price = price;



                            }

                            for(var k = 0; k<manu_discounts.length ; k++)
                            {
                                if(manu_discounts[k].manufacturerId == bestproducts[i].manufacturerId)
                                {
                                    var discountAmount = manu_discounts[k].amount != null ?
                                        (bestproducts[i].selectedPackagePrice * manu_discounts[k].amount)/100 :  manu_discounts[k].fixed_amount ;

                                    prices2.push(bestproducts[i].selectedPackagePrice - discountAmount) ;
                                }
                            }


                            if(prices2.length >0)
                            {

                                var disocuntedPrice2 = Math.min.apply(Math,prices2) ;
                                price = price > disocuntedPrice2 ? disocuntedPrice2 : price ;
                                bestproducts[i].price = price;
                            }

                            for(var l =0 ; l< cat_discount.length ; l++)
                            {

                                    var discountAmount = cat_discount[l].amount != null ?
                                        (bestproducts[i].selectedPackagePrice * cat_discount[l].amount)/100 :  cat_discount[l].fixed_amount ;
                                    var reducedPrice = (bestproducts[i].selectedPackagePrice - discountAmount);

                                    prices3.push(reducedPrice) ;
                            }

                            if(prices3.length >0)
                            {

                                var disocuntedPrice3 = Math.min.apply(Math,prices3) ;

                                price = price > disocuntedPrice3 ? disocuntedPrice3 : price ;

                                bestproducts[i].price = price;
                            }


                            /*bestproducts[i].price = Number(bestproducts[i].price);*/
                            /*  if(bestproducts[i].price>0 && Number.isInteger(bestproducts[i].price ===false ))*/
                                bestproducts[i].price = parseFloat(bestproducts[i].price.toFixed(2));



                        }

                        return bestproducts ;
                    },

                    calculateDisocunPriceForOneProduct(product,type)
                    {

                        if(type== 'new')
                        {
                            var productDiscount = this.newProductDiscounts ;
                            var manu_discounts = this.newManudiscount;
                        }
                        else {
                            var productDiscount = this.bestProductDiscounts ;
                            var manu_discounts = this.bestManudiscount;
                        }
                        var cat_discount = product.categoryDiscounts ;

                           var price = product.selectedPackagePrice ;
                           var prices1 =[];
                           var prices2 =[] ;
                           var prices3 = [] ;
                           for(var j = 0; j<productDiscount.length ; j++)
                           {
                               if(productDiscount[j].productId ==product.id && productDiscount[j].package_id == product.selectedPackageId )
                               {
                                   var discountAmount = productDiscount[j].amount != null ?
                                       (product * productDiscount[j].amount)/100 :  productDiscount[j].fixed_amount ;
                                   prices1.push(product.selectedPackagePrice - discountAmount) ;
                               }
                           }
                           if(prices1.length >0)
                           {

                               var disocuntedPrice1 = Math.min.apply(Math,prices1) ;
                               price = price > disocuntedPrice1 ? disocuntedPrice1 : price ;
                               product.price = price;
                           }

                           for(var k = 0; k<manu_discounts.length ; k++)
                           {
                               if(manu_discounts[k].manufacturerId == product.manufacturerId)
                               {
                                   var discountAmount = manu_discounts[k].amount != null ?
                                       (product.selectedPackagePrice * manu_discounts[k].amount)/100 :  manu_discounts[k].fixed_amount ;

                                   prices2.push(product.selectedPackagePrice - discountAmount) ;
                               }
                           }


                           if(prices2.length >0)
                           {

                               var disocuntedPrice2 = Math.min.apply(Math,prices2) ;
                               price = price > disocuntedPrice2 ? disocuntedPrice2 : price ;
                               product.price = price;
                           }


                           for(var l =0 ; l< cat_discount.length ; l++)
                           {

                               var discountAmount = cat_discount[l].amount != null ?
                                   (product.selectedPackagePrice * cat_discount[l].amount)/100 :  cat_discount[l].fixed_amount ;
                               var reducedPrice = (product.selectedPackagePrice - discountAmount);

                               prices3.push(reducedPrice) ;
                           }

                           if(prices3.length >0)
                           {

                               var disocuntedPrice3 = Math.min.apply(Math,prices3) ;
                               price = price > disocuntedPrice3 ? disocuntedPrice3 : price ;
                               product.price = price;
                           }

                           product.price = parseFloat(product.price.toFixed(2));



                    },
                    getBestProductBanners()
                    {
                        axios.get('get-best-product-banners').then(response=>{
                            this.bestBanners =response.data;

                        })
                    },
                    getNewProductBanners()
                    {
                        axios.get('get-new-product-banners').then(response=>{

                            this.newBanners =response.data;

                        })
                    },
                    addToCart(productIndex, productId, type)
                    {


                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                        if(type=='new_product')
                            this.cart.package = this.newProducts[productIndex].selectedPackageId;
                        else
                            this.cart.package = this.bestProducts[productIndex].selectedPackageId;


                        axios.post('add-to-cart',this.cart).then(response=>{
                            if(response.data.message == undefined)
                            {
                                this.added.push(productId);
                                this.getCartProductNumbers();
                            }
                            else {
                                alert(response.data.message) ;
                            }

                        })
                    },

                    changeQuantityOrAdd(productIndex, productId, type)
                    {
                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                        if(type=='new_product')
                            this.cart.package = this.newProducts[productIndex].selectedPackageId;
                        else
                            this.cart.package = this.bestProducts[productIndex].selectedPackageId;

                        var exists = false ;
                        console.log(this.cart);

                        axios.post('if-cart-product-exists',this.cart).then(response=>{
                            exists = response.data ;
                            console.log(exists);
                            var that = this ;
                            if(exists==true)
                            {
                                axios.post('add-item-quantity',this.cart).then(response=>{
                                    if(response.data.message == undefined)
                                    that.getCartProductNumbers();
                                    else alert(response.data.message)
                                });
                            }

                            else {
                                axios.post('add-to-cart', that.cart).then(response => {
                                    if (response.data.message == undefined) {
                                        that.added.push(productId);
                                        that.getCartProductNumbers();
                                    }
                                    else {
                                        alert(response.data.message);
                                    }
                                });
                            }
                        });

                    },
                    getCartProductNumbers()
                    {
                        axios.get('get-cart-number').then(response=>{
							//Vue.set(this, 'cartItemNumber', response.data)
							this.$refs.totalItemNumber.innerHTML = response.data
                        })
                    },
                    removeFromCart(productId)
                    {
                        let index = this.added.indexOf(productId);
                        this.added.splice(index,1);
                        this.cart.productId = productId ;
                        axios.post('remove-from-product',this.cart).then(response=>{

                            this.getCartProductNumbers();

                        })
                    },
                    increaseClickCount(id)
                    {
                        axios.get('increase-banner-click/'+id).then(response=>{
                            console.log(response.data);
                        })
                    },
                    getAdvantages()
                    {
                        axios.get('get-advantages-home').then(response=>{
                            this.advantages = response.data;
                        })
                    }



                }
        }


        let FilteredProductList = {
            template: `
         <div>
            <div class="container">
                <div class="row pb-5">
                  <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex">
                           <div class="input-group input-group-sm my-2">
                               <input class="form-control border-right-0 border" type="search" v-model="keyword" id="example-search-input" @keyup.enter="searchProduct">
                                    <span class="input-group-append">
                                        <div class="input-group-text search_field_icon"><i @click="searchProduct" style="cursor: pointer;" class="fa fa-search"></i></div>
                                    </span>
                           </div>
                        </div>
                  </div>

                  <div class="col-sm-6 col-md-6 col-lg-5 d-none d-sm-block">
                        <div class="d-flex flex-row justify-content-center">
                            <router-link class="del_info_link2" :to="{name:'deliveryInfo'}" ><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</router-link>
                                <div class="px-2"><hr class="vertical_line1"/></div>
                            <router-link class="del_info_link2" :to="{name:'customerInfo'}" ><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</router-link>
                        </div>
                  </div>

                  <div class="col-sm-12 col-md-12 col-lg-3">
                     <div id="product_filterbox_frame">
                        <div class="d-sm-flex d-md-flex d-lg-flex mb-3 filter_title_text">
                           Gamintojas
                        </div>
                           <div class="form-check mb-2" v-for="manufacturer in manufacturers">
                             <input class="form-check-label filter_item_checkbox" type="checkbox" :value="manufacturer.id" v-model="manufactureFilter" @change="filterProducts">  @{{ manufacturer.title }}
                           </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-9">
                     <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4" v-for="newProduct in newProducts">
                           <div id='home_product_item_frame'>
                              <div class="d-sm-flex d-md-flex d-lg-flex justify-content-center">
                                 <a class="home_product_item_img" href="#"><img class="img-fluid" :src="newProduct.image" alt="yzipet" /></a>
                              </div>
                              <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                                 <a class="" href="#"><img class="img-fluid home_product_com_logo" :src="newProduct.manufacturerPhoto" alt="yzipet" /></a>
                              </div>
                              <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                                 <p class="home_product_title"><a :href="newProduct.detailLink" >@{{ newProduct.title }}</a></p>
                              </div>
                              <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                                 <span class="home_product_price">@{{ newProduct.price }}</span>
                                 <span class="home_product_currency">eur</span>
                                 <p class="card-text" v-if="newProduct.old_price!=0"> Old Price@{{ newProduct.old_price }}</p>
                              </div>
                              <div class="mb-2" v-if="newProduct.packageLength>0">
                                   <select  v-model="cart.package" class="form-control">
                                       <option v-for="newpackage in newProduct.packages" :value="newpackage.id">@{{ newpackage.pavadinimas }}</option>
                                   </select>
                              </div>
                              <div class="d-flex">
                                 <div class="prod_qty_field">
                                    <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" v-model="cart.quantity">
                                 </div>
                                 <div class="prod_qty_text mr-5">
                                    @lang("global.Qty")
                                 </div>
                                 <div class="">
                                    <button @click="addToCart(newProduct.id)" class="btn add_to_cart_btn ">@lang("global.Add to cart")</button>
                                 </div>
                              </div>
                           </div>
                        </div>
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
                    keyword: this.$route.params.keyword,
                };
            },

            methods:
                {

                    getProducts()
                    {
                        axios.get('get-products-by-key/'+this.keyword).then(response=>{

                            this.newProducts=response.data;
                            var that =this;

                            axios.post('get-products-manufacturer',{products:this.newProducts}).then(response=>{
                                console.log(response.data)
                                that.manufacturers = response.data;
                            })

                        })
                    },
                    addToCart(productIndex, productId, type)
                    {

                        var productInfo;
						if(type=='new_product')
							productInfo = this.newProducts[productIndex];
						else(type=='best_products')
							productInfo = this.newProducts[productIndex];
						if(productInfo.packageLength>0)
						{
							var selectedPackageId  = productInfo.packageLength;
						}
						else
						{
							this.cart.productId = productId;
							axios.post('add-to-cart',this.cart).then(response=>{
								//console.log(response.data)
							})
						}
                    },
                    filterProducts()
                    {
                        if(this.manufactureFilter.length ==0)
                            this.getProducts();
                        axios.post('filter-products-by-manu',{manufacturers:this.manufactureFilter,keyword:this.keyword}).then(response=>{
                            this.newProducts =response.data;
                            console.log(response.data);
                        })
                    },
                    searchProduct()
                    {
                        if(this.keyword==''){
                            alert("Please provide a keyword");
                            return;
                        }
                        this.$router.push({name:'filteredProductList', params:{keyword:this.keyword}})
                    },
                },
            created(){
                this.getProducts();
            }
        }

        let DeliveryInfo = {
            template: `
                <div>
                    <div class="container">
                        <div v-for="deliveryInfo in deliveryInfoList">
                        <h2>@{{ deliveryInfo.title }}</h2>
                        <div v-html="deliveryInfo.description"></div>
                    </div>
                    </div>
                </div>`,
            data: function(){
                return {
                    deliveryInfoList:[]
                };
            },
            created(){
                this.getDeliveryInfo();
            },
            methods:
                {
                    getDeliveryInfo()
                    {
                        axios.get('delivery-info').then(response=>{
                            this.deliveryInfoList=response.data;
                        })
                    },
                },
        }


        let CustomerInfo = {
            template: `
                <div>
                    <div class="container">
                        <div v-for="customerInfo in customerInfoList">
                        <h2>@{{ customerInfo.title }}</h2>
                        <div v-html="customerInfo.description"></div>
                    </div>
                    </div>
                </div>`,
            data: function(){
                return {
                    customerInfoList:[]
                };
            },
            created(){
                this.getcustomerInfo();
            },
            methods:
                {
                    getcustomerInfo()
                    {
                        axios.get('customer-info').then(response=>{
                            this.customerInfoList=response.data;
                        })
                    },
                },
        }


        const routes = [

            {
                path: '/',
                component: DefaultProductList,
                name: 'defaultProductList'
            },

            {
                path: '/keyword/:keyword',
                component: FilteredProductList,
                name: 'filteredProductList'
            },

            {
                path: '/delivery-info',
                component: DeliveryInfo,
                name: 'deliveryInfo'
            },

            {
                path: '/customer-info',
                component: CustomerInfo,
                name: 'customerInfo'
            },
        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router,

        }).$mount('#eshop-page')

        Vue.use(axios)
        new Vue({
            el: "#slider",
            data: function(){
					return {
					   sliderImages:[], swiperOption: {!! json_encode($sliderOptionObj) !!}
					}
			},
            created() {
                this.getSliderImages();
            },
            methods: {
                getSliderImages() {
                   axios.get('{{route('get_slider_for_home')}}').then(response=>{
                       this.sliderImages = response.data ;
                    })
                },
            },
        });

    </script>
@endsection
