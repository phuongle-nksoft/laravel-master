<?php

use Nksoft\Master\Controllers\ContactsControllers;
use Nksoft\Master\Controllers\RecruitControllers;
use Nksoft\Master\Controllers\RolesController;
use Nksoft\Master\Controllers\SettingsController;
use Nksoft\Master\Controllers\UsersController;
use Nksoft\Master\Controllers\WebController;

Route::group(['prefix' => 'api/admin', 'middleware' => 'web'], function () {
    Route::delete('media/{id}', '\Nksoft\Master\Controllers\WebController@destroyUploadFile');
    Route::post('media/destroy', '\Nksoft\Master\Controllers\WebController@destroyImage');
    Route::resources([
        '/' => UsersController::class,
        'users' => UsersController::class,
        'roles' => RolesController::class,
        'media' => WebController::class,
        'settings' => SettingsController::class,
        'recruits' => RecruitControllers::class,
        'contacts' => ContactsControllers::class,
    ]);

});
