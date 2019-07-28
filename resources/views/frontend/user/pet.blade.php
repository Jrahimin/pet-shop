@extends('frontend.layouts.master')

@section('content')
    <div id="petPage">

         <div class="container my-4">
            <ul class="nav nav-tabs user_tabs">
              <li class="nav-item">
                <a class="nav-link user_single_tab" href="{{route('user_info_index')}}">@lang('user_pet.MY INFORMATION')</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active user_single_tab" href="{{route('user_pet_info_index')}}">@lang('user_pet.MY PETS')</a>
              </li>
              <li class="nav-item">
                <a class="nav-link user_single_tab" href="{{route('order_history_index')}}">@lang('user_pet.MY ORDER HISTORY')</a>
              </li>
            </ul>
         </div>

         <div class="container">
            <div class="row">
               <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <h3 class="user_title1">@lang('user_pet.PET INFORMATION')</h3>
                  <br/>
               </div>
            </div>
         </div>

         <div class="container">
            <div class="row">
               <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="table-responsive">
                      <table class="table table-hover table-borderless">
                          <thead>
                          <tr>
                              <th>@lang("user_pet.Pet's name")</th>
                              <th>@lang('user_pet.Breed')</th>
                              <th>@lang("user_pet.Pet's Birthday")</th>
                              <th></th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr v-for="(pet,index) in pets">
                              <td><input class="form-control col" type="text" v-model="pet.title"></td>
                              <td><input class="form-control col" type="text" v-model="pet.species"> </td>
                              <td><vuejs-datepicker v-model="pet.birthday" input-class="form-control col" format="yyyy-MM-dd"></vuejs-datepicker></td>
                              <input type="hidden" v-model="pet.id">
                              <td><button class="btn btn-danger" @click.prevent="removePackages(index)">@lang('user_pet.Remove')</button></td>
                          </tr>
                          <tbody>
                      </table>
                      <div class="d-flex mb-3">
                        <div class="mr-2">
                           <button class=" btn btn-secondary" @click.prevent="morePackages()">@lang('user_pet.Add Pet')</button>
                        </div>
                        <div class="ml-2">
                           <button class=" btn save_btn" @click.prevent="savePets">@lang('user_pet.Save')</button>
                        </div>
                     </div>
                      <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                          <ul>
                              <li v-for="error in errors">@{{ error }}</li>
                          </ul>
                      </div>
                  </div>
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

@endsection

@section('additionalJS')
    <script src="{{asset('js/vuejs-datepicker.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script>
        Vue.use(axios);

        new Vue({
            el: '#petPage',

            data: function(){
                return {
                    pets:[], errors: [], id: {{ json_encode(Auth::user()->id) }},contacts:{}
                };
            },
            created(){
                this.getPetInfo();
                this.getContact();
            },
            methods:{
                getPetInfo()
                {
                    axios.get('../pet-info/'+this.id).then(response=>{
                        this.pets = response.data;
                    })
                },
                getContact()
                {
                    axios.get('../get-contacts-home').then(response=>{
                        this.contacts = response.data;
                        console.log(this.contacts);
                    })
                },


                removePackages(index)
                {
                  this.pets.splice(index,1);
                },
                morePackages()
                {
                    this.pets[this.pets.length]
                    console.log(this.pets);
                    this.pets.push({
                        title:'',
                        birthday:'',
                        species:'',
                        id:'',
                    })
                },
                savePets()
                {
                    let that = this;
                    this.pets.forEach(function (pet) {
                        if(pet.birthday != "")
                            pet.birthday = moment(pet.birthday).format('YYYY-MM-DD');
                    })
                    axios.post('../save-pets',{pets:this.pets,userId:this.id}).then(response=>{
                        that.errors = response.data.message;
                        if(that.errors == undefined)
                            window.location.reload();
                    })
                }
            },
            components:{
                vuejsDatepicker
            }
        })
    </script>
@endsection
