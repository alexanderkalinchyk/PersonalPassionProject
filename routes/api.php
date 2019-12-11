<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    //get current user data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //fetch api data
    Route::get('businesses/{location}/{radius}/{categories}/{offset}', 'Businesses\BusinessController@showbusinesses');
    Route::get('businesses/{id}', 'Businesses\BusinessController@getbusinessbyid');


    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::patch('settings/preferences/updateLocation/{location}', 'Settings\PreferenceController@updatelocation');
    Route::patch('settings/preferences/updateRadius/{radius}', 'Settings\PreferenceController@updateradius');
    Route::post('settings/preferences/update/{name}', 'Settings\PreferenceController@updatecategory');
    Route::post('settings/preferences/delete/{name}', 'Settings\PreferenceController@deletecategory');
    Route::get('settings/preferences/get', 'Settings\PreferenceController@getpreferences');

    Route::post('favorites', 'Favorites\FavoriteController@store');
    Route::get('favorites', 'Favorites\FavoriteController@show');
    Route::get('favorites/{id}', 'Favorites\FavoriteController@showbyuserid');
    Route::post('favorites/delete/{id}', 'Favorites\FavoriteController@destroy');

    Route::post('offset/{id}', 'Offset\OffsetController@updatebyuserid');
    Route::get('offset/get/{id}', 'Offset\OffsetController@offsetbyuserid');

    Route::post('sms/notification', 'Notifications\NotificationController@sendnotification');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});