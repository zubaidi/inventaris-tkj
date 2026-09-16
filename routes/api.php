<?php

use Illuminate\Support\Facades\Route;

Route::get('/inventaris', [\App\Http\Controllers\InventarisController::class, 'index']);
Route::post('/inventaris', [\App\Http\Controllers\InventarisController::class, 'store']);
Route::get('/inventaris/{id}', [\App\Http\Controllers\InventarisController::class, 'show']);
Route::put('/inventaris/{id}', [\App\Http\Controllers\InventarisController::class, 'update']);
Route::delete('/inventaris/{id}', [\App\Http\Controllers\InventarisController::class, 'destroy']);
