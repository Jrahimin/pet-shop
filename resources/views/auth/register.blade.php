@extends('frontend.layouts.master')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8 mt-4">

             <div class="mb-3 product_content_title2">
                @lang("register_page.Register")
             </div>

             <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                 @csrf

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Name*")</label>
                     <div class="col-md-8">
                         <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                         @if ($errors->has('name'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('name') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Last Name*")</label>
                     <div class="col-md-8">
                         <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autofocus>
                         @if ($errors->has('name'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('surname') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Tel*")</label>
                     <div class="col-md-8">
                         <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required autofocus>
                         @if ($errors->has('name'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('phone') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.City*")</label>
                     <div class="col-md-8">
                         <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required autofocus>
                         @if ($errors->has('city'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('city') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Address*")</label>
                     <div class="col-md-8">
                         <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" required autofocus>
                         @if ($errors->has('city'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('address') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Zip Code*")</label>
                     <div class="col-md-8">
                         <input id="zip_code" type="text" class="form-control{{ $errors->has('zip_code') ? ' is-invalid' : '' }}" name="zip_code" value="{{ old('zip_code') }}" required autofocus>
                         @if ($errors->has('city'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('zip_code') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="email" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.E-mail*")</label>
                     <div class="col-md-8">
                         <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                         @if ($errors->has('email'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('email') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="password" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Password*")</label>
                     <div class="col-md-8">
                         <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                         @if ($errors->has('password'))
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('password') }}</strong>
                             </span>
                         @endif
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="password-confirm" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Confirm Password*")</label>
                     <div class="col-md-8">
                         <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-md-4 col-form-label text-md-right checkout_labels">@lang("register_page.Pet")</label>
                     <div class="col-md-8">
                         {{-- <input class="btn btn-secondary" onclick="addElement()" value="Add"> --}}
                         <a href="#" class="btn btn-secondary" onclick="addElement()" value="Add">@lang("register_page.Add Pet")</a>
                     </div>
                 </div>

                 <div class="form-group row">
                     <label for="name" class="col-form-label text-md-right checkout_labels"></label>
                     <div class="col-md-12">
                         <table class="table table-hover table-striped">
                             <thead>
                             <th>@lang("register_page.Pet's name")</th>
                             <th>@lang("register_page.Breed")</th>
                             <th>@lang("register_page.Pet's Birthday")</th>
                             </thead>
                             <tbody id="petdiv">

                             </tbody>
                         </table>
                     </div>
                 </div>

                 <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right checkout_labels"></label>
                    <div class="col-md-8">
                       <button type="submit" class="btn login_btn">@lang("login_page.Register")</button>
                    </div>
                 </div>
             </form>

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

    <script>
        function addElement(){

            // Container <div> where dynamic content will be placed
            var container = document.getElementById("petdiv");

            // Create an <input> element, set its type and name attributes
            var tr = document.createElement("tr");
              var td = document.createElement("td");
                var input = document.createElement("input");
                input.type = "text";
                input.name = "petName[]";
                input.className ="col-md-12";
                td.appendChild(input);
                tr.appendChild(td);

            var td2 = document.createElement("td");
                  var input2 = document.createElement("input");
                  input2.type = "text";
                  input2.name = "breed[]";
                  input2.className ="col-md-12";
                  td2.appendChild(input2)
                  tr.appendChild(td2);

            var td3 = document.createElement("td");
                  var input3 = document.createElement("input");
                  input3.type = "date";
                  input3.name = "birthday[]";
                  input3.className ="col-md-12";
                   td3.appendChild(input3)
                  tr.appendChild(td3);
                   container.appendChild(tr);


        }
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
