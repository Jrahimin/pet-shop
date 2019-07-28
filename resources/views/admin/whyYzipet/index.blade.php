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

        var AdvantagesList = {
            template: `
     <div>
      <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link :to="{name:'addAdvantage'}"  class="btn btn-primary">@lang('Create Advantage')</router-link>
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
                        <th>A Little Girl</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th></th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(advantage, index) in advantages" >
                        <td><img :src="advantage.img"></img></td>
                        <td v-html="advantage.title"></td>
                        <td>@{{ advantage.description }}</td>

                        <td>
                          <span v-if="index!=0" @click="moveUp(advantage.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></span></br>
                          <span v-if="index!=advantages.length-1" @click="moveDown(advantage.id)"><i style="color:#3f729b;cursor:pointer;"  class="fa fa-caret-down fa-2x"></i></span>
                        </td>

                         <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editAdvantage',params:{id:advantage.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="advantage_id=advantage.id">@lang('Delete')</a></li>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteAdvantage">@lang('Yes')</button>
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
                    advantages:[],
                    advantage_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {

                    getAdvantages()
                    {
                        axios.get('get-advantages').then(response=>{
                            this.advantages=response.data;
                            this.isLoading = false;
                        })
                    },

                    deleteAdvantage()
                    {
                        axios.get('delete-advantage/'+this.advantage_id).then(response=>{
                            this.getAdvantages();
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
                    moveUp(id)
                    {
                        axios.get('advantage-move-up/'+id).then(response=>{
                            this.getAdvantages();
                        })

                    },
                    moveDown(id)
                    {

                        axios.get('advantage-move-down/'+id).then(response=>{
                            this.getAdvantages();
                        })

                    }

                },
            created(){
                this.getAdvantages();
            }
        }

        const AddAdvantage = {
            template: ` <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Advantage</div>
                <div class="panel-body">
                <div v-if="isLoading">
                	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                </div>
                 <form class="form-horizontal"  @submit.prevent=saveAdvantage>
                       <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                               <input type="checkbox"  value="1" v-model="active">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Title</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="title" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Short Description</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="description"  />
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Avatar</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file"" id="file" ref="fileupload" ></input>
                            </div>

                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                             <ul class="list-unstyled">
                                 <li v-for="error in errors">@{{ error }}</li>
                             </ul>
                       </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'advantagesList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>`,
            data: function (){
                return {

                    errors:[],
                    active:'',
                    title:'',
                    description:'',
                    isLoading:false,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    saveAdvantage()
                    {
                        this.isLoading = true;
                        if(this.active)
                            this.active=1;
                        else this.active=0;

                        const fileInput = document.querySelector( '#file' );
                        const formData = new FormData();
                        formData.append( 'image', fileInput.files[0] );
                        formData.append( 'active', this.active);
                        formData.append( 'title', this.title );
                        formData.append( 'description', this.description );

                        axios.post('save-advantage', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                this.$router.push({name:'advantagesList'}, () => {
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

                            } )
                    }
                }
        }


        var EditAdvantage={
            template:`<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Advantage</div>
                <div class="panel-body">
                <div v-if="isLoading">
                	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                </div>
                 <form class="form-horizontal"  @submit.prevent=editAdvantage>
                       <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                               <input type="checkbox"  value="1" v-model="active">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Title</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="title" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Short Description</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="description"  />
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Avatar</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file"" id="file" ref="fileupload" ></input>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link :to="{name:'advantagesList'}"  class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
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
            data: function (){
                return {
                    active:'',
                    title:'',
                    description:'',
                    id: this.$route.params.id,
                    errors:[],
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getAdvantage()
                    {
                        axios.get('get-advantage/'+this.id).then(response=>{
                            this.active=response.data.active;
                            this.title=response.data.title;
                            this.description=response.data.description;
                            this.isLoading = false;
                        })
                    },
                    editAdvantage()
                    {
                        this.isLoading = true;
                        if(this.active)
                            this.active=1;
                        else this.active=0;

                        const fileInput = document.querySelector( '#file' );

                        const formData = new FormData();
                        formData.append( 'image', fileInput.files[0] );
                        formData.append( 'active', this.active);
                        formData.append( 'title', this.title );
                        formData.append( 'description', this.description );
                        formData.append( 'id', this.id );

                        axios.post('edit-advantage', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                    this.$router.push({name:'advantagesList'} ,() => {
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
                            } )
                    }



                },
            created(){
                this.getAdvantage();
            }
        }


        const routes = [

            {
                path: '/',
                component: AdvantagesList,
                name: 'advantagesList'
            },
            {
                path: '/add',
                component: AddAdvantage,
                name: 'addAdvantage'
            },
            {
                path: '/edit/:id',
                component: EditAdvantage,
                name: 'editAdvantage',
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