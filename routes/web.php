<?php

use Illuminate\Support\Facades\Route;

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
// 首页、帮助页、关于页路由
// 3.3章
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// 注册页面路由
// 4.7章
Route::get('signup','UsersController@create')->name('signup');

// 用户资源路由
//Laravel 为我们提供了 resource 方法来定义用户资源路由。
//6.2章
Route::resource('users','UsersController');

// 会话路由
// 新增的路由分别对应会话控制器的三个动作：create, store, destroy。
// 7.2章
Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

//用户的激活令牌功能的路由。 9.2章
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

// 忘记密码时，重设密码路由。 9.3章
Route::get('password/reset',  'PasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email',  'PasswordController@sendResetLinkEmail')->name('password.email');

Route::get('password/reset/{token}',  'PasswordController@showResetForm')->name('password.reset');
Route::post('password/reset',  'PasswordController@reset')->name('password.update');

// 10.4章 微博创建和删除路由，使用resource传参only键指定
Route::resource('statuses','StatusesController',['only' => ['store','destroy']]);


// 11.4章 关注者列表和粉丝列表路由
Route::get('/users/{user}/followings','UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers','UsersController@followers')->name('users.followers');

