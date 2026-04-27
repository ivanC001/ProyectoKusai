<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTipoPropiedadController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use Illuminate\Support\Facades\Route;


// index de la pagina
Route::get('/', [PortalController::class, 'index'])->name('home');
Route::get('/portal/propiedades/{propiedad}', [PortalController::class, 'show'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.show');
Route::post('/portal/propiedades/{propiedad}/clic', [PortalController::class, 'registrarClic'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.click');
Route::post('/portal/propiedades/{propiedad}/favorito/toggle', [PortalController::class, 'toggleFavorito'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.favoritos.toggle');

//muestra propiedades por id
Route::get('/portal/propiedades/{propiedad}/imagenes/{imagen}', [PortalController::class, 'imagen'])
    ->whereNumber('propiedad')
    ->whereNumber('imagen')
    ->name('portal.propiedades.imagen');

Route::get('/login', function () {
    return view('login');
})->middleware(['auth', 'verified'])->name('login');


// Gestion de propiedades
Route::middleware('auth')->group(function () {
    // vista publicacion por usuarios
    Route::get('/mis-publicaciones', [PropiedadController::class, 'misPublicaciones'])->name('propiedades.mine');
   // edita publicaciones
    Route::get('/mis-publicaciones/{propiedad}/editar', [PropiedadController::class, 'edit'])->name('propiedades.edit');
    
    Route::patch('/mis-publicaciones/{propiedad}', [PropiedadController::class, 'update'])->name('propiedades.update');
    Route::get('/mis-publicaciones/{propiedad}/imagenes/{imagen}', [PropiedadController::class, 'imagen'])
        ->name('propiedades.imagen.show');
    Route::patch('/mis-publicaciones/{propiedad}/estado', [PropiedadController::class, 'actualizarEstado'])
        ->name('propiedades.estado.update');
    Route::delete('/mis-publicaciones/{propiedad}', [PropiedadController::class, 'destroy'])
        ->name('propiedades.destroy');
    Route::get('/mis-publicaciones/{propiedad}/detalle', [PropiedadController::class, 'detalle'])
        ->name('propiedades.detalle');

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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {


    //rutas de administrador
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/paneladministrativo', [AdminTipoPropiedadController::class, 'index'])->name('PanelAdministrativo');
    Route::post('/paneladministrativo/tipos', [AdminTipoPropiedadController::class, 'store'])->name('PanelAdministrativo.tipos.store');
    Route::patch('/paneladministrativo/tipos/{tipoPropiedad}', [AdminTipoPropiedadController::class, 'update'])->name('PanelAdministrativo.tipos.update');
    Route::delete('/paneladministrativo/tipos/{tipoPropiedad}', [AdminTipoPropiedadController::class, 'destroy'])->name('PanelAdministrativo.tipos.destroy');

    Route::get('/paneladministrativo/usuarios', [AdminUserController::class, 'index'])->name('PanelAdministrativo.usuarios.index');
    Route::post('/paneladministrativo/usuarios', [AdminUserController::class, 'store'])->name('PanelAdministrativo.usuarios.store');
    Route::patch('/paneladministrativo/usuarios/{user}', [AdminUserController::class, 'update'])->name('PanelAdministrativo.usuarios.update');
    Route::patch('/paneladministrativo/usuarios/{user}/estado', [AdminUserController::class, 'actualizarEstado'])->name('PanelAdministrativo.usuarios.estado.update');
    Route::delete('/paneladministrativo/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('PanelAdministrativo.usuarios.destroy');
});

require __DIR__.'/auth.php';
