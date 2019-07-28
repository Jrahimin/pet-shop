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
<script src="https://cdnjs.cloudflare.com/ajax/libs/vuex/3.0.1/vuex.min.js"></script>
<script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

<script>
    Vue.use(Vuex)
    Vue.use(Toasted)

    var AttributesList = {
        template: `
     <div>

        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <router-link  :to="{name:'addAttribute'}"  class="btn btn-primary">@lang('Add Attribute')</router-link>
                </div>
            </div>
       </div>


     <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>

                        <th>Value</th>
                        <th>Detail</th>
                        <th>Actions</th>

                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="attribute in attributes" >
                        <td>@{{ attribute.value }}</td>
                        <td>@{{ attribute.detail }}</td>

                         <td >
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <a href="#"  @click.prevent="editAttribute(attribute.id)"  >@lang('Edit')</a></li>
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
                  <a @click.prevent="getGoods(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item" v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getGoods('get-goods?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getGoods(pagination.last_page_url)" href="#">Last Page</a>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteGood">@lang('Yes')</button>
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



            };
        },
        methods:
            {


                getAttributes(pageUrl)
                {
                    pageUrl = pageUrl == undefined ? 'get-attributes' : pageUrl

                    axios.get(pageUrl).then(response=>{

                        this.attributes=response.data.data;
                        this.pagination=response.data;
                    })
                },

                editProduct(id)
                {
                    console.log(this.pagination);
                    store.commit('savePagination',this.pagination) ;
                    store.commit('saveCurrentPage',this.pagination.current_page);
                    store.commit('saveSearchKeyword',this.search);
                    this.$router.push({name:'editProduct',params:{id : id}});

                },

                deleteGood()
                {
                    console.log(this.good);
                    axios.get('delete-good/'+this.good_id).then(response=>{
                        this.getGoods();
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
          this.getAttributes();
        }
    }


    var AddAttribute = {
        template: ` <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">Add Attribute Values</div>
                <div class="panel-body">

                 <form class="form-horizontal"  @submit.prevent=saveProduct>


                       <div class="form-group">
                            <label class="control-label col-md-2">Attribute</label>

                            <div class="col-md-8">
                                <select   class="form-control" v-model.number="input.attribute_id"  >
                                <option  value="1">Color</option>
                                <option  value="2">Size</option>
                                </select>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Value</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text"  v-model="input.value" ></input>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Detail</label>

                            <div class="col-md-8">
                                <input   class="form-control" type="text" v-model="input.detail" />
                            </div>

                        </div>



                      <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                      </div>


                        <div class="form-group">
                            <div class="col-md-10 text-right">
                                <router-link  :to="{name:'goodsList'}" class="btn btn-primary">@lang('Cancel')</router-link>
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
                    attribute_id :'',
                    value :'',
                    detail :''
                },
                errors :[],


            };
        },
        created()
        {

        },
        methods:
            {
                saveProduct()
                {
                    axios.post('add-attribute-value', this.input)
                        .then( ( response ) => {

                            this.errors = response.data.message;
                            if(this.errors == undefined)
                            {
                                location.reload() ;
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




                        } )
                },



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

    ]


    const router = new VueRouter({
        routes // short for `routes: routes`
    })

    const app = new Vue({
        router
    }).$mount('#app')

</script>

@endsection
