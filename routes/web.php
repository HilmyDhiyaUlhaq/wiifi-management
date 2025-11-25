<?php

use App\Http\Controllers\Auths\AuthController;
use App\Http\Controllers\Class\ClassController;
use App\Http\Controllers\DashboarController;
use App\Http\Controllers\Packages\PackageController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\payments\MidtransController;
use App\Http\Controllers\Transactions\TransactionUserClassController;
use App\Http\Controllers\Transactions\TransactionUserPackageController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserSettingController;
use App\Http\Controllers\Users\UserTransactionController;
use App\Http\Controllers\Users\UserWifiAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// User
Route::middleware('auth')->group(function () {
    Route::get('/', DashboarController::class)->name('dashboard');
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/edit/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/exports', [UserController::class, 'export'])->name('users.export');
        Route::get('/setting', [UserSettingController::class, 'edit'])->name('users.setting.edit');
        Route::patch('/setting', [UserSettingController::class, 'update'])->name('users.setting.update');

        Route::prefix('{userId}/wifi-account')->group(function () {
            Route::get('/', [UserWifiAccountController::class, 'index'])->name('users.wifis.accounts.index');
            Route::get('/create', [UserWifiAccountController::class, 'create'])->name('users.wifis.accounts.create');
            Route::post('/create', [UserWifiAccountController::class, 'store'])->name('users.wifis.accounts.store');
            Route::delete('/delete/{id}', [UserWifiAccountController::class, 'destroy'])->name('users.wifis.accounts.destroy');
            Route::post('/sync/{id}', [UserWifiAccountController::class, 'sync'])->name('users.wifis.accounts.sync');
        });

        Route::prefix('{userId}/transaction')->group(function () {
            Route::get('/', [UserTransactionController::class, 'index'])->name('users.transactions.index');
            Route::get('/create', [UserTransactionController::class, 'create'])->name('users.transactions.create');
            Route::post('/create', [UserTransactionController::class, 'store'])->name('users.transactions.store');
            Route::get('/edit/{id}', [UserTransactionController::class, 'edit'])->name('users.transactions.edit');
            Route::patch('/edit/{id}', [UserTransactionController::class, 'update'])->name('users.transactions.update');
            Route::delete('/delete/{id}', [UserTransactionController::class, 'destroy'])->name('users.transactions.destroy');
            Route::get('/export', [UserTransactionController::class, 'export'])->name('users.transactions.export');
        });
    });

    // Package
    Route::prefix('packages')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('packages.index');
        Route::get('/create', [PackageController::class, 'create'])->name('packages.create');
        Route::post('/create', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/edit/{id}', [PackageController::class, 'edit'])->name('packages.edit');
        Route::patch('/edit/{id}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/delete/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
        Route::get('/export', [PackageController::class, 'export'])->name('packages.export');
    });

    Route::prefix('/transactions')->group(function () {
        Route::get('/', [TransactionUserPackageController::class, 'index'])->name('transactions.index');
        Route::get('/create', [TransactionUserPackageController::class, 'create'])->name('transactions.create');
        Route::post('/create', [TransactionUserPackageController::class, 'store'])->name('transactions.store');
        Route::delete('/delete/{id}', [TransactionUserPackageController::class, 'destroy'])->name('transactions.destroy');
        Route::get('/edit/{id}', [TransactionUserPackageController::class, 'edit'])->name('transactions.edit');
        Route::patch('/edit/{id}', [TransactionUserPackageController::class, 'update'])->name('transactions.update');
        Route::get('/export', [TransactionUserPackageController::class, 'export'])->name('transactions.export');
    });
    Route::prefix('/transactions-class')->group(function () {
        Route::get('/', [TransactionUserClassController::class, 'index'])->name('transactions-class.index');
        Route::get('/create', [TransactionUserClassController::class, 'create'])->name('transactions-class.create');
        Route::post('/create', [TransactionUserClassController::class, 'store'])->name('transactions-class.store');
        Route::get('/edit/{id}', [TransactionUserClassController::class, 'edit'])->name('transactions-class.edit');
        Route::patch('/edit/{id}', [TransactionUserClassController::class, 'update'])->name('transactions-class.update');
        Route::delete('/delete/{id}', [TransactionUserClassController::class, 'destroy'])->name('transactions-class.destroy');
        Route::get('/export', [TransactionUserClassController::class, 'export'])->name('transactions-class.export');
    });

    Route::prefix('class')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('class.index');
        Route::get('/create', [ClassController::class, 'create'])->name('class.create');
        Route::post('/create', [ClassController::class, 'store'])->name('class.store');
        Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
        Route::patch('/edit/{id}', [ClassController::class, 'update'])->name('class.update');
        Route::delete('/delete/{id}', [ClassController::class, 'destroy'])->name('class.destroy');
        Route::get('/export', [ClassController::class, 'export'])->name('class.export');
    });

    Route::prefix('payments')->group(function () {
        Route::get('{id}', [PaymentController::class, 'show'])->name('payments.show');
    });
});



