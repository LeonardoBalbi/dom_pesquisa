<?php

use App\Http\Controllers\EdicaoPublicController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EdicaoPublicController::class, 'index'])->name('doeca.inicio');

Route::get('/doeca/autocomplete', [EdicaoPublicController::class, 'autocomplete'])
    ->name('doeca.autocomplete');

Route::get('/edicoes/{edicao}', [EdicaoPublicController::class, 'show'])
    ->name('doeca.edicao.show');

Route::get('/edicoes/{edicao}/download', [EdicaoPublicController::class, 'download'])
    ->name('doeca.edicao.download');

Route::get('/edicoes/{edicao}/view', [EdicaoPublicController::class, 'view'])
    ->name('doeca.edicao.view');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
