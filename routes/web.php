<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminTipoPropiedadController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileVerificacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\SupportPageController;
use App\Http\Controllers\VerificacionUsuarioController;
use Illuminate\Support\Facades\Route;

// index de la pagina
Route::get('/', [PortalController::class, 'index'])->name('home');
Route::get('/como-publicar', [PortalController::class, 'comoPublicar'])->name('portal.como-publicar');
Route::post('/como-publicar/comentarios', [PortalController::class, 'storeComentarioComoPublicar'])
    ->middleware(['auth', 'verified'])
    ->name('portal.como-publicar.comentarios.store');
Route::get('/portal/propiedades/{propiedad}', [PortalController::class, 'show'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.show');
Route::post('/portal/propiedades/{propiedad}/clic', [PortalController::class, 'registrarClic'])
    ->middleware('throttle:90,1')
    ->whereNumber('propiedad')
    ->name('portal.propiedades.click');
Route::post('/portal/propiedades/{propiedad}/favorito/toggle', [PortalController::class, 'toggleFavorito'])
    ->middleware('throttle:45,1')
    ->whereNumber('propiedad')
    ->name('portal.propiedades.favoritos.toggle');
Route::post('/portal/propiedades/{propiedad}/contacto', [PortalController::class, 'solicitarContacto'])
    ->middleware(['auth', 'verified', 'throttle:12,1'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.contacto');
Route::post('/portal/propiedades/{propiedad}/comentarios', [PortalController::class, 'storeComentario'])
    ->middleware(['auth', 'verified', 'throttle:20,1'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.comentarios.store');
Route::post('/portal/propiedades/{propiedad}/resenas', [PortalController::class, 'storeResena'])
    ->middleware(['auth', 'verified', 'throttle:20,1'])
    ->whereNumber('propiedad')
    ->name('portal.propiedades.resenas.store');
Route::get('/soporte', [SupportPageController::class, 'index'])->name('soporte.index');
Route::get('/soporte/centro-de-ayuda', [SupportPageController::class, 'helpCenter'])->name('soporte.ayuda');
Route::get('/soporte/terminos-y-condiciones', [SupportPageController::class, 'terms'])->name('soporte.terminos');
Route::get('/soporte/politica-de-privacidad', [SupportPageController::class, 'privacy'])->name('soporte.privacidad');
Route::get('/soporte/terminos-legales', [SupportPageController::class, 'legal'])->name('soporte.legales');

//muestra propiedades por id
Route::get('/portal/propiedades/{propiedad}/imagenes/{imagen}', [PortalController::class, 'imagen'])
    ->whereNumber('propiedad')
    ->whereNumber('imagen')
    ->name('portal.propiedades.imagen');

// Gestion de propiedades
Route::middleware('auth')->group(function () {
    Route::middleware('verified')->group(function () {
    // vista publicacion por usuarios
    Route::get('/mis-publicaciones', [PropiedadController::class, 'misPublicaciones'])->name('propiedades.mine');
    Route::get('/mis-solicitudes', [PropiedadController::class, 'solicitudes'])->name('propiedades.solicitudes');
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
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/photo', [ProfileController::class, 'photo'])->name('profile.photo');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/verificacion', [ProfileVerificacionController::class, 'edit'])->name('profile.verificacion.edit');
    Route::post('/profile/verificacion', [ProfileVerificacionController::class, 'store'])->name('profile.verificacion.store');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {


    //rutas de administrador
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/paneladministrativo', [AdminTipoPropiedadController::class, 'index'])->name('PanelAdministrativo');
    Route::get('/paneladministrativo/soporte', [AdminTipoPropiedadController::class, 'support'])->name('PanelAdministrativo.soporte');
    Route::get('/paneladministrativo/sugerencias', [AdminTipoPropiedadController::class, 'sugerencias'])->name('PanelAdministrativo.sugerencias.index');
    Route::patch('/paneladministrativo/sugerencias/{comentarioPortal}/visibilidad', [AdminTipoPropiedadController::class, 'actualizarVisibilidadSugerencia'])->name('PanelAdministrativo.sugerencias.visibilidad.update');
    Route::delete('/paneladministrativo/sugerencias/{comentarioPortal}', [AdminTipoPropiedadController::class, 'destroySugerencia'])->name('PanelAdministrativo.sugerencias.destroy');
    Route::post('/paneladministrativo/tipos', [AdminTipoPropiedadController::class, 'store'])->name('PanelAdministrativo.tipos.store');
    Route::patch('/paneladministrativo/tipos/{tipoPropiedad}', [AdminTipoPropiedadController::class, 'update'])->name('PanelAdministrativo.tipos.update');
    Route::delete('/paneladministrativo/tipos/{tipoPropiedad}', [AdminTipoPropiedadController::class, 'destroy'])->name('PanelAdministrativo.tipos.destroy');
    Route::patch('/paneladministrativo/soporte', [AdminTipoPropiedadController::class, 'updateSupport'])->name('PanelAdministrativo.soporte.update');

    Route::get('/paneladministrativo/usuarios', [AdminUserController::class, 'index'])->name('PanelAdministrativo.usuarios.index');
    Route::post('/paneladministrativo/usuarios', [AdminUserController::class, 'store'])->name('PanelAdministrativo.usuarios.store');
    Route::patch('/paneladministrativo/usuarios/{user}', [AdminUserController::class, 'update'])->name('PanelAdministrativo.usuarios.update');
    Route::patch('/paneladministrativo/usuarios/{user}/estado', [AdminUserController::class, 'actualizarEstado'])->name('PanelAdministrativo.usuarios.estado.update');
    Route::delete('/paneladministrativo/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('PanelAdministrativo.usuarios.destroy');
    Route::resource('verificaciones-usuarios', VerificacionUsuarioController::class)
        ->only(['index', 'edit', 'update']);
});

require __DIR__.'/auth.php';
