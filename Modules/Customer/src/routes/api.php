<?php

use Customer\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/customers/','middleware' => ['api']], function () {

    Route::get('/index', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');

    Route::patch('/update-firstname', [CustomerController::class, 'update'])->name('customers.update-firstname');
    Route::patch('/update-lastname', [CustomerController::class, 'update'])->name('customers.update-lastname');
    Route::patch('/update-phoneNumber', [CustomerController::class, 'update'])->name('customers.update-phoneNumber');
    Route::patch('/update-email', [CustomerController::class, 'update'])->name('customers.update-email');
    Route::patch('/update-dateOfBirth', [CustomerController::class, 'update'])->name('customers.update-dateOfBirth');
    Route::patch('/update-bankAccountNumber', [CustomerController::class, 'update'])->name('customers.update-bankAccountNumber');

    Route::delete('/destroy', [CustomerController::class, 'destroy'])->name('customers.destroy');

});
