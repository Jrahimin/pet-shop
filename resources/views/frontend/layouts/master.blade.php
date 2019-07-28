<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Yzipet') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    {{-- <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> --}}

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap4.1/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core.css') }}">
    <link rel="stylesheet" href="{{asset('css/drift-1.2.0/dist/drift-basic.css')}}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/drift-1.2.0/dist/drift-basic.min.css')}}" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ asset('css/fontawesome-free_5.0.13/css/fontawesome-all.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free-5.3.1/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ionicons2.min.css') }}">
    <!-- Jquery Ui -->
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('css/mega-menu/style.css')}}">

    <style>
        .overlay{position: absolute;left: 0; top: 0; right: 0; bottom: 0;z-index: 2;background-color: rgba(255,255,255,0.6);}
        .overlay-content {
            position: absolute;
            transform: translateY(-50%);
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            top: 50%;
            left: 0;
            right: 0;
            text-align: center;
            color: #555;
        }
    </style>

    @yield('header')
</head>
<body>

   <!-- Navigation -->
   <nav class="navbar navbar-expand-md navbar-expand-lg fixed-top main_navigation">
      <div class="container">
         <a class="company_logo" href="{{route('index_new')}}"><img class="img-fluid" src="{{ asset('images/yzipet_logo.png') }}" alt="yzipet" /></a>
         {{-- <a class="navbar-brand " href="index.php"><img class="img-fluid" src="{{ asset('images/yzipet_logo.png') }}" alt="yzipet" /></a> --}}
         <button class="navbar-toggler navbar-dark ml-auto nav_custom_togler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          {{-- <span class="navbar-toggler-icon"></span>  <i class="fas fa-grip-horizontal"></i> <i class="fas fa-ellipsis-h"></i> <i class="fas fa-fingerprint"></i> <i class="fas fa-circle-notch"></i> --}}
          <i class="fas fa-paw fa-lg"></i>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav main_nav_items">
             <li class="nav-item active ml-auto">
               <a class="nav-link main_nav_urls" href="{{route('index_new')}}">@lang("global.HOME") <span class="sr-only">(current)</span></a>
             </li>
             {{-- <li class="nav-item">
               <a class="nav-link main_nav_urls" href="#">Aprašymai</a>
             </li> --}}
             <li class="nav-item ml-auto">
               <a class="nav-link main_nav_urls" href="{{route('interesting_index_front')}}">@lang("global.INTERESTING")</a>
             </li>
             <li class="nav-item ml-auto">
               <a class="nav-link main_nav_urls" href="{{route('contact_index_front')}}">@lang("global.CONTACTS")</a>
             </li>

{{--
             |            <li class="nav-item">
                            <a class="nav-link main_nav_urls" href="{{route('category_index_front')}}">@lang('CATEGORY')</a>
                          </li>
             |            <li class="nav-item">
                            <a class="nav-link main_nav_urls" href="{{route('product_index_front')}}">@lang('PRODUCT')</a>
                          </li>
            |             <li class="nav-item">
                           <a class="nav-link main_nav_urls" href="{{route('checkout_step2')}}">@lang('CHECKOUT2')</a>
                          </li>

            |             <li class="nav-item">
                           <a class="nav-link main_nav_urls" href="{{route('checkout_step3')}}">@lang('CHECKOUT3')</a>
                          </li>
            |             <li class="nav-item">
                           <a class="nav-link main_nav_urls" href="{{route('checkout_step4')}}">@lang('CHECKOUT4')</a>
                          </li>--}}



          </ul>


             @if(\Illuminate\Support\Facades\Auth::check())

                <ul class="navbar-nav ml-auto">
                   <li class="nav-item ml-auto">
                        <a class="nav-link main_nav_urls" href="{{route('user_info_index')}}"><i class="fas fa-portrait"></i> @lang("login_page.User Info")</a>
                   </li>
                   <li class="nav-item ml-auto">
                        <a class="nav-link main_nav_urls" href="#" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();" ><i class="fas fa-sign-out-alt"></i> @lang("login_page.Log Out")</a>
                        <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                   </li>
                </ul>


             @endif
             @if(\Illuminate\Support\Facades\Auth::check()==false)
                <ul class="navbar-nav ml-auto">
                   <li class="nav-item ml-auto">
                        <a class="nav-link main_nav_urls" href="{{route('login')}}"><i class="fas fa-user"></i> @lang("global.Join")</a>
                   </li>
                </ul>
            @endif
         </div>
      </div>
   </nav>
   <br/><br/>
   <div>
       {!! $totalString !!}
   </div>





<div>

        @yield('content')

</div>

@section('coreJs')
<!--   Core JS Files   -->
<script src="{{ asset('js/bootstrap4.1/jquery-3.3.1.slim.min.js')  }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src={{ asset('js/bootstrap4.1/popper.min.js')}} type="text/javascript"></script>
<script src={{ asset('js/bootstrap4.1/bootstrap.min.js')}} type="text/javascript"></script>
   <script src="{{asset('js/vue-2.5.17/dist/vue.min.js')}}"></script>
   <script src="{{asset('js/axios-0.18.0/dist/axios.min.js')}}"></script>
<script src="https://unpkg.com/ionicons@4.4.2/dist/ionicons.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
   <script src="{{asset('js/mega-menu/megamenu.js')}}"></script>
<script src="{{asset('js/vue-spinner/vue-spinner.min.js')}}"></script>
@show

@yield('additionalJS')


</body>
</html>
