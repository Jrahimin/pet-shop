<template>
    <div>

        <div id="scroll_nav_block">
            <div class="container">
                <div class="row py-2">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="d-flex">
                            <div class="input-group input-group-sm my-2">
                                <input class="form-control border-right-0 border" type="search" v-model="keyword" id="example-search-input" @keyup.enter="searchProduct">
                                <span class="input-group-append"><div class="input-group-text search_field_icon"><i class="fa fa-search"></i></div></span>
                            </div>
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

                                <div class="cart_circle_shape"><a href="#"><div class="cart_circle_icon"></div></a></div>
                                <div class="cart_circle_counter_container">
                                    <div class="cart_circle_counter1">{{ cartItemNumber }}</div>
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
            <div class="row pb-4">
                <div class="col-sm-6 col-md-4 col-lg-3 mb-3" v-for="(newProduct,index) in newProducts">
                    <div id="home_product_item_frame">
                        <div class="d-flex justify-content-center">
                            <a class="home_product_item_picframe" href="#"><img class="img-fluid" :src="newProduct.image" alt="yzipet" /></a>
                        </div>
                        <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                            <a class="" href="#"><img class="img-fluid home_product_com_logo" :src="newProduct.manufacturerPhoto" alt="yzipet" /></a>
                        </div>
                        <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                            <a class="home_product_title_link" :href="newProduct.detailLink" >{{ newProduct.title }}</a>
                        </div>
                        <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                            <span class="home_product_price"> {{ newProduct.selectedPackagePrice }}</span>
                            <span class="home_product_currency">eur</span>
                        </div>


                        <div class="mb-2" v-if="newProduct.packageLength>0">
                            <div class="dropdown">
                                <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{newProduct.selectedPackageName}}
                                    <i class="fas fa-angle-down fa-border"></i>
                                </button>
                                <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                                    <a v-for="(newpackage,pindex) in newProduct.packages" class="dropdown-item" href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,'new',newpackage.kaina)">{{ newpackage.pavadinimas }}</a>
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
                                <button v-if="added.includes(newProduct.id)==false" @click="addToCart(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
                                <button v-if="added.includes(newProduct.id)" @click="changeQuantityOrAdd(index, newProduct.id, 'new_product')" class="btn btn-block add_to_cart_btn">Į krepšelį</button>
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
                        <a :href="bestBanner.link"><img  @click="increaseClickCount(bestBanner.id)"  class="" ><img class="card-img-top" :src="bestBanner.img" alt="yzipet" /></a>
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
            <div class="row pb-4">
                <div class="col-sm-6 col-md-4 col-lg-3 mb-2" v-for="(bestProduct,index) in bestProducts">
                    <div id="home_product_item_frame_2">
                        <div class="d-flex justify-content-center">
                            <a class="home_product_item_picframe" href="#"><img class="img-fluid" :src="bestProduct.image" alt="yzipet" /></a>
                        </div>
                        <div class="d-sm-flex d-md-flex d-lg-flex mt-4 home_product_com_logo">
                            <a class="" href="#"><img class="img-fluid home_product_com_logo" :src="bestProduct.manufacturerPhoto" alt="yzipet" /></a>
                        </div>
                        <div class="d-sm-flex d-md-flex d-lg-flex mt-3">
                            <a class="home_product_title_link" :href="bestProduct.detailLink" >{{ bestProduct.title }}</a>
                        </div>
                        <div class="d-sm-inline-flex d-md-inline-flex d-lg-inline-flex mb-3">
                            <span class="home_product_price"> {{ bestProduct.selectedPackagePrice }}</span>
                            <span class="home_product_currency">eur</span>
                        </div>


                        <div class="mb-2" v-if="bestProduct.packageLength>0">
                            <div class="dropdown">
                                <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{bestProduct.selectedPackageName}}
                                    <i class="fas fa-angle-down fa-border"></i>
                                </button>
                                <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton2">
                                    <a v-for="(newpackage,pindex) in bestProduct.packages" class="dropdown-item" href="#" @click.prevent="setPackage(index, pindex,newpackage.id,newpackage.pavadinimas,'best',newpackage.kaina)">{{ newpackage.pavadinimas }}</a>
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
                                <button v-if="added.includes(bestProduct.id)==false" @click="addToCart(index, bestProduct.id, 'best_products')" class="btn btn-block add_to_cart_btn">@lang('Į krepšelį')</button>
                                <button v-if="added.includes(bestProduct.id)" @click="changeQuantityOrAdd(index, bestProduct.id, 'best_products')" class="btn btn-block add_to_cart_btn">@lang('home.Remove')</button>
                            </div>
                        </div>
                    </div>
                </div></div>
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
                        <h5 class="feature_text_center">Kodėl verta rinktis Yzipet</h5>
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
                            {{ advantage.description }}
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
                                    <a href="index.php"><img class="img-fluid footer_info_icon"  alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="">
                                        <span class="footer_info_txt1">Yzipet</span> <span class="footer_info_txt2">Vilniuje:</span>
                                    </div>
                                    <div class="footer_info_txt3">{{ contacts.adresas }}</div>
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

                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        {{ contacts.work_hours }}
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

                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        {{ contacts.telefonas }}
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

                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        {{ contacts.email }}
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

                    </div>
                </div>
            </div>

        </div>

    </div>
</template>


<script>
    //import Vue from 'vue';
    import axios from 'axios';
    import VueCarousel from 'vue-carousel';

    /*Vue.use(VueCarousel);
    Vue.use(VueCarousel.Carousel);
    Vue.use(VueCarousel.Slide);*/

    export default {
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
            };
        },
        created(){
            console.log('helloo');
            this.getNewProducts();
            this.getBestProducts();
            this.getBestProductBanners();
            this.getNewProductBanners();
            this.getCartProductNumbers();
            this.getAdvantages();
            this.getContact();
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
                        console.log(this.newProducts[productIndex].selectedPackageId);
                    }
                    else {
                        this.bestProducts[productIndex].selectedPackageName = name;
                        this.bestProducts[productIndex].selectedPackageId = id;
                        this.bestProducts[productIndex].selectedPackagePrice = price;
                    }

                },

                searchProduct()
                {
                    if(this.keyword==''){
                        alert("Please provide a keyword");
                        return;
                    }
                    this.$router.push({name:'filteredProductList', params:{keyword:this.keyword}})
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
                        // console.log(response.data)
                        this.newProducts=response.data;
                    })
                },
                getBestProducts()
                {
                    axios.get('get-best-products').then(response=>{
                        //console.log(response.data)
                        this.bestProducts=response.data;
                    })
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
                                that.getCartProductNumbers();
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
                        this.cartItemNumber = response.data;

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



            },
        components:{ VueCarousel }
    }

</script>