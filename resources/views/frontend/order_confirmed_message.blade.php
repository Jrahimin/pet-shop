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

            <div class="alert alert-success" role="alert">
               <h2 class="text-center text-dark mb-3">@lang("order_confirmed1.Your Order Has Been Confirmed, Thank You!")</h2>
               <h2 class="text-center text-dark mt-3">@lang("order_confirmed2.We will inform about its status soon.")</h2>
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
