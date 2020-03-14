<?php

use Illuminate\Support\Facades\Route;
use Nksoft\Master\Controllers\UsersController;

Route::group(['middleware' => 'web'], function () {
    Route::get('login', function () {
        return view('master::modules.users.login');
    });
    Route::post('login', '\Nksoft\Master\Controllers\UsersController@login');
    Route::group(['middleware' => 'nksoft', 'prefix' => 'admin'], function () {
        Route::resources([
            'users' => UsersController::class
        ]);
    });
});
Route::get('admin', function () {
    return view('master::modules.dashboard.index');
});
