<?php

use Modules\Quotation\Http\Controllers\QuotationBulkDestroy;
use Modules\Quotation\Http\Controllers\QuotationDestroy;
use Modules\Quotation\Http\Controllers\QuotationImportCsv;
use Modules\Quotation\Http\Controllers\QuotationShow;
use Modules\Quotation\Http\Controllers\QuotationsList;
use Modules\Quotation\Http\Controllers\QuotationStore;
use Modules\Quotation\Http\Controllers\QuotationUpdate;
use Modules\Quotation\Http\Controllers\QuotationFormOptions;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/quotations/form_options', QuotationFormOptions::class)->name('quotations.form_options');
    Route::get('/quotations', QuotationsList::class)->name('quotations.index');
    Route::post('/quotations', QuotationStore::class)->name('quotations.store');
    Route::put('/quotations/{quotation}', QuotationUpdate::class)->name('quotations.update');
    Route::get('/quotations/{quotation}', QuotationShow::class)->name('quotations.show');
    Route::delete('/quotations/{quotation}', QuotationDestroy::class)->name('quotations.destroy');
    Route::post('/quotations/bulk_destroy', QuotationBulkDestroy::class)->name('quotations.bulk_destroy');
    Route::post('/quotations/import_csv', QuotationImportCsv::class)->name('quotations.import_csv');
});
