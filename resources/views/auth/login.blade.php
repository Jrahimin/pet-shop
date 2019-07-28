@extends('frontend.layouts.master')

@section('content')

    <div class="container">
      <div class="row">
         <div class="col-sm-12 col-md-8 col-lg-5 mt-4">

              <!-- /.login-logo -->
                  <div class="mb-3 product_content_title2">
                     @lang("login_page.Login in to start!")
                  </div>
                  @if ( session()->has('error'))
                      <center><label class="text text-danger">{{  session()->get('error') }}</label></center>
                  @endif
                  <form action="{{route('login')}}" method="post">
                      {{ csrf_field() }}

                      <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                          <label class="checkout_labels" for="email">@lang("global.Email*")</label>
                          <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                          @if ($errors->has('email'))
                              <span class="help-block">
                                  {{ $errors->first('email') }}
                              </span>
                          @endif
                      </div>

                      <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                          <label class="checkout_labels" for="password">@lang("global.Password*")</label>
                          <input type="password" class="form-control" name="password">
                          <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                          @if ($errors->has('password'))
                              <span class="help-block">
                                  {{ $errors->first('password') }}
                              </span>
                          @endif
                      </div>

                      <div class="row">
                          {{-- <div class="col-xs-7 col-xs-offset-1">
                              <div class="checkbox icheck">
                                  <label>
                                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                                  </label>
                              </div>
                          </div> --}}
                          <!-- /.col -->
                          <div class="col-sm-12 col-md-8 col-lg-5 mb-2">
                              <button type="submit" class="btn btn-primary login_btn">@lang("global.Join")</button>

                          </div>
                          <!-- /.col -->
                      </div>
                  </form>

                  <div class="">
                     <a class="user_text2" href="{{ route('password.request') }}">@lang("login_page.Forgot my Password")</a>
                  </div>

              <!-- /.login-box-body -->
        </div>
      </div>
    </div>
    <!-- /.login-box -->

    <div class="container">
      <div class="row">
         <div class="col-sm-12 col-md-8 col-lg-12 mt-3">
            <div class="mb-3 mt-2 user_divider"></div>
            <div class="mb-2 d-flex">
               <div class="user_text3">
                  @lang("login_page.Haven't registered yet?")
               </div>
            </div>
            <div class="d-flex mb-1">
               <div class="user_title2">
                  @lang("login_page.Get Registered")
               </div>
            </div>
            <div class="d-none d-sm-flex">
               <div class="user_text4">
                  <ul class="ml-3 user_ul1">
                     <li>Matysite užsakymų istoriją</li>
                  </ul>
               </div>
               <div class="user_text4">
                  <ul>
                     <li>Patogiau apsipirksite kitą kartą</li>
                  </ul>
               </div>
               <div class="user_text4">
                  <ul>
                     <li>Gausite nuolaidas</li>
                  </ul>
               </div>
            </div>
            <div class="">
               <a href="{{route('register')}}" role="button" class="btn btn-primary login_btn">@lang("login_page.Register")</a>
            </div>

         </div>
       </div>
     </div>

    <div id="footer">

        <div class="container mt-5">
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
@endsection

    <!--   Core JS Files   -->
    <script src="{{ asset('js/jquery.min.js')  }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src={{ asset('js/bootstrap.min.js')}} type="text/javascript"></script>



    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            var is_error = '{{ ( session()->has('error')) ? session()->get('error') : 0 }}';

            if(is_error!="0"){

                $.notify({
                    icon: 'pe-7s-gift',
                    message: is_error

                },{
                    type: 'danger',
                    timer: 4000
                });
            }

            var is_success = '{{ ( session()->has('success')) ? session()->get('success') : 0 }}';

            if(is_success!="0"){

                $.notify({
                    icon: 'pe-7s-gift',
                    message: is_success

                },{
                    type: 'success',
                    timer: 4000
                });
            }


            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

@section('additionalJS')

    <script>
        var app = new Vue({
            el: '#footer',
            data: {
               contacts:{}
            },
            methods:{
                getContact()
                {
                    axios.get('get-contacts-home').then(response=>{
                        this.contacts = response.data;

                    })
                },
            },
            created(){
                this.getContact();

            }
        })
    </script>
    @endsection
