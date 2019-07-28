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
        var SubscriberList = {
            template: `
    <div id="bannerPage">
       <div class="filter-box" >
         <div class="row">
                <div class="col-md-12 text-right">
                   <router-link :to="{name:'addSubscriber'}"  class="btn btn-primary">@lang('Add New Subscriber')</router-link>
                </div>

         </div>
        </div>
         <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <a href="{{route('export_subscriber')}}" class="btn btn-primary">@lang('Export to Excel')</a>
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
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="subscriber in subscribers" >
                        <td>@{{ subscriber.email }}</td>
                        <td>@{{ subscriber.reg_date }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editSubscriber',params:{id:subscriber.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="subscriber_id=subscriber.id">@lang('Delete')</a></li>
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
                  <a @click.prevent="getSubscribers(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getSubscribers(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getSubscribers('get-subscribers?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getSubscribers(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getSubscribers(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteSubscriber">@lang('Yes')</button>
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
                    subscribers:[],
                    subscriber_id:'',
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
                    getSubscribers(pageUrl)
                    {
                        pageUrl = pageUrl == undefined ? 'get-subscribers' : pageUrl
                        axios.get(pageUrl).then(response=>{
                            //console.log(response.data)
                            this.subscribers=response.data.data;
                            this.pagination=response.data;
                            this.isLoading = false;
                        })
                    },
                    deleteSubscriber()
                    {
                        axios.get('delete-subscriber/'+this.subscriber_id).then(response=>{
                            this.getSubscribers();
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
                this.getSubscribers();
            }
        }

        var AddSubscriber = {
            template: `
       <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Subscriber</div>
                <div class="panel-body">
                    <div v-if="isLoading">
					    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				    </div>
                    <form class="form-horizontal" @submit.prevent="save">
                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="subscriber.email" />
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                             <router-link :to="{name:'subscriberList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Add')</button>
                            </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

`,
            data: function(){
                return {
                  subscriber:{
                      email:''
                  },
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
                        axios.post('save-subscriber', this.subscriber)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined){
                                    this.$router.push({name:'subscriberList'}, () => {
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
                                    });
                                }
                            } )
                    },
                },
        }

        var EditSubscriber={
            template:`
             <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Subscriber</div>
                <div class="panel-body">
                <div v-if="isLoading">
					<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				</div>
                    <form class="form-horizontal" @submit.prevent="editSubscriber">
                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="subscriber.email" />
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'subscriberList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Change')</button>
                            </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                    </form>
                </div>
            </div>
        </div>`,
            data: function(){
                return {

                    subscriber:{
                      email:'',
                        id:''
                    },
                    subscriber_id:this.$route.params.id,
                    isLoading:true, errors:[],
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getSubscriber()
                    {
                        axios.get('get-subscriber/'+this.subscriber_id).then(response=>{
                            this.subscriber=response.data;
                            this.isLoading = false;
                        })

                    },
                    editSubscriber()
                    {
                        this.isLoading = true;
                        this.subscriber.id=this.subscriber_id;
                        axios.post('edit-subscriber', this.subscriber)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;

                                if(this.errors == undefined){
                                    this.$router.push({name:'subscriberList'}, () => {
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
                                }
                            } )
                    }


                },
            created(){
                this.getSubscriber();
            }

        }


        Vue.use(axios);


        const routes = [

            {
                path: '/',
                component: SubscriberList,
                name: 'subscriberList'
            },

            {
                path: '/add',
                component: AddSubscriber,
                name: 'addSubscriber'
            },
            {
                path: '/edit/:id',
                component: EditSubscriber,
                name: 'editSubscriber'
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