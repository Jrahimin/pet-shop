@extends('layouts.master')
@section('content')
    <div id="app">

        <ul class="nav nav-tabs">
            <li class="nav-item active">
                <a class="nav-link" href="{{route('product_catalog_index')}}"><span>@lang('Goods')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('inventory_index')}}"><span>@lang('Inventory')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('categories_info_index')}}"><span>@lang('Categories')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('delivery_info_index')}}"><span>@lang('Delivery')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('customer_info_index')}}"><span>@lang('Information for the buyer')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('attribute_colors')}}"><span>@lang('Package Colors')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('attribute_sizes')}}"><span>@lang('Package Sizes')</span></a>
            </li>

        </ul>
        <br/>

        <div >
            <router-view></router-view>
        </div>

    </div>

    <div style="clear: both;"></div>


@endsection


@section('additionalJS')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.0.1/vuex.min.js"></script>
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vue-select-2.5.1/vue-select.js')}}"></script>
    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('js/vue-ckeditor2/dist/vue-ckeditor2.js')}}"></script>

    <script>
        CKFinder.config( {connectorPath: '{{route('ckfinder_connector')}}'} );
    </script>
    <script>
        Vue.use(Vuex);
        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        Vue.use(VueCkeditor);
        Vue.component('v-select', VueSelect.VueSelect);


        const store = new Vuex.Store({
            state: {
                currentPage: 1,
                pagination : {},
                keyword :''
            },
            mutations: {
                saveCurrentPage(state,page)
                {
                    state.currentPage = page ;
                },
                 savePagination(state,pagination)
                {
                    state.pagination = pagination ;
                },
                saveSearchKeyword(state,keyword)
                {
                    state.keyword = keyword ;
                },

                deleteCurrentPage(state,page)
                {
                    state.currentPage = 0 ;
                },
                deletePagination(state)
                {
                    state.pagination = {} ;
                },
                deleteSearchKeyword(state)
                {
                    state.keyword = '' ;
                },

                
            }
        })

        var GoodsList = {
            template: `
     <div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'addProduct'}"  class="btn btn-primary">@lang('Create Product')</router-link>
                </div>
            </div>
       </div>

       <div class="filter-box" >
            <div class="row">
            <form @submit.prevent="filterProducts(0)">

               <div class="form-group">
                   <label class="control-label col-md-1">@lang('Search')</label>
                  <div class="col-md-2">
                    <input  name="client" class="form-control" v-model="search"  />
                   </div>
               </div>

               <div class="form-group" >
                  <div class="col-md-2">
                    <button class="btn btn-primary">@lang('Filter')</button>
                  </div>
               </div>
           </form>
            </div>
       </div>

     <div class="box box-primary" style="padding:20px" id="list">
        <div style="margin-top: 200px;" v-if="isLoading">
	        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
        </div>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="good in goods" >
                        <td><img :src="good.image" ></td>
                        <td><a href="#"  @click.prevent="editProduct(good.id)"  >@{{ good.title }}</a></td>
                        <td>@{{ good.categoryNames }}</td>
                        <td>@{{ good.manufacturer }}</td>
                         <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <a href="#"  @click.prevent="editProduct(good.id)"  >@lang('Edit')</a></li>
                                     <li> <a href="#" @click.prevent="editProductGallery(good.id)"  >@lang('Edit Gallery')</a></li>
                                     <li> <a href="#" @click.prevent="showReviewList(good.id)"  >@lang('Reviews')</a></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="good_id=good.id">@lang('Delete')</a></li>
                                    <li> <a href="#"  data-toggle="modal" data-target="#addModal" @click.prevent="getPackages(good.id)">@lang('Add Stock')</li>

                                </ul>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page" v-if="notfiltering" class="col-md-offset-4">
              <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getGoods('get-goods?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>
           </div>
            <div v-if="pagination.total > pagination.per_page" v-if="notfiltering==false" class="col-md-offset-4">
             <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterProducts(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterProducts(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="filterProducts(n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="filterProducts(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>
           </div>

        </div>
        <div id="myModal" class="modal fade"  >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                        <p> Are you sure?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteGood">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="addModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                           <div class="row">
                                <label class="control-label col-md-2" for="title">@lang('Quantity')</label>
                                 <div class="col-md-8">
                                     <input type="text" v-model="quantity" class="form-control">
                                </div>
                           </div>

                            <div class="row">
                                <label class="control-label col-md-2" for="title">@lang('Package')</label>
                                 <div class="col-md-8">
                                     <select  class="form-control" v-model="package">
                                          <option v-for="package in packages" :value="package.id">@{{ package.pavadinimas }}</option>
                                     </select>
                                </div>
                           </div>
                    </div>
                    <div class="modal-footer">
                      <div class="col-md-10">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="addStock" >@lang('Save')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('Cancel')</button>
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
                    goods:[],
                    good_id:'',
                    pagination:{
                        first_page_url: '',
                        from: '',
                        last_page: '',
                        last_page_url: '',
                        next_page_url:'',
                        path: '',
                        per_page: 20,
                        prev_page_url: '',
                        to: '',
                        total: ''
                    },
                    search:'',
                    notfiltering:true,
                    packages :[],
                    quantity:'',
                    package :'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    checkIfFromEdit()
                    {
                        var current_page = store.state.currentPage ;
                        console.log(current_page) ;
                        var keywords = store.state.keyword ;
                        if(current_page != 0 )
                        {
                            if(keywords == '')
                            {
                                this.getGoods('get-goods?page='+current_page);
                            }
                            else {
                                this.search = keywords ;
                                this.filterProducts(current_page) ;
                            }

                            store.commit('deleteCurrentPage');
                            store.commit('deletePagination');
                            store.commit('deleteSearchKeyword');
                        }

                        else {
                            this.getGoods();
                        }

                    },

                    getGoods(pageUrl)
                    {
                        pageUrl = pageUrl == undefined ? 'get-goods' : pageUrl

                        axios.get(pageUrl).then(response=>{
                            this.goods=response.data.data;
                            this.pagination=response.data;
                            this.isLoading = false;
                        })
                    },

                    editProduct(id)
                    {
                        store.commit('savePagination',this.pagination);
                        store.commit('saveCurrentPage',this.pagination.current_page);
                        store.commit('saveSearchKeyword',this.search);
                        this.$router.push({name:'editProduct',params:{id : id}});
                    },

                    editProductGallery(id)
                    {
                        store.commit('savePagination',this.pagination) ;
                        store.commit('saveCurrentPage',this.pagination.current_page);
                        store.commit('saveSearchKeyword',this.search);
                        this.$router.push({name:'editProductGallery',params:{id : id}});
                    },
                    showReviewList(id)
                    {
                        store.commit('savePagination',this.pagination) ;
                        store.commit('saveCurrentPage',this.pagination.current_page);
                        store.commit('saveSearchKeyword',this.search);
                        this.$router.push({name:'reviewList',params:{id : id}});
                    },

                    deleteGood()
                    {
                        console.log(this.good);
                        axios.get('delete-good/'+this.good_id).then(response=>{
                            this.getGoods();
                            this.$toasted.success('Successfully Deleted',{
                                position: 'top-center',
                                theme: 'bubble',
                                duration: 10000,
                                action : {
                                    text : 'Close',
                                    onClick : (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            })
                        })
                    },

                    filterProducts(pageUrl)
                    {
                        this.isLoading = true;
                        this.search = this.search.trim();
                        if(isNaN(pageUrl)==false && pageUrl!=0)
                        {
                            pageUrl =  'get-goods/'+this.search+'?page='+pageUrl ;
                        }

                        pageUrl = pageUrl==0 ? 'get-goods/'+this.search : pageUrl
                        //console.log(pageUrl)
                        axios.get(pageUrl).then(response=>{
                            this.goods = response.data.data ;
                            this.pagination =response.data ;
                            this.notfiltering=false;
                            this.isLoading = false;
                        })

                    },

                    getPackages(id)
                    {
                        this.good_id = id ;
                        axios.get('get-product-packages/'+id).then(response=>{
                            this.packages = response.data ;
                        })
                    },
                    addStock()
                    {
                        this.isLoading = true;
                        axios.post('add-stock',{id:this.package ,product:this.good_id, quantity:this.quantity}).then(response=>{
                            this.isLoading = false;
                            this.getInventoryInfoAll();
                            this.$toasted.success('Successfully Updated', {
                                duration: 10000,
                                position: 'top-center',
                                theme: 'bubble',
                                action: {
                                    text: 'Close',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            });
                        })
                    }


                },
            created(){
                this.checkIfFromEdit();
            }
        }

        var ReviewList = {
            template: `
     <div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'addReview',params:{id : good_id}}"  class="btn btn-primary">@lang('Create Review')</router-link>
                </div>
            </div>
       </div>

     <div class="box box-primary" style="padding:20px" id="list">
        <div style="margin-top: 200px;" v-if="isLoading">
	        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
        </div>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Response</th>
                        <th></th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(review,index) in reviews" >
                        <td>@{{ review.title }}</td>
                        <td>@{{ review.description }}</td>
                        <td>
                           <span v-if="index!=0" @click="goUp(review.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span></br>
                           <span v-if="index!=reviews.length-1" @click="goDown(review.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>
                         <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editReview',params:{id:review.id}}"  >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="review_id=review.id">@lang('Delete')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'goodsList'}"  class="btn btn-primary">@lang('Back To Product List')</router-link>
                </div>
            </div>
       </div>

        <div id="myModal" class="modal fade"  >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                        <p> Are you sure?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteReview">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

