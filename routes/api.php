<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    // All Authenticate Route
Route::group(['namespace'=>'Api','middleware'=>'Auth:api'], function (){

    // Get user information
    Route::get('user','AuthController@user');
    Route::post('update-user','AuthController@updateUser');
    Route::post('update-password','AuthController@updatePassword');

    // Brand Route
    Route::get('brand','BrandController@index');
    Route::post('brand','BrandController@store');
    Route::post('brand/{brand}','BrandController@update');
    Route::post('brand-delete/{brand}','BrandController@destroy');

    // Category Route
    Route::get('category','CategoryController@index');
    Route::post('category','CategoryController@store');
    Route::post('category/{category}','CategoryController@update');
    Route::post('category-delete/{category}','CategoryController@destroy');

    // Sub Category Route
    Route::get('sub-category','SubCategoryController@index');
    Route::post('sub-category','SubCategoryController@store');
    Route::post('sub-category/{sub_category}','SubCategoryController@update');
    Route::post('sub-category-delete/{sub_category}','SubCategoryController@destroy');

    // Shipping Address Route
    Route::get('shipping-address','ShippingAddressController@index');
    Route::post('shipping-address','ShippingAddressController@store');
    Route::post('shipping-address/{shipping_address}','ShippingAddressController@update');
    Route::post('shipping-address-delete/{shipping_address}','ShippingAddressController@destroy');
    Route::post('shipping-address-default/{shipping_address}','ShippingAddressController@setDefault');

    // Shipping Cost Route
    Route::get('shipping-cost','ShippingCostController@index');
    Route::get('get/shipping-cost','ShippingCostController@getShippingCost');
    Route::post('shipping-cost','ShippingCostController@store');
    Route::post('shipping-cost/{shipping_cost}','ShippingCostController@update');
    Route::post('shipping-cost-delete/{shipping_cost}','ShippingCostController@destroy');

    // Coupon Route
    Route::get('coupon','CouponController@index');
    Route::post('coupon','CouponController@store');
    Route::post('coupon/{coupon}','CouponController@update');
    Route::post('coupon-delete/{coupon}','CouponController@destroy');
    Route::post('cart/coupon', 'CouponController@coupon');

    // All Store Route
    Route::get('store','StoreController@index');
    Route::post('store/create','StoreController@create');
    Route::post('store/update','StoreController@update');

    // Product Route
    Route::get('product','ProductController@index');
    Route::get('product/create','ProductController@create');
    Route::get('product/{product}/edit','ProductController@edit');
    Route::get('get-image/product', 'ProductController@getImage');
    Route::post('product','ProductController@store');
    Route::post('product-update/{product}','ProductController@update');
    Route::post('product-delete/{product}','ProductController@destroy');
    Route::post('product/getSubCategory/{id}','ProductController@getSubCategory');
    Route::post('product/image', 'ProductController@productImage');
    Route::post('product/edit-image','ProductController@editProductImage');
    Route::post('product/get-edit-image/{id}', 'ProductController@getEditImage');
    Route::post('product/image-delete/{id}', 'ProductController@productImageDelete');

    // product Question and Answer route
    Route::post('product/qna', 'QnaController@postQna');
    Route::post('product/qna/replay', 'QnaController@postQnaReplay');
    Route::post('product/{id}/qna', 'QnaController@deleteQna');
    Route::post('product/{id}/qna-replay', 'QnaController@deleteQnaReplay');

    // product Review route
    Route::post('product/review', 'ReviewController@postReview');
    Route::post('product/review/replay', 'ReviewController@postReviewReplay');
    Route::post('product/{id}/review', 'ReviewController@deleteReview');
    Route::post('product/{id}/review-replay', 'ReviewController@deleteReviewReplay');

    // Product Order Route
    Route::get('order', 'OrderController@index');
    Route::post('order', 'OrderController@create');
    Route::get('get-order', 'OrderController@get');
    Route::get('order/{id}', 'OrderController@order');

    // Currency Controller
    Route::post('currency', 'CurrencyController@store');
    Route::post('currency-update/{currency}', 'CurrencyController@update');
    Route::post('currency-delete/{currency}', 'CurrencyController@destroy');
    Route::post('currency-default/{currency}', 'CurrencyController@setDefault');

    // Site Setting Controller
    Route::get('setting','SettingController@index');
    Route::post('about','SettingController@about');
    Route::post('about-member','SettingController@aboutMember');
    Route::post('about-member-delete/{about_member}','SettingController@aboutMemberDelete');
    Route::post('contact-us','SettingController@contactUs');
    Route::post('faq','SettingController@faq');
    Route::post('terms','SettingController@terms');
});


    // All Public Route
Route::group(['namespace'=>'Api'], function (){

    // Currency Route
    Route::get('currency', 'CurrencyController@index');

    // Product Related Route
    Route::get('{slug}/product', 'ProductController@getProduct');
    Route::get('{slug}/random-product', 'ProductController@randomProduct');
    Route::post('product/view-count/{product}', 'ProductController@viewCount');
    Route::get('product/{id}/qna', 'QnaController@getQna');
    Route::get('product/{id}/review', 'ReviewController@getReview');

    // Additional Page
    Route::get('about','SettingController@getAbout');
    Route::get('contact-us','SettingController@getContactUs');
    Route::get('faq','SettingController@getFaq');
    Route::get('terms','SettingController@getTerms');

    // Category Related Route
    Route::get('all-categories','CategoryController@allCategories');

});
