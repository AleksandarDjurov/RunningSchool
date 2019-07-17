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

Route::pattern('slug', '[a-z0-9- _]+');

//Route::group([ 'namespace'=>'Admin'], function () {

    # All basic routes defined here   
    Route::get('login', 'AuthController@getSignin')->name('login');
    Route::get('signin', 'AuthController@getSignin')->name('signin');
    Route::post('signin', 'AuthController@postSignin')->name('postSignin');
    Route::post('signup', 'AuthController@postSignup')->name('signup');
    Route::post('forgot-password', 'AuthController@postForgotPassword')->name('signup');


    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm')->name('forgot-password-confirm');
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm');

    # Logout
    Route::get('logout', 'AuthController@getLogout')->name('logout');

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate')->name('activate');

    #get country, state, region, province, city
    Route::any('state', 'UtilController@State_list')->name('state');
    Route::any('region', 'UtilController@Region_list')->name('region');
    Route::any('province', 'UtilController@Province_list')->name('province');
    Route::any('city', 'UtilController@City_list')->name('city');
    Route::any('front', 'UtilController@Front')->name('front');
    Route::any('back', 'UtilController@BackendDemo')->name('backdemo');
//});

//Route::get('/', 'JoshController@showHome_ang')->name('frontend');
Route::group(['middleware' => 'angular'], function () {
    Route::any('/pages', 'JoshController@pages');
});

Route::group(['namespace'=>'Admin','middleware' => 'admin'], function () {         
    # school Management
    Route::group([ 'prefix' => 'schools'], function () {
        Route::get('data', 'SchoolController@data')->name('schools.data');
        Route::get('{user}/delete', 'SchoolController@destroy')->name('schools.delete');
        Route::get('{user}/confirm-delete', 'SchoolController@getModalDelete')->name('schools.confirm-delete');
        Route::get('{user}/restore', 'SchoolController@getRestore')->name('restore.school');
        Route::post('{user}/passwordreset', 'SchoolController@passwordreset')->name('passwordreset');
        Route::post('technical', 'SchoolController@addTechnical')->name('schools.technical');
        Route::post('technical/{user}', 'SchoolController@updateTechnical');
    });
    Route::resource('schools', 'SchoolController');
    Route::get('deleted_schools',['before' => 'Sentinel', 'uses' => 'SchoolController@getDeletedSchools'])->name('deleted_schools');

    # trainer Management
    Route::group([ 'prefix' => 'trainer'], function () {
        Route::get('data', 'TrainerController@data')->name('trainer.data');
        Route::get('{user}/delete', 'TrainerController@destroy')->name('trainer.delete');
        Route::get('{user}/confirm-delete', 'TrainerController@getModalDelete')->name('trainer.confirm-delete');
        Route::get('{user}/restore', 'TrainerController@getRestore')->name('restore.trainer');
        Route::post('{user}/passwordreset', 'TrainerController@passwordreset')->name('passwordreset');

    });
    Route::resource('trainer', 'TrainerController');
    Route::get('deleted_trainer',['before' => 'Sentinel', 'uses' => 'TrainerController@getDeletedTrainer'])->name('deleted_trainer');


    # User Management
    Route::group([ 'prefix' => 'users'], function () {
        Route::get('data', 'UsersController@data')->name('users.data');
        Route::get('{user}/delete', 'UsersController@destroy')->name('users.delete');
        Route::get('{user}/confirm-delete', 'UsersController@getModalDelete')->name('users.confirm-delete');
        Route::get('{user}/restore', 'UsersController@getRestore')->name('restore.user');
        Route::post('{user}/passwordreset', 'UsersController@passwordreset')->name('passwordreset');
    });
    Route::resource('users', 'UsersController');

    Route::get('deleted_users',['before' => 'Sentinel', 'uses' => 'UsersController@getDeletedUsers'])->name('deleted_users');

    # Group Management
    Route::group(['prefix' => 'groups'], function () {
        Route::get('{group}/delete', 'GroupsController@destroy')->name('groups.delete');
        Route::get('{group}/confirm-delete', 'GroupsController@getModalDelete')->name('groups.confirm-delete');
        Route::get('{group}/restore', 'GroupsController@getRestore')->name('groups.restore');
    });
    Route::resource('groups', 'GroupsController');   
    # contact usl management
    Route::resource('contact', 'ContactController');

    #mail template function
    Route::any('/mailtemplate', 'MailTemplateController@index');
    Route::any('/mailtemplate/{tempid}/edit', 'MailTemplateController@edit');
    Route::any('/mailtemplate/update', 'MailTemplateController@update');
    Route::any('/mailtemplate/new', 'MailTemplateController@create');
    Route::any('/mailtemplate/save', 'MailTemplateController@store');

    # package Management
    Route::group([ 'prefix' => 'packages'], function () {
        Route::get('data', 'PackageController@data')->name('packages.data');
        Route::get('{user}/delete', 'PackageController@destroy')->name('packages.delete');
        Route::get('{user}/confirm-delete', 'PackageController@getModalDelete')->name('packages.confirm-delete');
        Route::get('{user}/restore', 'PackageController@getRestore')->name('packages.index');
    });
    
});

Route::group(['middleware' => 'admin'], function () {   
   
    # Dashboard / Index
    Route::get('/', 'JoshController@showHome')->name('dashboard');

    # Lock screen
    Route::get('{id}/lockscreen', 'UsersController@lockscreen')->name('lockscreen');
    Route::post('{id}/lockscreen', 'UsersController@postLockscreen')->name('lockscreen');
  
    Route::get('{name?}', 'JoshController@showView');
});

# Remaining pages will be called from below controller method
# in real world scenario, you may be required to define all routes manually

Route::get('activate/{userId}/{activationCode}','FrontEndController@getActivate')->name('activate');
Route::get('forgot-password','FrontEndController@getForgotPassword')->name('forgot-password');
Route::post('forgot-password', 'FrontEndController@postForgotPassword');

# Forgot Password Confirmation
Route::post('forgot-password/{userId}/{passwordResetCode}', 'FrontEndController@postForgotPasswordConfirm');
Route::get('forgot-password/{userId}/{passwordResetCode}', 'FrontEndController@getForgotPasswordConfirm')->name('forgot-password-confirm');
Route::fallback(function () {
    return view('frontend');
});


