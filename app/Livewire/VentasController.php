<?php

namespace App\Livewire;

use App\Models\Denominacion;
use App\Models\Producto;
use Livewire\Component;
use App\Models\Carrito;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\VentaDetalle;
use Illuminate\Support\Facades\Redirect;

class VentasController extends Component
{
    public $total, $itemsCantidad, $efectivo, $cambio;

    

    // mount es el primer componente que se ejecuta en el ciclo de vida de livewire
    //para inicializar las nuestras propieddes en cero
    public function mount()
    {
        $this->efectivo = 0;
        $this->cambio = 0;
        //   $this->total = Carrito::sum('total'); // suma el total de la columna total de la tabla carrito
        //   $this->itemsCantidad = Carrito::sum('cantidad'); //el itemsCantidad que esta en la table carritos
        $this->updateCart(); // con esta fucnion hace los dos procesos que estan arriba
    }

    // public function updateCart()
    // {
    //     $carritos = Carrito::all();

    //     $this->total = $carritos->sum(function($carrito) {
    //         return $carrito->total; //colocar el total que esta en la table carrito
    //     });
    //    // $this->itemsCantidad = $carritos->sum('cantidad'); //suma todas las cantidades
    // }

    public function render()
    {
        //la variable publica monedas tambien se puede utilizar de la manera tradicional. con el compact y sin el this desde el propío render
        // $this->monedas = Denominacion::all();
        $monedas = Denominacion::orderBy('valor', 'desc')->get();
        $carritos = Carrito::orderBy('nombre', 'asc')->get();

        // $carritos= Carrito::getContent()->sortBy('nombre');

        return view('livewire.ventas.ventas', compact('monedas', 'carritos'))
            ->extends('ventapos.layouts.admin')->section('contenido');
    }
    //cuando se precione un boton de denominaiones se ejecuta esta funcion.
    //la finalidad que que el valor que este boton tenga se le pase a la sesion efectivo 
    //tambien se realizar la operacion para que el total del cambio
    public function AEfecivo($valor)
    {
        //si el el boton que se preciona es cero =>'Exacto' me coloque el total + el efectivo--> coloca la cantidad a pagar.. de lo contrario
        // que sume el valor y el efectivo
        $this->efectivo += ($valor == 0 ? $this->total : $valor);
        //cuando ya se tiene el efectivo se se reste el total de la venta para saber canto hay que dar de cambio
        $this->cambio = $this->efectivo - $this->total;
    }

    //de esta manera se ecuchan los eventos emitidos desde el fronend
    protected $listeners = [
        'escanearCodigo',
        'eliminarItem',
        'limpiarCarrito',
        'guardarVenta',
       
    ];
    
    //buscar el codigo a través de scanner codigo de barras o digitado
    // si este lo encuentra lo va agregar o mostrar en la tabla
    // recibe una cantidad por defecto que es 1
   // public function escanearCodigo($codigoBarras , $cantidad = 1)
    public function escanearCodigo($codigoBarras , $cantidad = 1)
    {   
       
        // dd($codigoBarras); // Para depuración
        //me trae el producto con el id que se le ha pasado
        $producto = Producto::where('codigobarras', $codigoBarras)->first();

       // dd($producto);

        //validar si el producto fue encontrado
        if ($producto === null || empty($producto)) {
            
            $this->dispatch('busqueda-noencontrada', 'El producto  no esta registrado');
        } else {

            // validar con esta fucion si el producto o el codigo ya esta en el carrito la finalidad es que se actualice la cantidad y no se repita el codigo
            if ($this->enCarrito($producto->id)) {
                // si esta que la actulilce o que la incremente con la siguiente funcion. 
                //su logica esta desrrollada abajo
                $this->incrementarCantidad($producto->id);
                return; // para que el proceso se detenga
            }

            //Validar si el stock es suficiente o si hay stock
            if ($producto->stock < 1) {
                $this->dispatch('No-stock', 'Stock Insuficiente :/');
            }

            // Si todo esta bien luego insertar en el carrito

            Carrito::create([
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad, // esta en la cantidad del parametro de la funcion inicial
                'imagen' => $producto->imagen

            ]);
            // cuando se guarda el carrito se actualiza la cantidad y el  total
            $this->updateCart();

            $this->dispatch('busqueda-ok', 'Producto Agregado');
        }
    }


