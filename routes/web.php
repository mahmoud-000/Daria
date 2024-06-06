<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::any('{any?}', ApplicationController::class)->where('any', '^(?!api|setup).*')->name('dashboard');