`,
            data: function(){
                return {
                    reviews:[],
                    good_id:this.$route.params.id,
                    review_id:'',
                    pagination:{
                        first_page_url: '',
                        from: '',
                        last_page: '',
                        last_page_url: '',
                        next_page_url:'',
                        path: '',
                        per_page: 20,
                        prev_page_url: '',
                        to: '',
                        total: ''
                    },
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getReviews()
                    {
                        axios.get('get-reviews/'+this.good_id).then(response=>{
                            this.reviews=response.data;
                            this.isLoading = false;
                        })
                    },

                    goDown(id)
                    {

                        axios.get('move-review-down/'+id).then(response=>{
                            this.getReviews();
                        })
                    },
                    goUp(id)
                    {
                        axios.get('move-review-up/'+id).then(response=>{
                            this.getReviews();
                        })

                    },

                    deleteReview()
                    {
                        axios.get('delete-review/'+this.review_id).then(response=>{
                            this.getReviews();
                            this.$toasted.success('Successfully Deleted',{
                                position: 'top-center',
                                theme: 'bubble',
                                duration: 10000,
                                action : {
                                    text : 'Close',
                                    onClick : (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            })
                        })
                    },

                },
            created(){
                this.getReviews();
            }

        }


        var AddReview = {
            template :`<div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Add Review</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent="saveReview">
                       <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="review.title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Text</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="review.description">
                            </div>
                        </div>


                          <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  v-model="review.active" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'reviewList',params:{id:review.skiltis}}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data(){
                return {
                  review :{
                      title:'',
                      description:'',
                      active :'',
                      skiltis: this.$route.params.id,
                  },
                    errors:[],
                    isLoading:false,
                }
            },
            components: {
                ClipLoader
            },
            methods:{
                saveReview()
                {
                    this.isLoading = true;
                    if(this.review.active)
                    {
                        this.review.active = 1;
                    }
                    else this.review.active = 0;
                    let that = this;
                    axios.post('save-review',this.review).then(response=>{
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.$router.push({name:'reviewList'}, () => {
                                    this.$toasted.success('Successfully Added', {
                                        duration: 10000,
                                        position: 'top-center',
                                        theme: 'bubble',
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                }
                            );
                        }
                    })
                }
            }
        }

        var EditReview = {
            template :`<div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Review</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent="editReview">
                       <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="review.title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Text</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="review.description">
                            </div>
                        </div>


                          <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="review.active" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'reviewList',params:{id:review.skiltis}}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data(){
                return {
                    review :{
                        title:'',
                        description:'',
                        active :'',
                        skiltis: '',
                        id :this.$route.params.id
                    },
                    errors:[],
                    isLoading:true,
                }
            },
            created(){
               this.getReview();
            },
            components: {
                ClipLoader
            },
            methods:{
                getReview()
                {
                   axios.get('get-review/'+this.review.id).then(response=>{
                       this.review = response.data;
                       this.isLoading = false;
                   })
                },
                editReview()
                {
                    this.isLoading = true;
                    let that = this;
                    axios.post('edit-review',this.review).then(response=>{
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            this.$router.push({name:'reviewList',params:{id: that.review.skiltis}}, () => {
                                    this.$toasted.success('Successfully Updated', {
                                        duration: 10000,
                                        position: 'top-center',
                                        theme: 'bubble',
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                }
                            );
                        }

                    })
                }
            }
        }




        var AddProduct = {
            template: ` <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Add Product</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent=saveProduct>
                       <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="pavadinimas_lt">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Description</label>

                            <div class="col-md-8">
                                <textarea   class="form-control" v-model="description" ></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Meta Key</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="meta_key">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Meta Description</label>

                            <div class="col-md-8">
                                <textarea   class="form-control" v-model="meta_description" ></textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Category</label>

                            <div class="col-md-8">
                                <v-select multiple v-model="cat" :options="categoryList">
                                     <template slot="option" slot-scope="option">
                                            <span v-html="option.valueNoImage"></span>
                                     </template>
                                </v-select>
                            </div>

                        </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">Manufacturer</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="gamintojas"  >
                                <option v-for="manufacturer in manufacturers" :value="manufacturer.id">@{{ manufacturer.title }}</option>
                                </select>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">List Photo</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file" id="file1" ref="fileupload1" >
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Quality Awards</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file" id="file2" ref="fileupload2"/>
                            </div>

                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-2">Product Description</label>

                            <div class="col-md-8">
                                <input  class="form-control" type="file" id="file3" ref="fileupload3"/>
                            </div>

                        </div>
                           <div class="form-group">
                            <label class="control-label col-md-2">Additional Files</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file" id="file4" ref="fileupload4"/>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Visible in e-shop</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="visible_eshop"/>
                            </div>

                        </div>

                        <div v-if="visible_eshop">
                           <div class="form-group">
                                <label class="control-label col-md-2">New Item</label>

                                <div class="col-md-2">
                                    <input  type="checkbox"  value="1" v-model="newitem"/>
                                </div>

                           </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Popular Items</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="popitem"/>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Have Packages</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="havpacks"/>
                            </div>

                        </div>
                        </div>




                        <div v-if="havpacks" class="form-group">
                          <label class="control-label col-md-2">Packages</label>

                        <div class="col-md-10">



                        <div  class="panel panel-info" v-for="(package,index) in packages" >
                           <div class="panel-heading">
                              Package Details

                                 <div class="pull-right">
                                     <span  @click="down(package,index+1)" v-if="index != packages.length-1"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-down fa-2x"></i></span>
                                      <span @click="up(package,index-1)" v-if="index != 0"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span>
                                </div>

                           </div>

                           <div class="panel-body">


                                     <div  class="form-group">
                                        <label class="col-md-2">Product properties</label>

                                        <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="package.hasSize" @change="changeAttribute('size',index,package.hasSize)" /> Size
                                        </div>

                                        <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="package.hasColor" @change="changeAttribute('color',index,package.hasColor)"/> Color
                                        </div>

                                        <div class="col-md-2">
                                            <input  type="checkbox" :true-value="1" :false-value="0"  v-model="package.hasCapacity" @change="changeAttribute('capacity',index,package.hasCapacity)"/> Capacity
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox" :true-value="1" :false-value="0"  v-model="package.hasVolume" @change="changeAttribute('volume',index,package.hasVolume)"/> Volume
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="package.hasLength" @change="changeAttribute('length',index,package.hasLength)"/> Length
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="package.hasDiameter" @change="changeAttribute('diameter',index,package.hasDiameter)"/> Diameter
                                        </div>



                                    </div>

                                     <div class="form-group">
                                            <label class="col-md-2">Title</label>
                                             <div class="col-md-6">
                                                    <input class="form-control"  type="text" v-model="package.pavadinimas" placeholder="Title" required>
                                             </div>

                                     </div>
                                     <div class="form-group">
                                        <label class="col-md-2">Weight</label>
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" v-model="package.svoris" placeholder="Weight" required>

                                        </div>
                                     </div>
                                     <div class="form-group">
                                        <label class="col-md-2">Price</label>
                                         <div class="col-md-6">
                                            <input class="form-control" type="text" v-model="package.kaina" placeholder="Price" required>
                                         </div>
                                     </div>

                                     <div class="form-group" v-if="package.hasSize == 1">
                                         <label class="col-md-2">Size</label>

                                         <div class="col-md-6">
                                              <select class="col-md-12 form-control" type="text" v-model="package.size_id"  required>
                                                    <option v-for="size in sizes" :value="size.id">@{{ size.name }}</option>
                                             </select>
                                          </div>
                                     </div>

                                     <div class="form-group" v-if="package.hasColor == 1">
                                         <label class="col-md-2">Color</label>

                                         <div class="col-md-6">
                                             <select class="col-md-12 form-control" type="text" v-model="package.color_id" required>

                                                    <option v-for="color in colors" :value="color.id">@{{ color.name }}</option>
                                            </select>
                                          </div>
                                     </div>

                                     <div class="form-group"   v-for="attribute in package.attributes">
                                        <label class="col-md-2" v-if="attribute.attribute_id==1">Capacity</label>
                                        <div class="col-md-3"  v-if="attribute.attribute_id==1"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Capacity"  required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==1"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"  required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==2">Volume</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==2"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Volume"  required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==2"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"  required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==3">Length</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==3"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Length"  required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==3"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"  required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==4">Diameter</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==4"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Diameter"  required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==4"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"  required>  </div>
                                    </div>


                                    <div class="form-group" v-if="packages.length >1">
                                        <div class="col-md-8 text-right">
                                           <button class="col-md-12" class="btn btn-danger" @click.prevent="removePackages(index)" >Remove</button>
                                       </div>
                                    </div>

                           </div>
                        </div>

                         <button class=" btn btn-info" @click.prevent="morePackages">Add Packages</button>
                        </div>
                        </div>

                        <div v-if="!havpacks && visible_eshop==true">

                        <div class="form-group">
                            <label class="control-label col-md-2">Price</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="price">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Weight</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="svoris">
                            </div>

                        </div>
                       </div>




                       <div class="form-group">
                       <label class="control-label col-md-2" for="">Full Description</label>
                       <div class="col-md-8">
                            <vue-ckeditor v-model="full_text" :config="config" @blur="onBlur($event)" @focus="onFocus($event)"/>
                        </div>
                       </div>

                      <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                      </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'goodsList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {

                    visible_eshop:0,
                    meta_description:'',
                    cat:[],
                    gamintojas:'',
                    pavadinimas_lt:'',
                    description:'',
                    meta_key:'',
                    full_text:'',
                    newitem:'',
                    popitem:'',
                    havpacks:'',

                    packages:[
                        {
                            pavadinimas :'',
                            svoris :'',
                            kaina :'',
                            attributes : [],
                            color_id :0 ,
                            size_id :0 ,
                            hasCapacity:0,
                            hasVolume:0,
                            hasLength:0,
                            hasDiameter:0,
                            hasSize:0,
                            hasColor:0,
                            default:0,
                            position :1 ,
                        }
                    ],
                    price:'',
                    svoris:'',
                    categoryList:[],
                    manufacturers:[],
                    colors :[],
                    sizes  :[],
                    config: {
                        toolbar :[
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                            { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                            '/',
                            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                        ],
                        height: 250,
                        width: 720,
                    },
                    errors :[],
                    isLoading:false,
                };
            },
            created()
            {
              this.getCategories();
              this.getManufacturers();
              this.getColors() ;
              this.getSizes() ;
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    saveProduct()
                    {
                        this.isLoading = true;

                        if(this.visible_eshop)
                            this.visible_eshop=1;
                        else this.visible_eshop=0;

                        if(this.popitem)
                            this.popitem=1;
                        else this.popitem=0;

                        if(this.newitem)
                            this.newitem=1;
                        else this.newitem=0;

                        if(this.havpacks)
                            this.havpacks=1;
                        else this.havpacks=0;

                        var categories= [];

                      /*  for(var j = 1 ; j<this.ptitle.length ; j++)
                        {
                            var str = this.ptitle[j];
                            this.ptitle[j] = str.replace(',','_')
                        }*/



                        for(var i =0 ; i<this.cat.length ; i++)
                        {
                            categories.push(this.cat[i].id);
                        }

                        const fileInput = document.querySelector( '#file1' );
                        const fileInput2 = document.querySelector( '#file2' );
                        const fileInput3 = document.querySelector( '#file3' );
                        const fileInput4 = document.querySelector( '#file4' );
                        const formData = new FormData();

                        formData.append( 'file1', fileInput.files[0] );
                        formData.append( 'file2', fileInput2.files[0] );
                        formData.append( 'file3', fileInput2.files[0] );
                        formData.append( 'file4', fileInput2.files[0] );
                        formData.append( 'eshop', this.visible_eshop );
                        formData.append( 'meta_description', this.meta_description );
                        formData.append( 'meta_key', this.meta_key );
                        formData.append( 'description', this.description );
                        formData.append( 'cat', categories );
                        formData.append( 'gamintojas', this.gamintojas );
                        formData.append( 'pavadinimas_lt', this.pavadinimas_lt );
                        formData.append( 'svoris', this.svoris );
                        formData.append( 'price', this.price );
                       /* formData.append( 'pcolors', this.pcolors );
                        formData.append( 'psizes', this.psizes );
                        formData.append( 'pprices', this.pprices );
                        formData.append( 'pweights', this.pweights );
                        formData.append( 'ptitle', this.ptitle );*/
                        formData.append( 'havpacks', this.havpacks );
                        formData.append( 'popitem', this.popitem );
                        formData.append( 'newitem', this.newitem );
                        formData.append( 'full_text', this.full_text );

                       /* formData.append( 'hasSize', this.hasSize );
                        formData.append( 'hasColor', this.hasColor );
                        formData.append( 'hasCapacity', this.hasCapacity );
                        formData.append( 'hasVolume', this.hasVolume );
                        formData.append( 'hasLength', this.hasLength );
                        formData.append( 'hasDiameter', this.hasDiameter );*/

                        formData.append('packages',JSON.stringify(this.packages));
                        /*formData.append( 'pcolors', this.pcolors );
                        formData.append( 'psizes', this.psizes );
                        formData.append( 'pcapacity', this.pcapacity );
                        formData.append( 'pcapacityUnit', this.pcapacityUnit );
                        formData.append( 'pvolume', this.pvolume );
                        formData.append( 'pvolumeUnit', this.pvolumeUnit );
                        formData.append( 'plength', this.plength );
                        formData.append( 'plengthUnit', this.plengthUnit );
                        formData.append( 'pdiameter', this.pdiameter);
                        formData.append( 'pdiameterUnit', this.pdiameterUnit );*/



                        axios.post('save-product', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                    this.$router.push({name:'goodsList'}, () => {
                                            this.$toasted.success('Successfully Added', {
                                                duration: 10000,
                                                position: 'top-center',
                                                theme: 'bubble',
                                                action: {
                                                    text: 'Close',
                                                    onClick: (e, toastObject) => {
                                                        toastObject.goAway(0);
                                                    }
                                                },
                                            });
                                        }
                                    );

                            } )
                    },


                    onBlur (editor) {
                        console.log(editor)
                    },
                    onFocus (editor) {
                        console.log(editor)
                    },

                    changeAttribute(attributeType ,index,value)
                    {

                        if(attributeType == 'color')
                        {
                            if(value == 0 )
                            {
                               this.packages[index].color_id = 0 ;
                            }

                        }
                        else if(attributeType == 'size')
                        {
                            if(value == 0 )
                            {

                                this.packages[index].size_id = 0 ;

                            }
                        }

                        else if(attributeType == 'capacity')
                        {
                                var attributes = this.packages[index].attributes ;
                                if(value == 1)
                                {

                                    attributes.push({
                                            attribute_id :1,
                                            value :'',
                                            unit :'',
                                            id:'',
                                        }

                                    )


                                }
                                else {
                                    for(var j = 0 ; j< attributes.length ; j++)
                                    {
                                        if(attributes[j].attribute_id == 1)
                                            attributes.splice(j,1)
                                    }
                                }



                        }
                        else if(attributeType == 'volume')
                        {

                            var attributes = this.packages[index].attributes ;
                                if(value ==1)
                                {
                                    attributes.push({
                                            attribute_id :2,
                                            value :'',
                                            unit :'',
                                            id:'',
                                        }

                                    )


                                }
                                else {
                                    for(var j = 0 ; j< attributes.length ; j++)
                                    {
                                        if(attributes[j].attribute_id == 2)
                                            attributes.splice(j,1)
                                    }
                                }



                        }
                        else if(attributeType == 'length')
                        {

                            var attributes = this.packages[index].attributes ;
                                if(value ==1)
                                {
                                    attributes.push({
                                            attribute_id :3,
                                            value :'',
                                            unit :'',
                                            id:'',
                                        }

                                    )

                                }
                                else {
                                    for(var j = 0 ; j< attributes.length ; j++)
                                    {
                                        if(attributes[j].attribute_id == 3)
                                            attributes.splice(j,1)
                                    }
                                }



                        }
                        else if(attributeType == 'diameter')
                        {
                            var attributes = this.packages[index].attributes ;
                                if(value ==1)
                                {
                                    attributes.push({
                                            attribute_id :4,
                                            value :'',
                                            unit :'',
                                            id:'',
                                        }

                                    )

                                }
                                else {
                                    for(var j = 0 ; j< attributes.length ; j++)
                                    {
                                        if(attributes[j].attribute_id == 4)
                                            attributes.splice(j,1)
                                    }
                                }



                        }
                    },

                    morePackages()
                    {
                        this.packages[this.packages.length];
                        this.packages.push({
                                id:'',
                                pavadinimas :'',
                                svoris :'',
                                kaina:'',
                                color_id:0,
                                size_id:0,
                                attributes:[],
                                hasCapacity:0,
                                hasVolume:0,
                                hasLength:0,
                                hasDiameter:0,
                                hasSize:0,
                                hasColor:0,
                                default:0,
                                position: this.packages.length +1
                            }
                        );
                        var lastIndex = this.packages.length ;

                    },
                    removePackages(index)
                    {
                        var previousLength = this.packages.length ;
                        this.packages.splice(index,1) ;

                        if(index != previousLength-1)
                        {
                            for(var i = index ; i<this.packages.length ; i++)
                            {
                                this.packages[i].position = this.packages[i].position-1 ;
                            }
                        }

                        console.log(this.packages);

                    },

                    up(package,previousIndex)
                    {
                        package.position = package.position - 1 ;
                        this.packages[previousIndex].position = this.packages[previousIndex].position+1 ;


                        this.packages.sort(function (obj1 , obj2) {
                            return obj1.position -obj2.position ;
                        })
                        console.log(this.packages);

                    },
                    down(package,nextIndex)
                    {
                        package.position = package.position +1 ;
                        this.packages[nextIndex].position =  this.packages[nextIndex].position -1 ;
                        this.packages.sort(function (obj1 , obj2) {
                            return obj1.position -obj2.position ;
                        })

                    },

                    getCategories() {
                        axios.get('{{ route('get_category_list') }}').then(response=>{
                            this.categoryList = response.data;
                        })
                    },
                    getManufacturers()
                    {
                        axios.get('get-manufacturers').then(response=>{
                            this.manufacturers = response.data;
                        })
                    },

                    getColors()
                    {
                        axios.get('get-colors-for-product').then(response=>{
                            this.colors = response.data ;
                        })
                    },

                    getSizes()
                    {
                        axios.get('get-sizes-for-product').then(response=>{
                            this.sizes = response.data ;
                        })
                    }
                }

        }

        var EditProduct = {
            template: ` <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Product</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent=editProduct>
                       <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="pavadinimas_lt">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Description</label>

                            <div class="col-md-8">
                                <textarea   class="form-control" v-model="description" ></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Meta Key</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="meta_key">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Meta Description</label>

                            <div class="col-md-8">
                                <textarea   class="form-control" v-model="meta_description" ></textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Category</label>

                            <div class="col-md-8">
                                <v-select multiple v-model="cat" :options="categoryList">
                                     <template slot="option" slot-scope="option">
                                            <span v-html="option.valueNoImage"></span>
                                     </template>
                                </v-select>
                            </div>

                        </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">Manufacturer</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="gamintojas"  >
                                <option v-for="manufacturer in manufacturers" :value="manufacturer.id">@{{ manufacturer.title }}</option>
                                </select>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">List Photo</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file"" id="file1" ref="fileupload1" >
                                <img :src="list_photo"></img> Remove It
                                <input type="checkbox"  value="1" v-model="remove1">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Quality Awards</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file" id="file2" ref="fileupload2"/>
                            </div>

                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-2">Product Description</label>

                            <div class="col-md-8">
                                <input  class="form-control" type="file" id="file3" ref="fileupload3"/>
                            </div>

                        </div>
                           <div class="form-group">
                            <label class="control-label col-md-2">Additional Files</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file" id="file4" ref="fileupload4"/>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Visible in e-shop</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="visible_eshop"/>
                            </div>

                        </div>

                      <div v-if="visible_eshop">
                           <div class="form-group">
                            <label class="control-label col-md-2">New Item</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="newitem"/>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Popular Items</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="popitem"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Have Packages</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  value="1" v-model="havpacks"/>
                            </div>

                        </div>
                         </div>




                        <div v-if="havpacks && visible_eshop" class="form-group">
                          <label class="control-label col-md-2">Packages</label>

                        <div class="col-md-10">

                           <div  class="panel panel-info" v-for="(package,index) in packages" v-if="package.default != 1">
                           <div class="panel-heading">
                              Package Details
                              <div class="pull-right">
                             <span  @click="down(package,index+1)" v-if="index != packages.length-1"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-down fa-2x"></i></span>
                              <span @click="up(package,index-1)" v-if="index != 0 && packages[index-1].default !=1"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span>
                              </div>

                           </div>

                           <div class="panel-body">

                                     <div  class="form-group">
                                        <label class="col-md-2">Product properties</label>
                                        <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="packages[index].hasSize" @change="changeAttribute('size',index,package.hasSize)" /> Size
                                        </div>

                                        <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="packages[index].hasColor" @change="changeAttribute('color',index,package.hasColor)"/> Color
                                        </div>



                                        <div class="col-md-2">
                                            <input  type="checkbox" :true-value="1" :false-value="0"  v-model="packages[index].hasCapacity" @change="changeAttribute('capacity',index,package.hasCapacity)"/> Capacity
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox" :true-value="1" :false-value="0"  v-model="packages[index].hasVolume" @change="changeAttribute('volume',index,package.hasVolume)"/> Volume
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="packages[index].hasLength" @change="changeAttribute('length',index,package.hasLength)"/> Length
                                        </div>
                                         <div class="col-md-2">
                                            <input  type="checkbox"  :true-value="1" :false-value="0" v-model="packages[index].hasDiameter" @change="changeAttribute('diameter',index,package.hasDiameter)"/> Diameter
                                        </div>



                                    </div>


                                     <div class="form-group">
                                            <label class="col-md-2">Title</label>
                                             <div class="col-md-6">
                                                    <input class="form-control"    type="text" v-model="package.pavadinimas" placeholder="Title" required>
                                             </div>

                                     </div>
                                     <div class="form-group">
                                        <label class="col-md-2">Weight</label>
                                        <div class="col-md-6">
                                            <input class="form-control"   type="text" v-model="package.svoris" placeholder="Weight" required>

                                        </div>
                                     </div>
                                     <div class="form-group">
                                        <label class="col-md-2">Price</label>
                                         <div class="col-md-6">
                                            <input class="form-control"   type="text" v-model="package.kaina" placeholder="Price" required>
                                         </div>
                                     </div>

                                     <div class="form-group" v-if="package.hasSize == 1">
                                         <label class="col-md-2">Size</label>

                                         <div class="col-md-6">
                                              <select class="col-md-12 form-control" type="text" v-model="package.size_id" required >
                                                    <option v-for="size in sizes" :value="size.id">@{{ size.name }}</option>
                                             </select>
                                          </div>
                                     </div>


                                     <div class="form-group" v-if="package.hasColor == 1">
                                         <label class="col-md-2">Color</label>

                                         <div class="col-md-6">
                                             <select class="col-md-12 form-control" type="text" v-model="package.color_id" required >

                                                    <option v-for="color in colors" :value="color.id">@{{ color.name }}</option>
                                            </select>
                                          </div>
                                     </div>

                                     <div class="form-group"   v-for="attribute in package.attributes">
                                        <label class="col-md-2" v-if="attribute.attribute_id==1">Capacity</label>
                                        <div class="col-md-3"  v-if="attribute.attribute_id==1"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Capacity" required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==1"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==2">Volume</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==2"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Volume"required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==2"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==3">Length</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==3"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Length"required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==3"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit"required>  </div>

                                        <label class="col-md-2" v-if="attribute.attribute_id==4">Diameter</label>
                                        <div class="col-md-3" v-if="attribute.attribute_id==4"> <input class="col-md-12 form-control"  type="text" v-model="attribute.value" placeholder="Diameter"required> </div>
                                        <div class="col-md-3" v-if="attribute.attribute_id==4"> <input class="col-md-12 form-control" type="text" v-model="attribute.unit" placeholder="Unit" required>  </div>
                                    </div>



                                    <div class="form-group" v-if="packages.length >1">
                                        <div class="col-md-8 text-right">
                                           <button class="col-md-12" class="btn btn-danger" @click.prevent="removePackages(index)" >Remove</button>
                                       </div>
                                    </div>

                           </div>
                        </div>

                         <button class=" btn btn-info" @click.prevent="morePackages">Add Packages</button>

                        </div>
                        </div>

                        <div v-if="!havpacks && visible_eshop==true">

                        <div class="form-group">
                            <label class="control-label col-md-2">Price</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="price">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Weight</label>

                            <div class="col-md-8">
                               <input class="form-control" v-model="svoris">
                            </div>

                        </div>
                         </div>




                       <div class="form-group">
                       <label class="control-label col-md-2" for="">Full Description</label>
                       <div class="col-md-8">
                            <vue-ckeditor v-model="full_text" :config="config" @contentDom.once="onContentDom($event)"/>
                        </div>
                       </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                        <ul class="list-unstyled">
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                      </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                             <router-link  :to="{name:'goodsList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {
                    visible_product:0,
                    visible_eshop:0,
                    meta_description:'',
                    cat:'',
                    gamintojas:'',
                    pavadinimas_lt:'',
                    description:'',
                    meta_key:'',
                    full_text:'',
                    newitem:'',
                    popitem:'',
                    havpacks:'',

                    packages:[],
                    price:'',
                    svoris:'',
                    categoryList:[],
                    manufacturers:[],
                    list_photo:'',
                    remove1:'',
                    product_id:this.$route.params.id,
                    errors :[],
                    colors :[],
                    sizes  :[],

                    ckEditorText:'',
                    config: {
                        toolbar :[
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                            '/',
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                            { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                            '/',
                            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                        ],
                        height: 250,
                        width: 720
                    },
                    isLoading:true,
                };
            },
            created(){
                this.getCategories();
                this.getManufacturers();
                this.getProduct();
                this.getColors() ;
                this.getSizes();
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    onContentDom($event){
                        this.full_text = this.ckEditorText;
                    },
                    getProduct()
                    {
                        var that = this ;
                        axios.get('get-product/'+this.product_id).then(response=>{
                            that.visible_product=response.data.inproducts;
                            that.visible_eshop = response.data.eshop;
                            that.cat = response.data.categories;
                            that.gamintojas = response.data.gamintojas;
                            that.pavadinimas_lt = response.data.pavadinimas_lt;
                            that.description = response.data.description;
                            that.full_text = response.data.tekstas_lt;
                            that.ckEditorText = response.data.tekstas_lt;
                            that.newitem = response.data.newitem>0 ? true: false;
                            that.popitem = response.data.popitem>0 ? true: false;
                            that.havpacks = response.data.haspacks>0 ? true: false;
                            that.list_photo = response.data.foto;
                            that.meta_key = response.data.meta.meta_key;
                            that.meta_description = response.data.meta.meta_desc;
                            that.price = response.data.price;
                            that.svoris = response.data.svoris;

                            that.packages = response.data.packages ;
                          /*  this.hasColor = this.packages[0].color_id != null ? 1 : 0 ;
                            this.hasSize  = this.packages[0].size_id != null ? 1 :0 ;
                            var attributes = response.data.packages[0].attributes ;

                            this.hasCapacity = attributes[0].attribute_id == 1? 1 : 0 ;
                            this.hasVolume = attributes[0].attribute_id == 2? 1 : 0 ;
                            this.hasLength = attributes[0].attribute_id == 3? 1 : 0 ;
                            this.hasDiameter = attributes[0].attribute_id == 4? 1 : 0 ;*/
                            //packages info
                              var length = that.packages.length;
                             /* this.packages= length;*/
                              for(var i = 0 ; i<length   ; i++ )
                              {
                                  if(that.packages[i].color_id != null && that.packages[i].color_id != 0 )
                                  {
                                     that.$set(that.packages[i],'hasColor',1);
                                  }

                                  if(that.packages[i].size_id != null && that.packages[i].size_id !=0 )
                                  {
                                      that.$set(that.packages[i],'hasSize',1);

                                  }

                                  var attributes = this.packages[i].attributes ;
                                  if(attributes.length >0)
                                  {
                                      for(var k = 0 ; k< attributes.length ; k++)
                                      {
                                          if(attributes[k].attribute_id == 1)
                                          {
                                              that.$set(that.packages[i],'hasCapacity',1);

                                          }
                                          else if(attributes[k].attribute_id == 2)
                                          {
                                              that.$set(that.packages[i],'hasVolume',1);

                                          }
                                          else if(attributes[k].attribute_id == 3)
                                          {
                                              that.$set(that.packages[i],'hasLength',1);

                                          }
                                          else if(attributes[k].attribute_id == 4)
                                          {
                                              that.$set(that.packages[i],'hasDiameter',1);
                                          }
                                      }
                                  }
                              }
                            this.isLoading = false;
                        })
                    },
                    editProduct()
                    {
                        this.isLoading = true;

                        if(this.visible_product)
                            this.visible_product=1;
                        else this.visible_product=0;

                        if(this.visible_eshop)
                            this.visible_eshop=1;
                        else this.visible_eshop=0;

                        if(this.popitem)
                            this.popitem=1;
                        else this.popitem=0;

                        if(this.newitem)
                            this.newitem=1;
                        else this.newitem=0;

                        if(this.havpacks)
                            this.havpacks=1;
                        else this.havpacks=0;

                        if(this.remove1)
                            this.remove1=1;
                        else this.remove1=0;

                        var categories= [];
                        for(var i =0 ; i<this.cat.length ; i++)
                        {
                            categories.push(this.cat[i].id);
                        }
                       /* for(var j = 1 ; j<this.ptitle.length ; j++)
                        {
                            var str = this.ptitle[j];
                            this.ptitle[j] = str.replace(',','_')
                        }*/


                        const fileInput = document.querySelector( '#file1' );
                        const fileInput2 = document.querySelector( '#file2' );
                        const fileInput3 = document.querySelector( '#file3' );
                        const fileInput4 = document.querySelector( '#file4' );
                        const formData = new FormData();

                        formData.append( 'file1', fileInput.files[0] );
                        formData.append( 'file2', fileInput2.files[0] );
                        formData.append( 'file3', fileInput3.files[0] );
                        formData.append( 'file4', fileInput4.files[0] );
                        formData.append( 'inproducts', this.visible_product);
                        formData.append( 'eshop', this.visible_eshop );
                        formData.append( 'meta_description', this.meta_description );
                        formData.append( 'meta_key', this.meta_key );
                        formData.append( 'description', this.description );
                        formData.append( 'cat', categories );
                        formData.append( 'gamintojas', this.gamintojas );
                        formData.append( 'pavadinimas_lt', this.pavadinimas_lt );
                        formData.append( 'svoris', this.svoris );
                        formData.append( 'price', this.price );

                        formData.append( 'havpacks', this.havpacks );
                        formData.append( 'popitem', this.popitem );
                        formData.append( 'newitem', this.newitem );
                        formData.append( 'full_text', this.full_text );
                        formData.append( 'id', this.product_id );
                        formData.append( 'remove1', this.remove1 );



                        for(var j =0 ; j< this.packages.length ; j++)
                        {
                            if(isNaN(this.packages[j].svoris))
                            {
                                alert('You must provide a valid number for weight of the package');
                                this.isLoading = false;
                                return ;
                            }
                            if(isNaN(this.packages[j].kaina))
                            {
                                alert('You must provide a valid number for price of the package');
                                this.isLoading = false;
                                return ;
                            }
                        }


                        formData.append('packages',JSON.stringify(this.packages));
                       /* formData.append( 'hasSize', this.hasSize );
                        formData.append( 'hasColor', this.hasColor );
                        formData.append( 'hasCapacity', this.hasCapacity );
                        formData.append( 'hasVolume', this.hasVolume );
                        formData.append( 'hasLength', this.hasLength );
                        formData.append( 'hasDiameter', this.hasDiameter );*/




                        axios.post('edit-product', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message ;

                                if(this.errors == undefined)
                                this.$router.push({name:'goodsList'}, () => {
                                        this.$toasted.success('Successfully Updated', {
                                            duration: 10000,
                                            position: 'top-center',
                                            theme: 'bubble',
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    }
                                );

                            } )
                    },

                    changeAttribute(attributeType ,index,value)
                    {

                        console.log(this.packages[index].hasColor)
                        if(attributeType == 'color')
                        {
                            if(value == 0 )
                            {
                                this.packages[index].color_id = 0 ;
                            }

                         console.log(this.packages[index]);
                        }
                        else if(attributeType == 'size')
                        {
                            console.log(this.packages[index])
                            if(value == 0 )
                            {

                                this.packages[index].size_id = 0 ;

                            }
                        }

                        else if(attributeType == 'capacity')
                        {
                            var attributes = this.packages[index].attributes ;
                            if(value == 1)
                            {

                                attributes.push({
                                        attribute_id :1,
                                        value :'',
                                        unit :'',
                                        id:'',
                                    }

                                )


                            }
                            else {
                                for(var j = 0 ; j< attributes.length ; j++)
                                {
                                    if(attributes[j].attribute_id == 1)
                                        attributes.splice(j,1)
                                }
                            }



                        }
                        else if(attributeType == 'volume')
                        {

                            var attributes = this.packages[index].attributes ;
                            if(value ==1)
                            {
                                attributes.push({
                                        attribute_id :2,
                                        value :'',
                                        unit :'',
                                        id:'',
                                    }

                                )


                            }
                            else {
                                for(var j = 0 ; j< attributes.length ; j++)
                                {
                                    if(attributes[j].attribute_id == 2)
                                        attributes.splice(j,1)
                                }
                            }



                        }
                        else if(attributeType == 'length')
                        {

                            var attributes = this.packages[index].attributes ;
                            if(value ==1)
                            {
                                attributes.push({
                                        attribute_id :3,
                                        value :'',
                                        unit :'',
                                        id:'',
                                    }

                                )

                            }
                            else {
                                for(var j = 0 ; j< attributes.length ; j++)
                                {
                                    if(attributes[j].attribute_id == 3)
                                        attributes.splice(j,1)
                                }
                            }



                        }
                        else if(attributeType == 'diameter')
                        {
                            var attributes = this.packages[index].attributes ;
                            if(value ==1)
                            {
                                attributes.push({
                                        attribute_id :4,
                                        value :'',
                                        unit :'',
                                        id:'',
                                    }

                                )

                            }
                            else {
                                for(var j = 0 ; j< attributes.length ; j++)
                                {
                                    if(attributes[j].attribute_id == 4)
                                        attributes.splice(j,1)
                                }
                            }



                        }
                    },


                    onBlur (editor) {
                        console.log(editor)
                    },
                    onFocus (editor) {
                        console.log(editor)
                    },
                    morePackages()
                    {
                        this.packages[this.packages.length];
                        this.packages.push({
                            id:'',
                            pavadinimas :'',
                            svoris :'',
                            kaina:'',
                            color_id:0,
                            size_id:0,
                            attributes:[],
                            hasSize:0,
                            hasColor:0,
                            hasCapacity:0,
                            hasVolume:0,
                            hasLength:0,
                            hasDiameter:0,
                            default:0,
                            position:this.packages.length+1,
                            }
                        );
                        var lastIndex = this.packages.length ;

                        /*console.log(this.packages[lastIndex-1])*/

                       /* if(this.hasCapacity==1)
                        {
                            this.packages[lastIndex-1].attributes.push({
                                attribute_id :1,
                                value :'',
                                unit :'',
                                id:'',
                            })
                        }
                        if(this.hasVolume==1)
                        {
                            this.packages[lastIndex-1].attributes.push({
                                attribute_id :2,
                                value :'',
                                unit :'',
                                id:'',
                            })
                        }
                        if(this.hasLength==1)
                        {
                            this.packages[lastIndex-1].attributes.push({
                                attribute_id :3,
                                value :'',
                                unit :'',
                                id:'',
                            })
                        }

                        if(this.hasDiameter==1)
                        {
                            this.packages[lastIndex-1].attributes.push({
                                attribute_id :4,
                                value :'',
                                unit :'',
                                id:'',
                            })*/
                        }
                    ,
                    removePackages(index)
                    {
                        var previousLength = this.packages.length ;
                        this.packages.splice(index,1) ;

                        if(index != previousLength-1)
                        {
                            for(var i = index ; i<this.packages.length ; i++)
                            {
                                this.packages[i].position = this.packages[i].position-1 ;
                            }
                        }

                        console.log(this.packages);
                    },

                    up(package,previousIndex)
                    {
                        package.position = package.position - 1 ;
                        this.packages[previousIndex].position = this.packages[previousIndex].position+1 ;


                        this.packages.sort(function (obj1 , obj2) {
                            return obj1.position -obj2.position ;
                        })
                        console.log(this.packages);

                    },
                    down(package,nextIndex)
                    {
                       package.position = package.position +1 ;
                       this.packages[nextIndex].position =  this.packages[nextIndex].position -1 ;
                        this.packages.sort(function (obj1 , obj2) {
                            return obj1.position -obj2.position ;
                        })

                    },

                    getCategories() {
                        axios.get('{{ route('get_category_list') }}').then(response=>{
                            this.categoryList = response.data;
                        })
                    },
                    getManufacturers()
                    {
                        axios.get('get-manufacturers').then(response=>{
                            this.manufacturers = response.data;
                        })
                    },
                    getColors()
                    {
                        axios.get('get-colors-for-product').then(response=>{
                            this.colors = response.data ;
                        })
                    },

                    getSizes()
                    {
                        axios.get('get-sizes-for-product').then(response=>{
                            this.sizes = response.data ;
                        })
                    }
                }


        } ;

        var EditProductGallery = {
            template:`
            <div>
            <div class="box box-primary" style="padding:20px" id="list">
            <div v-if="isLoading">
				<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
			</div>
            <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-3">Photo</th>
                            <th class="col-md-6">Title</th>
                            <th class="col-md-6">Link</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(gallery, index) in galleries">
                            <td><img :src="gallery.img"></td>
                            <td>@{{ gallery.pavadinimas }}</td>
                            <td><a :href="gallery.video">@{{ gallery.video }}</a></td>
                            <td>
                                <span v-if="index!=0" @click="goUp(gallery.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span></br>
                                <span v-if="index!=galleries.length-1" @click="goDown(gallery.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                            </td>

                            <td><a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#myModal" @click.prevent="gallery_id=gallery.id">@lang('Delete')</a></td>
                            <td><router-link :to="{name:'editPhoto',params:{id:gallery.id}}"  class="btn btn-info">@lang('Edit')</router-link></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
            <ul class="pagination">
                <li class="page-item">
                <a @click.prevent="getProductGalleries(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getProductGalleries(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getProductGalleries('product/galleries/'+id+'?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getProductGalleries(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li class="page-item">
                <a @click.prevent="getProductGalleries(pagination.last_page_url)" href="#">Last Page</a>
                </li>
            </ul>
        </div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'goodsList'}"  class="btn btn-primary">@lang('Back To Product List')</router-link>
                </div>
            </div>
       </div>

            <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                        <p> Are you sure?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteGallery">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

        </div></div>

                <form class="form-horizontal" @submit.prevent="productGalleryPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Product Gallery</div>
                <div class="panel-body">

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Title')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="productGallery.pavadinimas" class="form-control">
                       </div>
                   </div>

                   <div class="form-group" v-if="hasPacks">
                       <label class="control-label col-md-2" for="">@lang('Packages')</label>
                       <div class="col-md-8">
                           <select  v-model="productGallery.fotopack" class="form-control">
                           <option v-for="package in packages" :value="package.id">@{{ package.pavadinimas }}</option>
                           </select>
                       </div>
                   </div>


                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Youtube link (if video)')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="productGallery.video" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-8 text-right">
                           <router-link :to="{name:'goodsList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                        </div>

                        <div class="col-md-1">
                           <button type="submit" class="btn btn-primary ">@lang('Upload')</button>
                        </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                    <ul>
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                    </div>

                    </div></div></div>
            </form>
            </div>`,
            data(){
                return{
                    productGallery: {
                        pavadinimas: '',
                        video: '',
                        fotopack:''
                    },
                    packages:[],
                    hasPacks:false,
                    galleries: [], errors: [],
                    id: this.$route.params.id, baseDir: '', gallery_id: '',
                    pagination:{
                        from: '',
                        to: '',
                        first_page_url: '',
                        last_page: '',
                        last_page_url: '',
                        next_page_url:'',
                        prev_page_url: '',
                        path: '',
                        per_page: 20,
                        total: ''
                    },
                    length:'', totalPages:'', pageOffset:[], url:'',error:[],
                    isLoading:true,
                }
            },
            created(){
                this.getProductGalleries();
                this.getProductPackages();
            },
            components: {
                ClipLoader
            },
            methods:{
                getProductGalleries(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'product/galleries/'+this.id : pageUrl;
                    let that = this;
                    axios.get(pageUrl).then(response=>{
                        console.log(response.data)
                        that.galleries = response.data.galleries.data;
                        that.baseDir = response.data.base_dir;
                        this.pagination = response.data.galleries;
                        this.isLoading = false;
                    })
                },
                getProductPackages()
                {
                    axios.get('get-product-packages/'+this.id).then(response=>{
                        this.packages =response.data;
                        if(this.packages.length > 0)
                          this.hasPacks =true ;
                    })
                },
                productGalleryPost(){
                    this.isLoading = true;

                    if(this.productGallery.fotopack=='')
                        this.productGallery.fotopack =0 ;

                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('img', imgFile.files[0]);
                    formData.append('straipsnis', this.id);
                    formData.append('pavadinimas', this.productGallery.pavadinimas);
                    formData.append('video', this.productGallery.video);
                    formData.append('fotopack', this.productGallery.fotopack);

                    let that = this;
                    axios.post('add-product-photo', formData).then(function (response) {
                        that.isLoading = false;

                        that.errors = response.data.message;
                        if(that.errors == undefined) {
                            that.getProductGalleries();
                            that.$toasted.success('Successfully Added', {
                                duration: 10000,
                                position: 'top-center',
                                theme: 'bubble',
                                action: {
                                    text: 'Close',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            });
                        }


                    })
                },
                deleteGallery()
                {
                    axios.get('delete-product-gallery/'+this.gallery_id).then(response=>{
                        this.getProductGalleries();
                        this.getProductPackages();
                        this.$toasted.success('Successfully Deleted',{
                            position: 'top-center',
                            theme: 'bubble',
                            duration: 10000,
                            action : {
                                text : 'Close',
                                onClick : (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        })
                    })
                },
                goUp(id)
                {
                    axios.get('product-gallery-up/'+id).then(response=>{
                        this.getProductGalleries();
                        this.getProductPackages();
                    })
                },
                goDown(id)
                {
                    axios.get('product-gallery-down/'+id).then(response=>{
                        this.getProductGalleries();
                        this.getProductPackages();
                    })
                }
            }
        }
        var EditPhoto = {
            template:` <form class="form-horizontal" @submit.prevent="productPhotoEdit" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Product Gallery</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Title')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="productGallery.aprasymas_lt" class="form-control">
                       </div>
                   </div>
                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Packages')</label>
                       <div class="col-md-8">
                           <select  v-model="productGallery.fotopack" class="form-control">
                           <option v-for="package in packages" :value="package.id">@{{ package.pavadinimas }}</option>
                           </select>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Youtube link (if video)')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="productGallery.video" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control"></input>
                           <img :src="image"></img>Remove It
                           <input type="checkbox" v-model="productGallery.removeIt" value="1">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-8 text-right">
                           <router-link :to="{name:'goodsList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                        </div>

                        <div class="col-md-1">
                           <button type="submit" class="btn btn-primary ">@lang('Upload')</button>
                        </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                    <ul class="list-unstyled">
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                    </div>

                    </div></div></div>
            </form>`,


            data(){
                return{
                    productGallery: {
                        aprasymas_lt: '',
                        video: '',
                        removeIt :'',
                        id:'',
                        fotopack:''
                    },
                    image:'',
                    galleries: [], errors: [],
                    id: this.$route.params.id,
                    packages :[],
                    isLoading:true,
                }
            },
            created(){
                this.getProductGalleryPhoto();
            },
            components: {
                ClipLoader
            },
            methods:{
                getProductGalleryPhoto()
                {
                    axios.get('get-photo-info/'+this.id).then(response=>{
                        console.log(response.data);
                       this.productGallery.aprasymas_lt = response.data.pavadinimas;
                       this.productGallery.video = response.data.video;
                       this.productGallery.fotopack = response.data.fotopack;
                       this.packages = response.data.packages;
                       this.image = response.data.img;

                       this.isLoading = false;
                    })
                },
                productPhotoEdit(){
                    this.isLoading = true;
                    this.productGallery.removeIt = this.productGallery.removeIt===true ? 1 : 0 ;
                    if(this.productGallery.fotopack=='')
                        this.productGallery.fotopack =0 ;
                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('img', imgFile.files[0]);
                    formData.append('pavadinimas', this.productGallery.aprasymas_lt);
                    formData.append('video', this.productGallery.video);
                    formData.append('removeIt', this.productGallery.removeIt);
                    formData.append('id', this.id);
                    formData.append('fotopack', this.fotopack);

                    let that = this;
                    axios.post('edit-product-photo', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        console.log(response.data);
                       /* if(that.errors == undefined)
                        {

                            that.$toasted.success('Successfully Updated', {
                                    duration: 10000,
                                    position: 'top-center',
                                    theme: 'bubble',
                                    action: {
                                        text: 'Close',
                                        onClick: (e, toastObject) => {
                                            toastObject.goAway(0);
                                        }
                                    },
                                });

                            that.$router.go();
                        }*/

                    })
                },

            }


        }



        const routes = [

            {
                path: '/',
                component: GoodsList,
                name: 'goodsList'
            },

            {
                path: '/add',
                component: AddProduct,
                name: 'addProduct'
            },
            {
                path: '/edit/:id',
                component: EditProduct,
                name: 'editProduct',
            },
            {
                path: '/edit/gallery/:id',
                component: EditProductGallery,
                name: 'editProductGallery',

            },
            {
                path: '/edit/photo/:id',
                component: EditPhoto,
                name :'editPhoto',
            },
            {
              path : '/review/:id',
              component : ReviewList,
              name : 'reviewList'
            },
            {
                path :'/review/add/:id',
                component : AddReview,
                name :'addReview',
            },
            {
                path :'/review/edit/:id',
                component : EditReview,
                name :'editReview',
            }


        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#app')

    </script>

@endsection