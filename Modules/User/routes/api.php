<?php

use Modules\User\Http\Controllers\UserBulkDestroy;
use Modules\User\Http\Controllers\UserDestroy;
use Modules\User\Http\Controllers\UserImportCsv;
use Modules\User\Http\Controllers\UserShow;
use Modules\User\Http\Controllers\UsersList;
use Modules\User\Http\Controllers\UserStore;
use Modules\User\Http\Controllers\UserUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/users', UsersList::class)->name('users.index');
    Route::post('/users', UserStore::class)->name('users.store');
    Route::put('/users/{user}', UserUpdate::class)->name('users.update');
    Route::get('/users/{user}', UserShow::class)->name('users.show');
    Route::delete('/users/{user}', UserDestroy::class)->name('users.destroy');
    Route::post('/users/bulk_destroy', UserBulkDestroy::class)->name('users.bulk_destroy');
    Route::post('/users/import_csv', UserImportCsv::class)->name('users.import_csv');
});
