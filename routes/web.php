<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // categories
    Route::resource('categories', CategoryController::class);

    // customers
    Route::resource('customers', CustomerController::class);
    //get customer by auth
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customers.profile');
    //change status
    Route::post('/{id}/change-status', [CustomerController::class, 'changeStatus'])->name('customers.change-status');


    // books
    Route::resource('books', BookController::class);

    // transactions
    Route::resource('transactions', TransactionController::class);
    //change status returned
    Route::post('transactions/{id}/change-status', [TransactionController::class, 'returned'])->name('transactions.change-status');
});

