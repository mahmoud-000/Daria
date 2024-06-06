<?php

use Modules\Company\Http\Controllers\CompanyBulkDestroy;
use Modules\Company\Http\Controllers\CompanyDestroy;
use Modules\Company\Http\Controllers\CompanyImportCsv;
use Modules\Company\Http\Controllers\CompanyOptions;
use Modules\Company\Http\Controllers\CompanyShow;
use Modules\Company\Http\Controllers\CompaniesList;
use Modules\Company\Http\Controllers\CompanyStore;
use Modules\Company\Http\Controllers\CompanyUpdate;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/companies/options', CompanyOptions::class)->name('companies.options');
    Route::get('/companies', CompaniesList::class)->name('companies.index');
    Route::post('/companies', CompanyStore::class)->name('companies.store');
    Route::put('/companies/{company}', CompanyUpdate::class)->name('companies.update');
    Route::get('/companies/{company}', CompanyShow::class)->name('companies.show');
    Route::delete('/companies/{company}', CompanyDestroy::class)->name('companies.destroy');
    Route::post('/companies/bulk_destroy', CompanyBulkDestroy::class)->name('companies.bulk_destroy');
    Route::post('/companies/import_csv', CompanyImportCsv::class)->name('companies.import_csv');
});
