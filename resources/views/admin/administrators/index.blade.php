@extends('layouts.master')

@section('content')
    <div id="app">
        <div >
            <router-view></router-view>
        </div>
    </div>

    <div style="clear: both;"></div>


@endsection
@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        var AdminList = {
            template: `
    <div id="bannerPage">
       <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
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
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="admin in admins" >
                        <td>@{{ admin.user }}</td>
                        <td>@{{ admin.name }} @{{ admin.surname }}</td>
                        <td>@{{ admin.email }}</td>
                        <td>@{{ admin.active }}</td>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editAdmin',params:{id:admin.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="admin_id=admin.id">@lang('Delete')</a></li>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteAdmin">@lang('Yes')</button>
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
                    admins:[],
                    admin_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getAdmin()
                    {
                        axios.get('get-admin').then(response=>{
                            this.admins=response.data;
                            this.isLoading = false;
                        })
                    },
                    deleteAdmin()
                    {
                        axios.get('delete-admin/'+this.admin_id).then(response=>{
                            this.getAdmin();
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
                },
            created(){
                this.getAdmin();
            }
        }

        var AddAdmin = {
            template: `
        <div>
        <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
        </div>
       <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Admin</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <form class="form-horizontal" @submit.prevent="save">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Surname</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.surname"  />
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Telephone</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.telephone" />
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                                <input type="text"   class="form-control" v-model="admin.email" >
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                                <input  type="checkbox" v-model="admin.active" value="1" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Password</label>

                            <div class="col-md-8">
                                <input  type="password" class="form-control" v-model="admin.password"  />
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Repeat</label>

                            <div class="col-md-8">
                                <input  type="password" class="form-control"  v-model="admin.confirm_password" />
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Role</label>
                             <div class="col-md-8"  >
                             <select v-model="admin.role" class="form-control" @change="getPermissions(admin.role)">
                             <option v-for="role in roles" :value="role.name">@{{ role.name }}</option>
                             </select>
                            </div>
                        </div>
                        <div class="form-group" v-if="admin.role!=''">
                        <label class="control-label col-md-2">Permissions of the Role</label>
                         <div class="col-md-6">
                            <ul class="list-unstyled list-permission-actions">
                                <li v-for="permission in permissions">
                                 <span class="text-info">@{{permission.name}}</span>
                                 </li>
                            </ul>

                         </div>
                         </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>



                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'adminList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
`,
            data: function(){
                return {
                    admin:{
                      name:'',
                      surname:'',
                      email:'',
                      telephone:'',
                        active:0,
                        password:'',
                        confirm_password:'',
                       role:''
                    },
                    permissions:[],
                    roles:[],
                    errors:[],
                    isLoading:false,

                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    save()
                    {
                        this.isLoading = true;
                      axios.post('save-admin',this.admin).then(response=>{
                          this.isLoading = false;
                          this.errors = response.data.message;
                          if(this.errors == undefined)
                          this.$router.push({name:'adminList'}, () => {
                                  this.$toasted.success('Successfully Added', {
                                      duration: 10000,
                                      position: 'top-center',
                                      theme: 'bubble',
                                      action: {
                                          text: 'Close',
                                          onClick: (e, toastObject) => {
                                              toastObject.goAway(0);
                                          }
                                      },
                                  });
                              }
                          );
                      })
                    },

                    getRoles()
                    {
                        axios.get('get-roles').then(response=>{
                            this.roles=response.data;
                            //console.log(this.permissions);
                        })
                    },
                    getPermissions(role)
                    {
                        this.permissions=[];
                        axios.get('get-permission-for-role/'+role).then(response=>{
                            this.permissions=response.data;
                        })
                    }


                },
            created(){
               this.getRoles();
            }
        }
        var RoleList={

            template: `
    <div id="bannerPage">
    <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
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
                        <th>Name</th>
                        <th>Details</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="role in roles" >
                         <td>@{{ role.name }}</td>
                        <td><router-link :to="{name:'editAdmin',params:{id:role.id}}" >@{{ role.name }}</router-link></li></td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editRole',params:{id:role.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="role_id=role.id">@lang('Delete')</a></li>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteAdmin">@lang('Yes')</button>
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
                    roles:[],
                    role_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getRoles()
                    {
                        axios.get('get-roles').then(response=>{
                            this.roles=response.data;
                            this.isLoading = false;
                        })
                    },
                    deleteAdmin()
                    {
                        axios.get('delete-role/'+this.role_id).then(response=>{
                            this.getRoles();
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
                },
            created(){
                this.getRoles();
            }
        }

        var AddRole ={
            template: `
    <div>
        <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
        </div>
       <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-info">
                <div class="panel-heading">Add Role</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <form class="form-horizontal" @submit.prevent="save">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="role.name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Permissions</label>
                            <div class="col-md-6">
                            <ul class="list-unstyled list-permission-actions">
                                <li v-for="permission in allPermissions">
                                <input type="checkbox" :value="permission.name" v-model="role.permissions"/>
                                 <span class="text-info">@{{permission.name}}</span>
                                 </li>
                            </ul>

                         </div>
                        </div>
                           <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                             <router-link :to="{name:'roleList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
`,
            data: function(){
                return {
                    role:{
                        name:'',
                        permissions:[],
                    },
                    errors:[],
                    allPermissions:[],
                    isLoading:false,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    save()
                    {
                        this.isLoading = true;
                        axios.post('save-role',this.role).then(response=>{
                            this.isLoading = false;
                            this.errors = response.data.message;
                            if(this.errors == undefined)
                            this.$router.push({name:'roleList'}, () => {
                                    this.$toasted.success('Successfully Added', {
                                        duration: 10000,
                                        position: 'top-center',
                                        theme: 'bubble',
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                }
                            );
                        })
                    },

                    getPermissions()
                    {
                        axios.get('get-permissions').then(response=>{
                            this.allPermissions=response.data;
                        })
                    }
                },
            created(){
                this.getPermissions();
            }
        }

        var EditRole = {
            template: `
    <div>
    <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
        </div>
       <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Role</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <form class="form-horizontal" @submit.prevent="save">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="role.name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Permissions</label>
                            <div class="col-md-6">
                            <ul class="list-unstyled list-permission-actions">
                                <li v-for="permission in allPermissions">
                                <input type="checkbox" :value="permission.name" v-model="role.permissions"/>
                                 <span class="text-info">@{{permission.name}}</span>
                                 </li>
                            </ul>

                         </div>
                        </div>
                           <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                             <router-link :to="{name:'roleList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

`,
            data: function(){
                return {
                    role:{
                        name:'',
                        permissions:[],
                        id:''
                    },
                    role_id:this.$route.params.id,
                    allPermissions:[],
                    errors:[],
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getRole()
                    {
                        axios.get('get-role/'+this.role_id).then(response=>{
                            this.role=response.data;
                            this.isLoading = false;
                        })
                    },
                    save()
                    {
                        this.isLoading = true;
                        this.role.id=this.role_id;
                        axios.post('edit-role',this.role).then(response=>{
                            this.isLoading = false;
                            this.errors = response.data.message;
                            if(this.errors == undefined)
                            this.$router.push({name:'roleList'},() => {
                                this.$toasted.success('Successfully Updated', {
                                    duration: 10000,
                                    position: 'top-center',
                                    theme: 'bubble',
                                    action: {
                                        text: 'Close',
                                        onClick: (e, toastObject) => {
                                            toastObject.goAway(0);
                                        }
                                    },
                                });
                            }
                            );
                        })
                    },

                    getPermissions()
                    {
                        axios.get('get-permissions').then(response=>{
                            this.allPermissions=response.data;
                        })
                    }


                },
            created(){
                this.getRole();
                this.getPermissions();
            }
        }
        var EditAdmin={
            template:`
        <div>
        <div class="filter-box" >
            <ul class="nav nav-tabs">
                 <li>
                     <router-link :to="{name:'adminList'}"  >@lang('Admin List')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addAdmin'}"  >@lang('Create Admin')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'addRole'}"  >@lang('Create Role')</router-link>
                 </li>
                 <li>
                     <router-link :to="{name:'roleList'}"  >@lang('Roles')</router-link>
                 </li>
            </ul>
        </div>
           <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Admin</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <form class="form-horizontal" @submit.prevent="editAdmin">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Surname</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.surname"  />
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Telephone</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="admin.telephone" />
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                                <input type="text"   class="form-control" v-model="admin.email" >
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                                <input  type="checkbox" v-model="admin.active" value="1" />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Change Password</label>

                            <div class="col-md-2">
                                <input  type="checkbox" v-model="admin.change_password"  />
                            </div>

                        </div>
                       <div v-if="admin.change_password">
                        <div class="form-group">
                            <label class="control-label col-md-2">Password</label>

                            <div class="col-md-8">
                                <input  type="password" class="form-control" v-model="admin.password"  />
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Repeat</label>

                            <div class="col-md-8">
                                <input  type="password" class="form-control"  v-model="admin.confirm_password" />
                            </div>

                        </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Role</label>
                             <div class="col-md-8"  >
                             <select v-model="admin.role" class="form-control" @change="getPermissions(admin.role)">
                             <option v-for="role in roles" :value="role.name">@{{ role.name }}</option>
                             </select>
                            </div>
                        </div>
                        <div class="form-group" v-if="admin.role!=''">
                        <label class="control-label col-md-2">Permissions of the Role</label>
                         <div class="col-md-6">
                            <ul class="list-unstyled list-permission-actions">
                                <li v-for="permission in permissions">
                                 <span class="text-info">@{{permission.name}}</span>
                                 </li>
                            </ul>

                         </div>
                         </div>

                         <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'adminList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
`,
            data: function(){
                return {

                    admin:{
                        name:'',
                        surname:'',
                        email:'',
                        telephone:'',
                        active:0,
                        password:'',
                        confirm_password:'',
                        role:'',
                        id:'',
                        change_password:false,
                    },
                    permissions:[], roles:[], errors:[],
                    admin_id:this.$route.params.id,
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {

                    getAdmin()
                    {
                        axios.get('get-admin/'+this.admin_id).then(response=>{
                            this.admin=response.data;
                            this.admin.change_password = false;
                            this.getPermissions(this.admin.role);
                            this.isLoading = false;
                        })

                    },
                    getRoles()
                    {
                        axios.get('get-roles').then(response=>{
                            this.roles=response.data;
                            //console.log(this.permissions);
                        })
                    },
                    getPermissions(role)
                    {
                        this.permissions=[];
                        axios.get('get-permission-for-role/'+role).then(response=>{
                            this.permissions=response.data;
                        })
                    },
                    editAdmin()
                    {
                        this.isLoading = true;
                        this.admin.id=this.admin_id;
                     console.log(this.admin.change_password);
                        axios.post('edit-admin',this.admin).then(response=>{
                            this.isLoading = false;
                            this.errors = response.data.message;
                            if(this.errors == undefined)
                            this.$router.push({name:'adminList'}, () => {
                                this.$toasted.success('Successfully Updated', {
                                    duration: 10000,
                                    position: 'top-center',
                                    theme: 'bubble',
                                    action: {
                                        text: 'Close',
                                        onClick: (e, toastObject) => {
                                            toastObject.goAway(0);
                                        }
                                    },
                                });
                            });
                        })
                    }



                },
            created(){
                this.getRoles();
                this.getAdmin();
            }

        }


        Vue.use(axios);


        const routes = [

            {
                path: '/',
                component: AdminList,
                name: 'adminList'
            },
            {
                path: '/roles',
                component: RoleList,
                name: 'roleList'
            },


            {
                path: '/add',
                component: AddAdmin,
                name: 'addAdmin'
            },
            {
                path: '/edit/:id',
                component: EditAdmin,
                name: 'editAdmin'
            },
            {
                path: 'role/edit/:id',
                component: EditRole,
                name: 'editRole'
            },
            {
                path: '/add-role',
                component: AddRole,
                name: 'addRole'
            },


        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#app')


    </script>



@endsection