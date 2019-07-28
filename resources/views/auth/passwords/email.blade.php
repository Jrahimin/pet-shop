@extends('frontend.layouts.master')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-8 col-lg-6 mt-4">
               <div class="card border-dark mb-3">
                 <div class="card-header product_content_title2">@lang("login_page.Password Reset")</div>
                 <div class="card-body text-dark">

                    @if (session('status'))
                        <div class="alert alert-success">
                           {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                           <label class="checkout_labels" for="email">@lang("global.Email*")</label>
                           <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                           <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                           @if ($errors->has('email'))
                               <span class="help-block">
                               <strong>{{ $errors->first('email') }}</strong>
                           </span>
                           @endif
                        </div>
                        <div class="">
                           <button type="submit" class="btn login_btn">@lang("login_page.Send Password Reset Link")</button>
                        </div>
                    </form>
                 </div>
               </div>
            </div>
        </div>
    </div>

@endsection
