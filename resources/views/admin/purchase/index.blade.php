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
        var ClipLoader = VueSpinner.ClipLoader;

        var OrderList = {
            template: `
     <div>
         <div class="filter-box" >
            <div class="row">
            <form @submit.prevent="filterPurchase(0)" >
               <div class="form-group">
                 <label class="control-label col-md-1">@lang('Year')</label>
                  <div class="col-md-2">
                     <select   class="form-control" v-model="filterData.year" >
                     <option value=""></option>
                     <option v-for="year in years" :value=year>@{{ year }}</option>
                     </select>
                  </div>
               </div>

               <div class="form-group">
                 <label class="control-label col-md-1">@lang('Month')</label>
                  <div class="col-md-2">
                     <select  class="form-control" v-model="filterData.month" >
                         <option value=""></option>
                         <option value="01">January</option>
                         <option value="02">February</option>
                         <option value="03">March</option>
                         <option value="04">April</option>
                         <option value="05">May</option>
                         <option value="06">June</option>
                         <option value="07">July</option>
                         <option value="08">August</option>
                         <option value="09">September</option>
                         <option value="10">October</option>
                         <option value="11">November</option>
                         <option value="12">December</option>
                     </select>
                  </div>
               </div>

               <div class="form-group">
                   <label class="control-label col-md-1">@lang('Client')</label>
                  <div class="col-md-2">
                    <input  name="client" class="form-control" v-model="filterData.client"  />
                   </div>
               </div>

               <div class="form-group" >
                  <div class="col-md-2">
                    <button  class="btn btn-primary">@lang('Filter')</button>
                  </div>
               </div>
             </form>
            </div>
         </div>


        <div class="filter-box" >
            <div class="row">
                <div class="col-md-12 text-right">
                   <a :href="url" class="btn btn-primary">@lang('Export to Excel')</a>
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
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="order in orders" >
                        <td>@{{ order.date }}</td>
                        <td>@{{ order.name }} @{{ order.surname }}</td>
                        <td>@{{ order.email }}</td>
                        <td>@{{ order.final_sum }}</td>
                        <td v-if="order.paid == 'No'"><span style="background-color: red; color: white; padding: 5px;">@{{ order.paid }}</span></td>
                        <td v-if="order.paid == 'Yes'"><span style="background-color: green; color: white; padding: 5px;">@{{ order.paid }}</span></td>
                         <td class="text-right">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li> <router-link :to="{name:'detailOrder',params:{id:order.id}}"  >@lang('Order Detail')</router-link></li>
                                     <li v-if="order.shipped==0 && order.delivery_type!='shop'"> <router-link :to="{name:'createShipment',params:{id:order.id}}"  >@lang('Create Shipment')</router-link></li>
                                     <li v-if="order.shipped==1"> <a href="#" @click.prevent="getParcelLabel(order.id)"  >@lang('View Package Label')</a></li>
                                    <li><a href="#"  data-toggle="modal" data-target="#myModal" @click.prevent="purchase_id=order.id">@lang('Delete')</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>@{{ total_sum }}</td>
                    <td></td>
                    <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pagination.total > pagination.per_page" v-if="notfiltering" class="col-md-offset-4">
              <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getOrders(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="getOrders(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item"  :class="[{disabled:pagination.current_page==n}]"   v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="getOrders('get-orders?page='+n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getOrders(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="getOrders(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>
           </div>
            <div v-if="pagination.total > pagination.per_page" v-if="notfiltering==false" class="col-md-offset-4" >
              <ul class="pagination">

                  <ul class="pagination">
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterPurchase(pagination.first_page_url)" href="#">First Page</a>
                  </li>
                  <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                  <a @click.prevent="filterPurchase(pagination.prev_page_url)" href="#">Previous</a>
                  </li>
                  <li v-for="n in pagination.last_page" class="page-item"  :class="[{disabled:pagination.current_page==n}]"  v-if="n<=pagination.current_page+3&&n>=pagination.current_page-3">
                  <a @click.prevent="filterPurchase(n)" href="#">@{{ n }}</a>
                  </li>

                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="filterPurchase(pagination.next_page_url)" href="#">Next</a>
                  </li>
                  <li :class="[{disabled:!pagination.next_page_url}]" class="page-item">
                  <a @click.prevent="filterPurchase(pagination.last_page_url)" href="#">Last Page</a>
                  </li>
              </ul>

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
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  @click="deleteOrder">@lang('Yes')</button>
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
                    orders:[],
                    purchase_id:'',
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
                    filterData:{
                        year:'',
                        month:'',
                        client:'',
                        offset:''
                    },
                    length:'',
                    totalPages:'',
                    pageOffset:[],
                    notfiltering:true,
                    allUsers:[],
                    url:'',
                    total_sum:0,
                    client :'',
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                    getOrders(pageUrl)
                    {
                        pageUrl = pageUrl == undefined ? 'get-orders' : pageUrl

                        axios.get(pageUrl).then(response=>{
                            var total_sum=0;
                            this.orders=response.data.data;
                            for(var i=0;i<this.orders.length;i++)
                            {
                                //console.log(this.orders[i].final_sum)
                                var sum=parseFloat(this.orders[i].final_sum);
                                total_sum=total_sum + sum ;
                            }
                            this.total_sum=total_sum.toFixed(2);
                            this.pagination=response.data;
                            this.isLoading = false;
                        })
                    },

                    getYears()
                    {
                      axios.get('get-years').then(response=>{
                          this.years=response.data;
                      })
                    },


                    deleteOrder()
                    {
                        //console.log(this.purchase_id);
                        axios.get('delete-order/'+this.purchase_id).then(response=>{
                            this.getOrders();
                            this.getYears();
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

                    filterPurchase(pageUrl)
                    {
                        this.isLoading = true;
                        this.url = '{{route('export_excel',[':year',':month',':client'])}}';
                        if(this.filterData.year!='')
                        {
                            this.url = this.url.replace(':year', this.filterData.year);
                        }
                        else {
                            this.url = this.url.replace(':year', "null");
                        }
                        if(this.filterData.month!='')
                        {
                            this.url = this.url.replace(':month', this.filterData.month);
                        }
                        else {
                            this.url = this.url.replace(':month', "null");
                        }
                        if(this.filterData.client!='')
                        {
                            this.url = this.url.replace(':client', this.filterData.client);
                        }
                        else {
                            this.url = this.url.replace(':client', "null");
                        }


                         if(this.filterData.month=='')
                             this.filterData.month ="null";
                         if(this.filterData.year == '')
                             this.filterData.year = "null";
                        let client='';
                         if(this.filterData.client=='')
                           client = "null";
                         else client = this.filterData.client;

                        if(isNaN(pageUrl)==false && pageUrl!=0)
                        {
                            pageUrl =  'filter-orders/'+this.filterData.month+'/'+this.filterData.year+'/'+client+'?page='+pageUrl ;
                        }


                        pageUrl = pageUrl == 0 ? 'filter-orders/'+this.filterData.month+'/'+this.filterData.year+'/'+client : pageUrl


                        axios.get(pageUrl).then(response=>{

                            this.orders=response.data.data;
                            var total_sum=0;
                            for(var i=0;i<this.orders.length;i++)
                            {
                                //console.log(this.orders[i].final_sum)
                                var sum=parseFloat(this.orders[i].final_sum);
                                total_sum=total_sum + sum ;
                            }
                            this.total_sum=total_sum.toFixed(2);
                            this.notfiltering=false;
                            this.pagination=response.data;
                            this.isLoading = false;
                        })

                    },


                    getParcelLabel(id)
                    {
                       location.replace('view-parcel-label/'+id);
                    },


                },
            created(){
                this.getOrders();
                this.getYears();
                this.url = '{{route('export_excel',[':year',':month',':client'])}}';
                this.url = this.url.replace(':year', "null");
                this.url = this.url.replace(':month', "null");
                this.url = this.url.replace(':client', "null");
            }
        }


        var DetailOrder={
            template:` <div class="box box-primary" style="padding:20px" id="list">
                        <div v-if="isLoading">
					        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				        </div>
                       <div class="row">
                        <div class="col-md-12 table-responsive">
                        <div class="col-md-6"><h4>General Information</h4>
                        <table class="table table-hover table-striped">
                        <thead>
                        <th></th>
                      <th></th>
                        </thead>
                        <tbody>
                          <tr><td> Order Id  </td>  <td>@{{ order_id }}</td></tr>
                         <tr> <td> Date     </td>  <td>@{{ order.date }}</td></tr>
                          <tr><td> Paid   </td>    <td >@{{ order.paid }}</td></tr>
                          <tr><td> Delivery   </td>    <td>@{{ order.delivery }}</td></tr>
                          <tr v-if="order.paid ==1"><td> Payment </td> <td>@{{ order.paymentMethod }} </td> </tr>
                          <tr v-if="order.delivery_type =='omniva'"><td> Terminal Address </td> <td>@{{ order.terminalAddress }} </td> </tr>
                        </tbody>
                        </table>
                        </div>
                         <div class="col-md-6"><h4>Customer Information</h4>
                        <table class="table table-hover table-striped">
                        <thead>
                        <th></th>
                      <th></th>
                        </thead>
                        <tbody>
                          <tr><td> Name  </td>  <td>@{{ order.name }} @{{ order.surname }}</td></tr>
                         <tr> <td> Phone     </td>  <td>@{{ order.phone }}</td></tr>
                          <tr><td> Email   </td>    <td>@{{ order.email }}</td></tr>
                           <tr><td> City   </td>    <td>@{{ order.city }}</td></tr>
                            <tr><td> Address   </td>    <td>@{{ order.address }}</td></tr>
                             <tr><td> Zip Code   </td>    <td>@{{ order.zip_code }}</td></tr>
                               <tr v-if="order.company_title!='' && order.company_title != null"><td> Company    </td>    <td>@{{ order.company_title }}</td></tr>
                            <tr v-if="order.company_code!='' && order.company_code != null"><td> Company Code   </td>    <td>@{{ order.company_code }}</td></tr>
                             <tr v-if="order.company_vatcode!='' && order.company_vatcode != null"><td> Company Vat Code   </td>    <td>@{{ order.company_vatcode }}</td></tr>
                        </tbody>
                        </table>
                        </div>
                        <br/>
                         <div v-if="order.delivery_type == 'venipak'" class="col-md-8 col-md-offset-2"><h4>Delivery Details</h4>
                        <table class="table table-hover table-striped">
                        <thead>
                        <th></th>
                      <th></th>
                        </thead>
                        <tbody>

                          <tr><td> City   </td>    <td>@{{ order.delivery_city }}</td></tr>
                            <tr><td> Address   </td>    <td>@{{ order.delivery_address }}</td></tr>
                             <tr><td> Zip Code   </td>    <td>@{{ order.delivery_zip_code }}</td></tr>
                             <tr v-if="order.delivery_notes!='' "><td> Additional Info   </td>    <td>@{{ order.delivery_notes }}</td></tr>
                        </tbody>
                        </table>
                        </div>
                        <div class="col-md-12"><h4>Goods</h4>
                        <table class="table table-hover table-striped">
                        <thead>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Amount</th>
                        </thead>
                        <tbody>

                         <tr v-for="item in order.orderItems">
                          <td>@{{  item.detail.pavadinimas_lt}}</td>
                          <td>@{{  item.quantity}}</td>
                          <td>@{{  item.price}}</td>
                          <td>@{{  item.sum}}</td>
                         </tr>
                         <tr>
                          <td></td>
                          <td></td>
                          <td>Goods</td>
                          <td>@{{ order.items_sum }}  EUR</td>
                         </tr>
                         <tr>
                          <td></td>
                          <td></td>
                          <td>Delivery</td>
                          <td>@{{ order.delivery_price }}  EUR</td>
                         </tr>
                          <tr v-if="order.payondel=='Yes'">
                          <td></td>
                          <td></td>
                          <td>Payment for cash courier</td>
                          <td>@{{ order.payondel_price }}  EUR</td>
                         </tr>
                         <tr>
                          <td></td>
                          <td></td>
                          <td>Total</td>
                          <td>@{{ order.final_sum }}  EUR</td>
                         </tr>
                        </tbody>
                        </table>
                        </div>
              </div>


              <router-link  class="btn btn-primary" :to="{name:'orderList'}"  >@lang('Back')</router-link>

            </div>
        </div>`,
            data: function (){
                return {
                    order_id:this.$route.params.id,
                    order:{
                     date:'',
                        paid:''
                    },
                    isLoading:true,
                };
            },
            components: {
                ClipLoader
            },
            methods:
                {
                   getOrderDetail()
                   {
                       axios.get('order-detail/'+this.order_id).then(response=>{
                           this.order=response.data;
                           this.isLoading = false;
                       })
                   }
                },
            created(){
             this.getOrderDetail();
            }
        }

        var CreateShipment = {
            template :  `
            <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-info">
                 <div class="panel-heading">Create Shipment</div>
                    <div class="panel-body">
                        <div v-if="isLoading">
					        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
				        </div>
                        <form class="form-horizontal" @submit.prevent="save">
                            <div v-if="shipmentInfo.shipment_type=='venipak'" class="form-group">
                                <label class="control-label col-md-2">Number of Parcels</label>
                                <div class="col-md-8">
                                     <input class="form-control" v-model="shipmentInfo.num_of_parcel" />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 text-right">
                                    <router-link :to="{name:'orderList'}" class="btn btn-primary">@lang('Cancel')</router-link>
                                    <button class="btn btn-primary">@lang('Save')</button>
                                </div>
                            </div>

                          <div class="col-md-12"><h4>Goods</h4>
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                     </thead>
                                    <tbody>

                                         <tr v-for="item in order.orderItems">
                                          <td>@{{  item.detail.pavadinimas_lt}}</td>
                                          <td>@{{  item.quantity}}</td>
                                          <td>@{{  item.price}}</td>
                                          <td>@{{  item.sum}}</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                           <td>Goods</td>
                                           <td></td>
                                          <td>@{{ order.items_sum }}  EUR</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td>Delivery</td>
                                          <td></td>
                                          <td>@{{ order.delivery_price }}  EUR</td>
                                         </tr>
                                          <tr v-if="order.payondel=='Yes'">
                                          <td></td>

                                          <td>Payment for cash courier</td>
                                           <td></td>
                                          <td>@{{ order.payondel_price }}  EUR</td>
                                         </tr>
                                         <tr>
                                          <td></td>

                                          <td>Total Price</td>
                                           <td></td>
                                          <td>@{{ order.final_sum }}  EUR</td>
                                         </tr>
                                          <tr>
                                          <td></td>

                                          <td>Total Weight</td>
                                           <td></td>
                                          <td>@{{ order.totalweight }} </td>
                                         </tr>
                                   </tbody>
                                </table>
                         </div> </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                                            <ul>
                                            <li >@{{ errors }}</li>
                                            </ul>
                         </div>
                        </form>
                   </div>
              </div>
          </div>
        </div>
            `,
            data: function(){
                return {
                    errors:'',
                    order:{},
                    shipmentInfo :{
                        num_of_parcel:'',
                        order_id:this.$route.params.id,
                        shipment_type:'',
                        sendMethod:'c',
                    },
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
                        axios.post('create-shipment', this.shipmentInfo)
                            .then( response => {
                                this.isLoading = false;
                                this.errors = response.data.message;
                                console.log(response);
                              if(this.errors == undefined)
                              {
                                 /* this.$router.push({name:'orderList'});*/

                                  location.replace('view-parcel-label/'+this.shipmentInfo.order_id);
                              }

                            } )
                    },

                    getOrderDetail()
                    {
                        axios.get('order-detail/'+this.shipmentInfo.order_id).then(response=>{
                            this.order=response.data;
                        })
                    },
                    getShipmentType()
                    {
                        axios.get('get-shipment-type/'+this.shipmentInfo.order_id).then(response=>{
                            this.shipmentInfo.shipment_type = response.data ;

                        })
                    }


                },
            created(){
               this.getOrderDetail();
               this.getShipmentType();
            }
        }

        const routes = [

            {
                path: '/',
                component: OrderList,
                name: 'orderList'
            },

            {
                path: '/detail/:id',
                component: DetailOrder,
                name: 'detailOrder',
            },
            {
              path: '/shipment/:id',
              component: CreateShipment,
              name:'createShipment'
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