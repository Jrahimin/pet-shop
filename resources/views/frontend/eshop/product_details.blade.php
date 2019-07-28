@extends('frontend.layouts.master')

@section('content')

    <div>

    <div id="productPage">
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

                                <div class="cart_circle_shape"><a href="{{route('cart_index')}}"><div class="cart_circle_icon"><ion-icon name="cart"></ion-icon></div></a></div>
                                <div class="cart_circle_counter_container">
                                    <div class="cart_circle_counter1" ref="totalItemNumber">@{{cartItemNumber}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">

            <div style="margin-top: 200px;" v-if="isLoading">
                <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
            </div>
            <div class="row pb-2">

                <div class="col-sm-6 col-md-6 col-lg-6">
                  <div class="row">
                     <div class="col-md-8" id="app">
                          <div class="d-sm-flex mb-2">
                             <div class="ml-auto d-flex align-items-center prod_gallery_frame1">
                                   <img class="img-fluid prod_gallery_frame2" :src="currentImage" id='currentImage' :data-zoom="currentImage" alt="">
                             </div>
                          </div>
                     </div>
                     <div class="col-md-4 mb-2" id="viewZoom"></div>
                        <div class="col-md-12">
                           <div class="d-flex">
                               <div @click="prevImage" class="prev pr-2 align-self-center">
                                  <i class="fas fa-chevron-left"></i>
                               </div>

                                  <div
                                          v-for="(image, index) in  images"
                                          :class="['px-1', (activeImage == index) ? 'active' : '']"
                                          @click="activateImage(index)"
                                  >
                                     <img class="img-fluid rounded" :src="image.thumb">
                                  </div>

                               <div @click="nextImage" class="next pl-2 align-self-center">
                                  <i class="fas fa-chevron-right"></i>
                               </div>
                           </div>
                        </div>
                       <div data-toggle="modal" data-target="#myModal">
                           <i class="fas fa-search-plus"  @click="viewCurrentImage"></i>
                       </div>
                  </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="d-flex mb-3">
                        <div class="product_brand_logos">
                          <img class="img-fluid product_brand_logo_img" :src="product.manufacturerPhoto" alt="yzipet" />
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="product_title_text1">
                             @{{ product.title  }}
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="product_title_text2">
                            @{{ product.catTitle }}
                        </div>
                    </div>

                    <div class="d-flex mb-1" v-if="showColors">
                        <div class="mb-2 product_content_title3">
                            @lang('product_details.Select Color')
                        </div>
                    </div>
                    <div class="d-flex">
                       <div v-for="color in colors">
                          <div class="mr-3">
                             <button   :class="['btn','mb-2','color_swatch_diamond',colorChosen===color.id ? 'color_chosen' : '' ]" :style="{backgroundColor: color.hex_code}"  @click="showAvaibleSizes(color.id)" ></button>
                             <p>@{{ color.name }}</p>
                          </div>
                       </div>
                    </div>


                    <div class="d-flex mb-1" v-if="showSize" >
                        <div class="product_content_title3">
                            @lang('product_details.Select Size')
                        </div>
                    </div>

                    <div class="d-flex">
                       <div v-for="size in sizes">
                          <div class="mr-2">
                            <button  :class="['btn','btn-info','btn-sm',sizeChosen===size.id ? 'size_chosen' : '']"   @click="showAvaiblePackages(size.id)"> @{{ size.name }}</button>
                           </div>
                       </div>
                    </div>

                    <div class="d-flex mb-3" v-if="product.price!=0 && product.selectedPackagePrice">
                        <div class="discount_product_price"><div class="discount_price_linethrough">@{{ product.selectedPackagePrice }}</div></div>
                        <div class="discount_product_currency mr-3"><div class="discount_price_linethrough">Eur</div></div>
                        <div class="product_price">@{{ product.price }}</div>
                        <div class="product_currency">Eur</div>
                    </div>

                    <div class="d-flex mb-3" v-if="product.price!=0 && product.selectedPackagePrice < product.price">

                        <div class="product_price">@{{ product.selectedPackagePrice }}</div>
                        <div class="product_currency">Eur</div>
                    </div>

                    <div class="d-flex my-3" v-if="product.price == 0">

                        <div class="product_price">@{{ product.selectedPackagePrice }}</div>
                        <div class="product_currency">Eur</div>
                    </div>


                    <div class="d-flex mb-4">
                        <div class="w-50">
                            <div class="input-group product_qty_counter_frame" id="spinner">
                                <span class="input-group-btn btn-group-sm mr-2 product_counter_minus">
                                   <button type="button" class="btn product_qty_btn1" data-action="decrement" @click="decrementQuantity"><ion-icon ios="ios-remove" md="md-remove"></ion-icon></button>
                                </span>
                                        <input name="qty" type="text" class="form-control text-center product_counter_input"  v-model="cart.quantity" value="1" min="1" max="999" maxlength="3" enabled>
                                        <span class="input-group-btn btn-group-sm ml-2 product_counter_plus">
                                           <button type="button" class="btn product_qty_btn2" data-action="increment" @click="incrementQuantity" ><ion-icon ios="ios-add" md="md-add"></ion-icon></button>
                                        </span>
                                        <div class="prod_qty_text">
                                            @lang("global.Qty")
                                        </div>
                            </div>
                        </div>

                        <div class="dropdown" v-if="product.packages[0].default != 1">
                            <button class="btn product_atr_dropdown" type="button" id="productAtrDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                @{{ packageName }}
                                <!-- <span class="drop_vert_border"> </span> --> <i class="fas fa-angle-down fa-border"></i></button>
                            <div class="dropdown-menu product_atr_dropdown_show" aria-labelledby="productAtrDropdownButton">
                                <a v-for="package in product.packages" class="dropdown-item" href="#" @click.prevent="setPackage(package.id,package.pavadinimas,package.kaina)" >@{{ package.pavadinimas }}</a>
                            </div>
                        </div>



                    </div>
                    <div class="d-flex mb-4">
                     {{--   <div class="" v-if="packageName != 'Pasirinkimas'">--}}

                            <table class="col-md-12 table table-bordered table-hover" v-if="packageName != 'Pasirinkimas'" >
                                <tbody>

                                <tr v-if="product.selectedPackageColor !=''">
                                    <td>Color</td>
                                    <td> @{{ product.selectedPackageColor}}</td>
                                </tr>

                                <tr  v-if="product.selectedPackageSize !=''">
                                    <td>Size</td>
                                    <td>@{{ product.selectedPackageSize }}</td>

                                </tr>
                                <tr v-if="packageAttributes.length > 0" v-for=" attribute in packageAttributes">
                                    <td v-if="attribute.attribute_id ==1">Capacity</td>
                                    <td v-if="attribute.attribute_id ==2">Volume</td>
                                    <td v-if="attribute.attribute_id ==3">Length</td>
                                    <td v-if="attribute.attribute_id ==4">Diameter</td>
                                    <td>@{{ attribute.value }}  @{{ attribute.unit }}</td>

                                </tr>

                                </tbody>
                            </table>

                        {{--</div>--}}
                    </div>

                    <div class="d-flex mb-3">
                        <div class="">
                           {{-- <button v-if="added.includes(product.id)==false" @click="addToCart(product.id)" class="btn btn-block add_to_cart_btn ">@lang('product_details.Add to cart')</button>--}}
                            <button  @click="changeQuantityOrAdd(product.id)" class="btn btn-block add_to_cart_btn ">@lang('product_details.Add to cart')</button>
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
                        <p v-html="product.info"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                    <div class="col-md-3 m-2" v-if="product.qualityAwards != null">
                         <a :href="product.qualityAwards" target="_blank"  >Quality Awards</a>
                    </div>
                    <div class="col-md-3 m-2" v-if="product.productDescription != null">
                        <a :href="product.productDescription" download >Product Description</a>
                    </div>
                <div class="col-md-3 m-2" v-if="product.additionalFile != null">
                    <a :href="product.additionalFile" download >Additional File</a>
                </div>

            </div>
        </div>
        <div class="container">
            <div class="row">
                <div v-for="video in videos" class="m-1 auto">
                    <iframe  width="350" height="200" :src="video.embedVideoLink"></iframe>
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
                    <div v-for="comment in product.comments">
                    <div class="d-flex mb-3" >
                        <div class="pr-4"><img class="img-fluid" src="{{ asset('images/comment_quotes_icon.png') }}" alt="yzipet" /></div>
                        <div   class="px-3 comment_text">

                                  @{{ comment.description }}

                        </div>


                    </div>

                    <div class="d-flex pl-5 mb-3">
                        <div class="pl-4 comment_author"  >
                            @{{ comment.title }}
                        </div>
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
            <div class="col-sm-12 col-md-12 col-lg-12">

                <Carousel :per-page="3"  :navigation-enabled="true"  >
                    <Slide v-for="relatedProduct in relatedProducts" class="col-md-4">


                            <img  :src="relatedProduct.image"  height="150px">

                        <p  style="text-align:left;"><a :href="relatedProduct.detailLink" >@{{ relatedProduct.title }}</a></p>

                    </Slide>
                </Carousel>

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

        <div class="modal fade" id="myModal" >
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <img :src="modalImage" style="width: 100%; height: 100%;">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('product_details.Close')</button>
                    </div>

                </div>
            </div>
        </div>




    </div>
    </div>

@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset("js/vue-carousel-0.10.0/dist/vue-carousel.min.js")}}"></script>
    <script src="{{asset('js/drift-1.2.0/dist/Drift.js')}}"></script>

    <script>
        Vue.use(axios);
        Vue.use(VueCarousel);
        Vue.use(VueCarousel.Carousel);
        Vue.use(VueCarousel.Slide);
        var ClipLoader = VueSpinner.ClipLoader;

        new Vue({
           el:"#productPage",
            data: function(){
                return {
                    product :{

                    },
                    added:[],
                    modalOccur:false,
                    relatedProducts:[],
                    contacts :{},
                    cart:{
                        quantity:1,
                        package:'',
                        productId:''
                    },

                    packageName :'Pasirinkimas',

                    images:[],
                    activeImage: -1,
                    keyword:'',
                    cartItemNumber:0,
                    modalImage :'',
                    productId : '',
                    discounts :[],
                    colors :[],
                    sizes :[],
                  /*  capacities :[],
                    volumes :[],
                    lengths :[],
                    diameters :[],*/
                 /*   showVolume:false,
                    showLength :false ,
                    showDiameter : false ,*/
                    color_id:0 ,
                    size_id : 0,
                    capacity :0,
                    volume :0 ,
                    length :0,
                    diameter :0,
                    showColors :true,
                    showSize : true ,
                    packages :[],
                    videos :[],
                    colorChosen :'',
                    sizeChosen:'',
                    isLoading : true,
                  /*  allvolumes:[],
                    alllengths:[],
                    alldiameters:[],*/
                    packageAttributes :[],
                };
            },
            components: {
                'carousel': VueCarousel.Carousel,
                'slide': VueCarousel.Slide,
                ClipLoader
            },
            computed: {
                currentImage() {
                    if(this.activeImage>=0)
                        return this.images[this.activeImage].big;
                }
            },
            methods:
                {
                    nextImage() {
                        var active = this.activeImage + 1;
                        if(active >= this.images.length) {
                            active = 0;
                        }
                        this.activateImage(active);
                    },
                    prevImage() {
                        var active = this.activeImage - 1;
                        if(active < 0) {
                            active = this.images.length - 1;
                        }
                        this.activateImage(active);
                    },
                    activateImage(imageIndex) {
                        this.activeImage = imageIndex;
                    },
                    viewCurrentImage(){
                       this.modalImage = this.images[this.activeImage].big ;
                    },

                    showAvaibleSizes(color)
                    {
                        this.color_id = color ;
                        this.colorChosen = color ;
                       /* this.getPackage(this.color_id,this.size_id,this.capacity,this.volume, this.length , this.diameter);*/
                       this.sizes = [];
                      axios.post('../get-available-sizes',{color:color,product : this.product}).then(response=>{
                          this.sizes = response.data.sizes ;
                          this.product.packages = response.data.packages ;
                          if(this.sizes.length == 0)
                          {
                              this.showSize = false ;
                              this.showAvaiblePackages(0)
                          }

                     /*     if(this.sizes.length == 1 && this.sizes[0].name == 'One Size')
                          {
                              this.showSize = false ;
                              this.showAvaiblePackages(0)
                          }*/

                      })
                    },
                    showAvaiblePackages(size)
                    {
                        /*this.size_id = size ;
                        this.getPackage(this.color_id,this.size_id,this.capacity,this.volume, this.length , this.diameter);*/

                        this.sizeChosen = size ;

                        axios.post('../get-available-packages',{size:size,product : this.product,color: this.color_id}).then(response=> {
                           /* var attributes = response.data ;*/
                            /*this.capacities =[];
                            this.volumes = [];
                            this.lengths = [];
                            this.diameters = [] ;
                            for(var i = 0 ; i< attributes.length ; i++)
                            {
                                if(attributes[i].type=='Capacity')
                                    this.capacities.push(attributes[i])
                                else if(attributes[i].type=='Volume')
                                    this.allvolumes.push(attributes[i])
                                else if(attributes[i].type == 'Length')
                                    this.alllengths.push(attributes[i])
                                else if(attributes[i].type == 'Diameter')
                                    this.alldiameters.push(attributes[i])
                            }

                            if(this.capacities.length == 0)
                                this.showAvaibleVolumes(0);
*/

                            var packages = response.data ;
                           if(packages.length >0)
                           {
                               this.product.selectedPackageName = packages[0].pavadinimas;
                               this.packageName = this.product.selectedPackageName ;
                               this.product.selectedPackageId = packages[0].id;
                               this.product.selectedPackagePrice = packages[0].kaina;
                               this.product.packages = packages ;
                               this.product = this.calculatedDiscountedPrices(this.product,this.discounts);
                           }
                           else {
                               this.product.packages = this.packages ;
                           }




                        });
                    },


                    getProduct()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('product-detail');
                        var length = url_string.length;

                        var product_id = url_string.substr(index+15, length);

                        this.productId = product_id ;

                        axios.get('../get-product/'+product_id).then(response=>{
                            var product = response.data.product ;
                            var discounts = response.data.discounts ;
                            this.discounts = discounts;
                            this.product=this.calculatedDiscountedPrices(product,discounts);
                            this.packages = this.product.packages ;
                            this.colors = this.product.colors ;
                           /* if(this.colors.length == 1 && this.colors[0].name == 'One Color')
                            {
                                this.showColors = false ;
                                this.showAvaibleSizes(0);
                            }*/

                            if(this.colors.length == 0 )
                            {
                                this.showColors = false ;
                                this.showAvaibleSizes(0)
                            }
                            let photos = [];
                            this.product.gallery.forEach(function (photo, index) {
                                photo = {big:photo.bigImage, thumb:photo.smallImage};
                                photos.push(photo);
                            });
                            this.images = photos;
							if(photos.length>0)
								this.activeImage = 0;

							this.isLoading = false ;
                        })
                    },

                    calculatedDiscountedPrices(product, discounts)
                    {
                        var product = product ;
                        var productDiscount =discounts.productDiscount;
                        var manu_discounts = discounts.manDiscount;

                         product.price = 0;
                            var cat_discount = product.categoryDiscounts ;
                            var price = Number(product.selectedPackagePrice) ;
                            var prices1 =[];
                            var prices2 =[] ;
                            var prices3 = [] ;
                            for(var j = 0; j<productDiscount.length ; j++)
                            {
                                if(productDiscount[j].productId == product.id && productDiscount[j].package_id == product.selectedPackageId )
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
                            if(product.price != 0)
                                product.price = parseFloat(product.price.toFixed(2));




                        return product ;
                    },


                  /*  getPackage(color,size,capacity,volume,length,diameter)
                    {
                      axios.post('../get-package',{color :color,size:size,capacity:capacity,volume:volume,length:length,diameter:diameter,productId : this.productId}).then(response=>{
                          var result = response.data ;

                          if(result.length >1 )
                          {

                          }
                          else {
                             if(result[0] == undefined)
                             {
                                 this.setPackage(result.id, result.pavadinimas , result.kaina);
                                 this.showPrice = true ;
                             }
                             else {
                                 this.setPackage(result[0].id, result[0].pavadinimas , result[0].kaina)
                                 this.showPrice = true ;
                             }
                          }



                      })
                    },*/

                    setPackage(id,name,price)
                    {

                        this.cart.package = id ;
                        this.packageName = name ;
                        this.product.selectedPackagePrice = price ;
                        this.getPackageColorSizeAttribute(id);
                        this.calculatedDiscountedPrices(this.product,this.discounts) ;

                    },
                    getPackageColorSizeAttribute(id)
                    {
                        axios.get('../get-package-color-size-attribute/'+id).then(response=>{
                            this.product.selectedPackageSize = response.data.size ;
                            this.product.selectedPackageColor = response.data.color ;
                            this.packageAttributes = response.data.packageAttributes ;
                            console.log(this.product);
                        })
                    },
                    decrementQuantity()
                    {
                        if(this.cart.quantity>1)
                          this.cart.quantity -- ;
                    },
                    incrementQuantity()
                    {
                        this.cart.quantity ++ ;
                    },
                    getRelatedProducts()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('product-detail');
                        var length = url_string.length;

                        var product_id = url_string.substr(index+15, length);
                      axios.get('../get-related-products/'+product_id).then(response=>{
                          this.relatedProducts = response.data;


                      })
                    },

                    addToCart(productId)
                    {
                       if(this.cart.package=='')
                       {

                         this.cart.package = this.product.selectedPackageId ;
                       }

                        this.cart.productId = productId;

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

                    changeQuantityOrAdd(productId)
                    {
                        this.cart.productId = productId;
                        if(this.cart.package=='')
                        {

                            this.cart.package = this.product.selectedPackageId ;
                        }


                        var exists = false ;

                        axios.post('../if-cart-product-exists',this.cart).then(response=>{
                            exists = response.data ;
                            var that = this ;
                            if(exists==true)
                            {
                                axios.post('../add-item-quantity',this.cart).then(response=>{
                                    this.getCartProductNumbers();
                                });
                            }

                            else {
                                axios.post('../add-to-cart', that.cart).then(response => {
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

                    /*removeFromCart(productId)
                    {
                        let index = this.added.indexOf(productId);
                        this.added.splice(index,1);
                        this.cart.productId = productId ;
                        axios.post('../remove-from-product',this.cart).then(response=>{

                           this.cart.quantity = 0 ;
                           this.cart.package = '';
                           this.packageName = 'Pasirinkimas';
                        })
                    },*/

                    getContact()
                    {
                        axios.get('../get-contacts-home').then(response=>{
                            this.contacts = response.data;
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
                    getCartProductNumbers()
                    {
                        axios.get('../get-cart-number').then(response=>{
                            //Vue.set(this, 'cartItemNumber', response.data)
                            this.$refs.totalItemNumber.innerHTML = response.data
                        })
                    },
                    getVideos()
                    {
                        var url_string = window.location.href;
                        var index = url_string.indexOf('product-detail');
                        var length = url_string.length;

                        var product_id = url_string.substr(index+15, length);
                        axios.get('../get-product-videos/'+product_id).then(response=>{
                            this.videos = response.data ;
                        })
                    }
                },
            created(){
                this.getCartProductNumbers();
                this.getProduct();
                this.getRelatedProducts();
                this.getContact();
                this.getVideos();
                new Drift(document.querySelector("#currentImage"), {
                    paneContainer: document.querySelector("#viewZoom"),
                    inlinePane: 900,
                    inlineOffsetY: 0,
                    containInline: true,
                    hoverBoundingBox: true
                });
            },
            mounted: function(){
				console.log('ok');
				let t = setInterval(() => {
					if (document.readyState === "complete") {
						if( document.querySelector("#currentImage") )
						{
							new Drift(document.querySelector("#currentImage"), {
								paneContainer: document.querySelector("#viewZoom"),
								inlinePane: 900,
								inlineOffsetY: 0,
								containInline: true,
								hoverBoundingBox: true
							});
							clearInterval(t);
						}
					}
				}, 500);
            }

        });

    </script>
@endsection
