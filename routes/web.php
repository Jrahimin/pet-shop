<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Front End Pages
/*Route::get('/',function () {
    return view('welcome');
});*/


use Illuminate\Support\Facades\DB;

Route::post('test','Admin\ShipmentController@test');

Route::get('test',function (){
    session()->flush();
})->name('test');

Route::get('category-menu', 'Admin\ProductCatalogCategoriesController@categoryListMenu')->name('get_category_menu');


//Login Routes...
Auth::routes();

// Ajax login routes

Route::post('ajax-login','FrontEnd\AjaxLoginRegisterController@login')->name('ajax_login');
Route::post('ajax-register','FrontEnd\AjaxLoginRegisterController@register')->name('ajax_register');



// Home Pages
Route::get('/design','FrontEnd\HomeController@index')->name('index_front');
//new home page with design : Aulipriya
Route::get('/','FrontEnd\HomeController@indexNew')->name('index_new');
Route::get('/test-home','FrontEnd\HomeController@testIndex')->name('test_home');
Route::get('get-advantages-home','FrontEnd\HomeController@getAdvantages')->name('get_advantages_home');
Route::get('get-contacts-home','FrontEnd\HomeController@getContacts')->name('get_contacts_home');
Route::get('get-slider-for-home','FrontEnd\HomeController@getSliderForHome')->name('get_slider_for_home');


Route::get('get-home-data','FrontEnd\HomeController@getHomeData')->name('get_home_data');

// FRONT END STATIC PAGES FOR SLICING AND USE THEM FOR DYNAMIC CONTENT GENERATION
Route::get('category_main','FrontEnd\CategoryController@index')->name('category_index_front');
Route::get('product_main','FrontEnd\ProductController@index')->name('product_index_front');





// Eshop Pages

Route::get('eshop','FrontEnd\EshopController@index')->name('eshop_index');
//
Route::get('check_two', 'FrontEnd\EshopController@checkOutTwo')->name('checkout_step2');
Route::get('check_three', 'FrontEnd\EshopController@checkOutThree')->name('checkout_step3');
Route::get('check_four', 'FrontEnd\EshopController@checkOutFour')->name('checkout_step4');
//


Route::get('get-new-products','FrontEnd\EshopController@getNewProducts')->name('get_new_products');
Route::get('get-best-products','FrontEnd\EshopController@getBestProducts')->name('get_best_products');
Route::get('get-best-product-banners','FrontEnd\EshopController@getBestProductsBanners')->name('get_best_product_banners');
Route::get('get-new-product-banners','FrontEnd\EshopController@getNewProductsBanners')->name('get_new_product_banners');
Route::get('eshop/category/{id}','FrontEnd\EshopController@eshopCategoryIndex')->name('eshop_category_index');
Route::get('get-products-by-cat/{id}','FrontEnd\EshopController@getProductsByCat')->name('get_products_by_cat');
Route::post('get-products-manufacturer','FrontEnd\EshopController@getProductsManufacturer')->name('get_products_manufacturer');
Route::post('filter-products-by-manu','FrontEnd\EshopController@getProductsByManufacturer')->name('get_products_by_manufacturer');
Route::get('increase-banner-click/{id}','FrontEnd\EshopController@increaseBannerClick')->name('increase_banner_click');

Route::get('get-products-by-key/{keyword}','FrontEnd\EshopController@getProductsByKey')->name('get_products_by_key');

//Cart
Route::get('cart-index','FrontEnd\CartController@cartIndex')->name('cart_index');
Route::get('get-cart-number','FrontEnd\CartController@getCartItemNumber')->name('get_cart_item_number');
Route::post('add-to-cart','FrontEnd\CartController@addToCart')->name('add_to_cart');
Route::post('remove-from-product','FrontEnd\CartController@removeFromCart')->name('remove_from_product');
Route::get('get-cart-items','FrontEnd\CartController@getCartItem')->name('get_cart_items');
Route::post('change-item-quantity','FrontEnd\CartController@changeItemQuantity')->name('change_item_quantity');
Route::post('apply-discount-code','FrontEnd\CartController@applyDiscountCode')->name('apply_discount_code');
Route::post('store-cart-total-sum','FrontEnd\CartController@storeCartTotalSum')->name('store_cart_total_sum');
Route::get('get-cart-sum','FrontEnd\CartController@getCartSum')->name('get_cart_sum');
Route::post('get-loyalty-discount','FrontEnd\CartController@getLoyaltyDiscount')->name('get_loyalty_discount');
Route::get('get-code-discount-amount','FrontEnd\CartController@getCodeDiscountAmount')->name('get_code_discount_amount');
Route::get('check-weight','FrontEnd\CartController@checkWeight')->name('check_weight');
Route::get('get-omniva-terminals','FrontEnd\CartController@getOmnivaTerminals')->name('get_omniva_terminals');
Route::post('if-cart-product-exists','FrontEnd\CartController@checkIfCartProductExists')->name('check_if_cart_product_exists');
Route::post('add-item-quantity','FrontEnd\CartController@addItemQuantity')->name('add_item_quantity');
Route::get('get-store-discount','FrontEnd\CartController@getPickupDiscount')->name('get_pickup_discount') ;
Route::get('get-prices-from-settings','FrontEnd\CartController@getCourierPrices')->name('get_courier_prices');


