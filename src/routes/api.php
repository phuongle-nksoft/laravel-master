<?php
use Nksoft\Master\Controllers\NavigationsController;
use Nksoft\Master\Controllers\RolesController;
use Nksoft\Master\Controllers\UsersController;

Route::group(['prefix' => 'api/admin', 'middleware' => 'api'], function () {
    Route::get('/', function () {
        return response()->json(['status' => 'success']);
    });
    Route::delete('media/:id', 'Nksoft\Master\Controllers\WebController@destroy');
    Route::resources([
        'users' => UsersController::class,
        'navigations' => NavigationsController::class,
        'roles' => RolesController::class,
    ]);
});
