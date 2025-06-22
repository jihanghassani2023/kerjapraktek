<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/check', [TrackingController::class, 'check'])->name('tracking.check');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'kepala_toko') {
            return redirect()->route('kepala-toko.dashboard');
        } elseif ($role === 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } else {
            return redirect()->route('login');
        }
    })->name('dashboard');


    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/search', [AdminController::class, 'search'])->name('search');
        Route::put('/perbaikan/{id}/status', [AdminController::class, 'updateStatus'])->name('perbaikan.update-status');
        Route::get('/pelanggan/{id}/edit-pelanggan', [AdminController::class, 'editPelanggan'])->name('perbaikan.edit-pelanggan');
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/export', [AdminController::class, 'exportTransaksi'])->name('transaksi.export');
        Route::get('/transaksi/{id}', [AdminController::class, 'showTransaksi'])->name('transaksi.show');
        Route::put('/transaksi/{id}/status', [AdminController::class, 'updateStatus'])->name('transaksi.update-status');
        Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
        Route::get('/pelanggan/create', [AdminController::class, 'createPelanggan'])->name('pelanggan.create');
        Route::post('/pelanggan', [AdminController::class, 'storePelanggan'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}/edit', [AdminController::class, 'editPelanggan'])->name('pelanggan.edit');
        Route::put('/pelanggan/{id}', [AdminController::class, 'updatePelanggan'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}', [AdminController::class, 'destroyPelanggan'])->name('pelanggan.destroy');
        Route::get('/perbaikan/create', [AdminController::class, 'createPerbaikan'])->name('perbaikan.create');
        Route::post('/perbaikan', [AdminController::class, 'storePerbaikan'])->name('perbaikan.store');
    });
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    Route::get('/kepala-toko/dashboard', [LaporanController::class, 'dashboard'])->name('kepala-toko.dashboard');
    Route::prefix('laporan')->name('laporan.')->middleware(['auth'])->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
        Route::get('/{id}', [LaporanController::class, 'show'])
            ->name('show')
            ->where('id', 'MG\d{9}');
    });
    Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class);
    });

    Route::prefix('teknisi')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [PerbaikanController::class, 'index'])->name('teknisi.dashboard');
        Route::post('/perbaikan/{id}/add-process', [PerbaikanController::class, 'addProcessStep'])->name('perbaikan.add-process');
        Route::get('/laporan', [PerbaikanController::class, 'laporan'])->name('teknisi.laporan');
        Route::get('/perbaikan/{id}', [PerbaikanController::class, 'show'])->name('perbaikan.show');
        Route::get('/perbaikan/{id}/edit', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
        Route::put('/perbaikan/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
        Route::get('/perbaikan/{id}/confirm-status/{status}', [PerbaikanController::class, 'confirmStatus'])->name('perbaikan.confirm-status');
    });

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/kepala-toko/search', [AdminController::class, 'search'])->name('kepala-toko.search');
    Route::get('/admin/api/customers', [AdminController::class, 'getCustomers'])->name('admin.api.customers');
    Route::put('/perbaikan/{id}/status', [PerbaikanController::class, 'updateStatus'])->name('perbaikan.update-status');
});
