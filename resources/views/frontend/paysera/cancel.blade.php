@extends('frontend.layouts.master')
@section('content')

   <div class="container">
     <div class="row">
        <div class="col-md-12 my-5">
           <hr class="payment_sep">
        </div>
     </div>
 </div>

 <div class="container">
     <div class="row">
        <div class="col-md-12">
           <div class="alert alert-danger" role="alert">
             <h2 class="text-center text-dark mb-3">@lang("paysera_cancel1.We are sorry, but payment was cancelled")</h2>
             <h2 class="text-center text-dark mt-3">@lang("paysera_cancel2.Probably, there is a mistake in payment, please try again!")</h2>
           </div>

        </div>
     </div>
 </div>

 <div class="container">
     <div class="row">
        <div class="col-md-12 my-5">
           <hr class="payment_sep">
        </div>
     </div>
 </div>

@endsection
