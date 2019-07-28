<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Yzipet Admin</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/skins/_all-skins.min.css')}}">

    <!-- Jquery Ui -->
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" />

    <style>
        .mn-hidden{
            color:grey;
        }
        .mn-selected{
            color: #6b9dbb;
        }
        .filter-box {
            background-color: #F0F8FF;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
        .wrapper {
            height:100%;position:relative;overflow-x:hidden;overflow-y:hidden
        }

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
    @yield('additionalCSS')
</head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a class="logo" href="#">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img style="padding:5px;" src="{{ asset('storage/images/yzipet_logo.png') }}" height="50px" width="50px"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img style="border-radius: 50px;padding:5px;" src="{{ asset('storage/images/yzipet_logo.png') }}" height="50px" width="50px"> Yzipet @lang('Admin')</span>
        </a>

        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" onclick="sidebarchange()">
                <span class="sr-only">Toggle navigation</span>
            </a>


            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">

                        <a href="{{route('admin_logout')}}" class="text-right">
                            <span >Log Out</span>
                        </a>


                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
                                    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <div id="collapse" style="display: block">
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                    </div>
                    <div class="pull-left info">

                    </div>
                </div>

                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="active"><a href="#">
                            <i class="fa fa-home"></i>
                            <span>Home</span>
                        </a></li>
                    @if (Auth::guard('admin')->user()->can('baneriai'))

                    <li><a href="{{route('banner_index')}}"><i class="fa fa-clipboard" aria-hidden="true"></i><span>@lang('Banners')</span></a></li>
                    @endif

                    @if (Auth::guard('admin')->user()->can('gamintojai'))

                    <li><a href="{{route('manufacturer_index')}}"><i class="fa fa-industry" aria-hidden="true"></i><span>@lang('Manufacturer')</span></a></li>

                    @endif

                      @if (Auth::guard('admin')->user()->can('idomu'))

                    <li><a href="{{route('interesting_index')}}"><i class="fa fa-edit" aria-hidden="true"></i><span>@lang('Interestings')</span></a></li>

                        @endif

                     @if (Auth::guard('admin')->user()->can('katalogas'))

                    <li><a href="{{route('product_catalog_index')}}"><i class="fa fa-file" aria-hidden="true"></i><span>@lang('Product Catalog')</span></a></li>

                    @endif

                  {{--  <li class="treeview">
                        <a  href="#">
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('Package Attribute')</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>

                        <ul class="treeview-menu">
                            <li><a href="{{route('attribute_colors')}}"><i class="fa fa-list" ></i><span>@lang('Colors')</span></a></li>
                            --}}{{--<li><a href="{{route('loyality_info_index')}}"><i class="fa fa-list" ></i><span>@lang('Loyalty Program')</span></a></li>--}}{{--
                            <li><a href="{{route('attribute_sizes')}}"><i class="fa fa-list" ></i><span>@lang('Sizes')</span></a></li>
                        </ul>
                    </li>--}}

                      @if (Auth::guard('admin')->user()->can('kontaktai'))
                    <li><a href="{{route('contact_index')}}"><i class="fa fa-comment" aria-hidden="true"></i><span>@lang('Contact')</span></a></li>

                    @endif

                     @if (Auth::guard('admin')->user()->can('pirkimai'))

                        <li><a href="{{route('purchase_index')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>@lang('Purchases')</span></a></li>

                         <li><a href="{{route('closing_manifest_index')}}"><i class="fa fa-file" aria-hidden="true"></i><span>@lang('Create Closing Manifest')</span></a></li>


                    @endif


                      @if (Auth::guard('admin')->user()->can('prenumeratoriai'))

                    <li><a href="{{route('subscriber_index')}}"><i class="fa fa-user" aria-hidden="true"></i><span>@lang('Subscribers')</span></a></li>

                     @endif

                     @if (Auth::guard('admin')->user()->can('privalumai'))

                    <li><a href="{{route('advantages_index')}}"><i class="fa fa-question" aria-hidden="true"></i><span>@lang('Why Yzipet')</span></a></li>

                     @endif

                    @if (Auth::guard('admin')->user()->can('registruoti_vartotojai'))

                    <li><a href="{{route('users_index')}}"><i class="fa fa-users" aria-hidden="true"></i><span>@lang('Registered Users')</span></a></li>

                    @endif

                   {{--@if (Auth::guard('admin')->user()->can('titulinio_info'))

                    <li><a href="{{route('home_info_index')}}"><i class="fa fa-houzz" aria-hidden="true"></i><span>@lang('Home Block')</span></a></li>

                     @endif--}}
                    <li><a href="{{route('slider_image_index')}}"><i class="fa fa-houzz" aria-hidden="true"></i><span>@lang('Homepage Slider')</span></a></li>


                    @if (Auth::guard('admin')->user()->can('administratoriai'))

                    <li><a href="{{route('administrators_index')}}"><i class="fa fa-lock" aria-hidden="true"></i><span>@lang('Administrators')</span></a></li>

                    @endif

                    <li class="treeview">
                        <a  href="#">
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('Promotion')</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>

                        <ul class="treeview-menu">
                            <li><a href="{{route('promotion_info_index')}}"><i class="fa fa-list" ></i><span>@lang('coupon')</span></a></li>
                            {{--<li><a href="{{route('loyality_info_index')}}"><i class="fa fa-list" ></i><span>@lang('Loyalty Program')</span></a></li>--}}
                            <li><a href="{{route('discount_info_index')}}"><i class="fa fa-list" ></i><span>@lang('Discounts')</span></a></li>
                        </ul>
                    </li>

                    @if (Auth::guard('admin')->user()->can('nustatymai'))

                    <li><a href="{{route('settings_index')}}"><i class="fa fa-cog" aria-hidden="true"></i><span>@lang('Settings')</span></a></li>

                    @endif

                </ul>
            </section>
        </aside>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('pageTitle')
            </h1>

            @yield('breadcrumbs')
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div>

    {{--<footer class="main-footer">

    </footer>--}}
</div>

<!--   Core JS Files   -->
<script src="{{ asset('js/jquery.min.js')  }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src={{ asset('js/bootstrap.min.js')}} type="text/javascript"></script>
<script src="{{ asset('js/adminlte.js') }}"></script>
<script src="{{asset('js/vue-2.5.17/dist/vue.min.js')}}"></script>
<script src="{{asset('js/axios-0.18.0/dist/axios.min.js')}}"></script>
<script src="{{asset('js/vue-toasted.min.js')}}"></script>
<script src="{{asset('js/vue-spinner/vue-spinner.min.js')}}"></script>


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
</script>

@yield('additionalJS')
</body>
