@extends('layouts.master')
@section('additionalCSS')
    <link rel="stylesheet" href="{{asset('css/toggle-button.css')}}"/>
@endsection
@section('content')
    <div id="sliderPage">
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
        Vue.use(Toasted);
        Vue.use(VueCkeditor);
        var ClipLoader = VueSpinner.ClipLoader;

        var SliderImageList = {
            template: `
            <div>
       <div class="filter-box" >
         <div style="margin-top: 200px;" v-if="isLoading">
	         <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
         </div>
         <div class="row">
                <div class="col-md-10 text-right">
                   <router-link :to="{name:'addSliderImage'}"  class="btn btn-primary">@lang('Add SliderImage')</router-link>
                </div>

                <div class="col-md-2 text-right">
                   <router-link :to="{name:'addSliderOptions'}"  class="btn btn-primary">@lang('Set Slider Option')</router-link>
                </div>
         </div>
        </div>

     <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Text</th>
                            <th>@lang('Active')</th>
                            <th></th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(sliderImage, index) in slider_images" >
                        <td><img :src="sliderImage.image"><img></td>
                        <td>@{{ sliderImage.text }}</td>
                        <td>
                            <span v-if="sliderImage.active==1">Active</span>
                            <span v-else">Inactive</span>
                        </td>

                        <td>
                           <span v-if="index!=0" @click="goUp(sliderImage.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                           <span v-if="index!=slider_images.length-1" @click="goDown(sliderImage.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editSliderImage',params:{id:sliderImage.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="sliderImage_id=sliderImage.id">@lang('Delete')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteSliderImage">@lang('Yes')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>
     </div>
      </div>
`,
            data: function(){
                return {
                    slider_images:[],
                    sliderImage_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getSliderImages()
                    {
                        axios.get('get-slider-images').then(response=>{
                            this.slider_images=response.data;
                            this.isLoading = false;
                        })
                    },
                    deleteSliderImage()
                    {
                        axios.get('delete-sliderImage/'+this.sliderImage_id).then(response=>{
                            this.getSliderImages();
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
                        axios.post('slider-image-up/'+id).then(response=>{
                            this.getSliderImages();
                        })
                    },
                    goDown(id)
                    {
                        axios.post('slider-image-down/'+id).then(response=>{
                            this.getSliderImages();
                        })
                    }

                },
            created(){
                this.getSliderImages();
            }
        }

        var AddSliderImage = {
            template: `
       <div class="col-md-11">
            <div class="panel panel-info">
                <div class="panel-heading">Add SliderImage</div>
                <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <form class="form-horizontal" @submit.prevent="save">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="active" type="checkbox" :true-value=1 :false-value=0> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Title</label>
                        <div class="col-md-8">
                            <input type="text"   class="form-control" v-model="text" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="description">@lang('Parallax')</label>
                        <div class="col-md-8">
                            <vue-ckeditor v-model="parallax" :config="config" @blur="onBlur($event)" @focus="onFocus($event)" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">@lang('link')</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-model="link" />
                        </div>
                    </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">SliderImage</label>

                            <div class="col-md-8">
                                <input type="file"  class="form-control" id="file"  ref="fileupload"/>
                            </div>

                        </div>

                         <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul class="list-unstyled">
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                  <router-link  :to="{name:'sliderImageList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
`,
            data: function(){
                return {

                    text:'', parallax:'', link:'', active:0,
                    errors:[],
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
                        width: 720,
                    },

                };
            },
            components: {
                vuejsDatepicker, ClipLoader,
            },
            methods:
                {
                    save()
                    {
                        this.isLoading = true;
                        const fileInput = document.querySelector( '#file' );
                        const formData = new FormData();
                        formData.append( 'img', fileInput.files[0] );
                        formData.append( 'text', this.text);
                        formData.append( 'parallax', this.parallax);
                        formData.append( 'link', this.link);
                        formData.append( 'active', this.active);

                        if(this.parallax == '' && fileInput.files[0] === undefined){
                            this.isLoading = false;
                            alert("please Provide either image or parallax");
                            return;
                        }

                        axios.post('save-sliderImage', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;

                                if(this.errors == undefined){
                                    this.$router.push({name:'sliderImageList'}, () => {
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

                            } )
                    },
                    onBlur (editor) {
                        console.log(editor)
                    },
                    onFocus (editor) {
                        console.log(editor)
                    },
                },
            created(){

            }
        }

        var EditSliderImage={
            template:`
            <div class="col-md-11">
               <div class="panel panel-info">
                   <div class="panel-heading">Edit SliderImage</div>
                  <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                     <form class="form-horizontal" @submit.prevent="editSliderImage">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="sliderImage.active" type="checkbox" :true-value=1 :false-value=0> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Title</label>
                        <div class="col-md-8">
                            <input type="text"   class="form-control" v-model="sliderImage.text" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2" for="description">@lang('Parallax')</label>
                        <div class="col-md-8">
                            <vue-ckeditor v-model="sliderImage.parallax" :config="config" @contentDom.once="onContentDom($event)" @blur="onBlur($event)" @focus="onFocus($event)" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">@lang('link')</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-model="sliderImage.link" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Slider Image</label>

                        <div class="col-md-8">
                            <input type="file"  class="form-control" id="file" ref="fileupload"/>
                            <br>
                              <img v-if="sliderImage.image!=''" :src="sliderImage.image"></img> Remove It
                                <input type="checkbox"   v-model="removeIt">
                        </div>

                    </div>


                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                         <ul class="list-unstyled">
                             <li v-for="error in errors">@{{ error }}</li>
                         </ul>
                    </div>


                    <div class="form-group">
                        <div class="col-md-10 text-right">
                            <router-link  :to="{name:'sliderImageList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                            <button class="btn btn-primary">@lang('Edit')</button>
                        </div>
                    </div>
                </form>
           </div>
        </div>
    </div>`,
            data: function(){
                return {

                    sliderImage:{
                        text:'', parallax:'', link:'', active:0, image:'',
                    },
                    ckEditorText:'',
                    errors:[],
                    removeIt : false , isLoading:true,
                    sliderImageId:this.$route.params.id,

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

                };
            },  components: {
                vuejsDatepicker, ClipLoader
            },
            methods:
                {
                    getSliderImage()
                    {
                        axios.get('get-sliderImage/'+this.sliderImageId).then(response=>{
                            this.sliderImage=response.data;
                            this.ckEditorText = response.data.parallax;
                            this.sliderImage.parallax = response.data.parallax;
                            this.isLoading = false;
                        })
                    },
                    editSliderImage()
                    {
                        this.isLoading = true;
                        if(this.removeIt == false)
                        {
                            this.removeIt = 0;
                        }
                        else this.removeIt = 1 ;

                        const fileInput = document.querySelector( '#file' );
                        const formData = new FormData();
                        formData.append( 'img', fileInput.files[0] );
                        formData.append( 'text', this.sliderImage.text);
                        formData.append( 'parallax', this.sliderImage.parallax);
                        formData.append( 'link', this.sliderImage.link);
                        formData.append( 'removeIt', this.removeIt);
                        formData.append( 'active', this.sliderImage.active);
                        formData.append( 'id', this.sliderImageId);

                        if(this.sliderImage.image==''){
                            if(this.sliderImage.parallax == '' && fileInput.files[0] === undefined){
                                alert("please Provide either image or parallax");
                                return;
                            }
                        }

                        axios.post('edit-sliderImage', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined){
                                    this.$router.push({name:'sliderImageList'}, () => {
                                        this.$toasted.success('Successfully Added',{
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

                            } )
                    },
                    onBlur (editor) {
                        console.log(editor)
                    },
                    onFocus (editor) {
                        console.log(editor)
                    },
                    onContentDom($event){
                        this.sliderImage.parallax = this.ckEditorText;
                    }
                },
            created(){
                this.getSliderImage();
            },
        }


        var AddSliderOptions={
            template:`
            <div class="col-md-8 col-md-offset-2">
               <div class="panel panel-info">
                   <div class="panel-heading">Add Slider Options</div>
                  <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                     <form class="form-horizontal" @submit.prevent="addSliderOptions">

                    <div class="form-group">
                        <label class="control-label col-md-2">Title</label>
                        <div class="col-md-8">
                            <input class="form-control" v-model="sliderOptions.title" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Navigation</label>
                        <div class="col-md-6">
                            <label class="switch">
                                <input v-model.number="sliderOptions.navigation" :true-value="1" :false-value="0" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="control-label col-md-2">Transition Speed</label>
                         <div class="col-md-8">
                             <input class="form-control" v-model.number="sliderOptions.speed"/>
                         </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Pagination Type</label>
                        <div class="col-md-4">
                           <select class="form-control" v-model.number="sliderOptions.pagination_type">
                                <option value="">None</option>
                                <option value="1">progressbar</option>
                                <option value="2">fraction</option>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Effect Type</label>
                        <div class="col-md-4">
                           <select class="form-control" class="form-control" v-model.number="sliderOptions.effect_type">
                                <option value="">None</option>
                                <option value="1">Fade</option>
                                <option value="2">Cube</option>
                                <option value="3">coverflow</option>
                                <option value="4">flip</option>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Verticle</label>
                        <div class="col-md-6">
                            <label class="switch">
                                <input v-model.number="sliderOptions.verticle" :true-value="1" :false-value="0" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Loop</label>
                        <div class="col-md-6">
                            <label class="switch">
                                <input v-model.number="sliderOptions.loop" :true-value="1" :false-value="0" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Autoplay</label>
                        <div class="col-md-6">
                            <label class="switch">
                                <input v-model.number="sliderOptions.autoplay" :true-value="1" :false-value="0" type="checkbox" @change="changeAutoplay">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div v-if="sliderOptions.autoplay==1">
                        <div class="form-group">
                            <label class="control-label col-md-2">Autoplay Delay</label>
                            <div class="col-md-8">
                                <input class="form-control" v-model.number="sliderOptions.autoplay_delay" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Disable On Interaction</label>
                            <div class="col-md-6">
                                <label class="switch">
                                    <input v-model.number="sliderOptions.autoplay_disable_on_interaction" :true-value="1" :false-value="0" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Parallax</label>
                        <div class="col-md-6">
                            <label class="switch">
                                <input v-model.number="sliderOptions.parallax" :true-value="1" :false-value="0" type="checkbox" @change="changeParallax">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div v-if="sliderOptions.parallax==1" class="form-group">
                        <label class="control-label col-md-2">@lang('parallax_position')</label>
                        <div class="col-md-4">
                           <select class="form-control" class="form-control" v-model="sliderOptions.parallax_position">
                                <option value="">@lang('parallax_position_none')</option>
                                <option value="top_left">@lang('parallax_position_top_left')</option>
                                <option value="top_right">@lang('parallax_position_top_right')</option>
                                <option value="top_middle">@lang('parallax_position_top_middle')</option>
                                <option value="middle_center">@lang('parallax_position_middle_center')</option>
                                <option value="bottom_left">@lang('parallax_position_bottom_left')</option>
                                <option value="bottom_right">@lang('parallax_position_bottom_right')</option>
                                <option value="bottom_middle">@lang('parallax_position_bottom_middle')</option>
                           </select>
                        </div>
                    </div>

                    <div class="form-group">
                         <label class="control-label col-md-2">Slides Per View</label>
                         <div class="col-md-8">
                             <input class="form-control" v-model.number="sliderOptions.slidesPerView" />
                         </div>
                    </div>

                    <div class="form-group">
                         <label class="control-label col-md-2">Space Between</label>
                         <div class="col-md-8">
                             <input class="form-control" v-model.number="sliderOptions.spaceBetween" />
                         </div>
                    </div>

                    <div class="form-group">
                         <label class="control-label col-md-2">Height</label>
                         <div class="col-md-8">
                             <input class="form-control" v-model.number="sliderOptions.height"/>
                         </div>
                    </div>

                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                         <ul class="list-unstyled">
                             <li v-for="error in errors">@{{ error }}</li>
                         </ul>
                    </div>


                    <div class="form-group">
                        <div class="col-md-10 text-right">
                            <router-link  :to="{name:'sliderImageList'}" class="btn btn-primary">@lang('Back')</router-link>
                            <button class="btn btn-primary">@lang('Save')</button>
                        </div>
                    </div>
                </form>
           </div>
        </div>
    </div>`,
            data: function(){
                return {
                    sliderOptions:{
                        title:'', navigation:0, pagination_type:'', clickable:0, verticle:0, slidesPerView:'', spaceBetween:'',
                        loop:0, effect_type:'', speed:'', parallax:0, autoplay:0, autoplay_delay:'',
                        autoplay_disable_on_interaction:0, active:0, height:'', parallax_position:'',
                    },
                    errors:[], isLoading:true,
                }
            },
            created(){
                this.getSliderOptions();
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getSliderOptions()
                    {
                        axios.get('{{route('get-slider-option')}}').then(response=>{
                            this.sliderOptions = response.data;
                            this.isLoading = false;
                        });
                    },

                    addSliderOptions()
                    {
                        this.isLoading = true;
                        axios.post('{{route('add_slider_options')}}', this.sliderOptions)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined){
                                    this.$toasted.success('Successfully Updated',{
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
                            } )
                    },
                    changeAutoplay()
                    {
                        this.sliderOptions.autoplay_delay = null;
                        this.sliderOptions.autoplay_disable_on_interaction = null;
                    },
                    changeParallax(){
                        if(this.sliderOptions.parallax == 0)
                            this.sliderOptions.parallax_position = '';
                    },
                },

        }


        Vue.use(axios);


        const routes = [

            {
                path: '/',
                component: SliderImageList,
                name: 'sliderImageList'
            },
            {
                path: '/add',
                component: AddSliderImage,
                name: 'addSliderImage'
            },
            {
                path: '/edit/:id',
                component: EditSliderImage,
                name: 'editSliderImage'
            },
            {
                path: '/add/slider-options',
                component: AddSliderOptions,
                name: 'addSliderOptions'
            },

        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#sliderPage')


    </script>



@endsection