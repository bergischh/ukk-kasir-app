<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;

Route::middleware('guest')->group(function() {
    Route::get('/', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login/submit', [UserController::class, 'submitLogin'])->name('login.submit');
});
// Route::get('/', function () {
//     return view('login');
// })->name('login');


Route::middleware('auth')->group(function() {
    
    Route::get('/transaction/struk', function() {
        return view('admin.pembelian.penjualan.struk');
    })->name('checkout.success');


    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::prefix('/dashboard')->name('dashboard.')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });


Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::patch('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('delete');
});

Route::controller(ProdukController::class)->prefix('product')->name('product.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::patch('/edit-stock/{id}', 'updateStock')->name('updateStock');
    Route::patch('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('delete');
});

Route::controller(PenjualanController::class)->prefix('penjualan')->name('purchase.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/menu', 'menuShow')->name('menu');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::patch('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('delete');
});

Route::controller(TransaksiController::class)->prefix('transaction')->name('transaction.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::post('/checkout/store', 'store')->name('checkout.store');
    Route::post('/update-cart', 'cart')->name('cart');

    Route::post('/member/store-session', 'storeSession')->name('member.storeSession');
    Route::post('/member/checkout', 'storeSession')->name('member.checkout');
});

});



