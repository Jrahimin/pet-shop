@extends('layouts.master')

@section('content')

    <div id="settings">

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{route('product_catalog_index')}}"><span>@lang('Goods')</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('inventory_index')}}"><span>@lang('Inventory')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('categories_info_index')}}"><span>@lang('Categories')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('delivery_info_index')}}"><span>@lang('Delivery')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('customer_info_index')}}"><span>@lang('Information for the buyer')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('attribute_colors')}}"><span>@lang('Package Colors')</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('attribute_sizes')}}"><span>@lang('Package Sizes')</span></a>
            </li>

        </ul>
        <br/>

        <form class="form-horizontal" @submit.prevent="changeSettings">
            {{ csrf_field() }}

            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">Change Settings</div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="control-label col-md-3" for="pickupdiscount">@lang('Discount withdrawal')</label>
                            <div class="col-md-6">
                                <input type="text" v-model="settings.pickupdiscount" class="form-control">
                            </div>
                            <label class="control-label"> % </label>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="deliveryprice">@lang('Delivery price')</label>
                            <div class="col-md-6">
                                <input type="text" v-model="settings.deliveryprice" class="form-control">
                            </div>
                            <label class="control-label"> Eur. </label>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="nodelpricefrom">@lang('Free shipping from')</label>
                            <div class="col-md-6">
                                <input type="text" v-model="settings.nodelpricefrom" class="form-control">
                            </div>
                            <label class="control-label"> Eur. </label>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="payondelprice">@lang('Fee for paying a courier upon pick-up of goods')</label>
                            <div class="col-md-6">
                                <input type="text" v-model="settings.payondelprice" class="form-control">
                            </div>
                            <label class="control-label"> Eur. </label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-9 text-right">
                                <button type="submit" class="btn btn-primary ">@lang('Change')</button>
                            </div>
                        </div>

                        <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>

                    </div></div></div>
        </form>
    </div>
    <div style="clear: both;"></div>

@endsection

@section('additionalJS')
    <script>
        Vue.use(axios);
        Vue.use(Toasted);

        new Vue({
            el: "#settings",
            data:{
                settings:{
                    pickupdiscount:'', deliveryprice:'', nodelpricefrom:'', payondelprice:''
                },
                errors:[],
            },
            created(){
                this.getSettings();
            },
            methods:{
                getSettings(){
                    let that = this;
                    axios.get('get-settings').then(response=>{
                        that.settings=response.data;
                    })
                },
                changeSettings(){
                    let that = this;
                    axios.post('change-settings', that.settings).then(function (response) {
                        that.errors = response.data.message;
                        that.getSettings();
                        if(that.errors == undefined){that.$toasted.success('Successfully Updated',{
                            position: 'top-center',
                            theme: 'bubble',
                            duration: 10000,
                            action : {
                                text : 'Close',
                                onClick : (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });}



                    })
                }
            }
        })
    </script>
@endsection