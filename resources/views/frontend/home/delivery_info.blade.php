@extends('frontend.layouts.master')
@section('header')
    <style>
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
            margin-top: 20px;
        }
        .activePanel, .accordion:hover {
            background-color: #ccc;
        }
        .activePanel:after {
            content: "\2212";
        }
        .accordion:after {
            content: '\002B';
            color: #777;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }
        .panel {
            padding: 0 18px;
            background-color: white;
            overflow: hidden;
            /*transition: max-height 1s ease-out;*/
            transition: 300ms ease-out;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div id="delivery">
        <br/>
        <div id="scroll_nav_block">
            <div class="container">
            <div class="row py-2">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="d-flex">
                        <div class="input-group input-group-sm my-2">
                            <input class="form-control border-right-0 border" type="search" v-model="keyword" id="example-search-input" @keyup.enter="searchProduct">
                            <span class="input-group-append"><div class="input-group-text search_field_icon"><i @click="searchProduct" style="cursor: pointer;" class="fa fa-search"></i></div></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-5 d-none d-sm-block">
                    <div class="d-flex flex-row justify-content-center">
                        <a class="del_info_link2" href="{{route('delivery_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/delivery_icon.png') }}" alt="yzipet" /> Pristatymas</a>
                        <div class="px-2"><hr class="vertical_line1"/></div>
                        <a class="del_info_link2" href="{{route('buyer_info_front_show')}}" ><img class="img-fluid" src="{{ asset('images/info_icon.png') }}" alt="yzipet" /> Informacija pirkėjui</a>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-3">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td class="top_call_td_frame">
                                <div class="top_call_info_icon"><i class="fas fa-phone-square fa-2x"></i></div>
                            </td>
                            <td class="top_call_td_frame">
                                <div class="top_call_info_txt1">
                                    @lang("global.You have any questions?")
                                </div>
                                <div class="top_call_info_txt2">
                                    +370 656 93284
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <div class="container">
            <accordion v-for="deliveryInfo in deliveryInfoList" :title="deliveryInfo.title" :description="deliveryInfo.description">
            </accordion>
        </div>

        <div id="footer">

            <div class="container">
                <div class="row pb-2">
                    <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/map_spot_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="">
                                        <span class="footer_info_txt1">Yzipet</span> <span class="footer_info_txt2">Vilniuje:</span>
                                    </div>
                                    <div class="footer_info_txt3">@{{ contacts.adresas }}</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/workhours_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contacts.work_hours }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/phone_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contacts.telefonas }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3 pt-5">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td class="footer_table_th_frame">
                                    <a href="index.php"><img class="img-fluid footer_info_icon" src="{{ asset('images/email_icon.png') }}" alt="yzipet" /></a>
                                </td>
                                <td class="footer_table_th_frame">
                                    <div class="footer_info_txt3">
                                        @{{ contacts.email }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row py-3">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="footer_divider_line"><br></div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row pt-1">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="text-center">@lang("global.Socialize")</div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row pt-2">
                    <div class="col-sm-12 col-md-12 col-lg-12 pb-4 text-center">
                        <a href="https://www.instagram.com/yzipet/" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/instagram.png') }}" alt="yzipet" /></a>
                        <a href="https://www.facebook.com/YZIpet" target="_blank"><img class="img-fluid footer_social_icons" src="{{ asset('images/fb.png') }}" alt="yzipet" /></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('additionalJS')

    <script type="text/javascript">
        Vue.component('accordion', {
            props: ['title', 'description'],

            template: `<div>
                <br/>
                <div class="row">
                    <button class="btn btn-outline-dark" style="height: 40px; width: 40px;" @click="toggle"><span style="color: #8a6d3b;"><i :class="toggleClass"></i></span></button>
                    <p style="text-transform: uppercase; font-size: 17px; padding-left: 10px; float: left; line-height: 32px;">@{{ title }}</p>
                </div>
				 <transition name="accordion" v-on:before-enter="beforeEnter" v-on:enter="enter" v-on:before-leave="beforeLeave" v-on:leave="leave">
					<div class="panel" v-show="show">
						<p v-html="description"></p>
					</div>
				</transition>
            </div>`,

            data() {
                return {
                    show: true,
                    toggleClass: 'fa fa-minus'
                };
            },

            methods: {
                toggle: function() {
                    this.show = !this.show;
                    this.show ? this.toggleClass = 'fa fa-minus' : this.toggleClass = 'fa fa-plus';
                },
                beforeEnter: function(el) {
                    el.style.height = '0';
                },
                enter: function(el) {
                    console.log('on enter');
                    el.style.height = el.scrollHeight + 'px';
                },
                beforeLeave: function(el) {
                    el.style.height = el.scrollHeight + 'px';
                },
                leave: function(el) {
                    el.style.height = '0';
                }
            }
        });

        new Vue({
            el: "#delivery",
            data: function(){
                return {
                    deliveryInfoList:[],
                    keyword:'',
                    contacts:[],
                    toggleClass:'fa fa-plus',
                };
            },
            created(){
                this.getDeliveryInfo();
                this.getContact();
            },
            methods:
                {
                    getDeliveryInfo()
                    {
                        axios.get('delivery-info').then(response=>{
                            this.deliveryInfoList=response.data;
                        })
                    },
                    getContact()
                    {
                        axios.get('{{route('get_contacts_home')}}').then(response=>{
                            this.contacts = response.data;
                        })
                    },
                    searchProduct()
                    {
                        if(this.keyword==''){
                            alert("Please provide a keyword");
                            return;
                        }
                        url = {!! json_encode(route('filtered_products_front', ["keyword"=>"@key"])) !!};
                        url = url.replace("@key", this.keyword);
                        location.replace(url);
                    },
                    toggleContent(event){
                        //this.classList.toggle("activePanel");
                        let panel = event.target.parentElement.nextElementSibling;
                        console.log(panel);
                        if (panel.style.maxHeight){
                            panel.style.maxHeight = null;
                            this.toggleClass = 'fa fa-plus';
                        } else {
                            panel.style.maxHeight = panel.scrollHeight + "px";
                            this.toggleClass = 'fa fa-minus';
                        }
                    }
                },
        })
    </script>

@endsection