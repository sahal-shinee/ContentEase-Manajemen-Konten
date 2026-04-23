<?php

use App\Http\Controllers\KontenController;
use Illuminate\Support\Facades\Route;

// Root → Dashboard
Route::get('/', [KontenController::class, 'dashboard'])->name('dashboard');

// Dashboard statistik
Route::get('/dashboard', [KontenController::class, 'dashboard'])->name('dashboard');

// Resource Route CRUD
Route::resource('konten', KontenController::class);

// Ubah Status cepat
Route::patch('konten/{konten}/ubah-status', [KontenController::class, 'ubahStatus'])
    ->name('konten.ubahStatus');