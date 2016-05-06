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

Route::get('/auth/login', 'Auth\AuthController@login');
Route::post('/auth/validate_login', 'Auth\AuthController@validate_login');
Route::get('/auth/logout', 'Auth\AuthController@logout');
Route::post('/auth/signup', 'Auth\AuthController@signup');
Route::get('/auth/verify_account', 'Auth\AuthController@verify_account');
Route::match(['get', 'post'], '/auth/forgot_password', 'Auth\AuthController@forgot_password');
Route::get('/auth/verify_reset_password', 'Auth\AuthController@verify_reset_password');
Route::get('/auth/change_password', 'Auth\AuthController@change_password');
Route::post('/auth/update_password', 'Auth\AuthController@update_password');
Route::get('/', 'News\NewsController@index');
Route::get('/news/{year}/{month}/{date}/{user}/{title}/{token?}', 'News\NewsController@show');
Route::get('/pdf/news/{year}/{month}/{date}/{user}/{title}/{token?}', 'News\NewsController@pdf');

//all authenticated requests
Route::group(['middleware' => array('auth')], function() {
	Route::get('/dashboard', 'News\DashboardController@index');
	Route::get('/dashboard/news', 'News\NewsController@news_list');
	Route::post('/news/create', 'News\NewsController@create');
	Route::get('/news/delete/{id}', 'News\NewsController@delete');
});
