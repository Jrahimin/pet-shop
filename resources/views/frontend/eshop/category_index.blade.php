@extends('frontend.layouts.master')

@section('content')

    <div id="eshop-page">

        <router-view></router-view>
    </div>

@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vue-carousel-0.10.0/dist/vue-carousel.min.js')}}"></script>
    <script>
        Vue.use(axios);
        var ClipLoader = VueSpinner.ClipLoader;


        var DefaultProductList = {
            template: `
         <div>

         <div id="scroll_nav_block" class="my-3">
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
               <div class="row pb-4">
                  <div class="col-sm-7 col-md-7 col-lg-7">
                     <div class="d-inline-flex pagination_filter_text">
                     Rodyti puslapyje:
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#" @click.prevent="showNumberOfProducts(15)">15</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#" @click.prevent="showNumberOfProducts(30)">30</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter" href="#" @click.prevent="showNumberOfProducts(90)">90</a>
                     </div>
                     <div class="d-inline-flex">
                        <a class="pagination_counter_last" href="#" @click.prevent="showAllProducts()">@lang("eshop_category.all")</a>
                     </div>
                  </div>

               </div>
           </div>

           <div class="container">
               <div class="row pb-5">
                  <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                     <div id="product_filterbox_frame">
                        <div class="d-sm-flex d-md-flex d-lg-flex mb-3 filter_title_text">
                           Gamintojas
                        </div>
                           <div class="form-check mb-2" v-for="manufacturer in manufacturers">
                             <input class="form-check-input filter_item_checkbox" type="checkbox" :value="manufacturer.id" v-model="manufactureFilter" @change="filterProducts" :id="'defaultCheck1'+manufacturer.id">
                             <label class="form-check-label filter_item_text" :for="'defaultCheck1'+manufacturer.id">
                             @{{ manufacturer.title }}
                             </label>
                           </div>

                     </div>
                  </div>

                   <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">

                             <div style="margin-top: 200px;" v-if="isLoading">
                                <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                            </div>
                           <div class="row">

                                 <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-2" v-for="(newProduct,index) in newProducts">
                                     <div id="home_product_item_frame">
                                        <div class="d-flex justify-content-center">
                                           <a class="home_product_item_img" :href="newProduct.detailLink"><img class="img-fluid" :src="newProduct.image" alt="yzipet" /></a>
                                        </div>
                                        <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                                           <a class="" href="#"><img class="img-fluid home_product_com_logo" :src="newProduct.manufacturerPhoto" alt="yzipet" /></a>
                                        </div>
                                        <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                                           <p class="home_product_title"><a class="home_product_title_link" :href="newProduct.detailLink">@{{ newProduct.title }}</a></p>
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
                                       <div class="mb-2" v-if="newProduct.packageLength>0 ">
                                            <div class="dropdown">
                                                <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="overflow-wrap :break-word; word-wrap: break-word;white-space:normal;">
                                                    @{{newProduct.selectedPackageName}}
                                                    <i class="fas fa-angle-down fa-border"></i>
                                                </button>
                                                <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                                                    <a v-for="(newpackage,pindex) in newProduct.packages" class="dropdown-item"   href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,newpackage.kaina)">@{{ newpackage.pavadinimas }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                           <div class="prod_qty_field">
                                              <input type="text" class="form-control prod_qty_field_inner" value="1" maxlength="3" :id="'quantity_'+newProduct.id">
                                           </div>
                                           <div class="prod_qty_text mr-5">
                                              @lang("global.Qty")
                                           </div>
                                           <div class="">
                                                <!--<button v-if="added.includes(newProduct.id)==false" @click="addToCart(index, newProduct.id)" class="btn btn-block add_to_cart_btn ">@lang("eshop_category.Add to cart")</button>-->
                                                <button  @click="changeQuantityOrAdd(index, newProduct.id)" class="btn btn-block add_to_cart_btn ">@lang("eshop_category.Add to cart")</button>
                                           </div>
                                        </div>
                                     </div>
                                  </div>

                           </div>
                   </div>
               </div>
           </div>

            <div class="container" v-if="pagination">
               <nav aria-label="product_page_pagination_label">
                  <ul class="pagination justify-content-center">
                  <li class="page-item"><a class="page-link pagination_link" href="#"  @click.prevent = "getPageProducts(firstPageObject,firstPageIndex)">@lang('eshop_category.First')</a></li>

                    <li class="page-item" v-for="(p,index) in page" v-if="index<=current+2 && index>=current-2"><a class="page-link pagination_link" href="#" @click.prevent = "getPageProducts(p,index)">@{{ index +1}}</a></li>


                    <li class="page-item"><a class="page-link pagination_link" @click.prevent = "getPageProducts(lastPageObject,lastIndex)" href="#">@lang('eshop_category.Last')</a></li>
                  </ul>
                </nav>
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
                    added:[],
                    allProducts :[],
                    totalRecords:0,
                    pages:0,
                    page :[],
                    pagination:false ,
                    current : 1,

                    firstPageObject:{},
                    firstPageIndex:'',
                    lastIndex:'',
                    lastPageObject : {},
                    keyword:'',
                    cartItemNumber :0,
                    contacts :{},
                    newManudiscount:[],
                    newProductDiscounts:[],
                    perPage : 0 ,
                    isLoading :true ,

                };
            },
            components: {
                ClipLoader
            },


            methods:
                {



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
                    showAllProducts()
                    {
                       this.pagination = false ;
                       this.perPage = 0 ;
                       this.getProducts();
                    },

                    getProducts()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('category');
                        var length = url_string.length;
                        var category_id = url_string.substr(index+9).replace('#/','');

                       axios.get('../../get-products-by-cat/'+category_id).then(response=>{

                            var newproducts = response.data.products ;
                            var discounts = response.data.discounts ;
                           this.newManudiscount = discounts.manDiscount ;
                           this.newProductDiscounts = discounts.productDiscount  ;
                            this.newProducts=this.calculatedDiscountedPrices(newproducts,discounts);
                            this.isLoading = false;
                            this.allProducts = this.newProducts ;
                            var that =this;

                           axios.post('../../get-products-manufacturer',{products:this.newProducts}).then(response=>{

                               that.manufacturers = response.data;
                           });

                           if(this.perPage != 0)
                           {
                               this.showNumberOfProducts(this.perPage);
                           }

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
                            var cat_discount = bestproducts[i].categoryDiscounts ;
                            var price = Number(bestproducts[i].selectedPackagePrice) ;
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
                           if(bestproducts[i].price != 0)
                              bestproducts[i].price = parseFloat(bestproducts[i].price.toFixed(2));

                        }

                        return bestproducts ;
                    },

                    showNumberOfProducts(number)
                    {
                        //this.allProducts = this.newProducts;
                        this.perPage = number ;
                        let allNewProducts = this.allProducts;
                        this.newProducts = allNewProducts.slice(0,number) ;
                        this.setPagination(number)
                    },
                    setPagination(number)
                    {

                        this.totalRecords = this.allProducts.length ;
                        this.pages = Math.ceil(this.totalRecords /number) ;
                        this.page = [];
                        let object = {start:0,end:number};
                        this.firstPageObject = object ;
                        this.firstPageIndex = 1 ;

                        this.lastIndex = this.pages ;
                        this.page.push(object);
                        console.log(this.page);
                        for(let i = 1 ; i<this.pages ; i++)
                        {
                            let pageObject = {start:this.page[i-1]['end'] , end:this.page[i-1]['end']+number };
                            this.page.push(pageObject);
                        }
                        this.lastPageObject = this.page[this.pages-1] ;
                        // this.page.shift();
                        this.pagination = true ;


                    },

                    getPageProducts(pageObject,index)
                    {
                        this.newProducts =[];
                        this.current = index ;


                        let allProducts = this.allProducts;
                        this.newProducts = allProducts.slice(pageObject.start , pageObject.end);
                        console.log("all products count: "+JSON.stringify(pageObject));
                    },


                    addToCart(productIndex, productId)
                    {


                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                        var  productInfo = this.newProducts[productIndex];

                        productInfo = this.newProducts[productIndex];
                        this.cart.package = productInfo.selectedPackageId;


                        axios.post('../../add-to-cart',this.cart).then(response=>{
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

                    changeQuantityOrAdd(productIndex, productId)
                    {
                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                        var  productInfo = this.newProducts[productIndex];
                        this.cart.package = productInfo.selectedPackageId;
                        var exists = false ;

                        axios.post('../../if-cart-product-exists',this.cart).then(response=>{
                            exists = response.data ;
                            var that = this ;
                            if(exists==true)
                            {
                                axios.post('../../add-item-quantity',this.cart).then(response=>{
                                    this.getCartProductNumbers();
                                });
                            }

                            else {
                                axios.post('../../add-to-cart', that.cart).then(response => {
                                    if (response.data.message == undefined) {
                                        that.added.push(productId);
                                        this.getCartProductNumbers();

                                    }
                                    else {
                                        alert(response.data.message);
                                    }
                                });
                            }
                        });

                    },

                    filterProducts()
                    {
                        /*this.pagination = false ;*/
                        this.isLoading = true ;
                        var url_string = window.location.href;
                        var index = url_string.indexOf('category');
                        var length = url_string.length;

                        var category_id = url_string.substr(index+9).replace('#/','');
                        if(this.manufactureFilter.length ==0)
                            this.getProducts();
                       axios.post('{{route('get_products_by_manufacturer')}}',{manufacturers:this.manufactureFilter,category:category_id}).then(response=>{
                           var newproducts = response.data.products ;
                           var discounts = response.data.discounts ;
                           this.newProducts=this.calculatedDiscountedPrices(newproducts,discounts);
                           this.allProducts = this.newProducts ;
                           if(this.perPage != 0)
                           {
                               this.showNumberOfProducts(this.perPage);
                           }
                           this.isLoading = false ;


                          /* this.newProducts =response.data;
                           console.log(this.newProducts) ;*/
                       })
                    },

                    setPackage(productIndex, packageIndex,id,name,price)
                    {
                        console.log(this.newProducts[productIndex])
                        this.newProducts[productIndex].selectedPackageName = name;
                        this.newProducts[productIndex].selectedPackageId = id;
                        this.newProducts[productIndex].selectedPackagePrice = price;
                        this.calculateDisocunPriceForOneProduct(this.newProducts[productIndex],'new') ;

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

                        var price = Number(product.selectedPackagePrice) ;
                        var prices1 =[];
                        var prices2 =[] ;
                        var prices3 = [] ;
                        for(var j = 0; j<productDiscount.length ; j++)
                        {
                            if(productDiscount[j].productId ==product.id && productDiscount[j].package_id == product.selectedPackageId )
                            {
                                var discountAmount = productDiscount[j].amount != null ?
                                    (product.selectedPackagePrice * productDiscount[j].amount)/100 :  productDiscount[j].fixed_amount ;
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


                      console.log(product);
                    },

                    getCartProductNumbers()
                    {
                        axios.get('../../get-cart-number').then(response=>{
                            //Vue.set(this, 'cartItemNumber', response.data)
                            this.$refs.totalItemNumber.innerHTML = response.data
                        })
                    },
                    getContact()
                    {
                        axios.get('../../get-contacts-home').then(response=>{
                            this.contacts = response.data;

                        })
                    },




                },
            created(){
                this.getProducts();
                this.getCartProductNumbers();
                this.getContact();

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
