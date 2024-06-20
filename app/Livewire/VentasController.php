<?php

namespace App\Livewire;

use Livewire\Component;

class VentasController extends Component
{
    public function render()
    {
        return view('livewire.ventas.ventas')->extends('ventapos.layouts.admin')->section('contenido');;
    }
}
