@extends('frontend.layouts.master')

@section('content')
    <div id="userPage">
        <div class="container my-4">
            <ul class="nav nav-tabs user_tabs">
                <li class="nav-item">
                    <a class="nav-link  user_single_tab" href="{{route('user_info_index')}}">@lang('order_history.MY INFORMATION')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link user_single_tab" href="{{route('user_pet_info_index')}}">@lang('order_history.MY PETS')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active user_single_tab" href="{{route('order_history_index')}}">@lang('order_history.MY ORDER HISTORY')</a>
                </li>
            </ul>
        </div>




        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>

        let OrderInfo = {
            template: `
            <div id="list">
               <div class="container">
                  <div class="row mb-3">
                     <div class="col-md-12 table-responsive">
                          <table class="table table-hover table-striped">
                              <thead class="bg-info">
                                 <tr>
                                    <th>@lang('order_history.Id')</th>
                                   <th>@lang('order_history.Date')</th>

                                   <th>@lang('order_history.Total Amount')</th>
                                   <th class="text-right">@lang('order_history.Action')</th>
                                 </tr>
                                </thead>
                                <tbody>
                                      <tr class="table-secondary" v-for="order in orders" >
                                         <td>@{{ order.id }}</td>
                                         <td>@{{ order.date }}</td>

                                         <td>@{{ order.final_sum }}</td>

                                          <td class="text-right">
                                              <div class="dropdown">
                                                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                     <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                                  </button>
                                                  <ul class="dropdown-menu dropdown-menu-right order_dropdown1" aria-labelledby="dropdownMenu1">
                                                     <li> <router-link class="order_dropdown_link1" :to="{name:'detailOrder',params:{id:order.id}}">@lang('order_history.Order Detail')</router-link></li>
                                                  </ul>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr class="bg-success">
                                         <td><strong>@lang('order_history.Total')</strong></td>
                                         <td></td>
                                         <td>@{{ total }}</td>
                                      </tr>
                                </tbody>
                          </table>
                     </div>
                  </div>
               </div>

                 <div id="footer">

                      <div class="container">
                         <div class="row pb-2">
                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/map_spot_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="">
                                              <span class="footer_info_txt1">Yzipet</span> <span class="footer_info_txt2">Vilniuje:</span>
                                           </div>
                                           <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.work_hours }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                              @{{ contacts.telefonas }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.email }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row py-3">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                               <div class="footer_divider_line"><br></div>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row pt-1">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                               <div class="text-center">@lang("global.Socialize")</div>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row pt-2">
                            <div class="col-sm-12 col-md-12 col-lg-12 pb-4 text-center">
                               <a href="https://www.instagram.com/yzipet/" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/instagram.png') }}" alt="yzipet" /></a>
                               <a href="https://www.facebook.com/YZIpet" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/fb.png') }}" alt="yzipet" /></a>
                            </div>
                         </div>
                      </div>

                   </div>
            </div>




            `,
            data(){
                return{

                    orders :[],
                    user: {password:'', password_confirmation:'', change_pass:0},
                    id: {{ json_encode(Auth::user()->id) }}, errors:[],
                    total :0 ,
                }
            },
            created(){
                this.getUserOrders();
                this.getContact();
            },
            methods:{

                getUserOrders()
                {
                    axios.get('get-user-orders').then(response=>{
                        this.orders = response.data.orders ;
                        this.total = response.data.total ;
                    })
                },
                getContact()
                {
                    axios.get('get-contacts-home').then(response=>{
                        this.contacts = response.data;
                        console.log(this.contacts);
                    })
                },

            }
        }

        var DetailOrder={
            template:`

            <div id="list">

               <div class="container">
                  <div class="row mb-3">
                     <div class="col-md-12 table-responsive">
                        <div class="d-flex">
                           <div class="">
                              <h5>@lang("order_details.Products")</h5>
                           </div>
                        </div>

                        <table class="table table-borderless">
                           <thead class="bg-info">
                            <th>@lang("order_details.Product Title")</th>
                            <th>@lang("order_details.Quantity")</th>
                            <th>@lang("order_details.Product Price")</th>
                            <th>@lang("order_details.Amount")</th>
                           </thead>
                           <tbody>
                             <tr v-for="item in order.orderItems">
                              <td>@{{ item.detail.pavadinimas_lt }}</td>
                              <td>@{{ item.quantity }} vnt.</td>
                              <td>@{{ item.price }} EUR</td>
                              <td>@{{ item.sum }} EUR</td>
                             </tr>
                             </br>
                             <tr>
                                <td></td>
                                <td></td>
                                <td class="table-warning"><strong>@lang("order_details.Order Sum:")</strong></td>
                                <td class="bg-warning"><strong>@{{ order.items_sum }} EUR</strong></td>
                             </tr>
                             <tr>
                                <td></td>
                                <td></td>
                                <td class="table-warning"><strong>@lang("order_details.Delivery")</strong></td>
                                <td class="bg-warning"><strong>@{{ order.delivery_price }} EUR</strong></td>
                             </tr>
                             <tr v-if="order.payondel=='1'">
                                <td></td>
                                <td></td>
                                <td class="table-warning"><strong>@lang("order_details.Pay on Delivery by Cash/Card:")</strong></td>
                                <td class="bg-warning"><strong>@{{ order.payondel_price }} EUR</strong></td>
                             </tr>
                             <tr v-if="order.discounts !=0">
                                <td></td>
                                <td></td>
                                <td class="table-warning"><strong>@lang("order_details.Total Discount")</strong></td>
                                <td class="bg-warning"><strong>@{{ order.discounts }} EUR</strong></td>
                             </tr>
                             <tr>
                                <td></td>
                                <td></td>
                                <td class="table-success"><strong>@lang("order_details.Total Sum:")</strong></td>
                                <td class="bg-success"><strong>@{{ order.final_sum }} EUR</strong></td>
                             </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>

           <div id="footer">

                      <div class="container">
                         <div class="row pb-2">
                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/map_spot_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="">
                                              <span class="footer_info_txt1">Yzipet</span> <span class="footer_info_txt2">Vilniuje:</span>
                                           </div>
                                           <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.work_hours }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                              @{{ contacts.telefonas }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                               <table class="table table-borderless">
                                  <tbody>
                                     <tr>
                                        <td class="footer_table_th_frame">
                                           <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                                        </td>
                                        <td class="footer_table_th_frame">
                                           <div class="footer_info_txt3">
                                             @{{ contacts.email }}
                                           </div>
                                        </td>
                                     </tr>
                                  </tbody>
                               </table>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row py-3">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                               <div class="footer_divider_line"><br></div>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row pt-1">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                               <div class="text-center">@lang("global.Socialize")</div>
                            </div>
                         </div>
                      </div>

                      <div class="container">
                         <div class="row pt-2">
                            <div class="col-sm-12 col-md-12 col-lg-12 pb-4 text-center">
                               <a href="https://www.instagram.com/yzipet/" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/instagram.png') }}" alt="yzipet" /></a>
                               <a href="https://www.facebook.com/YZIpet" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/fb.png') }}" alt="yzipet" /></a>
                            </div>
                         </div>
                      </div>

                   </div>

            </div>


`,
            data: function (){
                return {
                    order_id:this.$route.params.id,
                    order:{
                        date:'',
                        paid:''
                    },
                    contacts :{},
                };
            },
            methods:
                {
                    getOrderDetail()
                    {
                        axios.get('get-user-order-detail/'+this.order_id).then(response=>{
                            this.order=response.data;

                        })
                    },
                    getContact()
                    {
                        axios.get('get-contacts-home').then(response=>{
                            this.contacts = response.data;
                            console.log(this.contacts);
                        })
                    },

                },
            created(){
                this.getOrderDetail();
                this.getContact();

            }
        }



        const routes = [
            { path: '/', component: OrderInfo, name: 'orderInfo' },
            {
                path: '/detail/:id',
                component: DetailOrder,
                name: 'detailOrder',
            },
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#userPage')

    </script>

@endsection
