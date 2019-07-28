<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="promotionInfoPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vue-select-2.5.1/vue-select.js')}}"></script>
    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('js/vue-ckeditor2/dist/vue-ckeditor2.js')}}"></script>

    <script>
        CKFinder.config( {connectorPath: '{{route('ckfinder_connector')}}'} );
    </script>

    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    <script>
        Vue.use(VueCkeditor);
        Vue.use(Toasted);
        Vue.component('v-select', VueSelect.VueSelect);
        var ClipLoader = VueSpinner.ClipLoader;

        let PromotionInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addPromotionInfo'}" class="btn btn-primary">@lang('Create a post')</router-link>
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
                        <th>@lang('Title')</th>
                        <th>@lang('Code')</th>
                        <th>@lang('Promotion')</th>
                        <th>@lang('Validity')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(promotionInfo, index) in promotionInfoAll">
                        <td>@{{ promotionInfo.title }}</td>
                        <td>@{{ promotionInfo.code }}</td>
                        <td>@{{ promotionInfo.amount_percent }}</td>
                        <td>@{{ promotionInfo.validity }}</td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editPromotionInfo',params:{id:promotionInfo.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="promotionInfo_id=promotionInfo.id">@lang('Delete')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
            <ul class="pagination">
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getPromotionInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getPromotionInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getPromotionInfoAll('get-promotion-info/all?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getPromotionInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getPromotionInfoAll(pagination.last_page_url)" href="#">Last Page</a>
                </li>
            </ul>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deletePromotionInfo">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    promotionInfoAll: [],
                    promotionInfo_id: '',
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
            created(){
                this.getPromotionInfoAll();
            },
            components: {
                ClipLoader
            },
            methods:{
                getPromotionInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-promotion-info/all' : pageUrl

                    axios.get(pageUrl).then(response=>{
                        this.promotionInfoAll = response.data.data;
                        this.pagination = response.data;
                        this.isLoading = false;
                    })
                },
                deletePromotionInfo()
                {
                    axios.get('delete-promotion-info/'+this.promotionInfo_id).then(response=>{
                        this.getPromotionInfoAll();
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
                        });
                    })
                },
            }
        }

        let AddPromotionInfo = {
            template: `<form class="form-horizontal" @submit.prevent="promotionInfoPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-info">
                <div class="panel-heading">Add Promotion Info</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="promotionInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="promotionInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="code">@lang('Code')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="promotionInfo.code" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" value="1" v-model.number="promotionInfo.all_product" @click="productDiv=false">For all Products</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="promotionInfo.all_product" @click="productDiv=true, getProducts()">Specific Products</label>
                   </div>

                   <div v-if="productDiv">
                        <div class="form-group">
                            <label class="control-label col-md-2">Specify Products</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.product" :options="products"></v-select>
                            </div>
                        </div>
                   </div>

                   <div v-else>
                        <div class="form-group">
                            <label class="control-label col-md-2">Non Applicable Manufacturers</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.manufacturers" :options="manufacturerList"></v-select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Non Applicable Categories</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.categoryList" :options="categoryList">
                                    <template slot="option" slot-scope="option">
                                        <span v-html="option.valueNoImage"></span>
                                    </template>
                                </v-select>
                            </div>
                        </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" checked value="1" v-model.number="promotionInfo.show_promotion_percent">Promotion in Percentage</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="promotionInfo.show_promotion_percent">Fixed Amount</label>
                   </div>

                    <div v-if="promotionInfo.show_promotion_percent == 1" class="form-group">
                    <label class="control-label col-md-2" for="amount_percent">@lang('Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="promotionInfo.amount_percent" class="form-control">
                       </div>
                       <label class="control-label"> % </label>
                   </div>

                   <div v-if="promotionInfo.show_promotion_percent == 0" class="form-group">
                    <label class="control-label col-md-2" for="amount_fixed">@lang('Fixed Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="promotionInfo.amount_fixed" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datefrom">@lang('Valid From')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="promotionInfo.datefrom" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datetill">@lang('Valid Till')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="promotionInfo.datetill" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                        <div class="col-md-7">
                            <div class="col-md-1 pull-right">
                                <router-link :to="{name:'promotionInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary ">@lang('Add')</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                    <ul>
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                    </div>

                    </div></div></div>
            </form>`,
            data() {
                return {
                    promotionInfo: {
                        title:'', code:'', amount_percent:'', amount_fixed:'', datefrom:'', datetill:'', active:0, product:[],categoryList:[],
                        manufacturers:[], show_promotion_percent:1, all_product:1,
                    },
                    products:[], categoryList:[], manufacturerList:[],
                    errors: [],
                    productDiv:false, isLoading:false,
                }
            },
            components: {
                ClipLoader
            },
            created(){
                this.getCategories();
                this.getManufacturers();
            },
            methods: {
                promotionInfoPost() {
                    this.isLoading = true;
                    let that = this;
                    if(that.promotionInfo.all_product===0){
                        if(that.promotionInfo.product.length === 0){
                            alert("Please Specify Products for promotion");
                            return;
                        }
                    }
                    axios.post('add-promotion-info/post', this.promotionInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors === undefined){
                            //console.log(response.data)
                            that.$router.push({name:'promotionInfoList'}, () => {
                                that.$toasted.success('Successfully Added',{
                                    position: 'top-center',
                                    theme: 'bubble',
                                    duration: 10000,
                                    action : {
                                        text : 'Close',
                                        onClick : (e, toastObject) => {
                                            toastObject.goAway(0);
                                        }
                                    },
                                });
                            })
                        }
                    })
                },

                getProducts(){
                    axios.get('{{ route('get_all_products') }}').then(response=>{
                        this.products =response.data;
                        let productList = [];
                        this.products.map(function (product) {
                            productList.push({id:product.id, label:product.pavadinimas_lt})
                        })
                        this.products = productList;
                    })
                },
                getCategories() {
                    axios.get('{{ route('get_category_list') }}').then(response=>{
                        this.categoryList = response.data;
                    })
                },
                getManufacturers(){
                    axios.get('{{ route('get_manufacturers') }}').then(response=>{
                        this.manufacturerList =response.data;

                        let manufacturers = [];
                        this.manufacturerList.map(function (manufacturer) {
                            manufacturers.push({id:manufacturer.id, label:manufacturer.title})
                        })
                        this.manufacturerList = manufacturers;
                    })
                },
            }
        }

        let EditPromotionInfo = {
            template: `<form class="form-horizontal" @submit.prevent="promotionInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Promotion Info</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="promotionInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="promotionInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="code">@lang('Code')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="promotionInfo.code" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" value="1" v-model.number="promotionInfo.all_product" @click="productDiv=false">For all Products</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="promotionInfo.all_product" @click="productDiv=true, getProducts()">Specific Products</label>
                   </div>

                   <div v-if="productDiv">
                        <div class="form-group">
                            <label class="control-label col-md-2">Specify Products</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.product" :options="products"></v-select>
                            </div>
                        </div>
                   </div>

                   <div v-else>
                        <div class="form-group">
                            <label class="control-label col-md-2">Non Applicable Manufacturers</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.manufacturers" :options="manufacturerList"></v-select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Non Applicable Categories</label>
                            <div class="col-md-6">
                                <v-select multiple v-model="promotionInfo.categoryList" :options="categoryList">
                                    <template slot="option" slot-scope="option">
                                        <span v-html="option.valueNoImage"></span>
                                    </template>
                                </v-select>
                            </div>
                        </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" checked value="1" v-model.number="promotionInfo.show_promotion_percent">Promotion in Percentage</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="promotionInfo.show_promotion_percent">Fixed Amount</label>
                   </div>

                    <div v-if="promotionInfo.show_promotion_percent == 1" class="form-group">
                    <label class="control-label col-md-2" for="amount_percent">@lang('Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="promotionInfo.amount_percent" class="form-control">
                       </div>
                       <label class="control-label"> % </label>
                   </div>

                   <div v-if="promotionInfo.show_promotion_percent == 0" class="form-group">
                    <label class="control-label col-md-2" for="amount_fixed">@lang('Fixed Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="promotionInfo.amount_fixed" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datefrom">@lang('Valid From')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="promotionInfo.datefrom" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datetill">@lang('Valid Till')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="promotionInfo.datetill" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                        <div class="col-md-7">
                            <div class="col-md-1 pull-right">
                                <router-link :to="{name:'promotionInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary ">@lang('Save')</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                    <ul>
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                    </div>

                    </div></div></div>
            </form>`,
            data() {
                return {
                    promotionInfo: {
                        title:'', code:'', amount_percent:'', amount_fixed:'', datefrom:'', datetill:'', active:0, product:[],categoryList:[],
                        manufacturers:[], show_promotion_percent:''
                    },
                    products:[], categoryList:[], manufacturerList:[],
                    productDiv:false,
                    errors: [], id: this.$route.params.id,
                    isLoading:true,
                }
            },
            created(){
                let that = this;
                axios.get('promotion-info/'+this.id).then(function (response) {
                    that.promotionInfo = response.data;
                    that.promotionInfo.all_product==1 ? that.productDiv=false : that.productDiv=true;
                    if(that.promotionInfo.product !== undefined)
                        that.getProducts();
                    if(that.promotionInfo.manufacturers !== undefined)
                        that.getManufacturers();
                    if(that.promotionInfo.categoryList !== undefined)
                        that.getCategories();

                    that.getCategories();
                    that.getManufacturers();

                    that.isLoading = false;
                });
            },
            components: {
                ClipLoader
            },
            methods: {
                promotionInfoEditPost() {
                    this.isLoading = true;
                    this.active ? this.active = 1 : this.active = 0;

                    let that = this;
                    axios.post('edit-promotion-info/post', this.promotionInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            //console.log(response.data)
                            that.$router.push({name:'promotionInfoList'}, () => {
                                that.$toasted.success('Successfully Updated',{
                                    position: 'top-center',
                                    theme: 'bubble',
                                    duration: 10000,
                                    action : {
                                        text : 'Close',
                                        onClick : (e, toastObject) => {
                                            toastObject.goAway(0);
                                        }
                                    },
                                });
                            })
                        }
                    })
                },
                getProducts(){
                    axios.get('{{ route('get_all_products') }}').then(response=>{
                        this.products =response.data;

                        let productList = [];
                        this.products.map(function (product) {
                            productList.push({id:product.id, label:product.pavadinimas_lt})
                        })
                        this.products = productList;
                    })
                },
                getCategories() {
                    axios.get('{{ route('get_category_list') }}').then(response=>{
                        this.categoryList = response.data;
                    })
                },
                getManufacturers(){
                    axios.get('{{ route('get_manufacturers') }}').then(response=>{
                        this.manufacturerList =response.data;

                        let manufacturers = [];
                        this.manufacturerList.map(function (manufacturer) {
                            manufacturers.push({id:manufacturer.id, label:manufacturer.title})
                        })
                        this.manufacturerList = manufacturers;
                    })
                },
            }
        }


        const routes = [
            { path: '/', component: PromotionInfoList, name: 'promotionInfoList' },
            { path: '/promotionInfo/add', component: AddPromotionInfo, name: 'addPromotionInfo' },
            { path: '/edit/:id', component: EditPromotionInfo, name: 'editPromotionInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#promotionInfoPage')

    </script>

@endsection