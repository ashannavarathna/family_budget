<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\admin\TransactionTypeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

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

#Route::get('/', function () {
#    return view('welcome');
#});

// Public routes
Route::get('/', function () {
    // Check if the user is authenticated (logged in)
    if (auth()->check()) {
        // If logged in, redirect to the named 'dashboard' route
        return redirect()->route('dashboard');
    }

    // If not logged in, load the 'home' view as usual
    return view('home');
});

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // if tired logout on get
    Route::get('/logout', function () {
        return view('home');
    });

});

// Protected routes
Route::middleware(['auth'])->group(function (){
    // Regular user dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaction routes
    Route::resource('transactions', 'TransactionController');

    //Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');

    // AJAX route for category filtering
    Route::get('/transactions/categories/{typeId}', [TransactionController::class, 'getCategoriesByType'])
         ->name('transactions.categories.by-type');

    //logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (){
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/transactiontype', 'Admin\TransactionTypeController');
    Route::resource('/category', 'Admin\CategoryController');
});
