<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| ZONA PUBLIK (Guest — Tanpa Login)
|--------------------------------------------------------------------------
| Halaman yang bisa diakses siapa aja tanpa login.
| Return: Blade view.
*/

// Halaman depan: tabel inventaris + search + filter + summary
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail 1 barang
Route::get('/barang/{id}', [HomeController::class, 'show'])->name('barang.show');

// Daftar lab (publik)
Route::get('/lab', [LabController::class, 'publicIndex'])->name('lab.index');

// Daftar sumber dana (publik)
Route::get('/sumber-dana', [SumberDanaController::class, 'publicIndex'])->name('sumber-dana.index');

// Export Excel (publik juga bisa)
Route::get('/export-excel', [HomeController::class, 'export'])->name('export.excel');


/*
|--------------------------------------------------------------------------
| ZONA AUTH (Login & Logout)
|--------------------------------------------------------------------------
| Hanya guest (belum login) yang bisa akses halaman login.
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ZONA ADMIN (Wajib Login + Middleware 'admin')
|--------------------------------------------------------------------------
| Semua route di sini pakai prefix /admin, name prefix admin.,
| dan return view di folder resources/views/admin/.
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Inventaris
        Route::get('/inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');
        Route::resource('inventaris', InventarisController::class);

        // CRUD Lab
        Route::resource('labs', LabController::class);

        // CRUD Sumber Dana
        Route::resource('sumber-dana', SumberDanaController::class);
    });

