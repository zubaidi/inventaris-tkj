<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZONA PUBLIK (Guest — Tanpa Login)
|--------------------------------------------------------------------------
| Halaman yang bisa diakses siapa aja tanpa login.
| Return: Blade view.
*/

// Halaman depan: tabel inventaris + search + filter + summary
Route::get('/', [HomeController::class, 'index'])
    ->middleware('throttle:30,1')
    ->name('home');

// Detail 1 barang
Route::get('/barang/{id}', [HomeController::class, 'show'])->name('barang.show');
Route::get('/lab/{id}', [HomeController::class, 'perRuang'])->name('home.per-ruang');

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

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ADMIN & USER
        Route::get('inventaris/cetak', [InventarisController::class, 'cetakInventaris'])->name('inventaris.cetak');
        Route::get('inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');
        Route::get('inventaris/rekap', [InventarisController::class, 'rekapInventaris'])->name('inventaris.rekap');
        Route::get('inventaris/rekap-per-ruang', [InventarisController::class, 'rekapPerRuang'])->name('inventaris.rekap-per-ruang');
        // import export template excel
        Route::middleware(['auth', 'admin'])->prefix('inventaris')->name('inventaris.')->group(function () {
            Route::get('template', [InventarisController::class, 'downloadTemplate'])->name('template');
            Route::post('import', [InventarisController::class, 'import'])->name('import');
        });
        Route::resource('inventaris', InventarisController::class);

        // ADMIN ONLY
        Route::middleware('admin')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::resource('labs', LabController::class);
            Route::resource('sumber-dana', SumberDanaController::class);
        });

        // SUPER ADMIN ONLY
        Route::middleware('super_admin')->group(function () {
            Route::resource('jurusan', JurusanController::class);
            Route::resource('user', UserController::class);

            Route::prefix('backup')->name('backup.')->group(function () {
                Route::get('/database', [BackupController::class, 'indexDatabase'])->name('database');
                Route::get('/database/download', [BackupController::class, 'backupDatabase'])->name('database.download');
                Route::get('/csv', [BackupController::class, 'indexCsv'])->name('csv');
                Route::get('/csv/download/{table}', [BackupController::class, 'exportCsv'])->name('csv.download');
            });
        });
    });
