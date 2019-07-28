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

        var ManufacturerList = {
            template: `
     <div>
      <div class="filter-box" >
          <div class="row">
              <div class="col-md-12 text-right">
                  <router-link :to="{name:'addmanufacturer'}"  class="btn btn-primary">@lang('Create Manufacturer')</router-link>
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
                        <th>Logo</th>
                        <th>Logotype2</th>
                        <th>Title</th>
                        <th>Up/Down</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(manufacturer, index) in manufacturers" >
                        <td><img :src="manufacturer.img"></img></td>
                        <td><img :src="manufacturer.img2"></img></td>
                        <td>@{{ manufacturer.title }}</td>
                        <td>
                            <span v-if="index!=0" @click="up(manufacturer.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-up fa-2x"></i></i></span>
                            <span v-if="index!=manufacturers.length-1" @click="down(manufacturer.id)"><i style="color:#3f729b;cursor:pointer;" class="fa fa-caret-down fa-2x"></i></span>
                        </td>
                         <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editManufacturer',params:{id:manufacturer.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="manufacturer_id=manufacturer.id">@lang('Delete')</a></li>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteManufacturer">@lang('Yes')</button>
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
                    manufacturers:[],
                    manufacturer_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getManufacturers()
                    {
                        axios.get('get-manufacturers').then(response=>{
                            this.manufacturers=response.data;
                            this.isLoading = false;
                        })
                    },

                    deleteManufacturer()
                    {
                        axios.get('delete-manufacturer/'+this.manufacturer_id).then(response=>{
                            this.getManufacturers();
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
                    up(id)
                    {
                        axios.get('move-up/'+id).then(response=>{
                            this.getManufacturers();
                        })

                    },
                    down(id)
                    {

                        axios.get('move-down/'+id).then(response=>{
                            this.getManufacturers();
                        })

                    }

                },
            created(){
                this.getManufacturers();
            }
        };

        const AddManufacturer = {
            template: ` <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Manufacturer</div>
                <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                 <form class="form-horizontal"  @submit.prevent=saveManufacturer>
                       <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                               <input type="checkbox"  value="1" v-model="aktyvus">
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
                            <label class="control-label col-md-2">Products</label>

                              <div class="col-md-2">
                               <input type="radio"  value="1" v-model="prod"> For Horses
                            </div>
                            <div class="col-md-2">
                               <input type="radio"  value="2" v-model="prod">For Dogs
                            </div>
                            <div class="col-md-2">
                               <input type="radio"  value="3" v-model="prod">For Cats
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Logo in the directory</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file"" id="file1" ref="fileupload1" ></input>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Logo in the Homepage</label>

                            <div class="col-md-8">
                                <input  type="date" class="form-control" type="file" id="file2" ref="fileupload2"/>
                            </div>

                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                             <ul class ="list-unstyled">
                                 <li v-for="error in errors">@{{ error }}</li>
                             </ul>
                       </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                            <router-link  :to="{name:'manufacturerList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    aktyvus:'',
                    title:'',
                    description:'',
                    prod:'',
                    isLoading:false,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    saveManufacturer()
                    {
                        this.isLoading = true;
                        if(this.aktyvus)
                            this.aktyvus=1;
                        else this.aktyvus=0;

                        const fileInput = document.querySelector( '#file1' );
                        const fileInput2 = document.querySelector( '#file2' );
                        const formData = new FormData();
                        formData.append( 'file1', fileInput.files[0] );
                        formData.append( 'file2', fileInput2.files[0] );
                        formData.append( 'active', this.aktyvus);
                        formData.append( 'title', this.title );
                        formData.append( 'description', this.description );
                        formData.append( 'prod', this.prod );

                        axios.post('save-manufacturer', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                {
                                    this.$router.push({name:'manufacturerList'},() => {
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
                    }
                }
        }


        var EditManufacturer={
            template:`<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Manufacturer</div>
                <div class="panel-body">
                    <div v-if="isLoading">
	                    <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                 <form class="form-horizontal"  @submit.prevent=editManufacturer>
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
                            <label class="control-label col-md-2">Products</label>

                              <div class="col-md-2">
                               <input type="radio"  value="1" v-model="prod"> For Horses
                            </div>
                            <div class="col-md-2">
                               <input type="radio"  value="2" v-model="prod">For Dogs
                            </div>
                            <div class="col-md-2">
                               <input type="radio"  value="3" v-model="prod">For Cats
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Logo in the directory</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="file"" id="file1" ref="fileupload1" ></input>
                                <img :src="showImage"></img> Remove It
                                <input type="checkbox"  value="1" v-model="remove1">
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Logo in the Homepage</label>

                            <div class="col-md-8">
                                <input  type="date" class="form-control" type="file" id="file2" ref="fileupload2"/>
                                  <img :src="showImage2"></img>Remove It
                                <input type="checkbox"  value="1" v-model="remove2">
                            </div>

                        </div>
                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                             <ul class="list-unstyled">
                                 <li v-for="error in errors">@{{ error }}</li>
                             </ul>
                       </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                 <router-link  :to="{name:'manufacturerList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                <button class="btn btn-primary">@lang('Save')</button>
                            </div>
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
                    prod:'0',
                    id: this.$route.params.id,
                    showImage:'',
                    showImage2:'',
                    remove1:'',
                    remove2:'',
                    errors:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                   getManufacturer()
                   {
                       axios.get('get-manufacturer/'+this.id).then(response=>{
                           this.active=response.data.active;
                               this.title=response.data.title;
                               this.description=response.data.description;
                               this.showImage=response.data.showimg;
                               this.showImage2=response.data.showimg2;
                               if(response.data.prod1==1)
                               {
                                   this.prod=1;
                               }
                               else if(response.data.prod2==1)
                               {
                                   this.prod=2;
                               }
                               else if(response.data.prod3==1)
                               {
                                   this.prod=3;
                               }

                           this.isLoading = false;
                       })
                   },
                    editManufacturer()
                    {
                        this.isLoading = true;
                        if(this.active)
                            this.active=1;
                        else this.active=0;

                        this.remove1 = this.remove1 === true ? 1 : 0 ;
                        this.remove2 = this.remove2 === true ? 1 : 0 ;

                        const fileInput = document.querySelector( '#file1' );
                        const fileInput2 = document.querySelector( '#file2' );
                        const formData = new FormData();
                        formData.append( 'file1', fileInput.files[0] );
                        formData.append( 'file2', fileInput2.files[0] );
                        formData.append( 'active', this.active);
                        formData.append( 'title', this.title );
                        formData.append( 'description', this.description );
                        formData.append( 'prod', this.prod );
                        formData.append( 'id', this.id );
                        formData.append( 'remove1', this.remove1 );
                        formData.append( 'remove2', this.remove2 );

                        axios.post('edit-manufacturer', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                this.$router.push({name:'manufacturerList'},() => {
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


                            } )
                    }
                },
            created(){
                this.getManufacturer();
            }
        }

        const routes = [

            {
                path: '/',
                component: ManufacturerList,
                name: 'manufacturerList'
            },
            {
                path: '/add',
                component: AddManufacturer,
                name: 'addmanufacturer'
            },
            {
                path: '/edit/:id',
                component: EditManufacturer,
                name: 'editManufacturer',
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