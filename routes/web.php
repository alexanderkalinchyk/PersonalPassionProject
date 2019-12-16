<?php

/*
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');

Route::post("/reply", "Reply\ReplyController@commandHandler");
