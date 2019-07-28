@extends('layouts.master')

@section('content')
    <div id="sliderPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(Toasted);

        var SliderList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addSlider'}" class="btn btn-primary">@lang('Create New One')</router-link>
                    </div>
                </div>
            </div>

    <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>@lang('Photo')</th>
                        <th>@lang('Title')</th>
                        <th></th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(slider, index) in sliders" >
                        <td><img :src="baseDir+'s1_'+slider.img"></td>
                        <td>@{{ slider.title }}</td>
                        <td>
                           <span v-if="index!=0" @click="goUp(slider.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                           <span v-if="index!=sliders.length-1" @click="goDown(slider.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editSlider',params:{id:slider.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="slider_id=slider.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getSliders(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getSliders(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getSliders('get-sliders?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getSliders(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getSliders(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteSlider">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    sliders: [],
                    baseDir: '',
                    slider_id: '',
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
                this.getSliders();
            },
            methods:{
                getSliders(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-sliders' : pageUrl;

                    axios.get(pageUrl).then(response=>{
                        this.sliders = response.data.sliders.data;
                        this.baseDir = response.data.base_dir;
                        this.pagination = response.data.sliders;
                    })
                },
                deleteSlider()
                {
                    axios.get('delete-slider/'+this.slider_id).then(response=>{
                        this.getSliders();
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
                    axios.post('slider-up/'+id).then(response=>{
                        this.getSliders();
                    })
                },
                goDown(id)
                {
                    axios.post('slider-down/'+id).then(response=>{
                        this.getSliders();
                    })
                }
            }
        }

        let AddSlider = {
            template: `<form class="form-horizontal" @submit.prevent="sliderPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Slider</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="slider.active" type="checkbox" value="1"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="slider.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="link">@lang('Reference')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="slider.link" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="slider.description" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img">@lang('Photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'sliderList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    slider: {
                        title:'', link:'', description:'', img:'', active:0,
                    },
                    errors: [],
                }
            },
            methods: {
                sliderPost() {
                    let imgFile = document.querySelector('#img');

                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append('title', this.slider.title);
                    formData.append('link', this.slider.link);
                    formData.append('description', this.slider.description);
                    formData.append('active', this.slider.active);

                    let that = this;
                    axios.post('add-slider/post', formData).then(function (response) {
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.$router.push({name:'sliderList'}, () => {
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
            }
        }

        var EditSlider = {
            template: `<form class="form-horizontal" @submit.prevent="sliderEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Slider</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="slider.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="slider.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="link">@lang('Reference')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="slider.link" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Text')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="slider.description" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img">@lang('Photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                           <img :src="slider.show_image"></img> Remove it
                           <input type="checkbox"  value="1" v-model="removeImage">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'sliderList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    slider: {
                        title:'', link:'', description:'', img:'', show_image:'', active:0,
                    },
                    errors: [], id: this.$route.params.id, removeImage: 0,
                }
            },
            created(){
                let that = this;
                axios.get('slider/'+this.id).then(function (response) {
                    that.slider = response.data;
                    console.log(that.slider);
                });
            },
            methods: {
                sliderEditPost() {
                    this.active ? this.active = 1 : this.active = 0;
                    this.showform ? this.showform = 1 : this.showform = 0;

                    let imgFile = document.querySelector('#img');

                    let formData = new FormData();
                    formData.append( 'id', this.id );
                    formData.append('image', imgFile.files[0]);
                    formData.append('title', this.slider.title);
                    formData.append('link', this.slider.link);
                    formData.append('description', this.slider.description);
                    formData.append('active', this.slider.active);
                    formData.append('remove_image', this.removeImage);

                    let that = this;
                    axios.post('edit-slider/post', formData).then(function (response) {
                        console.log(response.data);
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.$router.push({name:'sliderList'}, () => {
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
            }
        }


        const routes = [
            { path: '/', component: SliderList, name: 'sliderList' },
            { path: '/slider/add', component: AddSlider, name: 'addSlider' },
            { path: '/edit/:id', component: EditSlider, name: 'editSlider'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#sliderPage')

    </script>

@endsection