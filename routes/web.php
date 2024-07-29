<?php

use App\Livewire\AsignarController;
use Illuminate\Support\Facades\Route;
use App\Livewire\CategoriasController;
use App\Livewire\ProductosController;
use App\Livewire\MonedasControlador;
use App\Livewire\RolesController;
use App\Livewire\VentasController;
use App\Livewire\PermisosContoller;
use App\Livewire\UsuarioController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// manera tradicional de laravel cuando se crean las rutas
//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//manera  de livewire cuando se crea la rutas
Route::get('/categoria', CategoriasController::class)->name('categoria');
Route::get('/producto', ProductosController::class)->name('producto');
Route::get('/monedas', MonedasControlador::class)->name('moneda'); 
Route::get('/ventas', VentasController::class)->name('ventas');
Route::get('/roles', RolesController::class)->name('roles');
Route::get('/permisos', PermisosContoller::class)->name('permisos');
Route::get('/asignar', AsignarController::class)->name('asignar');
Route::get('/usuario', UsuarioController::class)->name('usuario');

