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
                     Pristatymo informacija
                  </div>
               </div>
               <div class="d-flex mb-3">
                  <div class="product_desc_text1">
                     Pasirinkite prekių pristatymo ar atsiėmimo būdą.
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
         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="custom-control custom-radio">
                 <input type="radio" id="courierOneRadio" name="customCourierOneRadio" class="custom-control-input">
                 <label class="custom-control-label checkout_txt3" for="courierOneRadio">NEMOKAMAS PRISTATYMAS* <span class="courrier_extra_small">per kurjerių tarnybą visoje Lietuvoje</span></label>
                 <div class="courrier_extra_small">
                    *jei Jūsų užsakymo suma didesnė nei 25 eur. Kitais atvejais pristatymo mokestis yra 1.99 eur.
                 </div>
               </div>
               <div class="custom-control custom-checkbox mt-2 ml-4">
                 <input type="checkbox" class="custom-control-input" id="repCompanyCheck">
                 <label class="custom-control-label courrier_extra_small" for="repCompanyCheck">Mokėsiu grynais kurjeriui pristačius prekes (+1.29 eur)</label>
               </div>
               <div class="custom-control custom-radio mt-3">
                 <input type="radio" id="courierTwoRadio" name="customCourierTwoRadio" class="custom-control-input">
                 <label class="custom-control-label checkout_txt3" for="courierTwoRadio">ATSIĖMIMAS <span class="courrier_extra_small">Vilniuje, YZIpet parduotuvėje, Antakalnio g. 38 (Kaip mus rasti?)</span></label>
                 <div class="courrier_extra_small">Atsiimant taikoma papildoma nuolaida, kuri automatiškai bus pridėta prie Jūsų krepšelio.</div>
                 <div class="courrier_extra_small">Atsiimant prekes atsiskaitysite grynais arba mokėjimo kortele.</div>
               </div>
            </div>
         </div>
      </div>


      <div class="container">
         <div class="row pb-3">
            <div class="col-sm-12 col-md-12 col-lg-12">
               <div class="d-flex">
                  <div class="checkout_title_txt2">Pristatymo adresas</div>
               </div>
               <div class="d-flex">
                   <div class="courrier_extra_small">Pastaba: jei pageidaujate pakeisti pristatymo adresą - tiesiog įrašykite jį apačioje.</div>
               </div>
            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12">

               <div class="form-row align-items-center">
                 <div class="col-sm-4 pb-4">
                   <label class="checkout_labels" for="inputCity">Miestas</label>
                   <input type="text" class="form-control" id="cityInputField">
                 </div>
                 <div class="col-sm-4 py-1">
                 <label class="checkout_labels" for="inputAddress">Adresas</label>
                 <input type="text" class="form-control" id="addressInputField">
                 <small id="telHelpBlock" class="form-text text-muted label_help_block">
                    gatvė, namo nr.- buto nr.
                 </small>
                 </div>
                 <div class="col-sm-4 py-1">
                 <label class="checkout_labels" for="inputPostCode">Pašto kodas*</label>
                 <input type="text" class="form-control" id="postCodeInputField">
                 <small id="telHelpBlock" class="form-text text-muted label_help_block">
                    Pvz: 12345
                 </small>
                 </div>
               </div>

               <div class="form-row align-items-center">
                 <div class="col-sm-5 py-1">
                 <label class="checkout_labels" for="inputAdditionalInfo">Pristatymui reikalinga informacija</label>
                 <textarea class="form-control" id="additionalInfoInputField" rows="3"></textarea>
                 <small id="telHelpBlock" class="form-text text-muted label_help_block">
                    Pvz: laiptinės durų kodas
                 </small>
                 </div>
               </div>

            </div>
         </div>
      </div>

      <div class="container">
         <div class="row pb-4">
            <div class="col-sm-6 col-md-6 col-lg-6">
               <div class="d-flex">
                  <a class="chekcout_buy_more_link" href="index.php"><img class="img-fluid chekcout_buy_more_img" src="{{ asset('images/prev_arr.png') }}" alt="yzipet" />Grįžti</a>
               </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6">
               <div class="d-flex">
                  <div class="ml-auto">
                      <button type="submit" class="btn total_sum_btn">Tęsti</button>
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
