
<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
    // Customers routes
   Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('customers.store');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy');

    // Purchases routes
Route::get('/purchases', [PurchaseController::class, 'index'])
    ->name('purchases.index');

Route::post('/purchases', [PurchaseController::class, 'store'])
    ->name('purchases.store');

Route::put('/purchases/{purchase}', [PurchaseController::class, 'update'])
    ->name('purchases.update');

Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])
    ->name('purchases.destroy');


        // Payments routes
Route::get('/payments', [PaymentController::class, 'index'])
    ->name('payments.index');

Route::post('/payments', [PaymentController::class, 'store'])
    ->name('payments.store');

Route::put('/payments/{payment}', [PaymentController::class, 'update'])
    ->name('payments.update');

Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])
    ->name('payments.destroy');

    // Reports route
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

});


require __DIR__.'/auth.php';
