<?php

use Illuminate\Support\Facades\Route;
use Nksoft\Master\Controllers\WebController;

Route::group(['middleware' => 'web'], function () {
    Route::get('login', function () {
        return view('master::modules.users.login');
    });
    Route::post('login', '\Nksoft\Master\Controllers\UsersController@login');
    Route::group(['middleware' => 'nksoft', 'prefix' => 'admin'], function () {
        Route::resources([
            '/' => WebController::class,
            'dashboard' => WebController::class,
            'users' => WebController::class,
            'settings' => WebController::class,
            'navigations' => WebController::class,
            'roles' => WebController::class,
        ]);
    });
});
