@extends('layouts.master')

@section('content')
    <div id="contactPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        var ContactList = {
            template: `
            <div>
          <div class="filter-box" >
                <div class="row">
                    <div class="col-md-12 text-right">
                        <router-link :to="{name:'addContact'}" class="btn btn-primary">@lang('Create New One')</router-link>
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
                        <th>@lang('Photo')</th>
                        <th>@lang('Title')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('Phone')</th>
                        <th></th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(contact, index) in contacts" >
                        <td><img :src="baseDir+'s3_'+contact.img"></td>
                        <td>@{{ contact.title }}</td>
                        <td>@{{ contact.adresas }}</td>
                        <td>@{{ contact.telefonas }}</td>
                        <td>
                           <span v-if="index!=0" @click="goUp(contact.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span></br>
                           <span v-if="index!=contacts.length-1" @click="goDown(contact.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editContact',params:{id:contact.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="contact_id=contact.id">@lang('Delete')</a></li>
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
                <a @click.prevent="getContacts(pagination.first_page_url)" href="#">First Page</a>
                </li>
                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                <a @click.prevent="getContacts(pagination.prev_page_url)" href="#">Previous</a>
                </li>
                <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                <a @click.prevent="getContacts('get-contacts?page='+n)" href="#">@{{ n }}</a>
                </li>

                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                <a @click.prevent="getContacts(pagination.next_page_url)" href="#">Next</a>
                </li>
                <li class="page-item">
                <a @click.prevent="getContacts(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteContact">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>

    </div></div>`,
            data(){
                return{
                    contacts: [],
                    baseDir: '',
                    contact_id: '',
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
                this.getContacts();
            },
            components: {
                ClipLoader
            },
            methods:{
                getContacts(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-contacts' : pageUrl;

                    axios.get(pageUrl).then(response=>{
                        this.contacts = response.data.contacts.data;
                        this.baseDir = response.data.base_dir;
                        this.pagination = response.data.contacts;
                        this.isLoading = false;
                    })
                },
                deleteContact()
                {
                    axios.get('delete-contact/'+this.contact_id).then(response=>{
                        this.getContacts();
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
                    axios.post('contact-up/'+id).then(response=>{
                        this.getContacts();
                    })
                },
                goDown(id)
                {
                    axios.post('contact-down/'+id).then(response=>{
                        this.getContacts();
                    })
                }
            }
        }

        var AddContact = {
            template: `<form class="form-horizontal" @submit.prevent="contactPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Add Contact</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="contact.active" type="checkbox" value="1"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Address')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.adresas" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Working Hours')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="contact.work_hours" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Phone')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.telefonas" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('El. mail')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.email" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('El. mail for queries')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.form_email" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Requisites')</label>
                       <div class="col-md-8">
                           <textarea rows="4" v-model="contact.rekvizitai" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="contact.showform" type="checkbox" value="1"> View Request Form</label>
                            </div>
                        </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img2">@lang('Map photo')</label>
                       <div class="col-md-8">
                           <input type="file" id="img2" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img">@lang('Photo of the building')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'contactList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    contact: {
                        title: '', work_hours: '', adresas: '', telefonas: '',
                        showform: 0, email: '', form_email: '',
                        rekvizitai: '', active: 0, img: '', img2: ''
                    },
                    errors: [], isLoading:false,
                }
            },
            components: {
                ClipLoader
            },
            methods: {
                contactPost() {
                    this.isLoading = true;
                    this.contact.active ? this.contact.active = 1 : this.contact.active = 0;
                    this.contact.showform ? this.contact.showform = 1 : this.contact.showform = 0;

                    let buildingImgFile = document.querySelector('#img');
                    let mapImgFile = document.querySelector('#img2');

                    let formData = new FormData();
                    formData.append('buildingImage', buildingImgFile.files[0]);
                    formData.append('mapImage', mapImgFile.files[0]);
                    formData.append('title', this.contact.title);
                    formData.append('work_hours', this.contact.work_hours);
                    formData.append('adresas', this.contact.adresas);
                    formData.append('telefonas', this.contact.telefonas);
                    formData.append('showform', this.contact.showform);
                    formData.append('email', this.contact.email);
                    formData.append('form_email', this.contact.form_email);
                    formData.append('pozicija', this.contact.pozicija);
                    formData.append('rekvizitai', this.contact.rekvizitai);
                    formData.append('active', this.contact.active);

                    let that = this;
                    axios.post('add-contact/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'contactList'}, () => {
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
                    })
                },
            }
        }

        var EditContact = {
            template: `<form class="form-horizontal" @submit.prevent="contactEditPost" enctype="multipart/form-data">
                    {{ csrf_field() }}

                <div class="col-md-11">
                <div class="panel panel-info">
                <div class="panel-heading">Edit Contact</div>
                <div class="panel-body">
                    <div v-if="isLoading">
                    	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="contact.active" type="checkbox"> active</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Name')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.title" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Address')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.adresas" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Working Hours')</label>
                       <div class="col-md-8">
                           <textarea rows="3" v-model="contact.work_hours" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Phone')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.telefonas" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('El. mail')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.email" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('El. mail for queries')</label>
                       <div class="col-md-8">
                           <input type="text" v-model="contact.form_email" class="form-control">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="">@lang('Requisites')</label>
                       <div class="col-md-8">
                           <textarea rows="4" v-model="contact.rekvizitai" class="form-control"></textarea>
                       </div>
                   </div>

                   <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <div class="checkbox">
                                <label><input v-model="contact.showform" type="checkbox"> View Request Form</label>
                            </div>
                        </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img2">@lang('Map photo')</label>
                       <div class="col-md-8">
                           <input @click="removeMapImage=1" type="file" id="img2" class="form-control">
                           <img v-if="contact.img2!=''" :src="contact.show_map_image"></img> Remove It
                           <input type="checkbox"  value="1" v-model="removeMapImage">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="control-label col-md-2" for="img">@lang('Photo of the building')</label>
                       <div class="col-md-8">
                           <input type="file" id="img" class="form-control">
                           <img v-if="contact.img!=''" :src="contact.show_building_image"></img> Remove It
                           <input type="checkbox"  value="1" v-model="removeBuildingImage">
                       </div>
                   </div>

                    <div class="form-group">
                       <div class="col-md-9 text-right">
                           <router-link :to="{name:'contactList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    contact: {
                        title: '', work_hours: '', adresas: '', telefonas: '',
                        showform: 0, email: '', form_email: '', pozicija: '',
                        rekvizitai: '', active: 0, img: '', img2: ''
                    },
                    errors: [], id: this.$route.params.id, removeMapImage: 0, removeBuildingImage: 0,
                    isLoading:true,
                }
            },
            created(){
                let that = this;
                axios.get('contact/'+this.id).then(function (response) {
                    that.contact = response.data;
                    that.isLoading = false;
                });
            },
            components: {
                ClipLoader
            },
            methods: {
                contactEditPost() {
                    this.isLoading = true;
                    this.contact.active ? this.contact.active = 1 : this.contact.active = 0;
                    this.contact.showform ? this.contact.showform = 1 : this.contact.showform = 0;

                    let buildingImgFile = document.querySelector('#img');
                    let mapImgFile = document.querySelector('#img2');

                    let formData = new FormData();
                    formData.append( 'id', this.id );
                    formData.append('buildingImage', buildingImgFile.files[0]);
                    formData.append('mapImage', mapImgFile.files[0]);
                    formData.append('title', this.contact.title);
                    formData.append('work_hours', this.contact.work_hours);
                    formData.append('adresas', this.contact.adresas);
                    formData.append('telefonas', this.contact.telefonas);
                    formData.append('showform', this.contact.showform);
                    formData.append('email', this.contact.email);
                    formData.append('form_email', this.contact.form_email);
                    formData.append('pozicija', this.contact.pozicija);
                    formData.append('rekvizitai', this.contact.rekvizitai);
                    formData.append('active', this.contact.active);
                    formData.append( 'remove_building_image', this.removeBuildingImage);
                    formData.append( 'remove_map_image', this.removeMapImage);

                    let that = this;
                    axios.post('edit-contact/post', formData).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            that.$router.push({name:'contactList'}, () => {
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
                            });
                    })
                },
            }
        }


        const routes = [
            { path: '/', component: ContactList, name: 'contactList' },
            { path: '/contact/add', component: AddContact, name: 'addContact' },
            { path: '/edit/:id', component: EditContact, name: 'editContact'},
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#contactPage')

    </script>

@endsection