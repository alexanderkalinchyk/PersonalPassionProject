<?php

use Illuminate\Http\Request;

/*
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    //user moet ingelogd zijn om dit uit te voeren

    Route::post('logout', 'Auth\LoginController@logout');

    //get current user data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //fetch yelp api data
    Route::get('businesses/{location}/{radius}/{categories}/{offset}', 'Businesses\BusinessController@showbusinesses');
    //fetch restaurant details
    Route::get('businesses/{id}', 'Businesses\BusinessController@getbusinessbyid');

    //update profile
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    //update password
    Route::patch('settings/password', 'Settings\PasswordController@update');
    //update location
    Route::patch('settings/preferences/updateLocation/{location}', 'Settings\PreferenceController@updatelocation');
    //update radius
    Route::patch('settings/preferences/updateRadius/{radius}', 'Settings\PreferenceController@updateradius');
    //get radius
    Route::get('settings/preferences/getRadius', 'Settings\PreferenceController@getradius');
    //insert radius
    Route::post('settings/preferences/insertRadius/{radius}', 'Settings\PreferenceController@insertradius');
     //insert categories
    Route::post('settings/preferences/update/{name}', 'Settings\PreferenceController@updatecategory');
     //update categories (delete)
    Route::post('settings/preferences/delete/{name}', 'Settings\PreferenceController@deletecategory');
     //get all user preferences
    Route::get('settings/preferences/get', 'Settings\PreferenceController@getpreferences');

    //insert favorite restaurant into database
    Route::post('favorites', 'Favorites\FavoriteController@store');
    // get all favorite restaurants
    Route::get('favorites', 'Favorites\FavoriteController@show');
    //favorite restaurant details
    Route::get('favorites/{id}', 'Favorites\FavoriteController@showbyuserid');
    //delete favorite restaurant
    Route::post('favorites/delete/{id}', 'Favorites\FavoriteController@destroy');

    //update offset
    Route::post('offset/{id}', 'Offset\OffsetController@updatebyuserid');
     //get offset
    Route::get('offset/get/{id}', 'Offset\OffsetController@offsetbyuserid');

    //insert notification
    Route::post('sms/notification', 'Notifications\NotificationController@sendnotification');
    //get notification
    Route::get('sms/notification/get', 'Notifications\NotificationController@getnotifications');
});

Route::group(['middleware' => 'guest:api'], function () {
    //user moet niet ingelogd zijn om dit uit te voeren

    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});
