@extends('layouts.master')

@section('content')
    <div id="interestingPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vuejs-datepicker.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>

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

        var InterestingList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addInteresting'}" class="btn btn-primary">@lang('Create New One')</router-link>
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
                        <th>Photo</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Short Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="interesting in interestings" >
                        <td><img :src="baseDir+'s1_'+interesting.img"></td>
                        <td>@{{ interesting.data }}</td>
                        <td>@{{ interesting.title }}</td>
                        <td v-html="interesting.shortdesc"></td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editInteresting',params:{id:interesting.id}}" >@lang('Edit')</router-link></li>
                                    <li> <router-link :to="{name:'editInterestingGallery',params:{id:interesting.id}}" >@lang('Edit Gallery')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="interesting_id=interesting.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getInterestings(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInterestings(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getInterestings('get-interestings?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInterestings(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInterestings(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteInteresting">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    interestings: [],
                    baseDir: '',
                    interesting_id: '',
                    isLoading:true,
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
                this.getInterestings();
            },
            components: {
                ClipLoader
            },
            methods:{
                getInterestings(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-interestings' : pageUrl;

                    axios.get(pageUrl).then(response=>{
                        this.interestings = response.data.interestings.data;
                        this.baseDir = response.data.base_dir;
                        this.pagination = response.data.interestings;

                        this.isLoading = false;
                    })
                },
                deleteInteresting()
                {
                    axios.get('delete-interesting/'+this.interesting_id).then(response=>{
                        this.getInterestings();
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
                }
            }
        }

        var AddInteresting = {
            template: `<form class="form-horizontal" @submit.prevent="interestingPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Interesting</div>
                <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                                <div class="checkbox">
                                    <label><input v-model="interesting.active" type="checkbox"> active</label>
                                </div>
                            </div>
                        </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Date')</label>
                       <div class="col-md-6">
                           <vuejs-datepicker v-model="interesting.data" input-class="form-control" format="yyyy-MM-dd"></vuejs-datepicker>
                       </div>
                   </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Keywords')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.meta_key" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Description')</label>
                       <div class="col-md-8">
                           <textarea rows="2" v-model="interesting.meta_desc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Author')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.autorius" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Short Description')</label>
                       <div class="col-md-8">
                           <textarea rows="4" v-model="interesting.shortdesc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Full Description')</label>
                       <div class="col-md-8">
                            <vue-ckeditor v-model="interesting.description" :config="config" @blur="onBlur($event)" @focus="onFocus($event)" />
                        </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'interestingList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    interesting: {
                        img: '',
                        title: '',
                        autorius: '',
                        data: '',
                        url: '',
                        meta_key: '',
                        meta_desc: '',
                        shortdesc: '',
                        description: '',
                        active: false,
                    },
                    isLoading:false,
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
                    errors: [],
                }
            },
            methods: {
                interestingPost() {
                    this.isLoading = true;
                    this.interesting.active ? this.interesting.active = 1 : this.interesting.active = 0;

                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append('title', this.interesting.title);
                    formData.append('autorius', this.interesting.autorius);
                    formData.append('data', moment(this.interesting.data).format('YYYY-MM-DD'));
                    formData.append('meta_key', this.interesting.meta_key);
                    formData.append('meta_desc', this.interesting.meta_desc);
                    formData.append('shortdesc', this.interesting.shortdesc);
                    formData.append('description', this.interesting.description);
                    formData.append('active', this.interesting.active);

                    let that = this;
                    axios.post('add-interesting/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.$router.push({name:'interestingList'}, () => {
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
                            });
                        }
                    })
                },
                onBlur (editor) {
                    console.log(editor)
                },
                onFocus (editor) {
                    console.log(editor)
                },
            },
            components:{
                vuejsDatepicker,
                ClipLoader,
            }
        }

        var EditInteresting = {
            template: `<form class="form-horizontal" @submit.prevent="interestingEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Interesting</div>
                <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                                <div class="checkbox">
                                    <label><input v-model="interesting.active" type="checkbox"> active</label>
                                </div>
                            </div>
                        </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Date')</label>
                       <div class="col-md-8">
                            <vuejs-datepicker v-model="interesting.data" input-class="form-control" format="yyyy-MM-dd"></vuejs-datepicker>
                       </div>
                   </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Keywords')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.meta_key" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Description')</label>
                       <div class="col-md-8">
                           <textarea rows="2" v-model="interesting.meta_desc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Author')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interesting.autorius" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Short Description')</label>
                       <div class="col-md-8">
                           <textarea rows="4" v-model="interesting.shortdesc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Full Description')</label>
                       <div class="col-md-8">
                            <vue-ckeditor v-model="interesting.description" :config="config" @contentDom.once="onContentDom($event)"/>
                        </div>
                   </div>

                    <div class="form-group">
                            <label class="control-label col-md-2">Photo</label>
                            <div class="col-md-8">
                                <input class="form-control" type="file" id="img">
                                <img v-if="interesting.img!=''" :src="interesting.showImage"></img> Remove It
                                <input type="checkbox"  value="1" v-model="remove">
                            </div>
                    </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'interestingList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    interesting: {
                        img: '',
                        title: '',
                        autorius: '',
                        data: '',
                        url: '',
                        shortdesc: '',
                        description: '',
                        pozicija: '',
                        active: false,
                        showImage: '',
                    },
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
                        width: 720
                    },
                    errors: [], id: this.$route.params.id, remove: 0,
                }
            },

            created(){
                let that = this;
                axios.get('interesting/' + this.id).then(function (response) {
                    that.interesting = response.data;
                    that.ckEditorText = response.data.description;
                    that.interesting.description = response.data.description;
                    that.isLoading = false;
                });
            },
            methods: {
                onContentDom($event) {
                    this.interesting.description = this.ckEditorText;
                },
                interestingEditPost() {
                    this.isLoading = true;
                    this.interesting.active ? this.interesting.active = 1 : this.interesting.active = 0;
                    this.remove ? this.remove = 1 : this.remove = 0;

                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append( 'id', this.id );
                    formData.append('title', this.interesting.title);
                    formData.append('autorius', this.interesting.autorius);
                    formData.append('data', moment(this.interesting.data).format('YYYY-MM-DD'));
                    formData.append('meta_key', this.interesting.meta_key);
                    formData.append('meta_desc', this.interesting.meta_desc);
                    formData.append('shortdesc', this.interesting.shortdesc);
                    formData.append('description', this.interesting.description);
                    formData.append('active', this.interesting.active);
                    formData.append( 'remove', this.remove );

                    let that = this;
                    axios.post('edit-interesting/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'interestingList'}, () => {
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
            },
            components:{
                vuejsDatepicker,
                ClipLoader,
            }
        }

        var EditInterestingGallery = {
            template:`
            <div>
            <div class="box box-primary" style="padding:20px" id="list">
            <div v-if="isLoading">
	            <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
            </div>
            <div class="row">
            <div class="col-md-12 table-responsive">
            <label v-if="galleries.length == 0">@lang('there are no photos')</label>
                <table v-if="galleries.length != 0" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-3">Photo</th>
                            <th class="col-md-6">Title</th>
                            <th>Video Link</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(gallery, index) in galleries">
                            <td>
                            <img v-if="gallery.img!=''" :src="baseDir+'s3_'+gallery.img">
                            <img v-if="gallery.img==''" :src="logo">
                            </td>
                            <td>@{{ gallery.aprasymas_lt }}</td>
                            <td><a :href="gallery.video" target="=_blank">@{{ gallery.video }}</a></td>
                            <td>
                                <span v-if="index!=0" @click="goUp(gallery.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                                <span v-if="index!=galleries.length-1" @click="goDown(gallery.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                            </td>

                            <td><a href="#" class="btn btn-danger"  data-toggle="modal" data-target="#myModal" @click.prevent="gallery_id=gallery.id">@lang('Delete')</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
            <ul class="pagination">
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInterestingGalleries(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getInterestingGalleries(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getInterestingGalleries('interesting/galleries/'+id+'?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInterestingGalleries(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getInterestingGalleries(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteGallery">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

        </div></div>

                <form class="form-horizontal" @submit.prevent="interestingGalleryPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-9 col-md-offset-1">
                <div class="panel panel-info">
                <div class="panel-heading">Interesting Gallery</div>
                <div class="panel-body">

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Title')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interestingGallery.aprasymas_lt" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Youtube link (if video)')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="interestingGallery.video" class="form-control">
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
                           <router-link :to="{name:'interestingList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    interestingGallery: {
                        aprasymas_lt: '',
                        video: '',
                    },
                    galleries: [], errors: [],
                    logo: `{{asset('images/yzipet_logo.png')}}`,
                    id: this.$route.params.id, baseDir: '', gallery_id: '',
                    isLoading:true,
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
                this.getInterestingGalleries();
            },
            components: {
                ClipLoader
            },
            methods:{
                getInterestingGalleries(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'interesting/galleries/'+this.id : pageUrl;
                    let that = this;
                    axios.get(pageUrl).then(response=>{
                        console.log(response.data)
                        that.galleries = response.data.galleries.data;
                        that.baseDir = response.data.base_dir;
                        this.pagination = response.data.galleries;
                        that.isLoading = false;
                    })
                },
                interestingGalleryPost(){
                    this.isLoading = true;
                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append('skiltis', this.id);
                    formData.append('aprasymas_lt', this.interestingGallery.aprasymas_lt);
                    formData.append('video', this.interestingGallery.video);

                    let that = this;
                    axios.post('add-gallery/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.getInterestingGalleries();
                            that.interestingGallery.aprasymas_lt = "";
                            that.interestingGallery.video = "";
                            document.querySelector('#img').value = "";

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
                            })
                        }
                    })
                },
                deleteGallery()
                {
                    axios.get('delete-gallery/'+this.gallery_id).then(response=>{
                        this.getInterestingGalleries();
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
                    axios.post('gallery-up/'+id).then(response=>{
                        this.getInterestingGalleries();
                    })
                },
                goDown(id)
                {
                    axios.post('gallery-down/'+id).then(response=>{
                        this.getInterestingGalleries();
                    })
                }
            }
        }

        const routes = [
            { path: '/', component: InterestingList, name: 'interestingList' },
            { path: '/interesting/add', component: AddInteresting, name: 'addInteresting' },
            { path: '/edit/:id', component: EditInteresting, name: 'editInteresting'},
            { path: '/edit/gallery/:id', component: EditInterestingGallery, name: 'editInterestingGallery'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#interestingPage')

    </script>

@endsection