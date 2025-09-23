<?php

use App\Http\Controllers\payments\MidtransController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/payments-confirm/midtrans', MidtransController::class)->name('payments.confirm.midtrans');
});
