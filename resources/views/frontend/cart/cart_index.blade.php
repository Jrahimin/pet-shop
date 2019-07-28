@extends('frontend.layouts.master')

@section('content')

    <div id="eshop-page">

      <div class="container">
         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12">

            </div>
         </div>
      </div>

        <router-view></router-view>
    </div>

@endsection

@section('additionalJS')
    <script src="{{asset('js/vue-router-3.0.1/dist/vue-router.min.js')}}"></script>

    <script>
        Vue.use(axios);


        var Cart = {
            template:` <div>

<div class="container"   v-if="loggedIn==false">
     <div class="row pb-5">
         <div class="col-sm-12 col-md-12 col-lg-12" >

             <div class="d-flex mb-3">
                       <div class="product_desc_text1">
                           1. @lang("cart_details.User Information")
                       </div>
              </div>

              <div class="card mb-2 delivery_card_block_border">
                   <div class="card-body courrier_extra_small">
                       <p class="checkout_title_txt2">
                               <button class="btn checkout_collapse_btn" @click="logindivToggle" ><ion-icon :name="loginIcon"></button>
                               @lang("cart_details.Already bought?")
                       </p>

                       <p class="checkout_title_txt2">
                               <button @click="registerDivToggle" class="btn checkout_collapse_btn" type="button"><ion-icon :name="registerIcon" ></button>
                               @lang("cart_details.First time? Register?")
                       </p>

                       <p class="checkout_title_txt2">
                               <button class="btn checkout_collapse_btn"  @click="buyerInfoDivToggle"><ion-icon :name="nonRegisterIcon" ></button>

                               @lang("cart_details.Dont want to register? Buy without registration")
                       </p>

                    </div>

               </div>

               <div   class="card mb-2 delivery_card_block_border" v-if="logindiv">
                       <div class=" card-body courrier_extra_small">
                           <form @submit.prevent="login">
                               <input type="hidden" name="_token" value="{{csrf_token()}}">
                               <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputEmail">@lang("cart_details.Email*")</label>
                                       <input type="text" class="form-control" id="emailInputField"  v-model="loginInfo.email">
                                   </div>
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels"for="inputPassword">@lang("cart_details.Password*")</label>
                                       <input type="password" class="form-control" id="passwordInputField" v-model="loginInfo.password">
                                       <small id="passwordHelpBlock" class="form-text text-muted">
                                           <a class="password_help_block" href="#">@lang("cart_details.Forgot Password")</a>
                                       </small>
                                   </div>

                                   <div class="container" v-if="loginErrors !=''">
                                   <div class="row pb-3">
                                       <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="d-flex">
                                               <div class="ml-auto">
                                                   <div class="alert-danger">
                                                      <ul class="list-unstyled">
                                                         <li v-for="loginError in loginErrors">@{{ loginError }}</li>

                                                      </ul>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                 </div>

                                   <div class="my-2">
                                       <button type="submit" class="btn login_btn">@lang("cart_details.Login")</button>
                                   </div>
                               </div>
                           </form>
                       </div>
               </div>




                <div  class="card mb-2 delivery_card_block_border "  v-if="registerDiv">
                       <div class=" card-body  courrier_extra_small">

                           <div class="row">
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputName">@lang("cart_details.Name*")</label>
                                       <input type="text" class="form-control" id="nameInputField"   v-model="registerInfo.name">
                                   </div>
                               </div>
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputLastName">@lang("cart_details.Last Name*")</label>
                                       <input type="text" class="form-control" id="lastNameInputField"  v-model="registerInfo.surname">
                                   </div>
                               </div>
                           </div>

                           <div class="row">
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputEmail">@lang("cart_details.Email*")</label  >
                                       <input type="text" class="form-control" id="emailInputField" v-model="registerInfo.email">
                                   </div>
                               </div>
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels"for="inputTelNumber">@lang("cart_details.Tel*")</label>
                                       <input type="text" class="form-control" id="telNumberInputField"  v-model="registerInfo.phone">
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           +370
                                       </small>
                                   </div>
                               </div>
                           </div>

                           <div class="custom-control custom-checkbox mb-3">
                               <input type="checkbox" class="custom-control-input" id="repCompanyCheck"   v-model="registerInfo.iscompany">
                               <label class="custom-control-label checkout_labels" for="repCompanyCheck">@lang("cart_details.Company representative (Needs VAT on Company Name)")</label>
                           </div>

                           <div class="row" v-if="registerInfo.iscompany">
                               <div class="col-sm-10 col-md-8 col-lg-6 col-xl-6 pb-1" >
                                   <div class="form-group align-items-center" >
                                       <label class="checkout_labels" for="inputCompanyName">@lang("cart_details.Company Name*")</label>
                                       <input type="text" class="form-control" id="companyNameInputField" v-model="registerInfo.company_title"  >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputCompanyCode">@lang("cart_details.Company Code*")</label>
                                       <input type="text" class="form-control" id="companyCodeInputField"  v-model="registerInfo.company_code"  >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputVAT">@lang("cart_details.Company VAT Code*")</label>
                                       <input type="text" class="form-control" id="VATInputField" v-model="registerInfo.company_vatcode">
                                   </div>
                               </div>
                           </div>

                           <div class="row">
                               <div class="col-sm-10 col-md-8 col-lg-6 col-xl-6 pb-1">

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputCity">@lang("cart_details.City")</label>
                                       <input type="text" class="form-control" id="cityInputField" v-model="registerInfo.city" >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputAddress">@lang("cart_details.Address")</label>
                                       <input type="text" class="form-control" id="addressInputField" v-model="registerInfo.address" >
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           gatvė, namo nr.- buto nr.
                                       </small>
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputPostCode">@lang("cart_details.Zip Code*")</label>
                                       <input type="text" class="form-control" id="postCodeInputField" v-model="registerInfo.zip_code" >
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           Pvz: 12345
                                       </small>
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputCreatePassword">@lang("cart_details.Create Password*")</label>
                                       <input type="password" class="form-control" id="createPasswordInputField"  v-model="registerInfo.password" >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputRepeatPassword">@lang("cart_details.Repeat Password*")</label>
                                       <input type="password" class="form-control" id="repeatPasswordInputField" v-model="registerInfo.confirmPassword"  >
                                   </div>


                                   <div class="container" v-if="registerErrors !=''">
                                   <div class="row pb-3">
                                       <div class="col-sm-12 col-md-12 col-lg-12">
                                           <div class="d-flex">
                                               <div class="ml-auto">
                                                   <div class="alert-danger">
                                                      <ul class="list-unstyled">
                                                         <li v-for="registerError in registerErrors">@{{ registerError }}</li>

                                                      </ul>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                 </div>

                                   <div class="my-1">
                                       <button @click="register" class="btn login_btn">@lang("cart_details.Continue")</button>
                                   </div>
                               </div>
                           </div>

                       </div>
                </div>

                 <div class="card mb-2 delivery_card_block_border" id="collapseWithoutRegisteredUser"   v-if="buyerInfodiv">
                       <div class="card-body courrier_extra_small" >

                           <div class="row">
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputName">@lang("cart_details.Name*")</label>
                                       <input type="text" class="form-control" id="nameInputField"  v-model="buyerInfo.name">
                                   </div>
                               </div>
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputLastName">@lang("cart_details.Last Name*")</label>
                                       <input type="text" class="form-control" id="lastNameInputField" v-model="buyerInfo.surname" >
                                   </div>
                               </div>
                           </div>

                           <div class="row">
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputEmail">@lang("cart_details.Email*")</label  >
                                       <input type="text" class="form-control" id="emailInputField" v-model="buyerInfo.email">
                                   </div>
                               </div>
                               <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels"for="inputTelNumber">@lang("cart_details.Tel*")</label>
                                       <input type="text" class="form-control" id="telNumberInputField" v-model="buyerInfo.phone">
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           +370
                                       </small>
                                   </div>
                               </div>
                           </div>


                           <div class="custom-control custom-checkbox mb-3">
                                 <input  class="custom-control-input" id="needVat"  type="checkbox"  v-model="buyerInfo.needvat">
                               <label class="custom-control-label checkout_labels" for="needVat" >@lang("cart_details.Company representative (Needs VAT on Company Name)")</label>

                           </div>



                           <div class="row" v-if="buyerInfo.needvat">
                               <div class="col-sm-10 col-md-8 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center" >
                                       <label class="checkout_labels" for="inputCompanyName">@lang("cart_details.Company Name*")</label>
                                       <input type="text" class="form-control" id="companyNameInputField" v-model="buyerInfo.company_title" >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputCompanyCode">@lang("cart_details.Company Code*")</label>
                                       <input type="text" class="form-control" id="companyCodeInputField" v-model="buyerInfo.company_code" >
                                   </div>

                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputVAT">@lang("cart_details.Company VAT Code*")</label>
                                       <input type="text" class="form-control" id="VATInputField"  v-model="buyerInfo.company_vatcode">
                                   </div>
                               </div>
                           </div>


                           <div class="row">
                               <div class="col-sm-10 col-md-8 col-lg-6 col-xl-6 pb-1">
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputCity">@lang("cart_details.City")</label>
                                       <input type="text" class="form-control" id="cityInputField" v-model="buyerInfo.city" >
                                   </div>
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputAddress">@lang("cart_details.Address")</label>
                                       <input type="text" class="form-control" id="addressInputField"  v-model="buyerInfo.address">
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           gatvė, namo nr.- buto nr.
                                       </small>
                                   </div>
                                   <div class="form-group align-items-center">
                                       <label class="checkout_labels" for="inputPostCode">@lang("cart_details.Zip Code*")</label>
                                       <input type="text" class="form-control" id="postCodeInputField" v-model="buyerInfo.zip_code" >
                                       <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                           Pvz: 12345
                                       </small>
                                   </div>

                               </div>
                           </div>

                       </div>
                   </div>

         </div>
     </div>
</div>


<div class="container" v-if="loggedIn==true" id="loggedInBuyer">

         <div class="row pb-5">
            <div class="col-sm-12 col-md-12 col-lg-12" >
                <div class="d-flex mb-3">
                       <div class="product_desc_text1">
                           1. User Information
                       </div>
                </div>

                 <div class="col-sm-12 col-md-12 col-lg-12">
                   <div class="card card-body">

                       <div class="form-row align-items-center mb-3">
                           <div class="col-sm-3 pb-4">
                               <label class="checkout_labels" for="inputName">Vardas*</label>
                               <input type="text" class="form-control" id="nameInputField"  v-model="buyerInfo.name">
                           </div>
                           <div class="col-sm-3 pb-4">
                               <label class="checkout_labels" for="inputLastName">Pavardė*</label>
                               <input type="text" class="form-control" id="lastNameInputField" v-model="buyerInfo.surname" >
                           </div>
                           <div class="col-sm-3 pb-4">
                               <label class="checkout_labels" for="inputEmail">El. paštas*</label>
                               <input type="text" class="form-control" id="emailInputField" v-model="buyerInfo.email">
                           </div>
                           <div class="col-sm-3 my-1">
                               <label class="checkout_labels"for="inputTelNumber">Telefono nr.*</label>
                               <input type="text" class="form-control" id="telNumberInputField" v-model="buyerInfo.phone">
                               <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                   +370
                               </small>
                           </div>
                       </div>


                       <div class="custom-control custom-checkbox mb-3">
                           <input type="checkbox" class="custom-control-input" id="repCompanyCheck3"  v-model="buyerInfo.needvat">
                           <label class="custom-control-label checkout_labels" for="repCompanyCheck3">Atstovauju įmonę (reikalinga PVM sąskaita faktūra įmonės vardu)</label>
                       </div>


                       <div class="form-row align-items-center mt-2" v-if="buyerInfo.needvat">
                           <div class="col-sm-4 py-1">
                               <label class="checkout_labels" for="inputCompanyName">Įmonės pavadinimas*</label>
                               <input type="text" class="form-control" id="companyNameInputField" v-model="buyerInfo.company_title" >
                           </div>
                           <div class="col-sm-4 py-1">
                               <label class="checkout_labels" for="inputCompanyCode">Įmonės kodas*</label>
                               <input type="text" class="form-control" id="companyCodeInputField" v-model="buyerInfo.company_code" >
                           </div>
                           <div class="col-sm-4 py-1">
                               <label class="checkout_labels" for="inputVAT">PVM mokėtojo kodas*</label>
                               <input type="text" class="form-control" id="VATInputField"  v-model="buyerInfo.company_vatcode">
                           </div>
                       </div>
                       <div class="form-row align-items-center mt-2">
                           <div class="col-sm-4 pb-4">
                               <label class="checkout_labels" for="inputCity">Miestas</label>
                               <input type="text" class="form-control" id="cityInputField" v-model="buyerInfo.city" >
                           </div>
                           <div class="col-sm-4 py-1">
                               <label class="checkout_labels" for="inputAddress">Adresas</label>
                               <input type="text" class="form-control" id="addressInputField"  v-model="buyerInfo.address">
                               <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                   gatvė, namo nr.- buto nr.
                               </small>
                           </div>
                           <div class="col-sm-4 py-1">
                               <label class="checkout_labels" for="inputPostCode">Pašto kodas*</label>
                               <input type="text" class="form-control" id="postCodeInputField" v-model="buyerInfo.zip_code" >
                               <small id="telHelpBlock" class="form-text text-muted label_help_block">
                                   Pvz: 12345
                               </small>
                           </div>
                       </div>


                   </div>
               </div>
            </div>
         </div>

</div>


<div class="container">
     <div class="row pb-5">

          <div class="col-sm-12 col-md-12 col-lg-6 order-2 order-sm-2 order-md-2 order-lg-1" >
                   <div class="d-flex mb-3">
                       <div class="product_desc_text1">
                           2. @lang("cart_details.Choose your shipping method")
                       </div>
                   </div>
                   <div class="card mb-2 delivery_card_block_border">
                       <div class="card-header">
                           <div class="custom-control custom-radio">
                               <input type="radio" id="courierOneRadio" name="customCourierOneRadio" class="custom-control-input" value="venipak"  v-model="deliveryInfo.delivery_type">
                               <label class="custom-control-label checkout_txt3" for="courierOneRadio">PREKIŲ PRISTATYMAS Į JŪSŲ NAMUS AR DARBOVIETĘ *<span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                       <div class="card-body courrier_extra_small">
                           Pristatymas per 1-2 darbo dienas.<br>
                           DPD kurjeris paskambins Jums nurodytu kontaktiniu telefonu ir sutarsite tikslų pristatymo laiką.<br>
                           Paslaugos kaina @{{ dpd_courier_price }}€, jei krepšelio suma mažesnė nei @{{  free_shipping_from}}€.
                       </div>
                   </div>

                   <div class="card delivery_card_block_border mb-2" v-if="omniva">
                       <div class="card-header">
                           <div class="custom-control custom-radio mt-3">
                               <input type="radio" id="courierTwoRadio" name="customCourierTwoRadio" class="custom-control-input" value="omniva" v-model="deliveryInfo.delivery_type">
                               <label class="custom-control-label checkout_txt3" for="courierTwoRadio">PREKIŲ ATSIĖMIMAS OMNIVA PAŠTOMATUOSE</label><span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                       <div class="card-body courrier_extra_small">
                           Pristatymas į Jūsų pasirinktą OMNIVA paštomatą per 1-2 darbo dienas.<br>
                           Paslaugos kaina @{{ omniva_courier_price }}€.

                           <div v-if="deliveryInfo.delivery_type=='omniva'">
                                 <label>@lang("cart_details.Omniva Terminal")</label>

                               <select v-model="deliveryInfo.terminal" class="form-control">
                                   <option  v-for="terminal in terminals" :value="terminal.ZIP">@{{ terminal.NAME }}l</option>
                               </select>
                           </div>

                       </div>
                   </div>

                   <div class="card delivery_card_block_border">
                       <div class="card-header">
                           <div class="custom-control custom-radio">
                               <input type="radio" id="courierThreeRadio" name="customCourierThreeRadio" class="custom-control-input" value="shop" v-model="deliveryInfo.delivery_type">
                               <label class="custom-control-label checkout_txt3" for="courierThreeRadio">NEMOKAMAS ATSIĖMIMAS YZIPET PARDUOTUVĖJE, ANTAKALNIO G. 38, VILNIUJE<span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                       <div class="card-body courrier_extra_small">
                           Pasirinkę atsiėmimo parduotuvėje būdą, gausite papildomą @{{ store_picup_discount }}% nuolaidą visam prekių krepšeliui.
                       </div>
                   </div>
               </div>

       <div class="col-sm-12 col-md-12 col-lg-6 order-1 order-sm-1 order-sm-1 order-lg-2">

                <div class="d-flex mb-3">
                       <div class="product_desc_text1">
                           @lang("cart_details.Your Cart")
                       </div>
                </div>


                      <div class="card cart_card_block_border">
                         <div class="card-body">
                           <div v-for="item in cartItems">
                                 <div class="d-flex">
                                     <div class="ml-auto checkout_remove_btn1">
                                         <a type="" href="#"><img class="img-fluid checkout_img"  @click.prevent="removeFromCart(item.id)"   src="{{ asset('images/remove_icon.png') }}" alt="yzipet" /></a>
                                     </div>
                                 </div>
                                 <div class="d-flex">
                                    <div class="mr-2 checkout_img_container">
                                         <a href="#"><img class="img-fluid checkout_img" :src="item.image" alt="yzipet" /></a>
                                    </div>
                                    <div class="checkout_title_txt11">
                                        <table class="table table-borderless">
                                             <thead>
                                                   <tr>
                                                       <th class="checkout_title_txt11" scope="col">@{{ item.manufacturer }}</th>
                                                       <th class="checkout_title_txt11" scope="col"></th>
                                                       <th class="checkout_title_txt11" scope="col"></th>
                                                   </tr>
                                             </thead>
                                             <tbody>
                                                   <tr>
                                                       <td class="checkout_txt22">@{{ item.title }}</td>
                                                       <td class="checkout_txt22">
                                                           <div class="d-flex">
                                                               <div class="mx-auto">
                                                                   <input type="text" class="form-control prod_qty_field_inner" :id="'quantity'+item.id" :value="item.quantity" @input="changeQuantity(item.id)" maxlength="3">
                                                               </div>
                                                           </div>
                                                       </td>
                                                       <td class="checkout_txt22">
                                                           <span class="home_product_price" v-if="item.dicountedPrice==0">@{{ item.itemSum }}</span>
                                                           <span class="home_product_price" v-else>@{{ item.dicountedPrice }}</span>
                                                           <span class="home_product_currency">EUR</span>
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                       <td class="checkout_atr_txt33" v-if="item.packageTitle != 'default'">@{{ item.packageTitle }}</td>
                                                       <td class="checkout_atr_txt33"></td>
                                                       <td class="checkout_atr_txt33"></td>
                                                   </tr>
                                             </tbody>
                                         </table>
                                           <div class="my-2 checkout_prod_divider"></div>
                                    </div>

                                 </div>
                           </div>
                         </div>
                      </div>

                     <div class="card-header delivery_card_block_border">
                            <table class="table table-borderless">
                                  <thead>
                                  <tr>
                                      <th class="checkout_title_txt11" scope="col">@lang('cart_details.Sum with VAT')</th>
                                      <th class="checkout_title_txt11" scope="col">@{{ sum }}</th>
                                      <th class="checkout_title_txt11" scope="col">EUR</th>
                                  </tr>
                                  </thead>
                                  <tbody>


                                   <tr>
                                      <td class="checkout_txt22">@lang("cart_details.Discount")</td>
                                      <td class="checkout_txt22">@{{ totalDiscount }}</td>
                                      <td class="checkout_txt22">
                                          <span class="home_product_price"></span>
                                          <span class="checkout_atr_txt33">EUR</span>
                                      </td>
                                  </tr>

                                    <tr v-if="deliveryInfo.delivery_type=='shop'">
                                      <td class="checkout_txt22">@lang("cart_details.Store Pickup Discount Applied")</td>
                                      <td class="checkout_txt22"></td>
                                      <td class="checkout_txt22">
                                          <span class="home_product_price"></span>
                                          <span class="checkout_atr_txt33">EUR</span>
                                      </td>
                                  </tr>

                                  <tr>
                                      <td class="checkout_txt22">@lang("cart_details.Delivery")</td>
                                      <td class="checkout_txt22">@{{ deliveryprice }}</td>
                                      <td class="checkout_txt22">
                                          <span class="home_product_price"></span>
                                          <span class="checkout_atr_txt33">EUR</span>
                                      </td>
                                  </tr>

                                  <tr>
                                      <td class="checkout_atr_txt33">@lang("cart_details.Total Price")</td>
                                      <td class="checkout_atr_txt33">@{{ totalSum }}</td>

                                      <td class="checkout_atr_txt33">EUR</td>
                                  </tr>
                                  </tbody>
                            </table>
                     </div>


                    <div class="d-flex mt-2">
                       <div class="checkout_title_txt2">
                           @lang("cart_details.Have a Discount?")
                       </div>
                    </div>

                   <div class="form-row ">
                       <div class="col-sm-10 col-md-10 col-lg-10 my-1">
                           <input type="text" class="form-control discount_code_field" id="discountInputCode" maxlength="50" placeholder="Įveskite nuolaidos kodą" v-model="discountCode">
                       </div>
                       <div class="col-sm-2 col-md-2 col-lg-2 my-1">
                           <button  @click.prevent="applyDiscountCode"  class="btn discount_btn" :disabled="discount">@lang("cart_details.Use Discount")</button>
                       </div>
                   </div>
       </div>
    </div>
  </div>

    <div class="container" v-if="deliveryInfo.delivery_type!=''">
           <div class="row pb-5">
               <div class="col-sm-12 col-md-12 col-lg-12">
                   <div class="d-flex mb-3">
                       <div class="product_desc_text1">
                           3. Pasirinkite prekių apmokėjimo būdą.
                       </div>
                   </div>
                   <div class="card mb-2 checkout_payment_car_block" v-if="deliveryInfo.delivery_type=='venipak' || deliveryInfo.delivery_type=='omniva' || deliveryInfo.delivery_type=='shop'">
                       <div class="card-header">
                           <div class="custom-control custom-radio">
                               <input type="radio" id="paymentOneRadio" name="paymentOneRadio" class="custom-control-input" value="paysera" v-model="order.paymentMethod">
                               <label class="custom-control-label checkout_txt3" for="paymentOneRadio">ATSISKAITYMAS PER PAYSERA SISTEMĄ <span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                   </div>

                   <div class="card mb-2 checkout_payment_car_block" v-if="deliveryInfo.delivery_type=='venipak'">
                       <div class="card-header">
                           <div class="custom-control custom-radio">
                               <input type="radio" id="paymentTwoRadio" name="paymentTwoRadio" class="custom-control-input" value="payondel" v-model="order.paymentMethod">
                               <label class="custom-control-label checkout_txt3" for="paymentTwoRadio">ATSISKAITYMAS KURJERIUI GRYNAISIAIS ARBA KORTELE, PASLAUGOS KAINA @{{ dpd_pay_on_delivery }}€<span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                   </div>

                   <div class="card mb-2 checkout_payment_car_block" v-if="deliveryInfo.delivery_type=='omniva'">
                       <div class="card-header">
                           <div class="custom-control custom-radio">
                               <input type="radio" id="paymentThreeRadio" name="paymentThreeRadio" class="custom-control-input"  value="omnivaPayondel" v-model="order.paymentMethod">
                               <label class="custom-control-label checkout_txt3" for="paymentThreeRadio">ATSISKAITYMAS OMNIVA PAŠTOMATE<span class="courrier_extra_small"></span></label>
                           </div>
                       </div>
                   </div>

               </div>
           </div>
       </div>



        <div class="container" v-if="errors !=''">
           <div class="row pb-3">
               <div class="col-sm-12 col-md-12 col-lg-12">
                   <div class="d-flex">
                       <div class="ml-auto">
                           <div class="alert-danger">
                              <ul class="list-unstyled">
                                 <li v-for="error in errors">@{{ error }}</li>

                              </ul>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>




       <div class="container">
           <div class="row pb-3">
               <div class="col-sm-12 col-md-12 col-lg-12">
                   <div class="d-flex">
                       <div class="ml-auto">
                           <div class="form-check form-check-inline">
                               <input class="form-check-input" type="checkbox" id="policyAccepatanceCheck"  v-model="acceptance">
                               <label class="form-check-label" for="policyAccepatanceCheck"> @lang("cart_details.Agree with eshop privacy policy")</label>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>

         <div class="container">
           <div class="row pb-3">
               <div class="col-sm-12 col-md-12 col-lg-12">
                   <div class="d-flex">
                       <div class="ml-auto">
                           <button type="submit"  :disabled="!acceptance" class="btn total_sum_btn" @click.prevent="continueToNext">@lang("cart_details.Make Payment")</button>
                       </div>
                   </div>
               </div>
           </div>
       </div>


 </div>




`,

            data: function(){
                return {

                    cart:{
                        quantity:'',
                        package:'',
                        productId:''
                    },

                    order:{
                        buyer :{},
                        deliveryType :'',
                        terminal :'',
                        pickupMethod :'',
                        total :0,
                        totalPrice:0,
                        paymentMethod:''
                    },
                    cartItems :[],
                    cartItemNumber:0,
                    sum :0,
                    code:'',
                    totalSum:0,
                    codeDiscountAmount :0,
                    totalDiscount :0,
                    ifActive:false,
                    discount :false,
                    contacts :{},
                    omniva :false,
                    acceptance:false,

                    deliveryInfo:{
                        delivery_city:'',
                        delivery_address:'',
                        delivery_zip_code:'',
                        delivery_type:'',
                        payondel:'',
                        pickupMethod:'',
                        terminal:''
                    },

                    buyerInfo:{
                        name:'',
                        surname:'',
                        email:'',
                        phone:'',
                        city:'',
                        address:'',
                        zip_code:'',
                        needvat:false,
                        company_title:'',
                        company_code:'',
                        company_vatcode:'',
                        userid :'',

                    },
                    registerInfo:{
                        name:'',
                        surname:'',
                        email:'',
                        phone:'',
                        city:'',
                        address:'',
                        zip_code:'',
                        password:'',
                        confirmPassword:'',
                        iscompany:false,
                        company_title:'',
                        company_code:'',
                        company_vatcode:'',
                    },
                    userId:'',
                    loginInfo:{
                        email:'',
                        password:'',
                    },
                    discountCode:'',
                    buyerInfodiv:false,
                    logindiv:false,
                    registerDiv:false,
                    loggedIn :'',
                    terminals :[],
                    errors:[],
                    deliveryprice:0.00,

                    loginIcon:'add',
                    registerIcon :'add',
                    nonRegisterIcon : 'add' ,
                    errors :[],
                    storeDiscountedPrice :0,
                    storeDiscount : false ,
                    loginErrors :[],
                    registerErrors : [],
                    pickupdiscount : 0 ,

                    dpd_courier_price : 0 ,
                    omniva_courier_price :0,
                    free_shipping_from : 0 ,
                    dpd_pay_on_delivery :0 ,
                    store_picup_discount : 0 ,


                };
            },

            watch:{
              'deliveryInfo.delivery_type'  : function (val) {

                 this.getDeliveryPrice(val,this.sum,this.order.paymentMethod) ;

                  if(val == 'shop')
                  {
                      this.storeDiscount = true ;
                     /*  this.getCartItems();*/
                  }
                  else {
                      this.storeDiscount = false ;
                  }


              },
                'storeDiscount' : function (val) {
                    if(val)
                    {
                        this.getTotalSum();
                    }
                },

                'order.paymentMethod' : function (val) {

                    this.getDeliveryPrice(this.deliveryInfo.delivery_type,this.sum , val) ;

                }

            },
            methods:
                {

                    getCartItems()
                    {
                        axios.get('get-cart-items').then(response=>{
                            this.cartItems = response.data.items;
                            this.sum = response.data.total ;
                            console.log(response.data);
                            var totalWeight = response.data.totalWeight ;
                            this.storeDiscuntedPrice = response.data.storeDiscountedPrice;

                            this.totalDiscount = response.data.totalDiscount ;
                            if(this.deliveryInfo.delivery_type != '')
                              this.getDeliveryPrice(this.deliveryInfo.delivery_type,this.sum,this.order.paymentMethod) ;
                            this.getTotalSum();
                            this.omniva = totalWeight>30 ? false : true ;
                            if(this.omniva)
                            {
                                this.getTerminals();
                            }



                        })
                    },
                    changeQuantity(id)
                    {


                        var quantity = document.getElementById('quantity'+id).value;
                        axios.post('change-item-quantity',{id:id,quantity:quantity}).then(response=>{
                            if(response.data.message == undefined)
                            this.getCartItems();
                            else alert(response.data.message);

                        })
                    },

                    removeFromCart(id)
                    {

                        axios.post('remove-from-product',{id:id}).then(response=>{

                            this.getCartItems();
                          /*  this.getDeliveryPrice()*/
                        })
                    },
                    applyDiscountCode()
                    {

                        this.discount = true ;
                        axios.post('apply-discount-code',{code:this.discountCode}).then(response=>{
                            if(response.data.message == undefined)
                            {
                                this.getCartItems();
                            }
                            else {
                                alert(response.data.message) ;
                            }

                        })
                    },
                    continueToNext()
                    {

                        this.order.buyer = this.buyerInfo ;
                        this.order.deliveryType = this.deliveryInfo.delivery_type ;
                        this.order.terminal = this.deliveryInfo.terminal ;
                        this.order.pickupMethod = this.deliveryInfo.delivery_type == 'omniva' ? 'pt' : '' ;
                        this.order.total = this.totalSum ;
                        this.order.totalPrice = this.sum ;

                        axios.post('save-order',this.order).then(response=>{
                            this.errors = response.data.message ;
                            if(this.errors == undefined)
                            {
                                var orderId = response.data ;
                                if(this.order.paymentMethod=='paysera')
                                    location.replace('pay/'+orderId);
                                else {
                                    location.replace('order-confirmed/'+orderId);
                                }
                            }


                        })

                    },

                    getContact()
                    {
                        axios.get('get-contacts-home').then(response=>{
                            this.contacts = response.data;

                        })
                    },

                    getTerminals()
                    {
                        axios.get('get-omniva-terminals').then(response=>{
                            this.terminals = response.data ;
                        })
                    },

                    getDeliveryPrice(delivery_type,sum_price,paymentMethod)
                    {

                        axios.post('get-delivery-price',{deliveryType :delivery_type,total:sum_price,paymentMethod : paymentMethod}).then(response=>{
                            this.deliveryprice = response.data ;
                            console.log(this.deliveryprice);
                            this.getTotalSum();
                        })
                    },

                    getTotalSum()
                    {
                        if(this.storeDiscount)
                        {
                            axios.get('get-store-discount').then(response=>{
                                this.pickupdiscount = response.data ;
                                this.totalSum =parseFloat(this.sum)-(parseFloat(this.sum)*this.pickupdiscount/100)- parseFloat(this.totalDiscount) ;
                                this.totalSum = this.totalSum.toFixed(2);
                            });


                        }
                        else {
                            this.totalSum = parseFloat(this.sum) + parseFloat(this.deliveryprice) - parseFloat(this.totalDiscount);
                            this.totalSum = this.totalSum.toFixed(2);
                        }

                    },

                    getUserInfo()
                    {
                        axios.get('get-if-user-logged-in').then(response=>{
                            this.loggedIn = response.data.loggedIn;
                            var id = response.data.user;
                            if(this.loggedIn)
                            {
                                var that= this;
                                axios.get('get-user-info/'+id).then(response=>{
                                    that.buyerInfo.name = response.data.name;
                                    that.buyerInfo.surname= response.data.surname;
                                    that.buyerInfo.email= response.data.email;
                                    that.buyerInfo.phone=response.data.phone ;
                                    that.buyerInfo.city=response.data.city ;
                                    that.buyerInfo.address=response.data.address ;
                                    that.buyerInfo.zip_code=response.data.zip_code ;
                                    that.buyerInfo.needvat=response.data.iscompany ;
                                    that.buyerInfo.company_title=response.data.company_title ;
                                    that.buyerInfo.company_code= response.data.company_code;
                                    that.buyerInfo.company_vatcode=response.data.company_vatcode ;
                                    that.buyerInfo.userid = id ;
                                    //that.buyerInfodiv =true ;
                                })
                            }


                        })
                    },


                    buyerInfoDivToggle()
                    {
                        if(this.buyerInfodiv==true)
                        {
                            this.buyerInfodiv = false ;
                            this.nonRegisterIcon = 'add' ;
                        }

                        else
                        {
                            this.buyerInfodiv = true ;
                            this.nonRegisterIcon = 'remove' ;
                            this.registerIcon = 'add' ;
                            this.loginIcon ='add';
                        }
                        this.logindiv = false ;
                        this.registerDiv = false ;
                    },

                    registerDivToggle()
                    {
                        if(this.registerDiv == false)
                        {
                            this.registerDiv = true ;
                            this.registerIcon = 'remove' ;
                            this.nonRegisterIcon = 'add' ;
                            this.loginIcon ='add';
                        }

                        else
                        {
                            this.registerDiv = false;
                            this.registerIcon = 'add' ;
                        }
                        this.buyerInfodiv = false ;
                        this.logindiv = false ;
                    },

                    logindivToggle()
                    {
                        if(this.logindiv == false)
                        {
                            this.logindiv = true ;
                            this.loginIcon ='remove';
                            this.registerIcon = 'add' ;
                            this.nonRegisterIcon = 'add' ;

                        }
                        else
                        {
                            this.logindiv = false;
                            this.loginIcon ='add';

                        }
                        this.buyerInfodiv = false ;
                        this.registerDiv = false ;

                    },


                    login()
                    {
                        axios.post('ajax-login',this.loginInfo).then(response=>{
                            console.log(response.data) ;
                            if(response.data.message=='logged in')
                            {
                                this.loggedIn = true ;
                                this.userId =  response.data.user;
                                var that = this ;
                                this.logindiv = false;
                                location.reload();

                                axios.get('get-user-info/'+this.userId).then(response=>{
                                    that.buyerInfo.name = response.data.name;
                                    that.buyerInfo.surname= response.data.surname;
                                    that.buyerInfo.email= response.data.email;
                                    that.buyerInfo.phone=response.data.phone ;
                                    that.buyerInfo.city=response.data.city ;
                                    that.buyerInfo.address=response.data.address ;
                                    that.buyerInfo.zip_code=response.data.zip_code ;
                                    that.buyerInfo.needvat=response.data.iscompany ;
                                    that.buyerInfo.company_title=response.data.company_title ;
                                    that.buyerInfo.company_code= response.data.company_code;
                                    that.buyerInfo.company_vatcode=response.data.company_vatcode ;
                                    that.buyerInfo.userid = userId ;
                                    //that.buyerInfodiv =true ;
                                    that.getCartItems();
                                })
                            }
                            else{
                                this.loginErrors = response.data.message;
                            }
                        })
                    },
                    register()
                    {

                        if(this.registerInfo.iscompany)
                        {
                            this.registerInfo.iscompany =1;

                        }
                        else this.registerInfo.iscompany=0;
                        axios.post('ajax-register',this.registerInfo).then(response=>{
                            if(response.data.message=='logged in')
                            {

                                this.loggedIn = true ;
                                this.userId =  response.data.user;
                                var that = this ;


                                axios.get('get-user-info/'+this.userId).then(response=>{
                                    that.buyerInfo.name = response.data.name;
                                    that.buyerInfo.surname= response.data.surname;
                                    that.buyerInfo.email= response.data.email;
                                    that.buyerInfo.phone=response.data.phone ;
                                    that.buyerInfo.city=response.data.city ;
                                    that.buyerInfo.address=response.data.address ;
                                    that.buyerInfo.zip_code=response.data.zip_code ;
                                    if(response.data.iscompany==1)
                                        that.buyerInfo.needvat=true ;
                                    else that.buyerInfo.needvat=false ;
                                    that.buyerInfo.company_title=response.data.company_title ;
                                    that.buyerInfo.company_code= response.data.company_code;
                                    that.buyerInfo.company_vatcode=response.data.company_vatcode ;
                                    that.buyerInfo.userId = userId ;

                                    that.getCartItems() ;
                                })
                            }
                            else{
                                this.registerErrors = response.data.message;
                            }
                        })
                    },

                    getCourierPricesFromSettings()
                    {
                        axios.get('get-prices-from-settings').then(response=>{

                            this.dpd_courier_price = response.data.dpd_courier_price ;
                            this.omniva_courier_price = response.data.omniva_courier_price ;
                            this.free_shipping_from = response.data.free_shipping_from ;
                            this.dpd_pay_on_delivery = response.data.dpd_pay_on_delivery ;
                            this.store_picup_discount = response.data.store_picup_discount ;
                        })
                    },



                },
            created(){
                this.getCartItems();
                this.getUserInfo();
                this.getContact();
                this.getCourierPricesFromSettings();



            }

        }



        const routes = [

            {
                path: '/',
                component: Cart,
                name: 'cartShow'
            },


        ]


        const router = new VueRouter({
            routes // short for `routes: routes`
        })

        const app = new Vue({
            router,

        }).$mount('#eshop-page')


    </script>
@endsection
