<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="discountInfoPage">
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
        Vue.component('vue-multiselect', window.VueMultiselect.default);
        var ClipLoader = VueSpinner.ClipLoader;

        var DiscountInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addDiscountInfo'}" class="btn btn-primary">@lang('Create a post')</router-link>
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
                        <th>@lang('Discount')</th>
                        <th>@lang('Validity')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(discountInfo, index) in discountInfoAll">
                        <td>@{{ discountInfo.title }}</td>
                        <td>
                            <div v-if="discountInfo.amount">@{{ discountInfo.amount }} %</div>
                            <div v-if="discountInfo.amount_fixed">@{{ discountInfo.amount_fixed }}</div>
                        </td>
                        <td>@{{ discountInfo.validity }}</td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editDiscountInfo',params:{id:discountInfo.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="discountInfo_id=discountInfo.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getDiscountInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getDiscountInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getDiscountInfoAll('get-discount-info/all?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getDiscountInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getDiscountInfoAll(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteDiscountInfo">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    discountInfoAll: [],
                    discountInfo_id: '',
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
                this.getDiscountInfoAll();
            },
            components: {
                ClipLoader
            },
            methods:{
                getDiscountInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-discount-info/all' : pageUrl

                    axios.get(pageUrl).then(response=>{
                        this.discountInfoAll = response.data.data;
                        this.pagination = response.data;
                        this.isLoading = false;
                    })
                },
                deleteDiscountInfo()
                {
                    axios.get('delete-discount-info/'+this.discountInfo_id).then(response=>{
                        this.getDiscountInfoAll();
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
                goUp(id)
                {
                    axios.post('discount-info-up/'+id).then(response=>{
                        this.getDiscountInfoAll();
                    })
                },
                goDown(id)
                {
                    axios.post('discount-info-down/'+id).then(response=>{
                        this.getDiscountInfoAll();
                    })
                }
            }
        }

        var AddDiscountInfo = {
            template: `<form class="form-horizontal" @submit.prevent="discountInfoPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                <div class="panel-heading">Add Discount Info</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="discountInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="discountInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2">Discount</label>
                       <div class="col-md-6">
                           <select class="form-control" v-model.number="discountInfo.discount_type" @change="showDiscountOptions" >
                                <option value="">-- Select and Option --</option>
                                <option value="1">For specific Product</option>
                                <option value="2">For specific Manufacturers</option>
                                <option value="3">For Categories</option>
                           </select>
                       </div>
                   </div>

                   <div v-if="productDiv">
                        <div class="form-group">
                            <label class="control-label col-md-2">Specify Products</label>
                            <div class="col-md-8">
                                <v-select multiple v-model="discountInfo.product" :options="products"></v-select>
                            </div>
                        </div>

                        <div v-if="packageList.length>0" class="col-md-10 col-md-offset-1">
                            <div class="form-group" v-for="(aPackList, index) in packageList">
                                <div class="panel panel-default">
                                <div class="panel-heading">Packages for Product: <span style="color:#0066CC">@{{ discountInfo.product[index].label }}</span></div>
                                    <div class="panel-body">
                                         <label class="control-label col-md-2">Specify Packages</label>
                                         <div class="col-md-8">
                                             <v-select multiple v-model="discountInfo.packages[index]" :options="aPackList" label="pavadinimas"></v-select>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                   </div>

                   <div v-if="manufacturerDiv" class="form-group">
                        <label class="control-label col-md-2">Specify Manufacturers</label>
                        <div class="col-md-8">
                            <v-select multiple v-model="discountInfo.manufacturers" :options="manufacturerList"></v-select>
                        </div>
                   </div>

                   <div v-if="categoryDiv" class="form-group">
                        <label class="control-label col-md-2">Specify Categories</label>
                        <div class="col-md-8">
                            <v-select multiple v-model="discountInfo.categoryList" :options="categoryList">
                             <template slot="option" slot-scope="option">
                                    <span v-html="option.valueNoImage"></span>
                             </template>
                            </v-select>
                        </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" checked value="1" v-model.number="discountInfo.show_discount_percent">Discount in Percentage</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="discountInfo.show_discount_percent">Fixed Amount</label>
                   </div>

                    <div v-if="discountInfo.show_discount_percent == 1" class="form-group">
                    <label class="control-label col-md-2" for="amount">@lang('Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="discountInfo.amount" class="form-control">
                       </div>
                       <label class="control-label"> % </label>
                   </div>

                   <div v-if="discountInfo.show_discount_percent == 0" class="form-group">
                    <label class="control-label col-md-2" for="amount">@lang('Fixed Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="discountInfo.amount_fixed" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datefrom">@lang('Valid From')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="discountInfo.datefrom" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datetill">@lang('Valid Till')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="discountInfo.datetill" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" checked value="1" v-model.number="discountInfo.for_all_user" @click="showUserDiv=false">Applicable to all</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="discountInfo.for_all_user" @click="showUserDiv=true, getUsers()">Applicable to Selected Users</label>
                   </div>

                   <div v-if="showUserDiv">
                        <div class="form-group">
                            <label class="control-label col-md-2">Users</label>
                            <div class="col-md-8">
                                <v-select multiple v-model="discountInfo.users" :options="userList"></v-select>
                            </div>
                        </div>
                   </div>

                    <div class="form-group">
                        <div class="col-md-7">
                            <div class="col-md-1 pull-right">
                                <router-link :to="{name:'discountInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    discountInfo: {
                        title:'', amount:'', amount_fixed:'', datefrom:'', datetill:'', active:0, users:[], product:[],categoryList:[],
                        manufacturers:[], packages:[], for_all_user:1, show_discount_percent:1, discount_type:'',
                    },
                    userList:[], products:[], categoryList:[], manufacturerList:[], packageList:[], productListCopy:[],
                    errors: [],
                    productDiv:false, categoryDiv:false, manufacturerDiv:false,
                    showUserDiv:false, isLoading:false,
                }
            },
            watch:{
                'discountInfo.product'  : function (productNewList, productOldList) {
                    /*
                        As products and related packages are in different lists, if an element of product list is changed/removed then
                        respective package list element of the same index should also be removed.
                    */

                    // preserving a copy of old value
                    if(typeof(this.productListCopy) === "string"){
                        productOldList = JSON.parse(this.productListCopy);
                    }
                    else{
                        productOldList = this.productListCopy;
                    }

                    if(productOldList.length < productNewList.length){
                        this.productListCopy = JSON.stringify(productNewList);
                    }
                    if(productOldList.length > productNewList.length){
                        let removedItem, removedIndex;
                        productOldList.forEach(function (oldProduct) {
                            let match = false;
                            productNewList.forEach(function (productNew) {
                                if(JSON.stringify(oldProduct)===JSON.stringify(productNew)){
                                    match = true;
                                }
                            });
                            if(match===false)
                                removedItem = oldProduct;
                        });
                        removedIndex = productOldList.indexOf(removedItem);
                        this.discountInfo.packages.splice(removedIndex,1);
                        this.productListCopy = JSON.stringify(this.discountInfo.product)
                    }

                    let packList = [];
                    productNewList.forEach(function (productNew) {
                        let id = productNew.id;
                        let url = {!! json_encode(route('get_product_packages', ["id"=>"#packId"])) !!}
                            url = url.replace("#packId", id);
                        axios.get(url).then(response=>{
                            packList.push(response.data);
                        })
                    });
                    this.packageList = packList;
                }
            },
            components: {
                ClipLoader
            },
            methods: {
                discountInfoPost() {
                    this.isLoading = true;
                    let that = this;
                    axios.post('add-discount-info/post', this.discountInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors === undefined){
                            //console.log(response.data)
                            that.$router.push({name:'discountInfoList'}, () => {
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

                getUsers(){
                    axios.get('{{ route('get_select_users') }}').then(response=>{
                        this.userList = response.data;
                    });
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

                showDiscountOptions(){
                    this.discountInfo.product = [];
                    this.discountInfo.categoryList = [];
                    this.discountInfo.manufacturers = [];
                    this.discountInfo.packages = [];

                    if(this.discountInfo.discount_type == 1){
                        this.getProducts();
                        this.productDiv = true;
                        this.manufacturerDiv = false;
                        this.categoryDiv = false;
                        this.userDiv = false;
                    }
                    else if(this.discountInfo.discount_type == 2){
                        this.getManufacturers();
                        this.productDiv = false;
                        this.manufacturerDiv = true;
                        this.categoryDiv = false;
                        this.userDiv = false;
                    }
                    else if(this.discountInfo.discount_type == 3){
                        this.getCategories();
                        this.productDiv = false;
                        this.manufacturerDiv = false;
                        this.categoryDiv = true;
                        this.userDiv = false;
                    }
                },
            }
        }

        var EditDiscountInfo = {
            template: `<form class="form-horizontal" @submit.prevent="discountInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Discount Info</div>
                <div class="panel-body">
                    <div v-if="isLoading">
					    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="discountInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="discountInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2">Discount</label>
                       <div class="col-md-6">
                           <select class="form-control" v-model.number="discountInfo.discount_type" @change="showDiscountOptions" >
                                <option value="">-- Select and Option --</option>
                                <option value="1">For specific Product</option>
                                <option value="2">For specific Manufacturers</option>
                                <option value="3">For Categories</option>
                           </select>
                       </div>
                   </div>

                   <div v-if="discountInfo.discount_type==1">
                        <div class="form-group">
                            <label class="control-label col-md-2">Specify Products</label>
                            <div class="col-md-8">
                                <v-select multiple v-model="discountInfo.product" @input="getPackages" :options="products"></v-select>
                                <multiselect v-model="discountInfo.product" :options="products" :multiple="true" :clear-on-select="false" :preserve-search="true" placeholder="Pick some" label="label" track-by="label" :preselect-first="true">
                                     <template slot="selection" slot-scope="{ option, search }"><span class="multiselect__single">@{{ option.label }}</span></template>
                                </multiselect>
                            </div>
                        </div>

                        <div v-if="packageList.length>0" class="col-md-10 col-md-offset-1">
                            <div class="form-group" v-for="(aPackList, index) in packageList">
                                <div class="panel panel-default">
                                <div class="panel-heading">Packages for Product: <span v-if="discountInfo.product[index]!==undefined" style="color:#0066CC">@{{ discountInfo.product[index].label }}</span></div>
                                    <div class="panel-body">
                                         <label class="control-label col-md-2">Specify Packages</label>
                                         <div class="col-md-8">
                                             <v-select multiple v-model="discountInfo.packageList[index]" :options="aPackList" label="pavadinimas"></v-select>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                   </div>

                   <div v-if="discountInfo.discount_type==2" class="form-group">
                        <label class="control-label col-md-2">Specify Manufacturers</label>
                        <div class="col-md-8">
                            <v-select multiple v-model="discountInfo.manufacturers" :options="manufacturerList"></v-select>
                        </div>
                   </div>

                   <div v-if="discountInfo.discount_type==3" class="form-group">
                        <label class="control-label col-md-2">Specify Categories</label>
                        <div class="col-md-8">
                            <v-select multiple v-model="discountInfo.categoryList" :options="categoryList">
                             <template slot="option" slot-scope="option">
                                 <span v-html="option.valueNoImage"></span>
                             </template>
                            </v-select>
                        </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" value="1" v-model.number="discountInfo.show_discount_percent">Discount in Percentage</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="discountInfo.show_discount_percent">Fixed Amount</label>
                   </div>

                    <div v-if="discountInfo.show_discount_percent == 1" class="form-group">
                    <label class="control-label col-md-2" for="amount">@lang('Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="discountInfo.amount" class="form-control">
                       </div>
                       <label class="control-label"> % </label>
                   </div>

                   <div v-if="discountInfo.show_discount_percent == 0" class="form-group">
                    <label class="control-label col-md-2" for="amount">@lang('Fixed Amount')</label>
                       <div class="col-md-4">
                           <input type="text" v-model="discountInfo.amount_fixed" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datefrom">@lang('Valid From')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="discountInfo.datefrom" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="datetill">@lang('Valid Till')</label>
                       <div class="col-md-6">
                           <input type="date" v-model="discountInfo.datetill" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-md-2"></div>
                        <label class="radio-inline"><input type="radio" value="1" v-model.number="discountInfo.for_all_user" @click="showUserDiv=false">Applicable to all</label>
                        <label class="radio-inline"><input type="radio" value="0" v-model.number="discountInfo.for_all_user" @click="showUserDiv=true, getUsers()">Applicable to Selected Users</label>
                   </div>

                   <div v-if="discountInfo.for_all_user == 0">
                        <div class="form-group">
                            <label class="control-label col-md-2">Users</label>
                            <div class="col-md-8">
                                <v-select multiple v-model="discountInfo.users" :options="userList"></v-select>
                            </div>
                        </div>
                   </div>

                    <div class="form-group">
                        <div class="col-md-7">
                            <div class="col-md-1 pull-right">
                                <router-link :to="{name:'discountInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    discountInfo: {
                        title:'', amount:'', amount_fixed:'', datefrom:'', datetill:'', active:0, users:[], product:[],categoryList:[],
                        manufacturers:[], packageList:[], for_all_user:1, show_discount_percent:'', discount_type:'',
                    },
                    userList:[], products:[], categoryList:[], manufacturerList:[], packageList:[], productListCopy:[],
                    productDiv:false, categoryDiv:false, manufacturerDiv:false,
                    showUserDiv:false,

                    errors: [], id: this.$route.params.id,
                    isLoading:true,
                }
            },
            created(){
                let that = this;
                axios.get('discount-info/'+this.id).then(function (response) {
                    that.discountInfo = response.data;
                    if(that.discountInfo.product !== undefined){
                        that.getProducts();
                        that.productListCopy = JSON.stringify(that.discountInfo.product);
                    }
                    if(that.discountInfo.manufacturers !== undefined)
                        that.getManufacturers();
                    if(that.discountInfo.categoryList !== undefined)
                        that.getCategories();
                    if(that.discountInfo.users.length>0)
                        that.getUsers();

                    that.isLoading = false;
                });
            },
            watch:{
                'discountInfo.product'  : function (productNewList, productOldList) {
                    productOldList = JSON.parse(this.productListCopy);
                    if(productNewList.length < productOldList.length){
                        let removedItem, removedIndex;
                        productOldList.forEach(function (oldProduct) {
                            let match = false;
                            productNewList.forEach(function (productNew) {
                                if(JSON.stringify(oldProduct)===JSON.stringify(productNew)){
                                    match = true;
                                }
                            });
                            if(match===false)
                                removedItem = oldProduct;
                        })
                        removedIndex = productOldList.indexOf(removedItem);
                        this.discountInfo.packageList.splice(removedIndex,1);
                        this.productListCopy = JSON.stringify(this.discountInfo.product)
                    }
                }
            },
            components: {
                ClipLoader
            },
            methods: {
                getPackages(){
                    let packList = [];
                    this.discountInfo.product.forEach(function (product) {
                        let id = product.id;
                        let url = {!! json_encode(route('get_product_packages', ["id"=>"#packId"])) !!}
                            url = url.replace("#packId", id);
                        axios.get(url).then(response=>{
                            packList.push(response.data);
                        })
                    });
                    this.packageList = packList;
                },
                discountInfoEditPost() {
                    this.isLoading = true;
                    this.active ? this.active = 1 : this.active = 0;

                    let that = this;
                    axios.post('edit-discount-info/post', this.discountInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            //console.log(response.data)
                            that.$router.push({name:'discountInfoList'}, () => {
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



                getUsers(){
                    axios.get('{{ route('get_select_users') }}').then(response=>{
                        this.userList = response.data;
                    });
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

                showDiscountOptions(){
                    /*this.discountInfo.product = [];
                    this.discountInfo.categoryList = [];
                    this.discountInfo.manufacturers = [];
                    this.packageList = [];*/
                    this.discountInfo.packageList = [];

                    if(this.discountInfo.discount_type == 1){
                        this.getProducts();
                        this.productDiv = true;
                        this.manufacturerDiv = false;
                        this.categoryDiv = false;
                        this.userDiv = false;
                    }
                    else if(this.discountInfo.discount_type == 2){
                        this.getManufacturers();
                        this.productDiv = false;
                        this.manufacturerDiv = true;
                        this.categoryDiv = false;
                        this.userDiv = false;
                    }
                    else if(this.discountInfo.discount_type == 3){
                        this.getCategories();
                        this.productDiv = false;
                        this.manufacturerDiv = false;
                        this.categoryDiv = true;
                        this.userDiv = false;
                    }
                },
            }
        }


        const routes = [
            { path: '/', component: DiscountInfoList, name: 'discountInfoList' },
            { path: '/discountInfo/add', component: AddDiscountInfo, name: 'addDiscountInfo' },
            { path: '/edit/:id', component: EditDiscountInfo, name: 'editDiscountInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#discountInfoPage')

    </script>

@endsection