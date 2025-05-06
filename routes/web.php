<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

// Protected routes for admin, kepala toko, and teknisi
Route::middleware(['auth', 'role'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('Login');

    // Admin specific routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', [LoginController::class, 'users'])->name('admin.users');
        // More admin routes...
    });

    // Kepala Toko specific routes
    Route::middleware(['role:kepala_toko'])->group(function () {
        Route::get('/kepala-toko/reports', [LoginController::class, 'reports'])->name('kepala.reports');
        // More kepala toko routes...
    });

    // Teknisi specific routes
    Route::middleware(['role:teknisi'])->group(function () {
        Route::get('/teknisi/tasks', [LoginController::class, 'tasks'])->name('teknisi.tasks');
        // More teknisi routes...
    });

    Route::get('/login', [LoginController::class, 'index'])
    ->middleware(['auth', 'role:admin,kepala_toko,teknisi']);


});
