<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon; // ser utiliza para trabajar las fechas y porder darle formato segun la necesidad
//carbon es una biblioteca de php que extiende de la clase DateTime

class CorteCajaController extends Component
{
    public $fechaInicial, $fechaFina, $total, $usuario_id, $items, $venta, $detalle  ;
   
    public function muont(){
        $this->fechaFina = null;
        $this->fechaInicial = null;
        $this->usuario_id = 0;
        $this->total = 0;
        $this->venta = []; // Inicializa como una colección vacía
        $this->detalle = []; // Inicializa como una colección vacía
        
    }

    public function render()
    {
        $usuarios = User::orderBy('name', 'asc')->get();
        return view('livewire.cortecaja.componente', compact('usuarios'))
        ->extends('ventapos.layouts.admin')->section('contenido');
    }

    public function consultar()
    {       
        //para esta consulta va ser necesaria las fechas para el filtro
        // dar formato a la fecha
        $inicialFecha = Carbon::parse($this->fechaInicial)->format('Y-m-d') . ' 00:00:00';
        $finalFecha = Carbon::parse($this->fechaFina)->format('Y-m-d') . ' 23:59:59';
        //dd('fecha inicial:  ' . $inicialFecha . ' fecha final: ' . $finalFecha);
    
        // Obtener todas las ventas que cumplan con las condiciones especificadas
        $this->venta = Venta::whereBetween('created_at', [$inicialFecha, $finalFecha]) // Filtra las ventas por un rango de fechas
        ->where('estado', 'PAGADO') // Filtra las ventas que tienen el estado 'PAGADO'
        ->where('id_usuario', $this->usuario_id) // Filtra las ventas que pertenecen a un usuario específico (identificado por 'usuario_id')
        ->get(); // Obtiene los resultados de la consulta en forma de una colección
   
        // condicion: si venta tiene informacion se suman los totales de lo contrari sera 0
        $this->total = $this->venta ? $this->venta->sum('total') : 0 ;
        $this->items = $this->venta ? $this->venta->sum('item') : 0 ;
    }

    public function vistaDetalles(Venta $venta)
    {
        //para esta consulta va ser necesaria las fechas para el filtro
        // dar formato a la fecha
        $inicialFecha = Carbon::parse($this->fechaInicial)->format('Y-m-d') . ' 00:00:00';
        $finalFecha = Carbon::parse($this->fechaFina)->format('Y-m-d') . ' 23:59:59';

        // Realiza una consulta uniendo las tablas 'ventas', 'venta_detalles' y 'productos'
        $this->detalle = Venta::join('venta_detalles as d', 'd.ventas_id', 'ventas.id') // Une la tabla 'ventas' con 'venta_detalles' en base al campo 'venta_id'
        ->join('productos as p', 'p.id', 'd.productos_id') // Une la tabla 'venta_detalles' con 'productos' en base al campo 'productos_id'
        ->select('ventas.id', 'p.nombre as producto', 'd.cantidad', 'd.precio') // Selecciona los campos deseados para el resultado
        ->whereBetween('ventas.created_at', [$inicialFecha, $finalFecha]) // Filtra las ventas por un rango de fechas
        ->where('ventas.estado', 'PAGADO') // Filtra las ventas que tienen el estado 'PAGADO'
        ->where('ventas.id_usuario', $this->usuario_id) // Filtra las ventas que pertenecen a un usuario específico
        ->where('ventas.id', $venta->id) // Filtra para obtener detalles de una venta específica
        ->get(); // Obtiene los resultados de la consulta en forma de una colección
        $this->dispatch('show-modal'); // Despacha un evento para mostrar un modal
    }


    public function imprimir(){

    }

}
