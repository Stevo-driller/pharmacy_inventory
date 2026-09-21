
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


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
|
| When someone visits:
| http://127.0.0.1:8000/
|
| redirect them to the login page.
|
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
| Password Reset - 6 Digit Administrator Code
|--------------------------------------------------------------------------
|
| This system does NOT send an email reset link.
|
| Flow:
|
| Login
|   ↓
| Forgot Password
|   ↓
| Enter email
|   ↓
| Contact administrator
|   ↓
| Administrator generates 6-digit code
|   ↓
| Enter code + new password
|   ↓
| Password changed
|
*/


/*
|--------------------------------------------------------------------------
| Show Forgot Password Page
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
    ->name('password.request');


/*
|--------------------------------------------------------------------------
| Request Password Reset
|--------------------------------------------------------------------------
*/

Route::post('/forgot-password', [AuthController::class, 'requestResetCode'])
    ->name('password.email');


/*
|--------------------------------------------------------------------------
| Show Reset Code Verification Page
|--------------------------------------------------------------------------
*/

Route::get('/reset-password', [AuthController::class, 'showVerifyResetCode'])
    ->name('password.verify');


/*
|--------------------------------------------------------------------------
| Verify Reset Code + Set New Password
|--------------------------------------------------------------------------
*/

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.reset');


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
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | Admin
    | Pharmacist
    | Cashier
    |
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,pharmacist,cashier')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Medicines
    |--------------------------------------------------------------------------
    |
    | Admin + Pharmacist only
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist')->group(function () {

        Route::resource('medicines', MedicineController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    |
    | Admin + Pharmacist only
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
    | Admin + Pharmacist only
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,pharmacist')->group(function () {

        Route::resource('stock', MedicineBatchController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | POS / Sales / Customers
    |--------------------------------------------------------------------------
    |
    | Admin + Pharmacist + Cashier
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
    |
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | User CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource('users', UserController::class);


        /*
        |--------------------------------------------------------------------------
        | Generate 6-Digit Password Reset Code
        |--------------------------------------------------------------------------
        |
        | Administrator selects a user and generates
        | a temporary 6-digit reset code.
        |
        */

        Route::post(
            '/users/{user}/generate-reset-code',
            [UserController::class, 'generateResetCode']
        )->name('users.generate-reset-code');

    });

});

