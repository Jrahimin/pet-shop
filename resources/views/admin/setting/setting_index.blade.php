@extends('layouts.master')

@section('content')

    <div id="settings">
        <form class="form-horizontal" @submit.prevent="changeSettings" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-9 col-md-offset-1">
                <div class="panel panel-info">
                    <div class="panel-heading">Change Settings</div>
                    <div class="panel-body">
                        <div v-if="isLoading">
                            <div class="overlay"><clip-loader size="100px" class="overlay-content" style="top:20%;"></clip-loader></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="emailas">@lang('El. mail')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.emailas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="uzsakymu_mailas">@lang('El. mail order')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.uzsakymu_mailas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="gimtadieniu_mailas">@lang('El. mail for birthdays')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.gimtadieniu_mailas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="adresas">@lang('Address')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.adresas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="phone_cont">@lang('Tel/fax')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.phone_cont" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="darbo_laikas">@lang('Working hours')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.darbo_laikas" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="dpd_courier_price">@lang('DPD Courier Price')</label>
                            <div class="col-md-8">
                                <input type="text" v-model.number="settings.dpd_courier_price" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="omniva_courier_price">@lang('OMNIVA Courier Price')</label>
                            <div class="col-md-8">
                                <input type="text" v-model.number="settings.omniva_courier_price" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="free_shipping_from">@lang('Free shipping from')</label>
                            <div class="col-md-8">
                                <input type="text" v-model.number="settings.free_shipping_from" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="store_pickup_discount">@lang('Store Pickup discount')</label>
                            <div class="col-md-8">
                                <input type="text" v-model.number="settings.store_pickup_discount" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="dpd_pay_on_delivery">@lang('DPD pay on delivery price')</label>
                            <div class="col-md-8">
                                <input type="text" v-model.number="settings.dpd_pay_on_delivery" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="twitterlink">@lang('Instagram reference')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.twitterlink" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="fblink">@lang('FB link')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.fblink" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="tit_youtube">@lang('Youtube link homepage')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.tit_youtube" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="meta_description_lt">@lang('META description')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.meta_description_lt" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="meta_keywords_lt">@lang('META keywords')</label>
                            <div class="col-md-8">
                                <input type="text" v-model="settings.meta_keywords_lt" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="meta_keywords_lt">@lang('Check Stock Before Adding to Cart?')</label>
                            <div class="col-md-8">
                                <select  v-model="settings.stock_enable" class="form-control">
                                    <option value="1">@lang('Yes')</option>
                                    <option value="0">@lang('No')</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-11 text-right">
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
        var ClipLoader = VueSpinner.ClipLoader;

        new Vue({
            el: "#settings",
            data:{
                settings:{
                    emailas:'', uzsakymu_mailas:'', gimtadieniu_mailas:'',adresas:'', phone_cont:'', darbo_laikas:'',
                    twitterlink:'', fblink:'', tit_youtube:'', meta_description_lt:'', meta_keywords_lt:'',
                    dpd_courier_price:'', omniva_courier_price:'', free_shipping_from:'', store_pickup_discount:'', dpd_pay_on_delivery:'',stock_enable:''
                },
                errors:[], isLoading:true,
            },
            created(){
                this.getSettings();
            },
            components: {
                ClipLoader
            },
            methods:{
                getSettings(){
                    let that = this;
                    axios.get('get-settings').then(response=>{
                        that.settings=response.data;
                        that.isLoading = false;
                    })
                },
                changeSettings(){
                    this.isLoading = true;
                    let that = this;
                    axios.post('change-settings', that.settings).then(function (response) {
                        that.isLoading = false;
                        that.errors = response.data.message;
                        if(that.errors == undefined){
                            that.getSettings();
                            that.$toasted.success('Successfully Updated',{
                                position: 'top-center',
                                theme: 'bubble',
                                duration: 10000,
                                action : {
                                    text : 'Close',
                                    onClick : (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            });
                        }
                    }).catch(error=>{
                        that.isLoading = false;
                        console.log(error);
                    })
                }
            }
        })
    </script>
@endsection