//Order Info
Route::get('get-if-user-logged-in','FrontEnd\OrderController@getIfUserLoggedIn')->name('get_if_user_logged_in');
Route::post('store-buyer-info','FrontEnd\OrderController@storeBuyerInfo')->name('store_buyer_info');
Route::post('store-delivery-info','FrontEnd\OrderController@storeDeliveryInfo')->name('store_delivery_info');
Route::get('get-buyer-info','FrontEnd\OrderController@getBuyerInfo')->name('get_buyer_info');
Route::get('get-delivery-info/{id}','FrontEnd\OrderController@getDeliveryInfo')->name('get_delivery_info');
Route::get('get-user-info/{id}','FrontEnd\OrderController@getUserInfo')->name('get_user_info');
Route::get('store-delivery-price/{price}','FrontEnd\OrderController@storeDeliveryPrice')->name('store_delivery_price');
Route::get('get-total-sum','FrontEnd\OrderController@getTotalSum')->name('get_total_sum');
Route::post('get-delivery-price','FrontEnd\CartController@getDeliveryPrice')->name('get_delivery_price');


// paysera payment
Route::post('save-order','FrontEnd\OrderController@saveOrder')->name('save_order') ;
Route::get('pay/{orderId}','FrontEnd\OrderController@paymentRequest')->name('payment_request');
Route::get('accepted','FrontEnd\OrderController@acceptedPayment')->name('accepted_payment');
Route::get('cancelled','FrontEnd\OrderController@cancelledPayment')->name('cancelled_payment');
Route::get('callback','FrontEnd\OrderController@callBackPayment')->name('callback');

//Pay on delivery Message page
Route::get('order-confirmed/{orderId}','FrontEnd\OrderController@orderConfirmedIndex')->name('order_confirmed_index');
// Product details page

Route::get('product-detail/{id}','FrontEnd\EshopController@productDetailsPage')->name('get_product_details_page');
Route::get('get-product/{id}','FrontEnd\EshopController@getProduct')->name('get_product');
Route::get('get-related-products/{id}','FrontEnd\EshopController@getRelatedProducts')->name('get_related_products');
Route::post('get-available-sizes','FrontEnd\EshopController@getAvailableSizes')->name('get_available_sizes');
Route::post('get-available-packages','FrontEnd\EshopController@getAvailablePackages')->name('get_available_packages');
Route::post('get-package','FrontEnd\EshopController@getPackage')->name('get_package');
Route::post('refine-volumes','FrontEnd\EshopController@refineVolumes')->name('refine_volumes');
Route::post('refine-lengths','FrontEnd\EshopController@refineLengths')->name('refine_lengths');
Route::post('refine-diameters','FrontEnd\EshopController@refineDiameters')->name('refine_diameters');
Route::get('get-product-videos/{id}','FrontEnd\EshopController@getProductVideos')->name('get_product_videos');
Route::get('get-package-color-size-attribute/{id}','FrontEnd\EshopController@getPackageColorSizeAttribute')->name('get_package_color_size_attribute');






// Product details page
/*Route::get('aprasymai','FrontEnd\ProductController@index')->name('product_index_front');
Route::get('get-products/{id?}','FrontEnd\ProductController@getAllProducts')->name('get_all_products');
Route::get('get-products/filtered/{id}','FrontEnd\ProductController@getFilteredProducts')->name('get_filtered_products');
Route::get('get-product-detail/{id}','FrontEnd\ProductController@getProduct')->name('get_product_details');*/



// Interesting Pages
Route::get('idomu','FrontEnd\InterestingController@index')->name('interesting_index_front');
Route::get('get-new-interestings','FrontEnd\InterestingController@getInterestings')->name('get_interestings_front');
Route::get('get-new-interesting/{id}','FrontEnd\InterestingController@getInteresting')->name('get_interesting_front');

// Contacts Pages
Route::get('contact','FrontEnd\ContactController@index')->name('contact_index_front');
Route::get('get-new-contacts','FrontEnd\ContactController@getContacts')->name('get_contacts_front');
Route::post('contact/send-mail','FrontEnd\ContactController@sendMail')->name('send_contact_mail');


