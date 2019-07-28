@extends('frontend.layouts.master')

@section('content')
    <div id="userPage">
      <div class="container my-4">
         <ul class="nav nav-tabs user_tabs">
           <li class="nav-item">
             <a class="nav-link active user_single_tab" href="{{route('user_info_index')}}">@lang('user_info.MY INFORMATION')</a>
           </li>
           <li class="nav-item">
             <a class="nav-link user_single_tab" href="{{route('user_pet_info_index')}}">@lang('user_info.MY PETS')</a>
           </li>
           <li class="nav-item">
             <a class="nav-link user_single_tab" href="{{route('order_history_index')}}">@lang('user_info.MY ORDER HISTORY')</a>
           </li>
         </ul>
      </div>

      {{-- <div class="form-group">
          <div class="col-sm-4">
             <div class="checkbox">
                  <label><input v-model="user.iscompany" type="checkbox">Company reperesent</label>
             </div>
          </div>
      </div> --}}



        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>

        let UserInfo = {
            template: `
            <div>
               <div class="container">
                  <div class="row">
                     <div class="col-sm-12 col-md-12 col-lg-12 mb-3 mt-1">
                           <form @submit.prevent="editUser">
                                 <h3 class="user_title1">@lang('user_info.MY INFORMATION')</h3>
                                 <br/>
                                 <div class="d-flex">
                                    <div class="mr-1 user_text1">
                                       <p>@lang('user_info.User email'):</p>
                                    </div>
                                    <div class="ml-1 user_text1">
                                       <b>@{{ user.email }}</b>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                     <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                         <label class="checkout_labels" for="name">@lang('user_info.Name')*</label>
                                         <input type="text" class="form-control" v-model="user.name">
                                     </div>

                                     <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                         <label class="checkout_labels" for="surname">@lang('user_info.Surname')*</label>
                                         <input type="text" class="form-control" v-model="user.surname">
                                     </div>

                                     <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                         <label class="checkout_labels" for="phone">@lang('user_info.Telephone')</label>
                                         <input type="text" class="form-control" v-model="user.phone">
                                     </div>
                                 </div>

                                 <div class="custom-control custom-checkbox mb-3">
                                     <input type="checkbox" class="custom-control-input" id="repCompanyCheck" v-model="user.iscompany">
                                     <label class="custom-control-label checkout_labels" for="repCompanyCheck">Atstovauju įmonę (reikalinga PVM sąskaita faktūra įmonės vardu)</label>
                                 </div>

                                 <div style="clear:both;"></div>

                                 <div v-if="user.iscompany == 1">
                                     <div class="form-group">
                                         <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                             <label class="checkout_labels" for="name">@lang('user_info.Company name')</label>
                                             <input type="text" class="form-control" v-model="user.company_title">
                                         </div>

                                         <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                             <label class="checkout_labels" for="surname">@lang('user_info.Company code')</label>
                                             <input type="text" class="form-control" v-model="user.company_code">
                                         </div>

                                         <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                             <label class="checkout_labels" for="phone">@lang('user_info.Vat code')</label>
                                             <input type="text" class="form-control" v-model="user.company_vatcode">
                                         </div>
                                     </div>
                                 </div>

                                 <div class="form-group">
                                      <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                          <label class="checkout_labels" for="name">@lang('user_info.City')</label>
                                          <input type="text" class="form-control" v-model="user.city">
                                      </div>

                                      <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                          <label class="checkout_labels" for="surname">@lang('user_info.Address')</label>
                                          <input type="text" class="form-control" v-model="user.address">
                                      </div>

                                      <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                          <label class="checkout_labels" for="phone">@lang('user_info.Postal code')</label>
                                          <input type="text" class="form-control" v-model="user.zip_code">
                                      </div>
                                 </div>

                                 <div class="custom-control custom-checkbox mb-3">
                                     <input type="checkbox" class="custom-control-input" id="passwordChange" v-model="user.change_pass">
                                     <label class="custom-control-label checkout_labels" for="passwordChange">@lang('user_info.Change the password')</label>
                                 </div>

                                 <div style="clear:both;"></div>

                                 <div v-if="user.change_pass == 1">
                                     <div class="form-group">
                                         <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                             <label class="checkout_labels" for="name">@lang('user_info.Password')</label>
                                             <input type="text" class="form-control" v-model="user.password">
                                         </div>

                                         <div class="col-sm-12 col-md-10 col-lg-6 col-xl-6">
                                             <label class="checkout_labels" for="surname">@lang('user_info.Repeat Password')</label>
                                             <input type="text" class="form-control" v-model="user.password_confirmation">
                                         </div>
                                     </div>
                                 </div>
                                 <div style="clear:both;"></div>

                                 <div class="form-group">
                                     <button type="submit" class="btn btn-primary save_btn">@lang('user_info.Save')</button>
                                 </div>
                                 <div style="clear:both;"></div>

                                 <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                                     <ul>
                                         <li v-for="error in errors">@{{ error }}</li>
                                     </ul>
                                 </div>
                                 <div style="clear:both;"></div>
                           </form>
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

            </div>`,
            data(){
                return{
                    user: {password:'', password_confirmation:'', change_pass:0},
                    id: {{ json_encode(Auth::user()->id) }}, errors:[],contacts:{}
                }
            },
            created(){
                this.getUser();
                this.getContact();
            },
            methods:{
                getUser()
                {
                    axios.get('get-user/'+this.id).then(response=>{
                        this.user = response.data;
                    });
                },

                editUser()
                {
                    axios.post('edit-user', this.user).then(response=>{
                        this.errors = response.data.message;
                        if(this.errors == undefined)
                            this.$router.go();
                    });
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


        const routes = [
            { path: '/', component: UserInfo, name: 'userInfo' },
/*            {path: '/details/:id', component: ProductDetails, name: 'productDetails'}*/
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#userPage')

    </script>

@endsection
