<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Client\DashBoardController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\UserManagement\User\UserController;
use App\Http\Controllers\Client\Auth\AuthController as ClientAuth;
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

Route::get('/',  function () {
    return view('welcome');
})->name('index');

Route::get('/clear-cache', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    dd('Cache cleared');
});

/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::any('login', [AuthController::class, 'login'])->name('login');
    Route::any('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password.get');
    Route::post('reset-password', [AuthController::class, 'submitResetPassword'])->name('reset-password.post');

    Route::get('account-restricted', [AuthController::class, 'accountRestricted'])->name('account-restricted');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {

        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Start User Management Area
        |--------------------------------------------------------------------------
        */

        Route::prefix('user-management')->name('user-management.')->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Start User Area
            |--------------------------------------------------------------------------
            */

            Route::prefix('user')->name('user.')->group(function () {
                Route::post('store', [UserController::class, 'store'])->name('store');
                Route::put('update', [UserController::class, 'update'])->name('update');
                Route::get('status/{id}', [UserController::class, 'status'])->name('status');
                Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
                Route::get('delete/{id}', [UserController::class, 'destroy'])->name('delete');
                Route::get('permanent-delete/{id}', [UserController::class, 'permanentDelete'])->name('permanent-delete');
                Route::get('restore/{id}', [UserController::class, 'restore'])->name('restore');
                Route::get('{items?}', [UserController::class, 'index'])->name('index');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Start Product Area
        |--------------------------------------------------------------------------
        */

        Route::prefix('product')->name('product.')->group(function () {
            Route::post('store',                [ProductController::class, 'store'])->name('store');
            Route::put('update',                [ProductController::class, 'update'])->name('update');
            Route::get('status/{id}',           [ProductController::class, 'status'])->name('status');
            Route::get('edit/{id}',             [ProductController::class, 'edit'])->name('edit');
            Route::get('delete/{id}',           [ProductController::class, 'destroy'])->name('delete');
            Route::get('permanent-delete/{id}', [ProductController::class, 'permanentDelete'])->name('permanent-delete');
            Route::get('restore/{id}',          [ProductController::class, 'restore'])->name('restore');
            Route::get('/',                     [ProductController::class, 'index'])->name('index');
        });

        /*
        |--------------------------------------------------------------------------
        | Start Category Area
        |--------------------------------------------------------------------------
        */

        Route::prefix('category')->name('category.')->group(function () {
            Route::post('store', [CategoryController::class, 'store'])->name('store');
            Route::put('update', [CategoryController::class, 'update'])->name('update');
            Route::get('status/{id}', [CategoryController::class, 'status'])->name('status');
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
            Route::get('delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
            Route::get('permanent-delete/{id}', [CategoryController::class, 'permanentDelete'])->name('permanent-delete');
            Route::get('restore/{id}', [CategoryController::class, 'restore'])->name('restore');
            Route::get('/', [CategoryController::class, 'index'])->name('index');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Start Client Area
|--------------------------------------------------------------------------
*/

Route::prefix('client')->name('client.')->group(function () {
    Route::any('login', [ClientAuth::class, 'login'])->name('login');
    Route::any('register', [ClientAuth::class, 'register'])->name('register');
    Route::any('forgot-password', [ClientAuth::class, 'forgotPassword'])->name('forgot-password');
    Route::get('reset-password/{token}', [ClientAuth::class, 'showResetPassword'])->name('reset-password.get');
    Route::post('reset-password', [ClientAuth::class, 'submitResetPassword'])->name('reset-password.post');

    Route::get('account-restricted', [ClientAuth::class, 'accountRestricted'])->name('account-restricted');
    Route::any('verify-email', [ClientAuth::class, 'verifyEmail'])->name('verify-email');
    Route::get('resend-verification-email', [ClientAuth::class, 'resendVerificationMail'])->name('resend-verification-email');
    Route::get('logout', [ClientAuth::class, 'logout'])->name('logout');

    Route::middleware('client')->group(function () {

        Route::get('/', [DashBoardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Start User Management Area
        |--------------------------------------------------------------------------
        */
    });
});
