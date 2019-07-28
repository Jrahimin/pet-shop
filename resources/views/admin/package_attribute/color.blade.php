@extends('layouts.master')
@section('content')
    <div id="app">

        <ul class="nav nav-tabs">
            <li class="nav-item ">
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

            <li class="nav-item">
                <a class="nav-link" href="{{route('customer_info_index')}}"><span>@lang('Information for the buyer')</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="{{route('attribute_colors')}}"><span>@lang('Package Colors')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('attribute_sizes')}}"><span>@lang('Package Sizes')</span></a>
            </li>

        </ul>
        <br>

        <div >
            <router-view></router-view>
        </div>

    </div>

    <div style="clear: both;"></div>


@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        var ClipLoader = VueSpinner.ClipLoader;
        Vue.use(Toasted)

        var AttributesList = {
            template: `
     <div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'addAttribute'}"  class="btn btn-primary">@lang('Add Color')</router-link>
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

                        <th>Name</th>
                        <th>Hex Code</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="attribute in attributes" >
                        <td>@{{ attribute.name }}</td>
                        <td>@{{ attribute.hex_code }}</td>

                         <td >
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editColor',params:{id:attribute.id}}"  >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="attribute_id=attribute.id">@lang('Delete')</a></li>


                                </ul>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page"  class="col-md-offset-4">
              <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getColors(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getColors(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getColors('get-colors?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getColors(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getColors(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteColor">@lang('Yes')</button>
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
                    attributes:[],
                    attribute_id:'',
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
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getColors(pageUrl)
                    {
                        pageUrl = pageUrl == undefined ? 'get-colors' : pageUrl
                        axios.get(pageUrl).then(response=>{

                            this.attributes=response.data.data;
                            this.pagination=response.data;
                            this.isLoading = false;
                        })
                    },


                    deleteColor()
                    {

                        axios.get('delete-color/'+this.attribute_id).then(response=>{
                            this.getColors();
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
                this.getColors();
            }
        }


        var AddAttribute = {
            template: ` <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Add Color</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent="saveColor">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text"  v-model="input.name" ></input>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Hex Code</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text" v-model="input.hex_code" />
                            </div>

                        </div>



                      <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                      </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'attributeList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {


                    input :{
                        name :'',
                        hex_code :''
                    },
                    errors :[],
                    isLoading:false,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    saveColor()
                    {
                        this.isLoading = true;
                        axios.post('add-attribute-color', this.input)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                {
                                   this.$router.push({name:'attributeList'},()=>{
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
                                   })

                                }
                            } )
                    },
                }

        }

        var EditColor ={
            template: ` <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Colors</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                 <form class="form-horizontal"  @submit.prevent="editColor">

                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text"  v-model="input.name" ></input>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Hex Code</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text" v-model="input.hex_code" />
                            </div>

                        </div>



                      <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                      </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'attributeList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {


                    input :{
                        name :'',
                        hex_code :'',
                        id : this.$route.params.id
                    },
                    errors :[],
                    isLoading:true,

                };
            },
            created()
            {
               this.getColor();
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    editColor()
                    {
                        this.isLoading = true;
                        axios.post('edit-attribute-color', this.input)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                {
                                    this.$router.push({name:'attributeList'},()=>{
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
                                    })

                                }
                            } )
                    },
                    getColor()
                    {
                        axios.get('get-color/'+this.input.id).then(response=>{
                            this.input = response.data;
                            this.isLoading = false;
                        })
                    }
                }
        }


        const routes = [

            {
                path: '/add',
                component: AddAttribute,
                name: 'addAttribute'
            },

            {
                path: '/',
                component:   AttributesList,
                name: 'attributeList'
            },

            {
                path :'/edit/:id',
                component : EditColor,
                name:'editColor'
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
