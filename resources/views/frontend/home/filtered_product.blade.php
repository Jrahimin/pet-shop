@extends('frontend.layouts.master')

@section('content')
    <div id="filteredProduct">
        <div class="container">
            {{--<div class="row pb-5">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="d-flex">
                        <div class="input-group input-group-sm my-2">
                            <input class="form-control border-right-0 border" type="search" v-model="keyword" id="example-search-input" @keyup.enter="searchProduct">
                            <span class="input-group-append"><div class="input-group-text search_field_icon"><i class="fa fa-search"></i></div></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-5 d-none d-sm-block">
                    <div class="d-flex flex-row justify-content-center">
                        <a class="del_info_link2" href="{{route('delivery_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</a>
                        <div class="px-2"><hr class="vertical_line1"/></div>
                        <a class="del_info_link2" href="{{route('buyer_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</a>
                    </div>
                </div>--}}
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
                                            Iškilo neaiškumų?
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
                    <div class="row py-2">
						<div class="col-sm-12 col-md-3 col-lg-3">
							<div id="product_filterbox_frame">
								<div class="d-sm-flex d-md-flex d-lg-flex mb-3 filter_title_text">
									Gamintojas
								</div>
								<div class="form-check mb-2" v-for="manufacturer in manufacturers">
									<input class="form-check-label filter_item_checkbox" type="checkbox" :value="manufacturer.id" v-model="manufactureFilter" @change="filterProducts">  @{{ manufacturer.title }}
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-9 col-lg-9">
                    <div class="row">
                        <div class="container">
                            <div style="margin-top: 200px;" v-if="isLoading">
                                <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                            </div>
                            <div class="row pb-4">
                                <div class="col-sm-6 col-md-4 col-lg-4 mb-3" v-for="(newProduct,index) in newProducts">
                                    <div id="home_product_item_frame">
                                        <div class="d-flex justify-content-center">
                                            <a class="home_product_item_picframe" :href="newProduct.detailLink"><img class="img-fluid" :src="newProduct.image" alt="yzipet" /></a>
                                        </div>
                                        <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                                            <a class="" href="#"><img class="img-fluid home_product_com_logo" :src="newProduct.manufacturerPhoto" alt="yzipet" /></a>
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


                                        <div class="mb-2" v-if="newProduct.packageLength>0 ">
                                            <div class="dropdown">
                                                <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="overflow-wrap :break-word; word-wrap: break-word;white-space:normal;">
                                                    @{{newProduct.selectedPackageName}}
                                                    <i class="fas fa-angle-down fa-border"></i>
                                                </button>
                                                <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                                                    <a v-for="(newpackage,pindex) in newProduct.packages" class="dropdown-item" href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,'new',newpackage.kaina)">@{{ newpackage.pavadinimas }}</a>
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
                                                {{--<button v-if="added.includes(newProduct.id)==false" @click="addToCart(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">@lang("global.Add to cart")</button>--}}
                                                <button  @click="changeQuantityOrAdd(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">@lang("global.Add to cart")</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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


        </div>


    </div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script type="text/javascript">

        var ClipLoader = VueSpinner.ClipLoader;

        new Vue({
            el:"#filteredProduct",
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
                    keyword: {!! json_encode($keyword) !!},
                    newManudiscount:[],
                    newProductDiscounts:[],
                    added:[],
                    cartItemNumber:0,
                    error:false,
                    contacts :{},
                    packageName:'',
                    isLoading : true ,
                };
            },
            created(){
                this.getProducts();
                this.getCartProductNumbers();
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



                    },
                    getContact()
                    {
                        axios.get('{{route('get_contacts_home')}}').then(response=>{
                            this.contacts = response.data;
                        })
                    },
                    addToCart(productIndex, productId, type)
                    {
                         console.log('hello') ;
                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                        if(type=='new_product')
                            this.cart.package = this.newProducts[productIndex].selectedPackageId;
                        else
                            this.cart.package = this.bestProducts[productIndex].selectedPackageId;


                        axios.post('../add-to-cart',this.cart).then(response=>{
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

                        axios.post('../if-cart-product-exists',this.cart).then(response=>{
                            exists = response.data ;
                            console.log(exists);
                            var that = this ;
                            if(exists==true)
                            {
                                axios.post('../add-item-quantity',this.cart).then(response=>{
                                    that.getCartProductNumbers();
                                });
                            }

                            else {
                                axios.post('../add-to-cart', that.cart).then(response => {
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
                        axios.get('{{route('get_cart_item_number')}}').then(response=>{
                            //Vue.set(this, 'cartItemNumber', response.data)
                            this.$refs.totalItemNumber.innerHTML = response.data
                        })
                    },

                    getProducts()
                    {
                        axios.get('{{route('get_products_by_key', ["keyword"=>$keyword])}}').then(response=>{
                            var newproducts = response.data.products ;
                            var discounts = response.data.discounts ;
                            this.newManudiscount = discounts.manDiscount ;
                            this.newProductDiscounts = discounts.productDiscount  ;
                            this.newProducts=this.calculatedDiscountedPrices(newproducts,discounts);
                            this.isLoading = false ;
                            var that =this;

                            axios.post('{{route('get_products_manufacturer')}}',{products:this.newProducts}).then(response=>{
                                console.log(response.data)
                                that.manufacturers = response.data;
                            })

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
                                console.log(price)
                                price = price > disocuntedPrice3 ? disocuntedPrice3 : price ;
                                console.log(price)
                                bestproducts[i].price = price;
                            }

                            bestproducts[i].price = parseFloat(bestproducts[i].price.toFixed(2));
                        }

                        return bestproducts ;
                    },

                    filterProducts()
                    {
                        if(this.manufactureFilter.length ==0)
                            this.getProducts();
                        axios.post('../filter-products-by-manu',{manufacturers:this.manufactureFilter,keyword:this.keyword}).then(response=>{
                            this.newProducts =response.data;
                        })
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
                },
        })
    </script>

@endsection
