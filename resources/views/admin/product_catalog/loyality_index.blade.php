<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="loyalityInfoPage">
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

        var LoyalityInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addLoyalityInfo'}" class="btn btn-primary">@lang('Create a post')</router-link>
                    </div>
                </div>
            </div>

    <div class="box box-primary" style="padding:20px" id="list">
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
                    <tr v-for="(loyalityInfo, index) in loyalityInfoAll">
                        <td>@{{ loyalityInfo.title }}</td>
                        <td><div style="word-wrap: break-word;" v-html="loyalityInfo.description"></div></td>

                        <td>
                           <span v-if="index!=0" @click="goUp(loyalityInfo.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                           <span v-if="index!=loyalityInfoAll.length-1" @click="goDown(loyalityInfo.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editLoyalityInfo',params:{id:loyalityInfo.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="loyalityInfo_id=loyalityInfo.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getLoyalityInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getLoyalityInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getLoyalityInfoAll('get-loyality-info/all?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getLoyalityInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getLoyalityInfoAll(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteLoyalityInfo">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    loyalityInfoAll: [],
                    loyalityInfo_id: '',
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
                }
            },
            created(){
                this.getLoyalityInfoAll();
            },
            methods:{
                getLoyalityInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-loyality-info/all' : pageUrl

                    axios.get(pageUrl).then(response=>{
                        this.loyalityInfoAll = response.data.data;
                        this.pagination = response.data;
                    })
                },
                deleteLoyalityInfo()
                {
                    axios.get('delete-loyality-info/'+this.loyalityInfo_id).then(response=>{
                        this.getLoyalityInfoAll();
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
                    axios.post('loyality-info-up/'+id).then(response=>{
                        this.getLoyalityInfoAll();
                    })
                },
                goDown(id)
                {
                    axios.post('loyality-info-down/'+id).then(response=>{
                        this.getLoyalityInfoAll();
                    })
                }
            }
        }

        var AddLoyalityInfo = {
            template: `<form class="form-horizontal" @submit.prevent="loyalityInfoPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Loyality Info</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="loyalityInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="loyalityInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <vue-ckeditor v-model="loyalityInfo.description" :config="config" @blur="onBlur($event)" @focus="onFocus($event)"/>
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'loyalityInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    loyalityInfo: {
                        title:'', description:'', active:0,
                    },
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
            methods: {
                loyalityInfoPost() {
                    let that = this;
                    axios.post('add-loyality-info/post', this.loyalityInfo).then(function (response) {
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'loyalityInfoList'}, () => {
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

        var EditLoyalityInfo = {
            template: `<form class="form-horizontal" @submit.prevent="loyalityInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Loyality Info</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="loyalityInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="loyalityInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <vue-ckeditor v-model="loyalityInfo.description" :config="config" @contentDom.once="onContentDom($event)" @blur="onBlur($event)" @focus="onFocus($event)" />
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'loyalityInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    loyalityInfo: {
                        title:'', description:'', active:0,
                    },
                    ckEditorText:'',
                    errors: [], id: this.$route.params.id,
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
                axios.get('loyality-info/'+this.id).then(function (response) {
                    that.loyalityInfo = response.data;
                    that.ckEditorText = response.data.description;
                    that.loyalityInfo.description = response.data.description;
                });
            },
            methods: {
                onContentDom($event){
                    this.loyalityInfo.description = this.ckEditorText;
                },
                loyalityInfoEditPost() {
                    this.active ? this.active = 1 : this.active = 0;

                    let that = this;
                    axios.post('edit-loyality-info/post', this.loyalityInfo).then(function (response) {
                        console.log(response.data);
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'loyalityInfoList'}, () => {
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
            { path: '/', component: LoyalityInfoList, name: 'loyalityInfoList' },
            { path: '/loyalityInfo/add', component: AddLoyalityInfo, name: 'addLoyalityInfo' },
            { path: '/edit/:id', component: EditLoyalityInfo, name: 'editLoyalityInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#loyalityInfoPage')

    </script>

@endsection