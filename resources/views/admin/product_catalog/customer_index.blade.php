<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="customerInfoPage">

        <ul class="nav nav-tabs">
            <li class="nav-item">
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

            <li class="nav-item active">
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

        var CustomerInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addCustomerInfo'}" class="btn btn-primary">@lang('Create a post')</router-link>
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
                        <th>@lang('Information')</th>
                        <th></th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(customerInfo, index) in customerInfoAll">
                        <td>@{{ customerInfo.title }}</td>
                        <td v-html="customerInfo.description"></td>

                        <td>
                           <span v-if="index!=0" @click="goUp(customerInfo.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                           <span v-if="index!=customerInfoAll.length-1" @click="goDown(customerInfo.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editCustomerInfo',params:{id:customerInfo.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="customerInfo_id=customerInfo.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getCustomerInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getCustomerInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getCustomerInfoAll('get-customer-info/all?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getCustomerInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getCustomerInfoAll(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteCustomerInfo">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    customerInfoAll: [],
                    customerInfo_id: '',
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
                this.getCustomerInfoAll();
            },
            components: {
                ClipLoader
            },
            methods:{
                getCustomerInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-customer-info/all' : pageUrl
                    axios.get(pageUrl).then(response=>{
                        this.customerInfoAll = response.data.data;
                        this.pagination = response.data;
                        this.isLoading = false;
                    })
                },
                deleteCustomerInfo()
                {
                    axios.get('delete-customer-info/'+this.customerInfo_id).then(response=>{
                        this.getCustomerInfoAll();
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
                    axios.post('customer-info-up/'+id).then(response=>{
                        this.getCustomerInfoAll();
                    })
                },
                goDown(id)
                {
                    axios.post('customer-info-down/'+id).then(response=>{
                        this.getCustomerInfoAll();
                    })
                }
            }
        }

        var AddCustomerInfo = {
            template: `<form class="form-horizontal" @submit.prevent="customerInfoPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Customer Info</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="customerInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="customerInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <vue-ckeditor v-model="customerInfo.description" :config="config" @blur="onBlur($event)" @focus="onFocus($event)" />
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'customerInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                        </div>

                        <div class="col-md-2">
                           <button type="submit" class="btn btn-primary ">@lang('Add')</button>
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
                    customerInfo: {
                        title:'', description:'', active:0,
                    },
                    isLoading:false,
                    errors: [],
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
                }
            },
            components: {
                ClipLoader
            },
            methods: {
                customerInfoPost() {
                    this.isLoading = true;
                    let that = this;
                    axios.post('add-customer-info/post', this.customerInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'customerInfoList'}, () => {
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
                    })
                },
                onBlur (editor) {
                    console.log(editor)
                },
                onFocus (editor) {
                    console.log(editor)
                },
            }
        }

        var EditCustomerInfo = {
            template: `<form class="form-horizontal" @submit.prevent="customerInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Customer Info</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="customerInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="customerInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <vue-ckeditor v-model="customerInfo.description" :config="config" @contentDom.once="onContentDom($event)" @blur="onBlur($event)" @focus="onFocus($event)" />
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'customerInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                        </div>

                        <div class="col-md-2">
                           <button type="submit" class="btn btn-primary ">@lang('Save')</button>
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
                    customerInfo: {
                        title:'', description:'', active:0,
                    },
                    ckEditorText:'',
                    errors: [], id: this.$route.params.id,
                    isLoading:true,
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
                }
            },
            created(){
                let that = this;
                axios.get('customer-info/'+this.id).then(function (response) {
                    that.customerInfo = response.data;
                    that.ckEditorText = response.data.description;
                    that.customerInfo.description = response.data.description;
                    that.isLoading = false;
                });
            },
            components: {
                ClipLoader
            },
            methods: {
                onContentDom($event) {
                    this.customerInfo.description = this.ckEditorText;
                },
                customerInfoEditPost() {
                    this.isLoading = true;
                    this.active ? this.active = 1 : this.active = 0;

                    let that = this;
                    axios.post('edit-customer-info/post', this.customerInfo).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'customerInfoList'}, () => {
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
                    })
                },
                onBlur (editor) {
                    console.log(editor)
                },
                onFocus (editor) {
                    console.log(editor)
                },
            }
        }


        const routes = [
            { path: '/', component: CustomerInfoList, name: 'customerInfoList' },
            { path: '/customerInfo/add', component: AddCustomerInfo, name: 'addCustomerInfo' },
            { path: '/edit/:id', component: EditCustomerInfo, name: 'editCustomerInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#customerInfoPage')

    </script>

@endsection