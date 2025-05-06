<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin specific routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
        // More admin routes...
    });

    // Kepala Toko specific routes
    Route::middleware(['role:kepala_toko'])->group(function () {
        Route::get('/kepala-toko/reports', [DashboardController::class, 'reports'])->name('kepala.reports');
        // More kepala toko routes...
    });

    // Teknisi specific routes
    Route::middleware(['role:teknisi'])->group(function () {
        Route::get('/teknisi/tasks', [DashboardController::class, 'tasks'])->name('teknisi.tasks');
        // More teknisi routes...
    });
});
