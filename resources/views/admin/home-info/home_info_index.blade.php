<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="homeInfoPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(Toasted);

        var HomeInfoList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addHomeInfo'}" class="btn btn-primary">@lang('Create New One')</router-link>
                    </div>
                </div>
            </div>

    <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>@lang('Category')</th>
                        <th>@lang('Title')</th>
                        <th>@lang('Description')</th>
                        <th></th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(homeInfo, index) in homeInfoAll">
                        <td>@{{ homeInfo.category_name }}</td>
                        <td>@{{ homeInfo.title }}</td>
                        <td>@{{ homeInfo.description }}</td>

                        <td>
                           <span v-if="index!=0" @click="goUp(homeInfo.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span><br/>
                           <span v-if="index!=homeInfoAll.length-1" @click="goDown(homeInfo.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editHomeInfo',params:{id:homeInfo.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="homeInfo_id=homeInfo.id">@lang('Delete')</a></li>
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
                <li class="page-item">
                <a @click.prevent="getHomeInfoAll(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getHomeInfoAll(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getHomeInfoAll('get-home-info/all?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getHomeInfoAll(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li class="page-item">
                <a @click.prevent="getHomeInfoAll(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteHomeInfo">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    homeInfoAll: [],
                    homeInfo_id: '',
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
                this.getHomeInfoAll();
            },
            methods:{
                getHomeInfoAll(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-home-info/all' : pageUrl

                    axios.get(pageUrl).then(response=>{
                        this.homeInfoAll = response.data.data;
                        this.pagination = response.data;
                    })
                },
                deleteHomeInfo()
                {
                    axios.get('delete-home-info/'+this.homeInfo_id).then(response=>{
                        this.getHomeInfoAll();
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
                    axios.post('home-info-up/'+id).then(response=>{
                        this.getHomeInfoAll();
                    })
                },
                goDown(id)
                {
                    axios.post('home-info-down/'+id).then(response=>{
                        this.getHomeInfoAll();
                    })
                }
            }
        }

        var AddHomeInfo = {
            template: `<form class="form-horizontal" @submit.prevent="homeInfoPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Block</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="homeInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="company_id">@lang('Category')</label>
                        <div class="col-md-8">
                             <select class="form-control" v-model="homeInfo.cat">
                                  <option value="{{FoodCategory::$DOG}}">For dogs</option>
                                  <option value="{{FoodCategory::$CAT}}">For cats</option>
                                  <option value="{{FoodCategory::$HORSE}}">For horses</option>
                             </select>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="homeInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="link">@lang('Link')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="homeInfo.link" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Description')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="homeInfo.description" class="form-control"></textarea>
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'homeInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    homeInfo: {
                         cat:1, title:'', link:'', description:'', active:0,
                    },
                    errors: [],
                }
            },
            methods: {
                homeInfoPost() {
                    let that = this;
                    axios.post('add-home-info/post', this.homeInfo).then(function (response) {
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'homeInfoList'}, () => {
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
            }
        }

        var EditHomeInfo = {
            template: `<form class="form-horizontal" @submit.prevent="homeInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Block</div>
                <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="homeInfo.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="company_id">@lang('Category')</label>
                        <div class="col-md-8">
                             <select class="form-control" v-model="homeInfo.cat">
                                  <option value="{{FoodCategory::$DOG}}">For dogs</option>
                                  <option value="{{FoodCategory::$CAT}}">For cats</option>
                                  <option value="{{FoodCategory::$HORSE}}">For horses</option>
                             </select>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="title">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="homeInfo.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="link">@lang('Link')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="homeInfo.link" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Description')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="homeInfo.description" class="form-control"></textarea>
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'homeInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    homeInfo: {
                        cat:'', title:'', link:'', description:'', active:0,
                    },
                    errors: [], id: this.$route.params.id,
                }
            },
            created(){
                let that = this;
                axios.get('home-info/'+this.id).then(function (response) {
                    that.homeInfo = response.data;
                });
            },
            methods: {
                homeInfoEditPost() {
                    this.active ? this.active = 1 : this.active = 0;

                    let that = this;
                    axios.post('edit-home-info/post', this.homeInfo).then(function (response) {
                        console.log(response.data);
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'homeInfoList'}, () => {
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
            }
        }


        const routes = [
            { path: '/', component: HomeInfoList, name: 'homeInfoList' },
            { path: '/homeInfo/add', component: AddHomeInfo, name: 'addHomeInfo' },
            { path: '/edit/:id', component: EditHomeInfo, name: 'editHomeInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#homeInfoPage')

    </script>

@endsection