    //funcion para adquirir el total de carritos para anexarlo a sus valores
    public function updateCart()
    {
        $carritos = Carrito::all();

        // Calcula el total acumulado
        $this->total = $carritos->sum(function ($carrito) {
            return $carrito->precio * $carrito->cantidad;
        });
        // Calcula la cantidad total de items
        $this->itemsCantidad = $carritos->sum('cantidad');
    }


    //Valida si ya el id del producto ya existe en el carrito esta funcion se esta utilizando en la funcion de arriba
    public function enCarrito($productoId)
    {
        // Busca si existe el producto en el carrito
        $existencia = Carrito::where('producto_id', $productoId)->first();

        if ($existencia) {
            // Si el producto existe en el carrito
            // Aquí puedes poner lo que quieres que haga si el producto ya está en el carrito
            return true;
        } else {
            // Si el producto no existe en el carrito
            return false;
        }
    }


    // esta funcion busca acatualizar la cantidad  con dos condiciones.. si existe la actualizar y sin no esta el registro lo crea

    public function incrementarCantidad($productoid, $cantidad = 1)
    {
        $titulo = '';
        // Busca si el producto ya existe en el carrito
        $existe = Carrito::where('producto_id', $productoid)->first();
        // Encuentra el producto en la tabla de productos
        $existeProducto = Producto::find($productoid);

        if ($existeProducto) {
            if ($existe) {
                // Si el producto ya existe en el carrito, actualiza la cantidad
                $titulo = 'Cantidad Actualizada';

                // Validar si hay suficiente stock
                if ($existeProducto->stock < ($cantidad + $existe->cantidad)) {
                    // Despacha un evento de stock insuficiente
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                    return false;
                }

                // Actualiza la cantidad y guarda
                $existe->cantidad += $cantidad;
                $existe->save();
                $this->updateCart();    // funcion para que se actulice el total tambien

                $this->dispatch('busqueda-ok', $titulo);
            } else {

                // Si el producto no existe en el carrito, añade un nuevo registro o lo crea
                $titulo = 'Producto Agregado';

                // Validar si hay suficiente stock
                if ($existeProducto->stock < $cantidad) {
                    // Despacha un evento de stock insuficiente
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                    return false;
                }

                Carrito::create([
                    'producto_id' => $existeProducto->id,
                    'nombre' => $existeProducto->nombre,
                    'precio' => $existeProducto->precio,
                    'cantidad' => $cantidad, // Usa la cantidad pasada como parámetro
                    'imagen' => $existeProducto->imagen,
                    'total' => $existeProducto->precio * $cantidad
                ]);
                $this->updateCart();  // fucnion para que se actualice el total tambien
                $this->dispatch('busqueda-ok', $titulo);
            }
        } else {
            // Si el producto no existe en la tabla de productos
            $this->dispatch('Producto-no-existe', 'Producto no encontrado');
        }
    }

    // la funcion de arriba busca actualizar solo la cantidad y este va actualizar todo

    public function ActualizarCantidad($productoid, $cantidad = 1)
    {
        $titulo = '';
        // Busca si el producto ya existe en el carrito
        $existe = Carrito::where('producto_id', $productoid)->first();
        // Encuentra el producto en la tabla de productos
        $existeProducto = Producto::find($productoid);

        if ($existeProducto) {
            // Si el producto ya existe en el carrito
            if ($existe) {
                // Validar si hay suficiente stock
                if ($existeProducto->stock < ($cantidad + $existe->cantidad)) {
                    // Despacha un evento de stock insuficiente
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                    return false;
                }

                // Elimina el ítem actual del carrito
                $this->eliminarItem($productoid);
                $titulo = 'Cantidad Actualizada';
            } else {
                // Si el producto no existe en el carrito
                // Validar si hay suficiente stock
                if ($existeProducto->stock < $cantidad) {
                    // Despacha un evento de stock insuficiente
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                    return false;
                }
                $titulo = 'Producto Agregado';
            }

            // Añade un nuevo registro con la cantidad actualizada
            Carrito::create([
                'producto_id' => $existeProducto->id,
                'nombre' => $existeProducto->nombre,
                'precio' => $existeProducto->precio,
                'cantidad' => $cantidad, // Usa la cantidad pasada como parámetro
                'imagen' => $existeProducto->imagen
            ]);

            $this->updateCart(); // Actualiza el total
            $this->dispatch('busqueda-ok', $titulo);
        } else {
            // Si el producto no existe en la tabla de productos
            $this->dispatch('Producto-no-existe', 'Producto no encontrado');
        }
    }

