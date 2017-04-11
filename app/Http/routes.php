<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::any('fx','IndexController@index');
Route::any('weibo','TestController@index');

Route::any('/login','UserController@login');
Route::any('/center','UserController@center');
Route::any('/logout','UserController@logout');

Route::get('/','GoodsController@index');
Route::get('/insert','GoodsController@insert');
Route::get('/goods/{gid}','GoodsController@goods');
Route::any('cart/{gid}','GoodsController@cart');
Route::any('buy','GoodsController@buy');
Route::any('clear','GoodsController@clear');

Route::any('done','GoodsController@done');
Route::any('pay','GoodsController@pay');


