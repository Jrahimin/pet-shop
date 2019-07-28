@extends('layouts.master')
@section('content')
    <div id="closing" class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">Create Shipment</div>
            <div class="panel-body">

                <form class="form-horizontal" @submit.prevent="createClosingManifest">
                    <div class="form-group">
                        <label class="control-label col-md-2">Date</label>

                        <div class="col-md-8">
                            <vuejs-datepicker  v-model="date"  input-class="form-control" format="yyyy-MM-dd"> </vuejs-datepicker>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-10 text-right">
                            <button class="btn btn-primary">@lang('Create')</button>

                        </div>
                    </div>

            </form>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>
    <script src="{{asset('js/vuejs-datepicker.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script>

        Vue.use(axios);
        new Vue({
            el: "#closing",
            data: {
                date : ''
            },
            components: {
                vuejsDatepicker
            },
            created(){

            },
            methods:{
               createClosingManifest()
               {
                   var date = moment(this.date).format('YYYY-MM-DD')
                   location.replace('close-manifest/'+date);
               }

            }
        });


     </script>
    @endsection