    // funcion para eliminar que se encuantra arriba.. y tambien se utiliza en el llamado del evento    
    public function eliminarItem($productoid)
    {
        $existe = Carrito::where('producto_id', $productoid)->first();
        if ($existe) {
            $existe->delete();

            $this->updateCart(); // Actualiza el total
            $this->dispatch('busqueda-ok', 'producto Eliminado');
        }
        return false;
    }

    // metodo para decrementar la cantidad o actualiza la cantidad.
    public function restarCantidad($productoid, $cantidad = 1)
    {
        $titulo = '';
        // Busca si el producto ya existe en el carrito
        $existe = Carrito::where('producto_id', $productoid)->first();

        if ($existe) {
            // Resta la cantidad y verifica si es menor o igual a cero
            if ($existe->cantidad - $cantidad <= 0) {
                // Si la cantidad es cero o menor, elimina el ítem del carrito
                $this->eliminarItem($productoid);
                $titulo = 'Producto Eliminado del Carrito';
            } else {
                // Si la cantidad es mayor a cero, actualiza la cantidad
                $existe->cantidad -= $cantidad;
                $existe->save();
                $titulo = 'Cantidad Reducida';
            }

            // Actualiza el total y cantidad
            $this->updateCart();
            $this->dispatch('busqueda-ok', $titulo);
        } else {
            // Si el producto no existe en el carrito
            $this->dispatch('Producto-no-existe', 'Producto no encontrado en el carrito');
        }
    }



    // metodo para limpiar toda la infomacion del carrito
    public function limpiarItem()
    {
        $this->efectivo = 0;
        $this->cambio = 0;

         // Actualiza el total y cantidad
         $this->updateCart();
         $this->dispatch('busqueda-ok', 'Carrito vacio');

    }

   
    public function guardarVenta()
    {
        // 1) valiar total si total es 0 es por que no hay venta
        if($this->total= 0){
            $this->dispatch('venta-error', 'AGREGAR PRODUCTOS A LA VENTA');
            return;
        }

        if($this->efectivo <= 0){
            $this->dispatch('venta-error', 'INGRESA EL EFECTIVO');
            return;
        }
        if($this->total > $this->efectivo){
            $this->dispatch('venta-error', 'EFECTIVO INSUFICIENTE');
            return;
        }
        DB::beginTransaction();

        try{
            //Guardar la venta
            $venta = Venta::create([

                'total' => $this->total,
                'item' => $this->itemsCantidad,
                'cantidadpagada' => $this->efectivo,
                'cambio' => $this->cambio,
                 'id_usuario'=> Auth::user()->id
            ]);

            // hacer una validacion si ya se guardo la venta
            //luego guardar en detalles de ventas
            if($venta){
                //tomar lo que esta en carrito nuevamente para guardar en detalles
                $carritos = Carrito::all();

               foreach ($carritos as $carrito){
                    VentaDetalle::create([
                        'precio' => $carrito->precio,
                        'cantidad' => $carrito->cantidad,
                        'ventas_id' => $venta->id,
                        'productos_id' => $carrito->producto_id,
                    ]);  // hasta este punto ya se guarda

                    // actualizar el STOCK DEL PRODUCTO ya que se hizo un movimetno
                    $productos = Producto::find($carrito->producto_id);
                    $productos->stock = $productos->stock - $carrito->cantidad;
                    //guardar o actualizar el nuevo valor en ka base de datos
                    $productos->save();
                }
            }
            // conformacion de la transaccion
            DB::commit();

            $this->limpiarItem();
            $this->dispatch('busqueda-ok', 'Venta registrada con exito');
            // ACA SE ENVIA EL ID VENTA PARA QUE LA APLICACION DE C# PUEDA DETECTAR LA VENTA
            $this->dispatch('imprimir-tickect', $venta->id);
           
        }catch(Exception $e){
             
            // Y por ultimo si se genera un error lo va gardar la $e y se utiliza un roll back para echar el proceso atras
            DB::rollBack();
            $this->dispatch('venta-error', $e->getMessage());
        }

    }

    // Metodo para poder imprimir los ticket
    public function imprimirTicket($sale){
        
        //cuando se ejecute esto la aplicaion en c# lo va detectar y se manda imprimir los recibos
        return Redirect::to("print://$sale->id");
    }
}
