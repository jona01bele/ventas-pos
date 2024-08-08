<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VentaDetalle;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;

class ReporteController extends Component
{

    public $nombreComponente, $datos, $detalles, $sumaDetalles, $contarDetalles,
        $tipoReporte, $usuario_id, $fechaDesde, $fechaHasta, $ventaId;

    public function mount()
    {
        $this->datos = [];
        $this->detalles = [];
        $this->sumaDetalles = 0;
        $this->contarDetalles = 0;
        $this->tipoReporte = 0;
        $this->usuario_id = 0;
        $this->ventaId = 0;
    }

    public function render()
    {
        //metodo que hay que crear .
        //este metodo lleva toda la informacion necesaria para mostrar en el componente el reporte o reporte de ventas
        $this->ventasPorFecha();

        //llenar el Select de usuarios
        $usuarios = User::orderBy('name', 'asc')->get();
        return view('livewire.reporte.componente', compact('usuarios'))
            ->extends('ventapos.layouts.admin')
            ->section('contenido');
    }

    //crear la funcion para generar el reporte o ventas por fecha
    public function ventasPorFecha()
    {
            //por lo general cuando se trabaja con fechas se establecen 
            //rangos de estas en este caso se define un inicio y final
            //aca estamos desarrollando con Carbon 2 maneras distintas      
        if ($this->tipoReporte == 0) {

            //gestiona la fecha automaticamente de todo un dia desde las 0 horas hasta las 24 horas.
            $desde = Carbon::now()->startOfDay()->toDateTimeString();
            $hasta = Carbon::now()->endOfDay()->toDateTimeString();
            //$desde = Carbon::parse(Carbon::now())->format('Y-m-d').'00:00:00';
            //$hasta = Carbon::parse(Carbon::now())->format('Y-m-d').'23:59:59';    

            //dos maneras de realizar el mismo proceso        
        } else {

            //gestiona las fechas con un rango dado por el usuario. (desde-hasta)
            $desde = Carbon::parse($this->fechaDesde)->format('Y-m-d 00:00:00');
            $hasta = Carbon::parse($this->fechaHasta)->format('Y-m-d 23:59:59');
            // $desde = Carbon::parse($this->fechaDesde)->startOfDay();
            // $hasta = Carbon::parse($this->fechaHasta)->endOfDay();

            //dos maneras de hacer
        }
        //vaida que las fechas sean anhexada en caso que el reporte sea igual 
        //a un lo que quiere decir que va dar un rango de fechas      
        if ( $this->tipoReporte == 1 && ($this->fechaDesde =="" || $this->fechaHasta == "")) {               
            return; 
            $this->dispatch('seleFecha'); //informar al usuario que debe seleccionar las fechas.          
        }
       
        //aca vamos a validar segun el usuario seleccione, en este caso si es igual a cero fue que  selecciono todos los usuarios
        if ($this->usuario_id== 0) {

            //esta consulta busca unir la tabla usuarios con la tabla ventas
            //finalidad traer las ventas de todos los usuarios teniendo en cuenta el aporte de las fechas dadas
            $this->datos = Venta::join('users as u', 'u.id', 'ventas.id_usuario')
            ->select('ventas.*', 'u.name as usuario')
            ->whereBetween('ventas.created_at', [$desde, $hasta])->get();
            
        }else {
            //esta consulta es igual a la de arriba con la diferencia que aca se hace la consulta con
            // con un usuario especifico.
            $this->datos = Venta::join('users as u', 'u.id', '=', 'ventas.id_usuario')
            ->select('ventas.*', 'u.name as usuario')
            ->where('ventas.id_usuario', $this->usuario_id) //<- aca se le para el usuario
            ->whereBetween('ventas.created_at', [$desde, $hasta])->get();
        }
    }

    // en este metodo es para traer los detalles de la venta que va mostrar el modal
    public function optenerDetalles($ventaId){

        $this->detalles = VentaDetalle::join('productos as p', 'p.id', '=', 'venta_detalles.productos_id')
        ->select('venta_detalles.*', 'p.nombre as producto')
        ->where('venta_detalles.ventas_id', $ventaId)
        ->get();

        //aca se seleccionan todos los detalles de la venta que coinciden con el id de la venta
        //mostrar el total final de la venta
        // $this->sumaDetalles = $this->detalles->sum('precio * cantidad');

        // $this->sumaDetalles = 0;
        //calculo del total final de la venta
        foreach ($this->detalles as $detalle) {
            $this->sumaDetalles += $detalle->precio * $detalle->cantidad;
        }
       
        //este calcula la cantidad de detalles de la venta
        $this->contarDetalles = $this->detalles->sum('cantidad');
        

        //este calcula la cantidad de detalles de la venta
        $this->ventaId = $ventaId;
        //este guarda el id de la venta para poder usarlo en el modal
        $this->dispatch('show-modal');  //muestra el modal con los detalles de la venta
    }
}

