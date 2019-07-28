@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        Log In
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        @if ( session()->has('error'))
            <center><label class="text text-danger">{{  session()->get('error') }}</label></center>
        @endif
        <form action="{{route('admin_login')}}" method="post">
            {{ csrf_field() }}

            <div class="form-group has-feedback{{ session()->has('message') ? ' has-error' : '' }}">
                <input type="text" class="form-control" placeholder="Email or Username" value="{{ old('email') }}" name="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback{{ session()->has('message') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-xs-7 col-xs-offset-1">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>

             <br/>
            <div class="form-group col-6 col-md-offset-3">
                @if (session()->has('message'))
                    <span style="color: red">
                    {{session('message')}}
                </span>
                @endif
            </div>
        </form>

        <a href="{{ route('password.request') }}">I forgot my password</a><br>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!--   Core JS Files   -->
<script src="{{ asset('js/jquery.min.js')  }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src={{ asset('js/bootstrap.min.js')}} type="text/javascript"></script>

<script src="{{ asset('js/adminlte.js') }}"></script>

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

@endsection
