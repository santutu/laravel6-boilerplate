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

//verify email. see https://laravel.com/docs/6.x/verification, https://laravel.com/docs/6.x/mail
//Auth::routes(['verify' => true]);
//Route::get('/profile', 'ProfileController@show')->middleware('verified');

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('login')->group(function () {
    Route::get('{providerName}', 'SocialAccountController@redirectToProvider');
    Route::get('{providerName}/callback', 'SocialAccountController@handleProviderCallback');
//todo allow providerName data
});

Route::get('/test', 'TestController@test');
Route::get('/test2', 'TestController@test2');


Route::get('/home', 'HomeController@index')->name('home');

