<?php

use Illuminate\Support\Facades\Route;

/** for side bar menu active */
function set_active($route) {
    if (is_array($route )){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('authentication.login');
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    Route::controller(AuthenticationController::class)->group(function () {
        // ---------------------login----------------------//
        Route::get('login', 'login')->name('login');
        Route::post('login/account', 'loginAccount')->name('login/account');
        // ---------------------register--------------------//
        Route::get('register', 'register')->name('register');
        Route::post('register/account', 'registerAccount')->name('register/account');
        // ---------------------logout----------------------//
        Route::get('logout', 'logoutAccount')->name('logout');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // -------------------------- main dashboard ----------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
    });
});
