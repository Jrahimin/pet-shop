@extends('layouts.master')

@section('content')
    <div id="bannerPage">
        <router-view></router-view>
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

        var BannerList = {
            template: `
            <div>
       <div class="filter-box" >
         <div class="row">
                <div class="col-md-12 text-right">
                   <router-link :to="{name:'addBanner'}"  class="btn btn-primary">@lang('Add Banner')</router-link>
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
                            <th>Title</th>
                            <th>Home</th>
                            <th>Criterion</th>
                            <th>Limit</th>
                            <th>Shown</th>
                            <th>Pressed</th>
                            <th>Effect</th>
                            <th>Act</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    <tr v-for="banner in banners" >
                        <td>@{{ banner.pavadinimas }}</td>
                        <td>@{{ banner.data_nuo }}</td>
                        <td>@{{ banner.kriterijus }}</td>
                        <td>@{{ banner.limit }}</td>
                        <td>@{{ banner.parodyta }}</td>
                        <td>@{{ banner.paspausta }}</td>
                        <td>@{{ banner.effect }}%</td>
                        <td>@{{ banner.act }}

                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'editBanner',params:{id:banner.id}}" >@lang('Edit')</router-link></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="banner_id=banner.id">@lang('Delete')</a></li>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteBanner">@lang('Yes')</button>
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
                    banners:[],
                    banner_id:'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getBanners()
                    {
                        axios.get('get-banners').then(response=>{
                            this.banners=response.data;
                            this.isLoading = false;
                        })
                    },
                    deleteBanner()
                    {
                        axios.get('delete-banner/'+this.banner_id).then(response=>{
                            this.getBanners();
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
                this.getBanners();
            }
        }

        var AddBanner = {
            template: `
       <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Add Banner</div>
                <div class="panel-body">
                <div v-if="isLoading">
                	<div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                </div>
                    <form class="form-horizontal" @submit.prevent="save">
                        <div class="form-group">
                            <label class="control-label col-md-2">Title</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="pavadinimas" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Reference</label>

                            <div class="col-md-8">
                                <input   class="form-control" v-model="link"  />
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Banner</label>

                            <div class="col-md-8">
                                <input type="file"  class="form-control" id="file" ref="fileupload"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Code</label>

                            <div class="col-md-8">
                                <textarea   class="form-control" v-model="kodas"  ></textarea>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">From(date)</label>

                            <div class="col-md-8">
                                <vuejs-datepicker  v-model="data_nuo" input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Criterion</label>

                            <div class="col-md-8">
                                <select   class="form-control"  v-model.number="kriterijus" >
                                    <option value="1">Laikotarpis</option>
                                    <option value="2">Parodymų skaičius</option>
                                    <option value="3">Paspaudimų skaičius</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">By(date)</label>

                            <div class="col-md-8">

                              <vuejs-datepicker  v-model="data_iki"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Place of Display</label>

                            <div class="col-md-8">
                                <select   class="form-control"  v-model.number="vieta" >
                                    <option value="0">Naujų prekių viršuje</option>
                                    <option value="1">Perkamiausių prekių viršuje</option>

                                </select>
                            </div>

                        </div>

                        <div v-if="kriterijus==2" class="form-group">
                        <label class="control-label col-md-2">Impression</label>

                        <div class="col-md-8">
                          <input type="text" v-model="parodymai" class="form-control" />
                        </div>

                    </div>

                     <div v-if="kriterijus==3" class="form-group">
                        <label class="control-label col-md-2">Clicks</label>

                        <div class="col-md-8">
                          <input type="text"  v-model="paspaudimai" class="form-control" />
                        </div>

                    </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Active</label>

                            <div class="col-md-2">
                               <input type="checkbox"  value="1" v-model="aktyvus">
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                  <router-link  :to="{name:'bannerList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
        </div>
`,
            data: function(){
                return {

                    aktyvus:0,
                    vieta:'',
                    data_iki:'',
                    kriterijus:'',
                    data_nuo:'',
                    kodas:'',
                    pavadinimas:'',
                    link:'',
                    paspaudimai:'',
                    parodymai:'',
                    errors:[],
                    isLoading:false,

                };
            },
            components: {
                vuejsDatepicker,
                ClipLoader,
            },
            methods:
                {
                    save()
                    {
                        this.isLoading = true;
                        if(this.aktyvus)
                            this.aktyvus=1;
                        else this.aktyvus=0;

                        const fileInput = document.querySelector( '#file' );
                        const formData = new FormData();
                        formData.append( 'banner', fileInput.files[0] );
                        formData.append( 'aktyvus', this.aktyvus);
                        formData.append( 'vieta', this.vieta );
                        formData.append( 'link', this.link );
                        formData.append( 'data_iki', moment(this.data_iki).format('YYYY-MM-DD'));
                        formData.append( 'kriterijus', this.kriterijus );
                        formData.append( 'kodas', this.kodas );
                        formData.append( 'data_nuo', moment(this.data_nuo).format('YYYY-MM-DD') );
                        formData.append( 'pavadinimas', this.pavadinimas );
                        formData.append( 'paspaudimai', this.paspaudimai );
                        formData.append( 'parodymai', this.parodymai );
                        axios.post('save-banner', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                {
                                    this.$router.push({name:'bannerList'}, () => {
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

        var EditBanner={
            template:`
            <div class="col-md-8 col-md-offset-2">
               <div class="panel panel-info">
                   <div class="panel-heading">Edit Banner</div>
                  <div class="panel-body">
                  <div v-if="isLoading">
	                <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                  </div>
                     <form class="form-horizontal" @submit.prevent="editBanner">
                    <div class="form-group">
                        <label class="control-label col-md-2">Title</label>

                        <div class="col-md-8">
                            <input   class="form-control" v-model="banner.pavadinimas" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Reference</label>

                        <div class="col-md-8">
                            <input   class="form-control" v-model="banner.link"  />
                        </div>

                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-2">Banner</label>

                        <div class="col-md-8">
                            <input type="file"  class="form-control" id="file" ref="fileupload"/>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Code</label>

                        <div class="col-md-8">
                            <textarea   class="form-control" v-model="banner.kodas"  ></textarea>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">From(date)</label>

                        <div class="col-md-8">
                            <vuejs-datepicker  v-model="banner.data_nuo"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Criterion</label>

                        <div class="col-md-8">
                            <select   class="form-control"  v-model.number="banner.kriterijus" >
                                <option value="1">Laikotarpis</option>
                                <option value="2">Parodymų skaičius</option>
                                <option value="3">Paspaudimų skaičius</option>
                            </select>
                        </div>

                    </div>

                     <div v-if="banner.kriterijus==2" class="form-group">
                        <label class="control-label col-md-2">Impression</label>

                        <div class="col-md-8">
                          <input type="text" v-model="banner.parodymai" class="form-control" />
                        </div>

                    </div>

                     <div v-if="banner.kriterijus==3" class="form-group">
                        <label class="control-label col-md-2">Clicks</label>

                        <div class="col-md-8">
                          <input type="text" v-model="banner.paspaudimai" class="form-control" />
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">By(date)</label>

                        <div class="col-md-8">
                            <vuejs-datepicker  v-model="banner.data_iki"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Place of Display</label>

                        <div class="col-md-8">
                            <select   class="form-control"  v-model.number="banner.vieta" >
                                <option value="0">Naujų prekių viršuje</option>
                                <option value="1">Perkamiausių prekių viršuje</option>

                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Active</label>

                        <div class="col-md-2">
                            <input type="checkbox"  value="1" v-model="banner.aktyvus">
                        </div>

                    </div>
                    <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>


                    <div class="form-group">
                        <div class="col-md-10 text-right">
                            <router-link  :to="{name:'bannerList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                            <button class="btn btn-primary">@lang('Save')</button>
                        </div>
                    </div>
                </form>
           </div>
        </div>
    </div>`,
            data: function(){
                return {

                    banner:{
                        aktyvus:0,
                        vieta:'',
                        data_iki:'',
                        kriterijus:'',
                        data_nuo:'',
                        kodas:'',
                        pavadinimas:'',
                        paspaudimai:'',
                        parodymai:'',
                        link:'',
                    },
                    isLoading:true,
                    errors:[],
                    banner_id:this.$route.params.id,

                };
            },
            components: {
                vuejsDatepicker,
                ClipLoader,
            },
            methods:
                {
                    getBanner()
                    {
                        axios.get('get-banner/'+this.banner_id).then(response=>{
                            this.banner=response.data;
                            this.isLoading = false;
                        })
                    },
                    editBanner()
                    {
                        this.isLoading = true;
                        if(this.banner.aktyvus)
                            this.banner.aktyvus=1;
                        else this.banner.aktyvus=0;
                        const fileInput = document.querySelector( '#file' );
                        const formData = new FormData();
                        formData.append( 'banner', fileInput.files[0] );
                        formData.append( 'id', this.banner_id);
                        formData.append( 'aktyvus', this.banner.aktyvus);
                        formData.append( 'vieta', this.banner.vieta );
                        formData.append( 'link', this.banner.link );
                        formData.append( 'data_iki',  moment(this.banner.data_iki).format('YYYY-MM-DD'));
                        formData.append( 'kriterijus', this.banner.kriterijus );
                        formData.append( 'kodas', this.banner.kodas );
                        formData.append( 'data_nuo', moment(this.banner.data_nuo).format('YYYY-MM-DD')  );
                        formData.append( 'pavadinimas', this.banner.pavadinimas );
                        formData.append( 'paspaudimai', this.banner.paspaudimai );
                        formData.append( 'parodymai', this.banner.parodymai );
                        axios.post('edit-banner', formData)
                            .then( ( response ) => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                if(this.errors == undefined)
                                 this.$router.push({name:'bannerList'}, () => {
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
               this.getBanner();
            }

        }


        Vue.use(axios);


        const routes = [

            {
                path: '/',
                component: BannerList,
                name: 'bannerList'
            },

            {
                path: '/add',
                component: AddBanner,
                name: 'addBanner'
            },
            {
                path: '/edit/:id',
                component: EditBanner,
                name: 'editBanner'
            },

        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#bannerPage')


    </script>



@endsection