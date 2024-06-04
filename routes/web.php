<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CategoriasComponent;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// manera tradicional de laravel cuando se crean las rutas
//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//manera  de livewire cuando se crea la rutas
Route::get('/categoria', CategoriasComponent::class)->name('categoria');
Route::get('/registrarcategoria', CategoriasComponent::class)->name('registrocategoias');
