<?php

use Modules\Setup\Http\Controllers\ConfigureDatabase;
use Modules\Setup\Http\Controllers\SaveAccount;
use Modules\Setup\Http\Controllers\ShowAccountPage;
use Modules\Setup\Http\Controllers\ShowCompletePage;
use Modules\Setup\Http\Controllers\ShowDatabasePage;
use Modules\Setup\Http\Controllers\ShowRequirementsPage;
use Modules\Setup\Http\Controllers\ShowWelcomePage;

Route::prefix('setup')->group(function () {
    Route::get('/welcome', ShowWelcomePage::class)->name('setup.welcome');
    Route::get('/requirements', ShowRequirementsPage::class)->name('setup.requirements');
    Route::get('/database', ShowDatabasePage::class)->name('setup.database');
    Route::post('/database', ConfigureDatabase::class)->name('setup.configure-database');
    Route::get('/account', ShowAccountPage::class)->name('setup.account');
    Route::post('/account', SaveAccount::class)->name('setup.save-account');
    Route::get('/complete', ShowCompletePage::class)->name('setup.complete');
    Route::get('/', function () {
        return redirect('/');
    })->name('setup.dashboard');
});
