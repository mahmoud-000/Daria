<?php

use Modules\Auth\Http\Controllers\LoginAction;
use Modules\Auth\Http\Controllers\LogoutAction;
use Modules\Auth\Http\Controllers\AuthPermissions;
use Modules\Auth\Http\Controllers\ChangePasswordAction;
use Modules\Auth\Http\Controllers\CustomerProfileShow;
use Modules\Auth\Http\Controllers\CustomerProfileUpdate;
use Modules\Auth\Http\Controllers\ForgetPasswordAction;
use Modules\Auth\Http\Controllers\ResetPasswordAction;
use Modules\Auth\Http\Controllers\UserProfileShow;
use Modules\Auth\Http\Controllers\UserProfileUpdate;

Route::prefix('v1/auth')
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::post('/login', LoginAction::class)->name('auth.login');
            Route::post('/forget_password', ForgetPasswordAction::class)->name('auth.forget_password');
            Route::post('/reset_password', ResetPasswordAction::class)->name('auth.reset_password');
            // Route::get('/verifaction_email', [AuthController::class, 'verifactionEmail'])->name('verification.verify');
        });
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', LogoutAction::class)->name('auth.logout');
            Route::post('/change_password', ChangePasswordAction::class)->name('auth.change_password');
            Route::get('/profile/user', UserProfileShow::class)->name('profile.user.get');
            Route::get('/profile/customer', CustomerProfileShow::class)->name('profile.customer.get');
            Route::post('/profile/user', UserProfileUpdate::class)->name('profile.user.update');
            Route::post('/profile/customer', CustomerProfileUpdate::class)->name('profile.customer.update');
            Route::get('/permissions', AuthPermissions::class)->name('auth.permissions');
        });
    });
