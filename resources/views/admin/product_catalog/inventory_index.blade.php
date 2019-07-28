<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="inventoryInfoPage">

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{route('product_catalog_index')}}"><span>@lang('Goods')</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="{{route('inventory_index')}}"><span>@lang('Inventory')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('categories_info_index')}}"><span>@lang('Categories')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('delivery_info_index')}}"><span>@lang('Delivery')</span></a>
            </li>

            <li class="nav-item ">
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

        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('js/vue-ckeditor2/dist/vue-ckeditor2.js')}}"></script>

    <script>
        CKFinder.config( {connectorPath: '{{route('ckfinder_connector')}}'} );
    </script>

    <script>
        Vue.use(VueCkeditor);
        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        var InventoryInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'inventoryLogList'}" class="btn btn-primary">@lang('View Inventory Log')</router-link>
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
                        <th>@lang('Title')</th>
                        <th>@lang('Package Title')</th>
                        <th>@lang('Manufacturer')</th>
                        <th>@lang('Stock')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(inventoryInfo, index) in inventoryInfoAll">
                        <td>@{{ inventoryInfo.title }}</td>
                         <td>@{{ inventoryInfo.packageTitle }}</td>
                        <td >@{{ inventoryInfo.manufacturer }}</td>

                        <td>@{{ inventoryInfo.quantity }}</td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <a href="#"  data-toggle="modal" data-target="#addModal" @click.prevent="inventoryInfo_id=inventoryInfo.id,product_id=inventoryInfo.product_id">@lang('Add Stock')</li>
                                    <li v-if="inventoryInfo.quantity != null"><a href="#"  data-toggle="modal" data-target="#deleteModal" @click.prevent="inventoryInfo_id=inventoryInfo.id, product_id=inventoryInfo.product_id">@lang('Remove from strock')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4" v-if="notfiltering">
            <ul class="pagination">
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInventoryInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInventoryInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getInventoryInfoAll('get-inventory-info?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInventoryInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInventoryInfoAll(pagination.last_page_url)" href="#">Last Page</a>
                </li>
            </ul>
        </div>

        <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4" v-if="notfiltering==false">
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
                <a @click.prevent="filterProducts(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="filterProducts(pagination.last_page_url)" href="#">Last Page</a>
                </li>
            </ul>
        </div>


        <div id="addModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                       <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Quantity')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="quantity" class="form-control">
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

        <div id="deleteModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Quantity')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="quantity"  class="form-control">
                       </div>
                   </div>

                    </div>
                    <div class="modal-footer">
                        <div class="col-md-10">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteStock" >@lang('Delete')</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('Cancel')</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    inventoryInfoAll: [],
                    inventoryInfo_id: '',
                    product_id:'',
                    quantity :'',
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
                    notfiltering:true,
                    search :'',
                    length:'', totalPages:'', pageOffset:[], url:'',
                    isLoading:true,
                }
            },
            created(){
                this.getInventoryInfoAll();
            },
            components: {
                ClipLoader
            },
            methods:{
                getInventoryInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-inventory-info' : pageUrl;
                    axios.get(pageUrl).then(response=>{
                        this.inventoryInfoAll = response.data.data;
                        this.pagination = response.data;
                        this.isLoading = false;
                    })
                },
                addStock()
                {
                    this.isLoading = true;
                    axios.post('add-stock',{id:this.inventoryInfo_id ,product:this.product_id, quantity:this.quantity}).then(response=>{
                        //this.isLoading = false;
                        this.inventoryInfo_id = '';
                        this.product_id = '';
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
                },

                deleteStock()
                {
                    axios.post('delete-stock',{id:this.inventoryInfo_id ,product:this.product_id, quantity: this.quantity}).then(response=>{
                        this.inventoryInfo_id = '';
                        this.product_id = ''
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
                },
                filterProducts(pageUrl)
                {
                    this.isLoading = true;
                    this.search = this.search.trim();
                    if(isNaN(pageUrl)==false && pageUrl!=0)
                    {
                        pageUrl =  'get-inventory-info/'+this.search+'?page='+pageUrl ;
                    }

                    pageUrl = pageUrl==0 ? 'get-inventory-info/'+this.search : pageUrl
                    //console.log(pageUrl)
                    axios.get(pageUrl).then(response=>{
                        this.isLoading = false;
                        this.inventoryInfoAll = response.data.data ;
                        this.pagination =response.data ;
                        this.notfiltering=false;

                    })

                },


            }
        }

        var InventoryLogList = {
            template: `
             <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'inventoryInfoList'}" class="btn btn-primary">@lang('Go back To Inventory')</router-link>
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
                        <th>@lang('Product')</th>
                        <th>@lang('Package')</th>
                        <th>@lang('Action')</th>
                        <th>@lang('Note')</th>

                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="log in inventoryLog">
                        <td>@{{ log.title }}</td>
                        <td>@{{ log.packageTitle }}</td>
                        <td>@{{ log.actionText }}</td>
                        <td >@{{ log.note }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
            <ul class="pagination">
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInventoryLog(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInventoryLog(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getInventoryLog('get-inventory-log?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInventoryLog(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInventoryLog(pagination.last_page_url)" href="#">Last Page</a>
                </li>
            </ul>
        </div>

    </div></div>
            `,
            data() {
                return {
                    inventoryLog :[],
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
                    length:'', totalPages:'', pageOffset:[], url:'',
                    isLoading:true,
                }
            },
            components: {
                ClipLoader
            },
            methods: {
                getInventoryLog(pageUrl) {

                    pageUrl = pageUrl == undefined ? 'get-inventory-log' : pageUrl;

                      axios.get(pageUrl).then(response=> {
                          this.inventoryLog = response.data.data ;
                          this.pagination = response.data;
                          this.isLoading = false;
                    })
                },
            },
            created(){
                this.getInventoryLog() ;
            }
        };

        const routes = [
            { path: '/', component: InventoryInfoList, name: 'inventoryInfoList' },
            { path: '/inventoryLog', component: InventoryLogList, name: 'inventoryLogList' },

        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#inventoryInfoPage')

    </script>

@endsection