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
use App\Http\Controllers\LaporanController;
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

        /* ============================================================
         | PIMPINAN & ADMIN & USER — Read-only
         ============================================================ */
        Route::middleware('admin')->group(function () {
            // Dashboard
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Laporan (khusus pimpinan)
            Route::middleware('pimpinan')->group(function () {
                Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
                Route::get('laporan/per-jurusan', [LaporanController::class, 'perJurusan'])->name('laporan.per-jurusan');
                Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
            });
        });

        // Inventaris view (semua role)
        Route::get('inventaris/cetak', [InventarisController::class, 'cetakInventaris'])->name('inventaris.cetak');
        Route::get('inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');
        Route::get('inventaris/rekap', [InventarisController::class, 'rekapInventaris'])->name('inventaris.rekap');
        Route::get('inventaris/rekap-per-ruang', [InventarisController::class, 'rekapPerRuang'])->name('inventaris.rekap-per-ruang');
        Route::get('inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
        Route::get('inventaris/{id}', [InventarisController::class, 'show'])->name('inventaris.show');

        /* ============================================================
         | ADMIN & USER — Write (CRUD)
         ============================================================ */
        Route::middleware('can_write')->group(function () {
            Route::post('inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
            Route::get('inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
            Route::get('inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
            Route::put('inventaris/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
            Route::delete('inventaris/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

            Route::get('inventaris/template', [InventarisController::class, 'downloadTemplate'])->name('inventaris.template');
            Route::post('inventaris/import', [InventarisController::class, 'import'])->name('inventaris.import');
        });

        /* ============================================================
         | ADMIN ONLY — Master data
         ============================================================ */
        Route::middleware('admin')->group(function () {
            Route::resource('labs', LabController::class);
            Route::resource('sumber-dana', SumberDanaController::class);
        });

        /* ============================================================
         | SUPER ADMIN — User & Backup
         ============================================================ */
        Route::middleware('super_admin')->group(function () {
            Route::resource('user', UserController::class);
            Route::resource('jurusan', JurusanController::class);

            Route::prefix('backup')->name('backup.')->group(function () {
                Route::get('/database', [BackupController::class, 'indexDatabase'])->name('database');
                Route::get('/database/download', [BackupController::class, 'backupDatabase'])->name('database.download');
                Route::get('/csv', [BackupController::class, 'indexCsv'])->name('csv');
                Route::get('/csv/download/{table}', [BackupController::class, 'exportCsv'])->name('csv.download');
            });
        });
    });
