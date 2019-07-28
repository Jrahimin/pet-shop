<?php use App\Enumerations\FoodCategory; ?>
@extends('layouts.master')

@section('content')
    <div id="categoriesInfoPage">

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{route('product_catalog_index')}}"><span>@lang('Goods')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('inventory_index')}}"><span>@lang('Inventory')</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="{{route('categories_info_index')}}"><span>@lang('Categories')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('delivery_info_index')}}"><span>@lang('Delivery')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('customer_info_index')}}"><span>@lang('Information for the buyer')</span></a>
            </li>

            {{--<li class="nav-item">
                <a class="nav-link" href="{{route('catalog_settings_index')}}"><span>@lang('Settings')</span></a>
            </li>--}}

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

    <script>
        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        var CategoriesInfoList = {
            template: `
            <div>
            <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addCategory'}" class="btn btn-primary">@lang('Create')</router-link>
                    </div>
                </div>
            </div>

    <div class="box box-primary" style="padding:20px" id="list">
        <div style="margin-top: 200px;" v-if="isLoading">
	        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
        </div>
        <div class="row">
        <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-md-4"></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(category, index) in categoryList">
                        <td v-html="category.value"></td>

                        <td>
                           <span v-if="category.count!='last'" @click="goUp(category.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa"></i></span><br/>
                           <span v-if="category.count!='first'" @click="goDown(category.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa"></i></span>
                        </td>

                        <td>
                          <router-link :to="{name:'editCategoriesInfo',params:{id:category.id}}" >@lang('Edit') |</router-link>
                          <a href="#" data-toggle="modal" data-target="#myModal" @click.prevent="category_id=category.id">@lang('Delete')</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteCategory">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    categoryList: '',
                    category_id: '',
                    imageSrc: {!! json_encode(asset('storage/images/medis/folder-icon.png')) !!},
                    isLoading:true,
                }
            },
            created(){
                this.getCategoriesInfoAll();
            },
            components: {
                ClipLoader
            },
            methods:{
                getCategoriesInfoAll()
                {
                    axios.get('category-list').then(response=>{
                        this.categoryList = response.data;
                        this.isLoading = false;
                    })
                },
                deleteCategory()
                {
                    axios.get('delete-category/'+this.category_id).then(response=>{
                        this.getCategoriesInfoAll();
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
                    axios.post('category-info-up/'+id).then(response=>{
                        console.log(response.data)
                        this.getCategoriesInfoAll();
                    })
                },
                goDown(id)
                {
                    axios.post('category-info-down/'+id).then(response=>{
                        console.log(response.data)
                        this.getCategoriesInfoAll();
                    })
                }
            }
        }

        var AddCategory = {
            template: `<form class="form-horizontal" @submit.prevent="categoryPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Category</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="categoriesInfo.aktyvus" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="pavadinimas_lt">@lang('Name')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="categoriesInfo.pavadinimas_lt" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Level')</label>
                       <div class="col-md-6">
                           <select v-model="categoriesInfo.tevas" class="form-control">
                                <option value="0">Root level Category</option>
                                <option v-for="category in categoryList" :value="category.id" v-html="category.value"></option>
                           </select>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Keywords')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="categoriesInfo.meta_key" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Description')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="categoriesInfo.meta_desc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Photo')</label>
                       <div class="col-md-6">
                           <input type="file" id="img" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-7 text-right">
                           <router-link :to="{name:'categoriesInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    categoriesInfo: {
                        page: '', tipas:'', pavadinimas_lt: '', pavadinimas_en:'',
                        url:'', aktyvus:0, tevas:'', paryskinta:'', pozicija:'',
                        raktazodziai:'', meta_key:'', meta_desc:'',
                    },
                    isLoading:false,
                    categoryList:[],
                    errors: [],
                }
            },
            components: {
                ClipLoader
            },
            created(){
                let that = this;
                axios.get('category-list').then(response=>{
                    this.categoryList = response.data;
                })
            },
            methods: {
                categoryPost() {
                    this.isLoading = true;
                    this.categoriesInfo.aktyvus ? this.categoriesInfo.aktyvus = 1 : this.categoriesInfo.aktyvus = 0;

                    let that = this;
                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append('id', this.id);
                    formData.append('pavadinimas_lt', this.categoriesInfo.pavadinimas_lt);
                    formData.append('aktyvus', this.categoriesInfo.aktyvus);
                    formData.append('tevas', this.categoriesInfo.tevas);
                    formData.append('aktyvus', this.categoriesInfo.aktyvus);
                    formData.append('meta_key', this.categoriesInfo.meta_key);
                    formData.append('meta_desc', this.categoriesInfo.meta_desc);

                    axios.post('add-category/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'categoriesInfoList'}, () => {
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

        var EditCategoriesInfo = {
            template: `<form class="form-horizontal" @submit.prevent="categoriesInfoEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Categories Info</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="categoriesInfo.aktyvus" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="pavadinimas_lt">@lang('Name')</label>
                       <div class="col-md-6">
                           <input type="text" v-model="categoriesInfo.pavadinimas_lt" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="description">@lang('Level')</label>
                       <div class="col-md-6">
                           <select v-model="categoriesInfo.tevas" class="form-control">
                                <option value="0">Root level Category</option>
                                <option v-for="category in categoryList" :value="category.id" v-html="category.value"></option>
                           </select>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Keywords')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="categoriesInfo.meta_key" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Meta Description')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="categoriesInfo.meta_desc" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Photo')</label>
                       <div class="col-md-6">
                           <input type="file" id="img" class="form-control">
                           <img v-if="categoriesInfo.imgpavadinimas!==''" :src="categoriesInfo.showImage">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-7 text-right">
                           <router-link :to="{name:'categoriesInfoList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    categoriesInfo: {
                        page: '', tipas:'', pavadinimas_lt: '', pavadinimas_en:'',
                        url:'', aktyvus:0, tevas:'', paryskinta:'', pozicija:'',
                        raktazodziai:'', meta_key:'', meta_desc:'',
                    },
                    categoryList:[],
                    errors: [], id: this.$route.params.id,
                    isLoading:true,
                }
            },
            components: {
                ClipLoader
            },
            created(){
                let that = this;
                axios.get('categories-info/'+this.id).then(function (response) {
                    that.categoriesInfo = response.data;
                    that.isLoading = false;
                });

                axios.get('category-list').then(response=>{
                    this.categoryList = response.data;
                })
            },
            methods: {
                categoriesInfoEditPost() {
                    this.isLoading = true;
                    this.categoriesInfo.aktyvus ? this.categoriesInfo.aktyvus = 1 : this.categoriesInfo.aktyvus = 0;

                    let that = this;
                    let imgFile = document.querySelector('#img');
                    let formData = new FormData();
                    formData.append('image', imgFile.files[0]);
                    formData.append('id', this.id);
                    formData.append('pavadinimas_lt', this.categoriesInfo.pavadinimas_lt);
                    formData.append('aktyvus', this.categoriesInfo.aktyvus);
                    formData.append('tevas', this.categoriesInfo.tevas);
                    formData.append('aktyvus', this.categoriesInfo.aktyvus);
                    formData.append('meta_key', this.categoriesInfo.meta_key);
                    formData.append('meta_desc', this.categoriesInfo.meta_desc);

                    axios.post('edit-categories-info/post', formData).then(function (response) {
                        this.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'categoriesInfoList'}, () => {
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


        const routes = [
            { path: '/', component: CategoriesInfoList, name: 'categoriesInfoList' },
            { path: '/category/add', component: AddCategory, name: 'addCategory' },
            { path: '/edit/:id', component: EditCategoriesInfo, name: 'editCategoriesInfo'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#categoriesInfoPage')

    </script>

@endsection