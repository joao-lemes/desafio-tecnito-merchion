<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Middleware\JwtMiddleware;

Route::prefix('users')->controller(UserController::class)->name('user.')->group(function () {
    Route::post('/', 'storeAction')->name('store');
    Route::middleware(JwtMiddleware::class)->group(function (): void {
        Route::get('/', 'listAction')->name('list');
        Route::get('/{id}', 'getAction')->name('get');
        Route::put('/{id}', 'updateAction')->name('update');
        Route::delete('/{id}', 'deleteAction')->name('delete');
    });
});

Route::controller(AuthController::class)->name('auth.')->group(function (): void {
    Route::post('/login', 'loginAction')->name('login');
    Route::post('/logout', 'logoutAction')->name('logout')->middleware(JwtMiddleware::class);
});
