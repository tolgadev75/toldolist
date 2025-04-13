<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoViewController;
use Illuminate\Support\Facades\Route;

// Route d'accueil personnalisée
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Todo - Vue.js sera chargé ici
Route::get('/todos', [TodoViewController::class, 'index'])
    ->middleware(['auth'])
    ->name('todos');

// Routes Breeze d'authentification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
