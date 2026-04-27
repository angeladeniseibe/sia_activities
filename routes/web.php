<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ElectricUsageController;
use App\Http\Controllers\ElectricBillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (ALL ROLES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */

    // PDF FIRST
    Route::get('/customers/pdf', [CustomerController::class, 'exportPDF'])
        ->name('customers.pdf');

    Route::resource('customers', CustomerController::class);

    // ✅ ADDED: Assign customer to user
    Route::post('/customers/{customer}/assign-user', [CustomerController::class, 'assignUser'])
        ->name('customers.assignUser');

    /*
    |--------------------------------------------------------------------------
    | ELECTRIC USAGES
    |--------------------------------------------------------------------------
    */

    // PDF FIRST
    Route::get('/usages/pdf', [ElectricUsageController::class, 'downloadPDF'])
        ->name('usages.pdf');

    Route::resource('usages', ElectricUsageController::class);

    /*
    |--------------------------------------------------------------------------
    | ELECTRIC BILLS
    |--------------------------------------------------------------------------
    */

    // PDF FIRST
    Route::get('/bills/pdf', [ElectricBillController::class, 'pdf'])
        ->name('bills.pdf');

    Route::resource('bills', ElectricBillController::class);

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */

    // PDF FIRST
    Route::get('/payments/pdf', [PaymentController::class, 'pdf'])
        ->name('payments.pdf');

    Route::resource('payments', PaymentController::class);

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