// user dashboard panel
Route::get('user-info', 'FrontEnd\UserController@index')->name('user_info_index');
Route::get('get-user/{id}', 'FrontEnd\UserController@getUser')->name('get_user_front');
Route::post('edit-user', 'FrontEnd\UserController@editUser')->name('edit_user_front');

Route::get('user/pet', 'FrontEnd\UserController@petIndex')->name('user_pet_info_index');
Route::get('pet-info/{id}', 'FrontEnd\UserController@getPetInfo')->name('get_pet_info');
Route::post('save-pets', 'FrontEnd\UserController@savePets')->name('save_pets');

Route::get('user-order-history','FrontEnd\UserController@orderHistoryIndex')->name('order_history_index');
Route::get('get-user-orders','FrontEnd\UserController@getUserOrders')->name('get_user_orders');
Route::get('get-user-order-detail/{id}','FrontEnd\UserController@getUserOrderDetail')->name('get_user_order_detail');

// catalog
Route::get('buyer-info-show', 'FrontEnd\ProductCatalogController@buyerInfoIndex')->name('buyer_info_front_show');
Route::get('delivery-info-show', 'FrontEnd\ProductCatalogController@deliveryIndex')->name('delivery_info_front_show');
Route::get('delivery-info', 'FrontEnd\ProductCatalogController@getDeliveryInfo')->name('delivery_info_front');
Route::get('customer-info', 'FrontEnd\ProductCatalogController@getCustomerInfo')->name('customer_info_front');

Route::get('filtered-products/{keyword}', 'FrontEnd\ProductCatalogController@filterdProductsIndex')->name('filtered_products_front');

