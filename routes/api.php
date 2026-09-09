<?php

use App\Http\Controllers\BsiController;
use Illuminate\Support\Facades\Route;

Route::prefix('bsi')->controller(BsiController::class)->group(function () {
    Route::post('/open/auth', 'authOpen')->name('bsi.open.auth');
    Route::post('/open/inquiry', 'inquiryOpen')->name('bsi.open.inquiry');
    Route::post('/open/payment', 'paymentOpen')->name('bsi.open.payment');

    Route::post('/close/auth', 'authClose')->name('bsi.close.auth');
    Route::post('/close/inquiry', 'inquiryClose')->name('bsi.close.inquiry');
    Route::post('/close/payment', 'paymentClose')->name('bsi.close.payment');
});
