<?php

use App\Http\Controllers\AdminTipoPropiedadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/mis-publicaciones', [PropiedadController::class, 'misPublicaciones'])->name('propiedades.mine');
    Route::patch('/mis-publicaciones/{propiedad}/estado', [PropiedadController::class, 'actualizarEstado'])
        ->name('propiedades.estado.update');
    Route::delete('/mis-publicaciones/{propiedad}', [PropiedadController::class, 'destroy'])
        ->name('propiedades.destroy');

    Route::get('/propiedades/registro', [PropiedadController::class, 'create'])->name('propiedades.create');
    Route::post('/propiedades/registro/fotos', [PropiedadController::class, 'storeFotos'])->name('propiedades.fotos.store');
    Route::delete('/propiedades/registro/fotos/{index}', [PropiedadController::class, 'removeFotoTemporal'])
        ->whereNumber('index')
        ->name('propiedades.fotos.remove');
    Route::get('/propiedades/registro/fotos/{index}/preview', [PropiedadController::class, 'tempFoto'])
        ->whereNumber('index')
        ->name('propiedades.fotos.preview');
    Route::get('/propiedades/registro/datos', [PropiedadController::class, 'createDatos'])->name('propiedades.datos.create');
    Route::post('/propiedades', [PropiedadController::class, 'store'])->name('propiedades.store');
    Route::get('/propiedades/{propiedad}/publicada', [PropiedadController::class, 'publicada'])->name('propiedades.publicada');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/photo', [ProfileController::class, 'photo'])->name('profile.photo');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::get('/tipos-propiedad', [AdminTipoPropiedadController::class, 'index'])->name('tipos-propiedad.index');
    Route::post('/tipos-propiedad', [AdminTipoPropiedadController::class, 'store'])->name('tipos-propiedad.store');
});

require __DIR__.'/auth.php';
