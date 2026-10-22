<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MedicineBatchController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PharmacyQueueController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');


/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'requestResetCode'])
    ->name('password.email');

Route::get('/reset-password', [AuthController::class, 'showVerifyResetCode'])
    ->name('password.verify');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.reset');


/*
|--------------------------------------------------------------------------
| Public QR Receipt
|--------------------------------------------------------------------------
*/

Route::get('/r/{receipt_token}', [ReceiptController::class, 'show'])
    ->name('receipt.public');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Admin, Pharmacist and Cashier Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,pharmacist,cashier')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Doctor Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:doctor')
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {

            Route::get('/dashboard', [DoctorController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('/prescriptions/create', [DoctorController::class, 'create'])
                ->name('prescriptions.create');

            Route::post('/prescriptions', [DoctorController::class, 'store'])
                ->name('prescriptions.store');

            Route::get('/prescriptions/search', [DoctorController::class, 'searchPrescriptions'])
                ->name('prescriptions.search');

            Route::get('/prescriptions/{prescription}', [DoctorController::class, 'show'])
                ->name('prescriptions.show');

            Route::get('/patients/search', [DoctorController::class, 'searchPatients'])
                ->name('patients.search');

            Route::post('/patients', [DoctorController::class, 'storePatient'])
                ->name('patients.store');

            Route::get('/medicines/search', [DoctorController::class, 'searchMedicines'])
                ->name('medicines.search');
        });


    /*
    |--------------------------------------------------------------------------
    | Medicines
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist')->group(function () {

        Route::resource('medicines', MedicineController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist')->group(function () {

        Route::resource('suppliers', SupplierController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Stock
    |--------------------------------------------------------------------------
    |
    | Admin and Pharmacist only.
    |
    */

    Route::middleware('role:admin,pharmacist')->group(function () {

        Route::patch(
            '/stock/{stock}/deactivate',
            [MedicineBatchController::class, 'deactivate']
        )->name('stock.deactivate');

        Route::patch(
            '/stock/{stock}/activate',
            [MedicineBatchController::class, 'activate']
        )->name('stock.activate');

        Route::resource('stock', MedicineBatchController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Pharmacy Queue
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist')
        ->prefix('pharmacy')
        ->name('pharmacy.')
        ->group(function () {

            Route::get('/queue', [PharmacyQueueController::class, 'index'])
                ->name('queue');

            Route::get('/queue/{prescription}', [
                PharmacyQueueController::class,
                'show',
            ])->name('queue.show');

        });


    /*
    |--------------------------------------------------------------------------
    | POS / Sales / Customers
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist,cashier')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | POS
        |--------------------------------------------------------------------------
        */

        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');


        /*
        |--------------------------------------------------------------------------
        | Hospital Prescription Verification
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sales/prescription/verify',
            [SaleController::class, 'verifyPrescription']
        )->name('sales.prescription.verify');


        /*
        |--------------------------------------------------------------------------
        | Legacy Hospital Prescription Verification Route Name
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sales/prescription/verify',
            [SaleController::class, 'verifyPrescription']
        )->name('hospital-prescriptions.verify');


        /*
        |--------------------------------------------------------------------------
        | Complete Sale
        |--------------------------------------------------------------------------
        */

        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');


        /*
        |--------------------------------------------------------------------------
        | Customer Search
        |--------------------------------------------------------------------------
        */

        Route::get('/customers/search', [SaleController::class, 'searchCustomers'])
            ->name('customers.search');


        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');


        /*
        |--------------------------------------------------------------------------
        | Sales History
        |--------------------------------------------------------------------------
        */

        Route::get('/sales/history', [SaleController::class, 'history'])
            ->name('sales.history');


        /*
        |--------------------------------------------------------------------------
        | Individual Sale
        |--------------------------------------------------------------------------
        */

        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');


        /*
        |--------------------------------------------------------------------------
        | Receipt
        |--------------------------------------------------------------------------
        */

        Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
            ->name('sales.receipt');

    });


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class);

        Route::post(
            '/users/{user}/generate-reset-code',
            [UserController::class, 'generateResetCode']
        )->name('users.generate-reset-code');

    });


    /*
    |--------------------------------------------------------------------------
    | Signed Mobile Receipt
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/sales/{sale}/mobile-receipt',
        [SaleController::class, 'mobileReceipt']
    )
        ->name('sales.mobile-receipt')
        ->middleware('signed');

});