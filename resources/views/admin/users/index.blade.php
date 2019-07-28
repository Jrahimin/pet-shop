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
    <script src="{{asset('js/vuejs-datepicker.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script>

        Vue.use(Toasted);
        var ClipLoader = VueSpinner.ClipLoader;

        var UsersList = {
            template: `
     <div>
         <div class="filter-box" >
            <div v-if="isLoading">
	            <div class="overlay"><clip-loader size="100px" class="overlay-content" style="top:30%;"></clip-loader></div>
            </div>
            <div class="row">
            <form @submit.prevent="filterUsers(0)">

               <div class="form-group">
                   <label class="control-label col-md-1">@lang('Keywords')</label>
                  <div class="col-md-2">
                    <input  name="client" class="form-control" v-model="keywords"  />
                   </div>
               </div>

               <div class="form-group" >
                  <div class="col-md-2">
                    <button class="btn btn-primary">@lang('Search')</button>
                  </div>
               </div>
             </form>
            </div>
         </div>

     <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>City</th>
                        <th>Registration Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="user in users" >

                        <td><input type='checkbox' v-model='user_id_array' :value='user.id'> </td>

                        <td>@{{ user.email }}</td>
                        <td>@{{ user.name }} </td>
                        <td>@{{ user.surname }}</td>
                        <td>@{{ user.city }}</td>
                        <td>@{{ user.regdate }}</td>
                         <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editUser',params:{id:user.id}}"  >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="user_id=user.id">@lang('Delete')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                    <td></td>
                     <td></td>
                      <td><button class="btn btn-primary" @click="markAll">@lang('Mark All')</button></td>
                      <td><button class="btn btn-primary" @click="removeMark ">@lang('Unmark All')</button> </td>
                      <td> <button class="btn btn-primary" @click="deleteMany">@lang('Delete Marked')</button></td>
                      <td></td>
                      <td></td>

                    </tr>

                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page" v-if="notfiltering" class="col-md-offset-4">
              <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getUsers(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getUsers(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" :class="[{disabled:pagination.current_page==n}]"  v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getUsers('get-users?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getUsers(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getUsers(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>
           </div>

            <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4" v-if="notfiltering==false" >
               <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterUsers(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterUsers(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" :class="[{disabled:pagination.current_page==n}]"  v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="filterUsers(n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="filterUsers(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="filterUsers(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteUser">@lang('Yes')</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

`,
            data: function(){
                return {
                    users:[],
                    user_id:'',
                    pagination:{
                        first_page_url: '',
                        from: '',
                        last_page: '',
                        last_page_url: '',
                        next_page_url:'',
                        path: '',
                        per_page: 20,
                        prev_page_url: '',
                        to: '',
                        total: ''
                    },
                    years:[],
                    keywords:'',
                    length:'',
                    totalPages:'',
                    pageOffset:[],
                    notfiltering:true,
                    allUsers:[],
                    user_id_array:[],
                    isLoading:true,

                };
            },
            methods:
                {
                    getUsers(pageUrl)
                    {
                        pageUrl=pageUrl==undefined?'get-users':pageUrl
                        axios.get(pageUrl).then(response=>{
                            console.log(response.data);
                            this.users=response.data.data;
                            this.makePagination(response.data);
                            this.isLoading = false;
                        })
                    },


                    makePagination(response)
                    {
                        this.pagination=response;
                    },



                    deleteUser()
                    {
                        //console.log(this.purchase_id);
                        axios.get('delete-user/'+this.user_id).then(response=>{
                            this.getUsers();
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

                    filterUsers(pageUrl)
                    {
                        this.isLoading = true;
                        this.keywords = this.keywords.trim();

                        if(pageUrl===0 && this.keywords==''){
                            this.getUsers();
                            return;
                        }

                        if(isNaN(pageUrl)==false && pageUrl!=0)
                        {
                            pageUrl =  'filter-users/'+this.keywords+'?page='+pageUrl ;
                        }


                        pageUrl = pageUrl == 0 ? 'filter-users/'+this.keywords : pageUrl


                        axios.get(pageUrl).then(response=>{
                               // console.log(response.data);
                            /*this.length=response.data.length;
                            this.totalPages=Math.ceil( this.length/20);
                            for(var i=1, j=0;i<=this.totalPages,j<=this.length;i++,j=j+20)
                            {
                                var offset={};
                                offset[i]=j;
                                this.pageOffset.push(offset)
                            }
                            this.allUsers=response.data;
                            this.users=response.data.slice(0,20);*/

                            this.users = response.data.data ;
                            this.pagination=response.data;
                            this.notfiltering=false;

                            this.isLoading = false;
                        })

                    },
                    filterNext(i)
                    {
                        var offset=this.pageOffset[i-1][i];
                        this.users=this.allUsers.slice(offset,offset+20);

                    },
                    deleteMany()
                    {
                        axios.post('delete-many-users',{userArray:this.user_id_array}).then(response=>{
                            this.$router.go();
                        })
                    },
                    markAll()
                    {
                        for(var i=0;i<this.users.length;i++)
                        {
                            this.user_id_array.push(this.users[i].id);

                        }
                    },
                    removeMark()
                    {
                       this.user_id_array=[];
                    }


                },
            created(){
                this.getUsers();
            },
            components: {
                ClipLoader
            },
        }


        var EditUser={
            template:`<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Edit User</div>
                <div class="panel-body">
                <div v-if="isLoading">
                	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                </div>
                 <form class="form-horizontal" @submit.prevent="editUser" >
                       <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                               <input class="form-control"  v-model="user.email" disabled>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Surname</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.surname"  />
                            </div>

                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-2">Phone</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.phone"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">City</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.city"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Address</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.address"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Postal Code</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.zip_code"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Discount for user</label>

                            <div class="col-md-2">
                                <input type="checkbox" v-model="user.has_discount"  />
                            </div>

                        </div>
                        <div v-if="user.has_discount">
                        <div class="form-group">
                            <label class="control-label col-md-2">Date from</label>

                            <div class="col-md-8">
                                <vuejs-datepicker  v-model="user.discount_from"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Date to</label>

                            <div class="col-md-8">
                                <vuejs-datepicker  v-model="user.discount_to"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Discount %</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.discount_percent"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Discount Valid</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="user.discount_type" @change="showDiscountOptions" >
                                <option value="1">For All Goods</option>
                                <option value="2">For Category of Goods</option>
                                <option value="3">For specific Products</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="categoryDiv" class="form-group">
                            <label class="control-label col-md-2">Select Category</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="user.discount_cat" >
                                <option v-for="category in categories" :value="category.id" v-html="category.value"></option>

                                </select>
                            </div>
                        </div>

                         <div v-if="productDiv" class="form-group">
                            <label class="control-label col-md-2">Specify Products</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="user.discount_items" multiple>
                                      <option v-for="product in products" :value="product.id">@{{ product.pavadinimas_lt }}</option>

                                </select>
                            </div>
                        </div>

                        </div>
                          <div class="form-group">
                          <label class="control-label col-md-2">Pets</label>
                           <div class="col-md-6" >
                           <table class="table table-hover table-striped">
                           <thead>
                           <th>Name</th>
                           <th>Breed</th>
                           <th>Birthday</th>
                           </thead>
                           <tbody>
                            <tr v-for="pet in user.pets">
                            <td>@{{ pet.title }}</td>
                             <td>@{{ pet.species }}</td>
                              <td>@{{pet.birthday  }}</td>
                            </tr>
                           </tbody>
                           </table>
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Change Password</label>

                            <div class="col-md-2">
                                <input  type="checkbox"  v-model="user.change_password"  />
                            </div>

                        </div>
                        <div v-if="user.change_password">
                        <div class="form-group">
                            <label class="control-label col-md-2">Password</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="user.password"  />
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Repeat</label>

                            <div class="col-md-8">
                                <input    class="form-control"   />
                            </div>

                        </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'usersList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {
                    user_id:this.$route.params.id,

                        user:{
                            name:'',
                            surname:'',
                            email:'',
                            city:'',
                            address:'',
                            zip_code:'',
                            has_discount:false,
                            discount_from:'',
                            discount_to:'',
                            discount_percent:'',
                            discount_type:'',
                            password:'',
                            id:'',
                            discount_cat:'',
                            discount_items:[],
                            change_password:false,
                        },
                    products :[],
                    categories:[],
                    categoryDiv:false,
                    productDiv:false,
                    errors:[],
                    isLoading:true,
                };
            },
            methods:
                {
                    getUserDetail()
                    {
                        axios.get('user-detail/'+this.user_id).then(response=>{
                            this.user=response.data;
                            this.showDiscountOptions();
                            this.isLoading = false;
                        })
                    },

                    editUser()
                    {
                        this.isLoading = true;
                        this.user.id=this.user_id;

                        if(this.user.has_discount) {
                            this.user.discount_from = moment(this.user.discount_from).format('YYYY-MM-DD');
                            this.user.discount_to = moment(this.user.discount_to).format('YYYY-MM-DD');
                        }

                        this.user.has_discount ? this.user.has_discount = 1 : this.user.has_discount = 0;

                        if(this.user.discount_type==1 || this.user.discount_type==2 )
                            this.user.discount_items = [];
                        if(this.user.discount_type==1 || this.user.discount_type==3 )
                            this.user.discount_cat =0;

                       axios.post('user-edit',this.user).then(response=>{
                           this.isLoading = false;
                           this.errors = response.data.message;
                           if(this.errors == undefined)
                            this.$router.push({name:'usersList'}, () => {
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
                    showDiscountOptions()
                    {
                        if(this.user.discount_type==2)
                        {
                            this.categoryDiv =true;
                            this.productDiv = false;
                            this.getCategories();
                        }

                        else if (this.user.discount_type==3)
                        {
                            this.productDiv = true;
                            this.categoryDiv =false;
                            this.getProducts()
                        }
                        else if(this.user.discount_type==1)
                        {
                            this.categoryDiv =false;
                            this.productDiv = false;
                        }

                    },
                    getProducts()
                    {
                        axios.get('get-all-products').then(response=>{
                            this.products =response.data;
                        })
                    },
                    getCategories()
                    {
                        axios.get('catalog/category-list').then(response=>{
                            this.categories = response.data;
                        })
                    }
                },
            created(){
                this.getUserDetail()
            },
            components:{
                vuejsDatepicker,
                ClipLoader,
            }
        }


        const routes = [

            {
                path: '/',
                component: UsersList,
                name: 'usersList'
            },

            {
                path: '/edit/:id',
                component: EditUser,
                name: 'editUser',
            }

        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#app')

    </script>

@endsection