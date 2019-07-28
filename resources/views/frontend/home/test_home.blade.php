@extends('frontend.layouts.master')

@section('content')
<div id="app">
    <div class="container">
        <router-view></router-view>
    </div>
</div>
@endsection
@section('coreJs')
    <script src="{{asset('js/home.js')}}"></script>
@endsection

