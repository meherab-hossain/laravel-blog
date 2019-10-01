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

Route::get('/', 'HomeController@index')->name('home');

Route::post('subscriber','SubscriberController@store')->name('subscriber.store');

Auth::routes();

Route::group(['middleware'=>['auth']], function (){
    Route::post('favourite/{post}/add','FavouriteController@add')->name('post.favourite');
});

//admin route
Route::group(['as'=>'admin.','prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin']], function (){
    Route::get('dashboard','DashboardController@index')->name('dashboard');
    Route::resource('tag','TagController');
    Route::resource('category','CategoryController');
    Route::resource('post','PostController');
    Route::get('/pending/post','PostController@pending')->name('post.pending');
    Route::put('/post/{id}/approve','PostController@approval')->name('post.approve');

    //admin profile settings router
    Route::get('settings','SettingsController@index')->name('settings.index');
    Route::put('profile-update','SettingsController@updateProfile')->name('profile.update');
    Route::put('password-update','SettingsController@updatePassword')->name('password.update');

    //Admin Subscriber Route
    Route::get('/subscriber/list','SubscriberController@index')->name('subscriber.index');
    Route::post('/subscriber/{subscriber}','SubscriberController@destroy')->name('subscriber.destroy');
});
//author route
Route::group(['as'=>'author.','prefix'=>'author','namespace'=>'Author','middleware'=>['auth','author']],function (){
    Route::get('dashboard','dashboardController@index')->name('dashboard');
    Route::resource('post','PostController');


    //author profile settings router
    Route::get('settings','SettingsController@index')->name('settings.index');
    Route::put('profile-update','SettingsController@updateProfile')->name('profile.update');
    Route::put('password-update','SettingsController@updatePassword')->name('password.update');

});
