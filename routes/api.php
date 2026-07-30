<?php

use App\Http\Controllers\BsiController;
use Illuminate\Support\Facades\Route;

Route::prefix('bsi')->controller(BsiController::class)->group(function () {
    Route::post('/auth', 'auth')->name('bsi.auth');
    Route::post('/inquiry', 'inquiry')->name('bsi.inquiry');
    Route::post('/payment', 'payment')->name('bsi.payment');
});
