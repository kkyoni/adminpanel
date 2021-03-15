<?php

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
*/
Route::namespace('Api\User')->group(function () {
    Route::group(['middleware' => ['cors']], function() {
        Route::post('login','UserController@login');
        Route::post('register','UserController@register');
        Route::post('sendOtp','UserController@sendOtp');
        Route::post('verifyOtp','UserController@verifyOtp');
    });

    /*------------- JWT TOKEN AUTHORIZED ROUTE-------------------*/
    Route::group(['middleware' => ['cors','jwt.verify']], function() {
        Route::get('getProfile','UserController@getProfile');
        Route::post('updateProfile','UserController@updateProfile');
        Route::post('changePassword','UserController@changePassword');
        Route::post('logout','UserController@logout');
    });
    /*-------------Without JWT TOKEN AUTHORIZED ROUTE-------------------*/
    });

    /*
    |--------------------------------------------------------------------------
    | COMMON API Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::namespace('Api')->group(function () {
        Route::group(['middleware' => ['cors','jwt.verify']], function() {

        });
        Route::post('forgotPassword','CommonController@forgotPassword');
        Route::post('resetPassword','CommonController@resetPassword');
        Route::post('updateToken','CommonController@updateToken');
        Route::get('CmsPage','CommonController@CmsPage');
    });
