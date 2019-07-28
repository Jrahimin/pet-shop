@extends('frontend.layouts.master')

@section('content')
    <div id="contactPage">


      <div class="container">

          <div style="margin-top: 200px;" v-if="isLoading">
              <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
          </div>
         <div class="row">
            <div class="col-md-12 col-lg-12">


                  <div v-for="(contact, index) in contacts">

                      <div class="row mt-4">
                          <div class="col-sm-6 col-md-6">
                             <div class="d-flex mb-2">
                                <div class=""><img src="{{asset('storage/images/map.png')}}"></div>
                             </div>
                             <div class="d-flex">
                                <div class="contact_page_title1">@{{ contact.title }}</div>
                             </div>
                             <div class="d-flex mb-2">
                                <div class="user_text1"><p>@{{ contact.adresas }}</p></div>
                             </div>
                             <div class="d-flex mb-2">
                                <div class=""><img src="{{asset('storage/images/workhours.png')}}"></div>
                             </div>
                             <div class="d-flex mb-3">
                                <div class="user_text1" v-html="contact.work_hours"></div>
                             </div>
                             <div class="d-flex mb-2">
                                <div class=""><img src="{{asset('storage/images/phone.png')}}"></div>
                             </div>
                             <div class="d-flex">
                                <div class="user_text1">@{{ contact.telefonas }}</div>
                             </div>
                             <div class="d-flex mb-2">
                                <div class="user_text1"><p>@{{ contact.email }}</p></div>
                             </div>

                             <div class="mb-5">
                               <form @submit.prevent="sendMail(index)" enctype="multipart/form-data">
                                   <div class="form-group row">
                                      <div class="col-md-12 col-lg-10">
                                         <img src="{{asset('storage/images/mail.png')}}">
                                      </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="col-md-12 col-lg-10">
                                           <input type="text" v-model="contacts[index].name" placeholder="Vardas" class="form-control">
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="col-md-12 col-lg-10">
                                           <input type="text" v-model="contacts[index].mail_from" placeholder="El. paštas" class="form-control">
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="col-md-12 col-lg-10">
                                           <input type="text" v-model="contacts[index].contact_no" placeholder="Tel." class="form-control">
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="col-md-12 col-lg-10">
                                           <textarea rows="4" v-model="contacts[index].comment" placeholder="Klauskite, siūlykite, bendraukite" class="form-control"></textarea>
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="col-md-12 col-lg-10">
                                           <button type="submit" class="btn save_btn">@lang('contacts.Send')</button>
                                       </div>
                                   </div>
                                   <div class="form-group alert alert-danger" v-if="index == error_index && errors!='' && errors!=undefined">
                                       <ul>
                                           <li v-for="error in errors">@{{ error }}</li>
                                       </ul>
                                   </div>
                                </form>
                               </div>
                               <div class="d-flex mb-2">
                                  <div class=""><img src="{{asset('storage/images/info.png')}}"></div>
                               </div>
                               <div class="d-flex">
                                  <div class="user_text1"><div v-html="contact.rekvizitai"></div></div>
                               </div>
                          </div>
                          <div class="col-sm-6 col-md-6">
                              <div class="d-flex">
                                 <div class="mb-3">
                                    <img class="img-fluid rounded" :src="contact.mapImage">
                                 </div>
                              </div>
                              <div class="d-flex">
                                 <div class="mt-3">
                                    <img class="img-fluid rounded" :src="contact.contactImage">
                                 </div>
                              </div>
                          </div>
                      </div>
                  </div>

                     <hr style="background-color: #8e532b">

            </div>
         </div>
      </div>


        <div id="footer">

            <div class="container">
                <div class="row pb-2">
                    <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
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
                                    <div class="footer_info_txt3">@{{ contactFooter.adresas }}</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contactFooter.work_hours }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contactFooter.telefonas }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contactFooter.email }}
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
                        <div class="text-center">Socializuokimės</div>
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


@endsection

@section('additionalJS')
    <script>


        Vue.use(axios);
        var ClipLoader = VueSpinner.ClipLoader;

        new Vue({
            el: '#contactPage',

            data: function(){
                return {
                    contacts:[], errors: [], error_index:'',contactFooter:{}, isLoading:true,
                };
            },
            created(){
                this.getContacts();
                this.getContactFooter();
            },
            components: {
                ClipLoader
            },
            methods:{
                getContacts()
                {
                    axios.get('get-new-contacts').then(response=>{
                        this.contacts = response.data;
                        this.isLoading = false ;
                    })
                },
                sendMail(index)
                {
                    axios.post('contact/send-mail', this.contacts[index]).then(response=>{
                        this.errors = response.data.message;
                        this.error_index = index;
                        /*if(this.errors == undefined)
                            console.log(response.data);*/
                    })
                },
                getContactFooter()
                {
                    axios.get('get-contacts-home').then(response=>{
                        this.contactFooter = response.data;

                    })
                },
            }
        })
    </script>
@endsection
