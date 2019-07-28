@extends('frontend.layouts.master')

@section('content')

    <div id="eshop-page">
        <router-view></router-view>
    </div>

@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(axios);

        let DefaultProductList = {
            template: `
            <div>
            <div>
                <div class="col-md-6 pull-right">
                      <form class="form-inline" @submit.prevent="searchProduct">
                          <input type="text" v-model="keyword">
                          <router-link class="btn btn-primary btn-sm" :to="{name:'filteredProductList',params:{keyword:keyword}}" >@lang('Search')</router-link>&nbsp;&nbsp;

                          <router-link class="pull-right" :to="{name:'deliveryInfo'}" >@lang("global.Delivery")</router-link>&nbsp; | &nbsp;
                          <router-link class="pull-right" :to="{name:'customerInfo'}" >@lang("global.Information for buyer")</router-link>
                      </form>
                  </div>
                </div>

              <div class="col-md-12">
              <div class="pull-right">
                 <p>@{{ cartItemNumber }}</p>
                <p><a href="{{route('cart_index')}}"><i class="fa fa-shopping-cart fa-4x"></i></a></p>
                </div>
              </div>
             <div>
                   <div v-for="newBanner in newBanners"  class=" card col-md-6">
                       <a :href="newBanner.link" @click="increaseClickCount(newBanner.id)" ><img  :src="newBanner.image"  class="col-md-12"  height="200"></a>

                   </div>
             </div>
             <br/>
             <br/>

              <div id='newproducts'>
                <h3>New Products</h3>
                <div class="card col-md-3 " v-for="newProduct in newProducts">
                 <div class="card-body">
                  <div><img :src="newProduct.image"></div>
                    <p><img :src="newProduct.manufacturerPhoto"></p>
                    <h4 class="card-title"><a :href="newProduct.detailLink" >@{{ newProduct.title }}</a></h4>
                    <p class="card-text">Price @{{  newProduct.price}}</p>
                    <p class="card-text" v-if="newProduct.old_price!=0"> Old Price@{{ newProduct.old_price }}</p>

                    <div  v-if="newProduct.packageLength>0">
                                <select  :id="'package_'+newProduct.id" class="form-control">
                                <option value="">Select a package</option>
                                <option v-for="newpackage in newProduct.packages" :value="newpackage.id">@{{ newpackage.pavadinimas }}</option>
                                </select>
                     </div>


                    <div class="form-group">
                       <label class="control-label col-md-4" for="">@lang('Quantity')</label>
                      <div class="col-md-8">
                           <input type="text" value="1" :id="'quantity_'+newProduct.id" class="form-control">
                       </div>
                     </div>

                       <div>
                           <button v-if="added.includes(newProduct.id)==false" @click="addToCart(newProduct.id,newProduct.packageLength)" class="btn btn-info ">@lang("global.Add to cart")</button>
                           <button v-if="added.includes(newProduct.id)" @click="removeFromCart(newProduct.id)" class="btn btn-danger ">@lang("global.Remove")</button>
                        </div>
                   </div>
                 </div>
               </div>


           <br>
           <br>


              <div >
                   <div v-for="bestBanner in bestBanners"  class=" card col-md-6">
                     <a :href="bestBanner.link"><img  :src="bestBanner.img" class="col-md-12"   height="200"></a>

                   </div>
                </div>

               <div id='bestproducts'>
                <h3>Best Products</h3>
                <div class="card col-md-3" v-for="bestProduct in bestProducts">
                   <div class="card-body">
                    <div><img :src="bestProduct.image"></div>
                    <p><img :src="bestProduct.manufacturerPhoto"></p>
                    <h4 class="card-title"><a :href="bestProduct.detailLink" >@{{ bestProduct.title }}</a></h4>
                    <p class="card-text">Price @{{  bestProduct.price}}</p>
                    <p class="card-text" v-if="bestProduct.old_price!=0"> Old Price @{{ bestProduct.old_price }}</p>
                     <div v-if="bestProduct.packageLength>0" required >

                        <select  :id="'package_'+bestProduct.id" class="form-control">
                          <option v-for="package in bestProduct.packages" :value="package.id">@{{ package.pavadinimas }}</option>
                        </select>
                      </div>

                    <div class="form-group">
                       <label class="control-label col-md-4" for="">@lang("global.Qty")</label>
                       <div class="col-md-8">
                           <input type="text" :id="'quantity_'+bestProduct.id"  class="form-control">
                       </div>
                     </div>
                     <div >
                           <button v-if="added.includes(bestProduct.id)==false" @click="addToCart(bestProduct.id,bestProduct.packageLength)" class="btn add_to_cart_btn ">@lang("global.Add to cart")</button>
                            <button v-if="added.includes(bestProduct.id)" @click="removeFromCart(bestProduct.id)" class="btn add_to_cart_btn ">@lang("global.Remove")</button>
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
                };
            },
            created(){
                this.getNewProducts();
                this.getBestProducts();
                this.getBestProductBanners();
                this.getNewProductBanners();
                this.getCartProductNumbers();
            },
            methods:
                {

                    getNewProducts()
                    {
                        axios.get('get-new-products').then(response=>{
                            //console.log(response.data)
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
                            console.log(response.data) ;
                            this.newBanners =response.data;

                        })
                    },
                    addToCart(productId, packageLength)
                    {
                       //  console.log(typeof (document.getElementById('package_'+productId).value))
                       // if(document.getElementById('package_'+productId)!=null)
                        if(packageLength>0)
                        {
                            if(document.getElementById('package_'+productId).value=='')
                            {
                                alert("You must select a package");
                                return ;
                            }

                            else  this.cart.package = document.getElementById('package_'+productId).value;

                        }

                        else {
                            this.cart.package = '';
                        }
                        this.added.push(productId);
                        this.cart.productId = productId;
                        this.cart.quantity = document.getElementById('quantity_'+productId).value;

                      axios.post('add-to-cart',this.cart).then(response=>{
                                console.log(response.data)
                                this.getCartProductNumbers();
                            })

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
                           console.log(response.data);
                           this.getCartProductNumbers();

                        })
                    },
                    increaseClickCount(id)
                    {
                        axios.get('increase-banner-click/'+id).then(response=>{
                            console.log(response.data);
                        })
                    }



                }
        }

        let FilteredProductList = {
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
                       <label class="control-label col-md-4" for="">@lang("global.Qty")</label>
                      <div class="col-md-8">
                           <input type="text" v-model="cart.quantity" class="form-control">
                       </div>
                     </div>

                       <div>
                           <button @click="addToCart(newProduct.id)" class="btn add_to_cart_btn">@lang("global.Add to cart")</button>
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
                    addToCart(productId)
                    {
                        this.cart.productId = productId;
                        axios.post('add-to-cart',this.cart).then(response=>{
                            //console.log(response.data)
                        })
                    },
                    filterProducts()
                    {
                        if(this.manufactureFilter.length ==0)
                            this.getProducts();
                        axios.post('filter-products-by-manu',{manufacturers:this.manufactureFilter,keyword:this.keyword}).then(response=>{
                            this.newProducts =response.data;
                            console.log(response.data);
                        })
                    }
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


    </script>
@endsection
