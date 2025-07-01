<?php

use Modules\Customer\Http\Controllers\CustomerBulkDestroy;
use Modules\Customer\Http\Controllers\CustomerDestroy;
use Modules\Customer\Http\Controllers\CustomerImportCsv;
use Modules\Customer\Http\Controllers\CustomerOptions;
use Modules\Customer\Http\Controllers\CustomerRegister;
use Modules\Customer\Http\Controllers\CustomerShow;
use Modules\Customer\Http\Controllers\CustomersList;
use Modules\Customer\Http\Controllers\CustomerStore;
use Modules\Customer\Http\Controllers\CustomerUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/customers/options', CustomerOptions::class)->name('customers.options');
    Route::get('/customers', CustomersList::class)->name('customers.index');
    Route::post('/customers', CustomerStore::class)->name('customers.store');
    Route::put('/customers/{customer}', CustomerUpdate::class)->name('customers.update');
    Route::get('/customers/{customer}', CustomerShow::class)->name('customers.show');
    Route::delete('/customers/{customer}', CustomerDestroy::class)->name('customers.destroy');
    Route::post('/customers/bulk_destroy', CustomerBulkDestroy::class)->name('customers.bulk_destroy');
    Route::post('/customers/import_csv', CustomerImportCsv::class)->name('customers.import_csv');
});

Route::middleware('guest')->prefix('v1')->group(function () {
    Route::post('/customers/register', CustomerRegister::class)->name('customers.register');
});
