@extends('frontend.layouts.master')

@section('header')
    {{--<base href="{{asset('')}}">--}}
@endsection
@section('content')
    <div id="interestingPage">
        <router-view></router-view>
    </div>
    <div style="clear: both;"></div>
@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>

        var ClipLoader = VueSpinner.ClipLoader;

        let InterestingList = {
            template: `
   <div>

            <div style="margin-top: 200px;" v-if="isLoading">
                <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
            </div>

            <div v-for="(interesting,index) in interestings">
            <div class="container"  v-if="index%2 != 0">
                 <div class="row pb-3">
                    <div class="col-sm-6 col-md-6 col-lg-6">
                       <div class="d-flex justify-content-center">

                          <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                            <img class="img-fluid rounded-circle" :src="interesting.imageDir" alt="yzipet" style="cursor: pointer;" />
                        </router-link>
                       </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                       <div class="d-flex">
                          <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                             <a class="blog_title_link" href="#">@{{ interesting.title }}</a>
                          </router-link>
                       </div>
                       <div class="d-flex">
                          <div class="blog_desc_text1">
                             @{{ interesting.autorius }}
                          </div>
                       </div>

                       <div class="d-flex">
                          <div class="blog_desc_text2 pt-3">
                               <div v-html="interesting.shortdesc"
                               style="max-height: 3.6em;
                              line-height:1.2;
                              word-spacing: 0.20em;
                              overflow:hidden;
                              text-align: justify;"></div>
                          </div>
                       </div>

                       <div class="d-flex pt-4">
                          <div class="">
                             <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                                 <img class="img-fluid" src="{{ asset('images/blog_read_more_icon.png') }}" alt="yzipet" style="cursor: pointer;"/>
                              </router-link>
                          </div>
                       </div>
                    </div>
                 </div>

                  <div class="container">
                         <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 pb-4">
                               <div class="d-flex flex-row">
                                  <div class="w-50 section_div_line"></div>
                                  <div class="mx-3">
                                     <img class="img-fluid" src="{{ asset('images/yzipet_section_divider.png') }}" alt="yzipet" />
                                  </div>
                                  <div class="w-50 section_div_line"></div>
                               </div>
                            </div>
                         </div>
                 </div>
          </div>


          <div class="container"  v-if="index%2 == 0">
                 <div class="row mt-3 pb-3">

                    <div class="col-sm-6 col-md-6 col-lg-6">
                       <div class="d-flex">
                          <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                             <a class="blog_title_link" href="#">@{{ interesting.title }}</a>
                          </router-link>
                       </div>
                       <div class="d-flex">
                          <div class="blog_desc_text1">
                             @{{ interesting.autorius }}
                          </div>
                       </div>

                       <div class="d-flex">
                          <div class="blog_desc_text2 pt-3">
                               <div v-html="interesting.shortdesc"
                               style="max-height: 3.6em;
                              line-height:1.2;
                              word-spacing: 0.20em;
                              overflow:hidden;
                              text-align: justify;"></div>
                          </div>
                       </div>

                       <div class="d-flex pt-4">
                          <div class="">
                             <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                                 <img class="img-fluid" src="{{ asset('images/blog_read_more_icon.png') }}" alt="yzipet" style="cursor: pointer;"/>
                              </router-link>
                          </div>
                       </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6">
                       <div class="d-flex justify-content-center">

                          <router-link style="text-decoration: none;" :to="{name:'interestingDetails',params:{id:interesting.id}}">
                            <img class="img-fluid rounded-circle" :src="interesting.imageDir" alt="yzipet" style="cursor: pointer;" />
                        </router-link>
                       </div>
                    </div>

                 </div>

                  <div class="container">
                         <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 pb-4">
                               <div class="d-flex flex-row">
                                  <div class="w-50 section_div_line"></div>
                                  <div class="mx-3">
                                     <img class="img-fluid" src="{{ asset('images/yzipet_section_divider.png') }}" alt="yzipet" />
                                  </div>
                                  <div class="w-50 section_div_line"></div>
                               </div>
                            </div>
                         </div>
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
                                         <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
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
                                               @{{ contacts.work_hours }}
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
                                                                          @{{ contacts.telefonas }}
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




  </div>`,
            data(){
                return{
                    interestings: [], interesting_id: '', contacts:{},isLoading :true,
                }
            },
            created(){
                this.getInterestings();
                this.getContact();
            },
            components: {
                ClipLoader
            },
            methods:{
                getInterestings()
                {
                    axios.get('get-new-interestings').then(response=>{
                        this.interestings = response.data;
                        this.isLoading = false ;
                        console.log(this.interestings)
                    });
                },

                getContact()
                {
                    axios.get('get-contacts-home').then(response=>{
                        this.contacts = response.data;
                    })
                },
            }
        }

        let InterestingDetails = {
            template: `
            <div>
               <div class="container">

                     <div style="margin-top: 200px;" v-if="isLoading">
                        <div class="overlay"><clip-loader size="100px" class="overlay-content"></clip-loader></div>
                    </div>
                  <div class="row mt-3">
                     <div class="col-md-12 col-lg-12 my-4">
                        <div class="d-flex">
                           <div class="">
                              <div class="blog_title_link">@{{ interesting.title }}</div><br>
                              <div class="blog_desc_text1">@{{ interesting.autorius }}</div>
                           </div>
                           <div class="ml-auto">
                              <img class="img-fluid rounded-circle" :src="interesting.imageDir">
                           </div>
                        </div>

                        <div class="my-3 user_divider"></div>
                        <div v-html="interesting.description"></div>
                     </div>
                  </div>
               </div>

               <div class="container">
                    <div class="row">
                        <div v-for="gallery in interesting.gallery" class="col-md-4 col-lg-4">
                            <div class="d-flex">
                                <div class="ml-auto">
                                    <img v-if="!gallery.video" class="img-fluid rounded-circle" :src="gallery.imageDir">
                                    <iframe v-if="gallery.video" width="350" height="200" :src="gallery.embedVideoLink"></iframe>
                                </div>
                            </div>
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
                                            <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
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
                                                @{{ contacts.work_hours }}
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
                                                @{{ contacts.telefonas }}
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

            </div>`,
            data(){
                return{
                    interesting:{
                        title:'', autorius:'', imageDir:'', description:''
                    },
                    id: this.$route.params.id,contacts:{},isLoading : true ,
                }
            },
            created(){
                this.getInterestings();
                this.getContact();
            },
            components: {
                ClipLoader
            },
            methods:{
                getInterestings()
                {
                    axios.get('get-new-interesting/'+this.id).then(response=>{
                        this.interesting = response.data;
                        this.isLoading = false ;
                        console.log(this.interesting)
                    });
                },
                getContact()
                {
                    axios.get('get-contacts-home').then(response=>{
                        this.contacts = response.data;

                    })
                },

            }
        }


        const routes = [
            { path: '/', component: InterestingList, name: 'interestingList' },
            {path: '/details/:id', component: InterestingDetails, name: 'interestingDetails'}
        ]

        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router
        }).$mount('#interestingPage')

    </script>

@endsection