// Admin Panel Pages
    Route::prefix('admin')->group(function () {


        // Admin login
        Route::get('login', 'Admin\AuthController@loginPage')->name('admin_login_index');
        Route::post('login', 'Admin\AuthController@login')->name('admin_login');
        Route::get('logout', 'Admin\AuthController@logout')->name('admin_logout');

        Route::group(['middleware'=>['admin']],function() {
        Route::get('/', 'HomeController@index')->name('home');

        //Promona Routes

            //Slider Image Management

            Route::get('slider-images','Admin\SliderImageController@index')->name('slider_image_index');
            Route::get('get-slider-images','Admin\SliderImageController@getSliderImages')->name('get_slider_images');
            Route::get('delete-sliderImage/{id}','Admin\SliderImageController@deleteSliderImage')->name('delete_sliderImage');
            Route::post('save-sliderImage','Admin\SliderImageController@saveSliderImage')->name('save_sliderImage');
            Route::get('get-sliderImage/{id}','Admin\SliderImageController@getSliderImage')->name('get_sliderImage');
            Route::post('edit-sliderImage','Admin\SliderImageController@editSliderImage')->name('edit_sliderImage');

            Route::post('slider-image-up/{id}', 'Admin\SliderImageController@sliderUp')->name('slider__image_up');
            Route::post('slider-image-down/{id}', 'Admin\SliderImageController@sliderDown')->name('slider__image_down');

            Route::get('get-slider-option','Admin\SliderImageController@getSliderOption')->name('get-slider-option');
            Route::post('slider-options/save','Admin\SliderImageController@addSliderOptions')->name('add_slider_options');
            Route::get('slider-option/build','Admin\SliderImageController@buildSliderOption')->name('build_slider_option');


        Route::group(['middleware'=>['permission:baneriai']],function() {
            // Banner Management
            Route::get('banners', 'Admin\BannerController@index')->name('banner_index');
            Route::get('get-banners', 'Admin\BannerController@getBanners')->name('get_banners');
            Route::get('add-banner', 'Admin\BannerController@addBanner')->name('add_banner_index');
            Route::post('save-banner', 'Admin\BannerController@storeBanner')->name('store_banner');
            Route::get('delete-banner/{id}', 'Admin\BannerController@deleteBanner')->name('delete_banner');
            Route::get('get-banner/{id}', 'Admin\BannerController@getBanner')->name('get_banner');
            Route::post('edit-banner', 'Admin\BannerController@editBanner')->name('edit_banner');
        });

         Route::group(['middleware'=>['permission:gamintojai']],function() {
        //Manufacturer Management
        Route::get('manufacturers', 'Admin\ManufacturerController@index')->name('manufacturer_index');
        Route::get('get-manufacturers', 'Admin\ManufacturerController@getManufacturers')->name('get_manufacturers');
        Route::post('save-manufacturer', 'Admin\ManufacturerController@saveManufacturer')->name('save_manufacturer');
        Route::get('delete-manufacturer/{id}', 'Admin\ManufacturerController@deleteManufacturers')->name('delete_manufacturers');
        Route::get('get-manufacturer/{id}', 'Admin\ManufacturerController@getManufacturer')->name('get_manufacturer');
        Route::post('edit-manufacturer', 'Admin\ManufacturerController@editManufacturer')->name('edit_manufacturer');
        Route::get('move-up/{id}', 'Admin\ManufacturerController@moveUpManufacturer')->name('move_up_manufacturer');
        Route::get('move-down/{id}', 'Admin\ManufacturerController@moveDownManufacturer')->name('move_down_manufacturer');
         });


         Route::group(['middleware'=>['permission:pirkimai']],function() {
        //Purchase Management
        Route::get('purchase', 'Admin\PurchaseController@index')->name('purchase_index');
        Route::get('get-orders', 'Admin\PurchaseController@getOrders')->name('get_orders');
        Route::get('order-detail/{id}', 'Admin\PurchaseController@getOrderDetail')->name('get_order_detail');
        Route::get('delete-order/{id}', 'Admin\PurchaseController@deleteOrder')->name('delete_order');
        Route::get('get-years', 'Admin\PurchaseController@getYears')->name('get_years');
        Route::get('filter-orders/{month}/{year}/{client}', 'Admin\PurchaseController@filterOrders')->name('filter_orders');
        Route::get('export-excel/{year}/{month}/{client}', 'Admin\PurchaseController@exportExcel')->name('export_excel');

         });

        Route::post('create-shipment','Admin\ShipmentController@createShipment')->name('create_shipment');
        Route::get('view-parcel-label/{id}','Admin\ShipmentController@showParcelLabel')->name('show_parcel_label');
        Route::get('closing-manifest','Admin\ShipmentController@closingManifestIndex')->name('closing_manifest_index');
        Route::get('close-manifest/{date}','Admin\ShipmentController@closeManifest')->name('close_manifest');
        Route::get('get-shipment-type/{id}','Admin\ShipmentController@getShipmentType')->name('get_shipment_type');



            Route::group(['middleware'=>['permission:privalumai']],function() {
            //Why Yzipet
            Route::get('advantages', 'Admin\AdvantagesController@index')->name('advantages_index');
            Route::get('get-advantages', 'Admin\AdvantagesController@getAdvantages')->name('get_advantages');
            Route::post('save-advantage', 'Admin\AdvantagesController@saveAdvantage')->name('save_advantage');
            Route::get('get-advantage/{id}', 'Admin\AdvantagesController@getAdvantage')->name('get_advantage');
            Route::post('edit-advantage', 'Admin\AdvantagesController@editAdvantage')->name('edit_advantage');
            Route::get('delete-advantage/{id}', 'Admin\AdvantagesController@deleteAdvantage')->name('delete_advantage');
            Route::get('advantage-move-up/{id}', 'Admin\AdvantagesController@moveUp')->name('advantage_move_up');
            Route::get('advantage-move-down/{id}', 'Admin\AdvantagesController@moveDown')->name('advantage_move_down');
        });


        Route::group(['middleware'=>['permission:registruoti_vartotojai']],function() {
        //Registered Users
        Route::get('registered-users', 'Admin\UsersController@index')->name('users_index');
        Route::get('get-users', 'Admin\UsersController@getUsers')->name('get_users');
        Route::get('get-users-select', 'Admin\UsersController@getUserSelectList')->name('get_select_users');
        Route::get('filter-users/{keywords}', 'Admin\UsersController@filterUsers')->name('get_filtered_users');
        Route::get('delete-user/{id}', 'Admin\UsersController@deleteUser')->name('delete_user');
        Route::post('delete-many-users', 'Admin\UsersController@deleteManyUsers')->name('delete_many_users');
        Route::get('user-detail/{id}', 'Admin\UsersController@getUserDetail')->name('get_user_detail');
        Route::post('user-edit', 'Admin\UsersController@editUser')->name('user_edit');
        Route::get('get-all-products','Admin\UsersController@getAllProducts')->name('get_all_products');
        });


        Route::group(['middleware'=>['permission:administratoriai']],function() {

            // Administrators management
        Route::get('administrators', 'Admin\AdministratorController@index')->name('administrators_index');
        Route::get('get-admin', 'Admin\AdministratorController@getAdmin')->name('get_admin');
        Route::get('delete-admin/{id}', 'Admin\AdministratorController@deletetAdmin')->name('delete_admin');
        Route::get('get-permissions', 'Admin\AdministratorController@getPermissions')->name('get_permissions');
        Route::get('get-roles', 'Admin\AdministratorController@getRoles')->name('get_roles');
        Route::post('save-admin', 'Admin\AdministratorController@saveAdmin')->name('save_admin');
        Route::get('get-permission-for-role/{role}', 'Admin\AdministratorController@getPermissionsForRole')->name('get_permissions_for_role');
        Route::get('get-admin/{id}', 'Admin\AdministratorController@getAdminByID')->name('get_admin');
        Route::post('edit-admin', 'Admin\AdministratorController@editAdmin')->name('edit_admin');
        Route::post('save-role', 'Admin\AdministratorController@saveRole')->name('save_role');
        Route::get('delete-role/{id}', 'Admin\AdministratorController@deletetRole')->name('delete_role');
        Route::get('get-role/{id}', 'Admin\AdministratorController@getRole')->name('get_role');
        Route::post('edit-role', 'Admin\AdministratorController@editRole')->name('edit_role');

       });

        Route::group(['middleware'=>['permission:prenumeratoriai']],function() {
        // Subcribers
        Route::get('subscribers','Admin\SubscriberController@index')->name('subscriber_index');
        Route::get('get-subscribers','Admin\SubscriberController@getSubscribers')->name('get_subscribers');
        Route::post('save-subscriber','Admin\SubscriberController@saveSubscriber')->name('save_subscriber');
        Route::get('delete-subscriber/{id}','Admin\SubscriberController@deleteSubscriber')->name('delete_subscriber');
        Route::get('get-subscriber/{id}','Admin\SubscriberController@getSubscriber')->name('get_subscriber');
        Route::post('edit-subscriber','Admin\SubscriberController@editSubscriber')->name('edit_subscriber');
        Route::get('export-excel-subscriber','Admin\SubscriberController@exportSubscriber')->name('export_subscriber');

        });

         Route::group(['middleware'=>['permission:katalogas']],function() {
             //Product Catalog
             Route::get('product-catalog', 'Admin\ProductCatalogController@index')->name('product_catalog_index');
             Route::get('get-goods', 'Admin\ProductCatalogController@getGoods')->name('get_goods');
             Route::get('delete-good/{id}', 'Admin\ProductCatalogController@deleteGoods')->name('delete_goods');
             Route::post('save-product', 'Admin\ProductCatalogController@saveProduct')->name('save_product');
             Route::get('get-product-categories', 'Admin\ProductCatalogController@getProductCategories')->name('get_product_categories');
             Route::get('get-product/{id}', 'Admin\ProductCatalogController@getProduct')->name('get_product');
             Route::post('edit-product', 'Admin\ProductCatalogController@editProduct')->name('edit_product');
             Route::get('product/galleries/{id}', 'Admin\ProductCatalogController@getProductGallery')->name('get_product_gallery');
             Route::get('delete-product-gallery/{id}', 'Admin\ProductCatalogController@deleteProductPhoto')->name('get_product_photo');
             Route::get('product-gallery-up/{id}', 'Admin\ProductCatalogController@upProductPhoto')->name('up_product_photo');
             Route::get('product-gallery-down/{id}', 'Admin\ProductCatalogController@downProductPhoto')->name('down_product_photo');
             Route::post('add-product-photo', 'Admin\ProductCatalogController@addProductPhoto')->name('add_product_photo');
             Route::get('get-photo-info/{id}', 'Admin\ProductCatalogController@getphotoInfo')->name('get_photo_info');
             Route::post('edit-product-photo', 'Admin\ProductCatalogController@editProductPhoto')->name('edit_product_photo');
             Route::get('get-goods/{searchKey}', 'Admin\ProductCatalogController@filterGoods')->name('filter_goods');
             Route::get('get-reviews/{id}', 'Admin\ProductCatalogController@getReviews')->name('get_reviews');
             Route::get('move-review-down/{id}', 'Admin\ProductCatalogController@moveReviewDown')->name('move_review_down');
             Route::get('move-review-up/{id}', 'Admin\ProductCatalogController@moveReviewUp')->name('move_review_up');
             Route::get('delete-review/{id}', 'Admin\ProductCatalogController@deleteReview')->name('delete_review');
             Route::post('save-review', 'Admin\ProductCatalogController@saveReview')->name('save_review');
             Route::get('get-review/{id}', 'Admin\ProductCatalogController@getReviewInfo')->name('get_review_info');
             Route::post('edit-review', 'Admin\ProductCatalogController@editReview')->name('edit_review');
             Route::get('get-product-packages/{id}', 'Admin\ProductCatalogController@getProductPackages')->name('get_product_packages');
             Route::get('get-colors-for-product', 'Admin\ProductCatalogController@getColors')->name('get_colors');
             Route::get('get-sizes-for-product', 'Admin\ProductCatalogController@getSizes')->name('get_sizes');


             // Package Color management
             Route::get('attribute-colors','Admin\PackageColorController@index')->name('attribute_colors') ;
             Route::post('add-attribute-color','Admin\PackageColorController@addColor')->name('add_attribute_color') ;
             Route::get('get-colors','Admin\PackageColorController@getColors')->name('get_colors') ;
             Route::get('delete-color/{id}','Admin\PackageColorController@deleteColors')->name('delete_colors') ;
             Route::post('edit-attribute-color','Admin\PackageColorController@editColor')->name('edit_attribute_color') ;
             Route::get('get-color/{id}','Admin\PackageColorController@getColor')->name('get_color') ;

             // Package Size management
             Route::get('attribute-sizes','Admin\PackageSizeController@index')->name('attribute_sizes') ;
             Route::post('add-attribute-size','Admin\PackageSizeController@addSize')->name('add_attribute_size') ;
             Route::get('get-sizes','Admin\PackageSizeController@getSizes')->name('get_sizes') ;
             Route::get('delete-size/{id}','Admin\PackageSizeController@deleteSize')->name('delete_size') ;
             Route::post('edit-attribute-size','Admin\PackageSizeController@editSize')->name('edit_attribute_size') ;
             Route::get('get-size/{id}','Admin\PackageSizeController@getSize')->name('get_size') ;



             // Inventory
             Route::get('inventory', 'Admin\ProductCatalogInventory@index')->name('inventory_index');
             Route::get('get-inventory-info', 'Admin\ProductCatalogInventory@getInventoryInfo')->name('get_inventory_info');
             Route::get('get-inventory-info/{searchKey}', 'Admin\ProductCatalogInventory@filterProducts')->name('filter_products');
             Route::post('add-stock', 'Admin\ProductCatalogInventory@addStock')->name('add_stock');
             Route::post('delete-stock', 'Admin\ProductCatalogInventory@deleteStock')->name('delete_stock');
             Route::get('get-inventory-log', 'Admin\ProductCatalogInventory@getInventoryLog')->name('get_inventory_log');
         });



            //jr routes

       Route::group(['middleware'=>['permission:idomu']],function() {
           // interesting
           Route::get('interestings', 'Admin\InterestingController@index')->name('interesting_index');
           Route::get('get-interestings', 'Admin\InterestingController@getInterestings')->name('get_interestings');
           Route::get('interesting/{id}', 'Admin\InterestingController@getInteresting')->name('get_interesting_id');
           Route::get('add-interesting', 'Admin\InterestingController@addInteresting')->name('add_interesting');
           Route::post('add-interesting/post', 'Admin\InterestingController@addInterestingPost')->name('store_interesting');
           Route::post('edit-interesting/post', 'Admin\InterestingController@editInteresting')->name('edit_interesting');
           Route::get('delete-interesting/{id}', 'Admin\InterestingController@deleteInteresting')->name('delete_interesting');
           //interesting-gallery
           Route::get('interesting/galleries/{id}', 'Admin\InterestingGalleryController@getInterestingGalleries')->name('get_interestings');
           Route::post('gallery-up/{id}', 'Admin\InterestingGalleryController@galleryUp')->name('gallery_up');
           Route::post('gallery-down/{id}', 'Admin\InterestingGalleryController@galleryDown')->name('gallery_down');
           Route::get('delete-gallery/{id}', 'Admin\InterestingGalleryController@deleteGallery')->name('delete_interesting');
           Route::post('add-gallery/post', 'Admin\InterestingGalleryController@addGallery')->name('store_interesting_gallery');

       });

        Route::group(['middleware'=>['permission:kontaktai']],function() {

            // contact
            Route::get('contacts', 'Admin\ContactController@index')->name('contact_index');
            Route::get('get-contacts', 'Admin\ContactController@getContacts')->name('get_contacts');
            Route::get('contact/{id}', 'Admin\ContactController@getContact')->name('get_contact_id');
            Route::get('add-contact', 'Admin\ContactController@addContact')->name('add_contact');
            Route::post('add-contact/post', 'Admin\ContactController@addContactPost')->name('store_contact');
            Route::post('edit-contact/post', 'Admin\ContactController@editContact')->name('edit_contact');
            Route::get('delete-contact/{id}', 'Admin\ContactController@deleteContact')->name('delete_contact');

            Route::post('contact-up/{id}', 'Admin\ContactController@contactUp')->name('contact_up');
            Route::post('contact-down/{id}', 'Admin\ContactController@contactDown')->name('contact_down');
        });


         Route::group(['middleware'=>['permission:slider']],function() {
             // home gallery
             Route::get('sliders', 'Admin\SliderController@index')->name('slider_index');
             Route::get('get-sliders', 'Admin\SliderController@getSliders')->name('get_sliders');
             Route::get('slider/{id}', 'Admin\SliderController@getSlider')->name('get_slider_id');
             Route::get('add-slider', 'Admin\SliderController@addSlider')->name('add_slider');
             Route::post('add-slider/post', 'Admin\SliderController@addSliderPost')->name('store_slider');
             Route::post('edit-slider/post', 'Admin\SliderController@editSlider')->name('edit_slider');
             Route::get('delete-slider/{id}', 'Admin\SliderController@deleteSlider')->name('delete_slider');

             Route::post('slider-up/{id}', 'Admin\SliderController@sliderUp')->name('slider_up');
             Route::post('slider-down/{id}', 'Admin\SliderController@sliderDown')->name('slider_down');
         });

        Route::group(['middleware'=>['permission:titulinio_info']],function() {
            // home block
            Route::get('home-info', 'Admin\HomeInfoController@index')->name('home_info_index');
            Route::get('get-home-info/all', 'Admin\HomeInfoController@getHomeInfoAll')->name('get_home_info_all');
            Route::get('home-info/{id}', 'Admin\HomeInfoController@getHomeInfo')->name('get_home_info_id');
            Route::get('add-home-info', 'Admin\HomeInfoController@addHomeInfo')->name('add_home_info');
            Route::post('add-home-info/post', 'Admin\HomeInfoController@addHomeInfoPost')->name('store_home_info');
            Route::post('edit-home-info/post', 'Admin\HomeInfoController@editHomeInfo')->name('edit_home_info');
            Route::get('delete-home-info/{id}', 'Admin\HomeInfoController@deleteHomeInfo')->name('delete_home_info');

            Route::post('home-info-up/{id}', 'Admin\HomeInfoController@homeInfoUp')->name('home_info_up');
            Route::post('home-info-down/{id}', 'Admin\HomeInfoController@homeInfoDown')->name('home_info_down');
        });


        // product catalog
            Route::prefix('catalog')->group(function () {

                Route::group(['middleware'=>['permission:katalogas']],function() {
                // catalog- customer info
                Route::get('customer-info', 'Admin\ProductCatalogCustomerController@index')->name('customer_info_index');
                Route::get('get-customer-info/all', 'Admin\ProductCatalogCustomerController@getCustomerInfoAll')->name('get_customer_info_all');
                Route::get('customer-info/{id}', 'Admin\ProductCatalogCustomerController@getCustomerInfo')->name('get_customer_info_id');
                Route::get('add-customer-info', 'Admin\ProductCatalogCustomerController@addCustomerInfo')->name('add_customer_info');
                Route::post('add-customer-info/post', 'Admin\ProductCatalogCustomerController@addCustomerInfoPost')->name('store_customer_info');
                Route::post('edit-customer-info/post', 'Admin\ProductCatalogCustomerController@editCustomerInfo')->name('edit_customer_info');
                Route::get('delete-customer-info/{id}', 'Admin\ProductCatalogCustomerController@deleteCustomerInfo')->name('delete_customer_info');

                Route::post('customer-info-up/{id}', 'Admin\ProductCatalogCustomerController@customerInfoUp')->name('customer_info_up');
                Route::post('customer-info-down/{id}', 'Admin\ProductCatalogCustomerController@customerInfoDown')->name('customer_info_down');

                // catalog- delivery info
                Route::get('delivery-info', 'Admin\ProductCatalogDeliveryController@index')->name('delivery_info_index');
                Route::get('get-delivery-info/all', 'Admin\ProductCatalogDeliveryController@getDeliveryInfoAll')->name('get_delivery_info_all');
                Route::get('delivery-info/{id}', 'Admin\ProductCatalogDeliveryController@getDeliveryInfo')->name('get_delivery_info_id');
                Route::get('add-delivery-info', 'Admin\ProductCatalogDeliveryController@addDeliveryInfo')->name('add_delivery_info');
                Route::post('add-delivery-info/post', 'Admin\ProductCatalogDeliveryController@addDeliveryInfoPost')->name('store_delivery_info');
                Route::post('edit-delivery-info/post', 'Admin\ProductCatalogDeliveryController@editDeliveryInfo')->name('edit_delivery_info');
                Route::get('delete-delivery-info/{id}', 'Admin\ProductCatalogDeliveryController@deleteDeliveryInfo')->name('delete_delivery_info');

                Route::post('delivery-info-up/{id}', 'Admin\ProductCatalogDeliveryController@deliveryInfoUp')->name('delivery_info_up');
                Route::post('delivery-info-down/{id}', 'Admin\ProductCatalogDeliveryController@deliveryInfoDown')->name('delivery_info_down');

                // catalog- loyalty info
                Route::get('loyality-info', 'Admin\ProductCatalogLoyalityController@index')->name('loyality_info_index');
                Route::get('get-loyality-info/all', 'Admin\ProductCatalogLoyalityController@getLoyalityInfoAll')->name('get_loyality_info_all');
                Route::get('loyality-info/{id}', 'Admin\ProductCatalogLoyalityController@getLoyalityInfo')->name('get_loyality_info_id');
                Route::get('add-loyality-info', 'Admin\ProductCatalogLoyalityController@addLoyalityInfo')->name('add_loyality_info');
                Route::post('add-loyality-info/post', 'Admin\ProductCatalogLoyalityController@addLoyalityInfoPost')->name('store_loyality_info');
                Route::post('edit-loyality-info/post', 'Admin\ProductCatalogLoyalityController@editLoyalityInfo')->name('edit_loyality_info');
                Route::get('delete-loyality-info/{id}', 'Admin\ProductCatalogLoyalityController@deleteLoyalityInfo')->name('delete_loyality_info');

                Route::post('loyality-info-up/{id}', 'Admin\ProductCatalogLoyalityController@loyalityInfoUp')->name('loyality_info_up');
                Route::post('loyality-info-down/{id}', 'Admin\ProductCatalogLoyalityController@loyalityInfoDown')->name('loyality_info_down');

                // catalog- discount info
                Route::get('discount-info', 'Admin\ProductCatalogDiscountController@index')->name('discount_info_index');
                Route::get('get-discount-info/all', 'Admin\ProductCatalogDiscountController@getDiscountInfoAll')->name('get_discount_info_all');
                Route::get('discount-info/{id}', 'Admin\ProductCatalogDiscountController@getDiscountInfo')->name('get_discount_info_id');
                Route::get('add-discount-info', 'Admin\ProductCatalogDiscountController@addDiscountInfo')->name('add_discount_info');
                Route::post('add-discount-info/post', 'Admin\ProductCatalogDiscountController@addDiscountInfoPost')->name('store_discount_info');
                Route::post('edit-discount-info/post', 'Admin\ProductCatalogDiscountController@editDiscountInfo')->name('edit_discount_info');
                Route::get('delete-discount-info/{id}', 'Admin\ProductCatalogDiscountController@deleteDiscountInfo')->name('delete_discount_info');

                // catalog- promotion info
                    Route::get('promotion-info', 'Admin\PromotionController@index')->name('promotion_info_index');
                    Route::get('get-promotion-info/all', 'Admin\PromotionController@getPromotionInfoAll')->name('get_promotion_info_all');
                    Route::get('promotion-info/{id}', 'Admin\PromotionController@getPromotionInfo')->name('get_promotion_info_id');
                    Route::get('add-promotion-info', 'Admin\PromotionController@addPromotionInfo')->name('add_promotion_info');
                    Route::post('add-promotion-info/post', 'Admin\PromotionController@addPromotionInfoPost')->name('store_promotion_info');
                    Route::post('edit-promotion-info/post', 'Admin\PromotionController@editPromotionInfo')->name('edit_promotion_info');
                    Route::get('delete-promotion-info/{id}', 'Admin\PromotionController@deletePromotionInfo')->name('delete_promotion_info');

                // catalog- categories info
                Route::get('categories', 'Admin\ProductCatalogCategoriesController@index')->name('categories_info_index');
                Route::get('category-list', 'Admin\ProductCatalogCategoriesController@categoryListBuilder')->name('get_category_list');
                Route::post('add-category/post', 'Admin\ProductCatalogCategoriesController@addCategoryPost')->name('store_category');
                Route::get('categories-info/{id}', 'Admin\ProductCatalogCategoriesController@getCategoriesInfo')->name('get_categories_info_id');
                Route::post('edit-categories-info/post', 'Admin\ProductCatalogCategoriesController@editCategoriesInfo')->name('edit_categories_info');
                Route::get('delete-category/{id}', 'Admin\ProductCatalogCategoriesController@deleteCategory')->name('delete_categories_info');
                Route::post('category-info-up/{id}', 'Admin\ProductCatalogCategoriesController@categoryInfoUp')->name('category_info_up');
                Route::post('category-info-down/{id}', 'Admin\ProductCatalogCategoriesController@categoryInfoDown')->name('category_info_down');

                // catalog- settings
                Route::get('settings', 'Admin\ProductCatalogSettingsController@index')->name('catalog_settings_index');
                Route::get('get-settings', 'Admin\ProductCatalogSettingsController@getSettings')->name('get_catalog_settings');
                Route::post('change-settings', 'Admin\ProductCatalogSettingsController@changeSettings')->name('change_catalog_settings');
            });
            });


         Route::group(['middleware'=>['permission:nustatymai']],function() {
             //settings
             Route::get('settings', 'Admin\SettingsController@index')->name('settings_index');
             Route::get('get-settings', 'Admin\SettingsController@getSettings')->name('get_settings');
             Route::post('change-settings', 'Admin\SettingsController@changeSettings')->name('change_settings');
         });
    });
});
