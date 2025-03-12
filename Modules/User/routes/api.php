<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::prefix('users')->controller(UserController::class)->name('user.')->group(function () {
    Route::get('/', 'listAction')->name('list');
    Route::post('/', 'storeAction')->name('store');
});
