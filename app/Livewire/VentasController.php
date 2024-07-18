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
use Illuminate\Support\Facades\Log;

class VentasController extends Component
{
    public $total, $itemsCantidad, $efectivo, $cambio;
    public $titulo;



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
    public function AEfectivo($valor)
    {
        if ($this->total != 0) {
            //si el el boton que se preciona es cero =>'Exacto' me coloque el total + el efectivo--> coloca la cantidad a pagar.. de lo contrario
            // que sume el valor y el efectivo
            $this->efectivo += ($valor == 0 ? $this->total : $valor);
            //cuando ya se tiene el efectivo se se reste el total de la venta para saber canto hay que dar de cambio
            $this->cambio = $this->efectivo - $this->total;
        }
    }

    //se utilizan en el icono que esta fncion para resetear efectivo y cambio en cero
    public function resetEfectivoYCambio()
    {
        $this->efectivo = 0;
        $this->cambio = 0;
    }

    //de esta manera se ecuchan los eventos emitidos desde el fronend   
    protected $listeners = [
        'escanearCodigo',
        'eliminarItem',
        'limpiarCarrito',
        'guardarVenta',
        'eliminarTodo'

    ];

    // buscar el codigo a través de scanner codigo de barras o digitado
    // si este lo encuentra lo va agregar o mostrar en la tabla
    // recibe una cantidad por defecto que es 1
    //public function escanearCodigo($codigoBarras , $cantidad = 1)


    public function escanearCodigo($codigoBarras, $cantidad = 1)
    {
        // Busca el producto por su código de barras
        $producto = Producto::where('codigobarras', $codigoBarras)->first();

        // Verifica si el producto fue encontrado
        if ($producto === null || empty($producto)) {
            $this->dispatch('Producto-no-existe');
        } else {
            // Verifica si el producto ya está en el carrito
            if ($this->enCarrito($producto->id)) {
                // Si está en el carrito, incrementa la cantidad
                $this->incrementarCantidad($producto->id, $cantidad);
            } else {
                // Si no está en el carrito, valida el stock y añade el producto al carrito
                if ($producto->stock < $cantidad) {
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                } else {
                    // Agrega el producto al carrito
                    Carrito::create([
                        'producto_id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'precio' => $producto->precio,
                        'cantidad' => $cantidad,
                        'imagen' => $producto->imagen,
                        'total' => $producto->precio * $cantidad
                    ]);

                    // Actualiza el stock del producto
                    $producto->stock -= $cantidad;
                    $producto->save();

                    // Actualiza el carrito y despacha el evento
                    $this->updateCart();
                    $this->dispatch('busqueda-ok', 'Producto Agregado');
                }
            }
        }
    }





    //segunda funcion scanear
    //     public function escanearCodigo($codigoBarras, $cantidad = 1)
    // {
    //     // Busca el producto por código de barras
    //     $producto = Producto::where('codigobarras', $codigoBarras)->first();

    //     // Validar si el producto fue encontrado
    //     if ($producto === null || empty($producto)) {
    //         $this->dispatch('Producto-no-existe');
    //         return; // Detener el proceso si no se encuentra el producto
    //     }

    //     // Validar si el producto ya está en el carrito
    //     if ($this->enCarrito($producto->id)) {
    //         // Si está en el carrito, incrementar la cantidad
    //         $this->incrementarCantidad($producto->id, $cantidad);
    //         return; // Detener el proceso
    //     }

    //     // Validar si hay suficiente stock
    //     if ($producto->stock < $cantidad) {
    //         $this->dispatch('No-stock', 'Stock Insuficiente :/');
    //         return; // Detener el proceso si no hay suficiente stock
    //     }

    //     // Si todo está bien, insertar en el carrito
    //     Carrito::create([
    //         'producto_id' => $producto->id,
    //         'nombre' => $producto->nombre,
    //         'precio' => $producto->precio,
    //         'cantidad' => $cantidad,
    //         'imagen' => $producto->imagen
    //     ]);

    //     // Actualizar el stock del producto
    //     $producto->stock -= $cantidad;
    //     $producto->save();

    //     // Actualizar el carrito
    //     $this->updateCart();

    //     // Despachar evento de éxito
    //     $this->dispatch('busqueda-ok', 'Producto Agregado');
    // }


    //funcion para adquirir el total y la cantidad del carritos para anexarlo a sus valores
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

    // esta es la funcion 1...



    // public function incrementarCantidad($productoid, $cantidad = 1)
    // {
    //     $titulo = '';
    //     // Busca si el producto ya existe en el carrito
    //     $existe = Carrito::where('producto_id', $productoid)->first();
    //     //dd($existe);
    //     // Encuentra el producto en la tabla de productos
    //     $existeProducto = Producto::find($productoid);

    //     if ($existeProducto) {
    //         if ($existe) {
    //             // Si el producto ya existe en el carrito, actualiza la cantidad
    //             $titulo = 'Cantidad Actualizada';

    //             // Validar si hay suficiente stock
    //             if ($existeProducto->stock < ($cantidad + $existe->cantidad)) {

    //                 // Despacha un evento de stock insuficiente
    //                 $this->dispatch('No-stock', 'Stock Insuficiente :/');
    //                 return false;
    //             } 

    //             // Actualiza la cantidad y guarda
    //             $existe->cantidad += $cantidad;
    //             $existe->save(); // actualiza cantidad en la base de satos

    //             // Actualiza el stock del producto
    //             $existeProducto->stock -= $cantidad;

    //             $existeProducto->save();
    //             dd($existeProducto->stock);

    //             $this->updateCart();    // llama a la funcion para que se muestre el total y la cantidad de mi resumen de venta tambien


    //             $this->dispatch('busqueda-ok', $titulo); 
    //             //-------------------------- voy revisando hasta aca y vamos bien------------------------
    //         } else {

    //             // Si el producto no existe en el carrito, añade un nuevo registro o lo crea
    //             $titulo = 'Producto Agregado';

    //             // Validar si hay suficiente stock
    //             if ($existeProducto->stock < $cantidad) {
    //                 // Despacha un evento de stock insuficiente

    //                 $this->dispatch('No-stock', 'Stock Insuficiente :/');
    //                 return false;
    //             }

    //             Carrito::create([
    //                 'producto_id' => $existeProducto->id,
    //                 'nombre' => $existeProducto->nombre,
    //                 'precio' => $existeProducto->precio,
    //                 'cantidad' => $cantidad, // Usa la cantidad pasada como parámetro
    //                 'imagen' => $existeProducto->imagen,
    //                 'total' => $existeProducto->precio * $cantidad
    //             ]);


    //             // Actualiza el stock del producto
    //                 $existeProducto->stock -= $cantidad;
    //                 $existeProducto->save();;

    //             $this->updateCart();  // fucnion para que se actualice el total y ola cantidad de resumen de venta tambien
    //             $this->dispatch('busqueda-ok', $titulo);
    //         }
    //     } else {
    //         // Si el producto no existe en la tabla de productos
    //         $this->dispatch('Producto-no-existe', 'Producto no encontrado');
    //     }
    // }



    //esta es la funcion 2

    // esta fucncion ed la misma de arriba la diferencia es que esta se utiliza transaccion para 
    // asegurar que las operaciones que se realizan en este proceso se realicen de manera atomica.
    // esto quiere decir que si falla una no se realiza ninguna.
    // NOTA = "me parece excelente para realizar pagos y en genera por la seguridad del proceso"
    // ya que si un proceso se raliza y el otro no va existir un desbalance he en la integridad de los datos
    // ya que una cambia pero el otro no


    public function incrementarCantidad($productoid, $cantidad = 1)
    {
        DB::beginTransaction();

        try {
            $titulo = '';
            $existe = Carrito::where('producto_id', $productoid)->first();
            $existeProducto = Producto::find($productoid);

            if ($existeProducto) {
                Log::info("Stock antes de la operación para producto ID $productoid: " . $existeProducto->stock);

                // Verifica si el producto ya está en el carrito
                if ($existe) {
                    $titulo = 'Cantidad Actualizada';

                    // Validar si hay suficiente stock
                    if ($existeProducto->stock < $cantidad) {
                        $this->dispatch('No-stock', 'Stock Insuficiente :/');
                        DB::rollBack();
                        return false;
                    }

                    // Actualiza la cantidad en el carrito
                    $existe->cantidad += $cantidad;
                    $existe->save();
                } else {
                    $titulo = 'Producto Agregado';

                    // Validar si hay suficiente stock
                    if ($existeProducto->stock < $cantidad) {
                        $this->dispatch('No-stock', 'Stock Insuficiente :/');
                        DB::rollBack();
                        return false;
                    }

                    // Agregar el producto al carrito
                    Carrito::create([
                        'producto_id' => $existeProducto->id,
                        'nombre' => $existeProducto->nombre,
                        'precio' => $existeProducto->precio,
                        'cantidad' => $cantidad,
                        'imagen' => $existeProducto->imagen,
                        'total' => $existeProducto->precio * $cantidad
                    ]);
                }

                // Actualiza el stock del producto
                $existeProducto->stock -= $cantidad;
                $existeProducto->save();

                Log::info("Stock después de la operación para producto ID $productoid: " . $existeProducto->stock);

                // Actualiza el carrito y despacha el evento
                $this->updateCart();
                $this->dispatch('busqueda-ok', $titulo);
            } else {
                $this->dispatch('Producto-no-existe', 'Producto no encontrado');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ocurrió un error: ' . $e->getMessage());
            $this->dispatch('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }







    // la funcion de arriba busca actualizar solo la cantidad y este va actualizar todo.. con el input number
    //ojo con este metodo que esta en el curso

    // public function ActualizarCantidad($productoid, $cantidad = 1)
    // {
    //     $titulo = '';
    //     // Busca si el producto ya existe en el carrito
    //     $existe = Carrito::where('producto_id', $productoid)->first();
    //     // Encuentra el producto en la tabla de productos
    //     $existeProducto = Producto::find($productoid);

    //     if ($existeProducto) {
    //         // Si el producto ya existe en el carrito
    //         if ($existe) {
    //             // Validar si hay suficiente stock
    //             if ($existeProducto->stock < ($cantidad + $existe->cantidad)) {
    //                 // Despacha un evento de stock insuficiente
    //                 $this->dispatch('No-stock', 'Stock Insuficiente :/');
    //                 return false;
    //             }

    //             // Elimina el ítem actual del carrito
    //             $this->eliminarItem($productoid);
    //             $titulo = 'Cantidad Actualizada';
    //         } else {
    //             // Si el producto no existe en el carrito
    //             // Validar si hay suficiente stock
    //             if ($existeProducto->stock < $cantidad) {
    //                 // Despacha un evento de stock insuficiente
    //                 $this->dispatch('No-stock', 'Stock Insuficiente :/');
    //                 return false;
    //             }
    //             $titulo = 'Producto Agregado';
    //         }

    //         // Añade un nuevo registro con la cantidad actualizada
    //         Carrito::create([
    //             'producto_id' => $existeProducto->id,
    //             'nombre' => $existeProducto->nombre,
    //             'precio' => $existeProducto->precio,
    //             'cantidad' => $cantidad, // Usa la cantidad pasada como parámetro
    //             'imagen' => $existeProducto->imagen
    //         ]);

    //         $this->updateCart(); // Actualiza el total
    //         $this->dispatch('busqueda-ok', $titulo);
    //     } else {
    //         // Si el producto no existe en la tabla de productos
    //         $this->dispatch('Producto-no-existe', 'Producto no encontrado');
    //     }
    // }



    // la funcion de arriba busca actualizar solo la cantidad y este va actualizar todo.. con el input number
    //ojo con este metodo que esta en el curso
    public function actualizarCantidad($productoid, $nuevaCantidad)
    {
        
        $titulo = '';

        // Busca si el producto ya existe en el carrito
        $existe = Carrito::where('producto_id', $productoid)->first();
        // Encuentra el producto en la tabla de productos
        $existeProducto = Producto::find($productoid);

        if ($existeProducto) {
            if ($existe) {
                // Calcula la cantidad que se está agregando al carrito
                $cantidadDiferencia = $nuevaCantidad - $existe->cantidad;

                // Validar si hay suficiente stock
                if ($existeProducto->stock < $cantidadDiferencia) {
                    $this->dispatch('No-stock', 'Stock Insuficiente :/');
                    return false;
                }

                // Actualiza la cantidad en el carrito
                $existe->cantidad = $nuevaCantidad;
                $existe->save();

                // Ajusta el stock del producto
                $existeProducto->stock -= $cantidadDiferencia;
                $existeProducto->save();

                $titulo = 'Cantidad Actualizada';
            } else {
                $this->dispatch('Producto-no-existe', 'Producto no encontrado en el carrito');
                return false;
            }

            // Actualiza el total
            $this->updateCart();
            $this->dispatch('Cantidad-actualizada', $titulo);
        } else {
            $this->dispatch('Producto-no-existe', 'Producto no encontrado');
        }
    }





    // funcion para eliminar items del carrito segun el codigo pasado
    public function eliminarItem($productoid)
    {

        //funcion para que me actualice el stock despued de eliminar items
        $this->devolucionStock();

        // Encuentra el primer registro en la tabla 'Carrito' que coincide con el 'producto_id' proporcionado
        $existe = Carrito::where('producto_id', $productoid)->first();

        // Verifica si se encontró el registro
        if ($existe) {
            // Elimina el registro encontrado
            $existe->delete();

            // despues de eliminar limpia y actualizar el carrito
            $this->limpiarCarrito();

            // Despacha un evento para notificar la eliminación exitosa
            $this->dispatch('eliminado-ok');

            return true; // Opcional: Devuelve true si se eliminó el registro
        }

        // Opcional: Devuelve false si no se encontró el registro
        return false;
    }


    // Funcion para eliminar todo el carrito:
    // public function eliminarTodo()
    // {
    //     // Vacía la tabla 'Carrito'
    //     Carrito::truncate();

    //     // Actualiza el carrito (puedes ajustar esta función según tu lógica)
    //     $this->updateCart();
    //     $this->limpiarCarrito();
    // }

    //funcion para actualizar el stock de cada producto.. cuando la venta no se lleva a cabo
    public function devolucionStock()
    {
        // Recuperar los productos y sus cantidades desde el carrito
        $itemsCarrito = Carrito::all();

        // Actualizar el stock de cada producto
        foreach ($itemsCarrito as $item) {
            $producto = Producto::find($item->producto_id);
            if ($producto) {
                $producto->stock += $item->cantidad;
                $producto->save();
            }
        }
    }
    public function eliminarTodo()
    {
        //actualiza el stock de productos por la cancelacion del carrito 
        $this->devolucionStock();

        // Vaciar la tabla 'Carrito' o eliminar su contenido
        Carrito::truncate();

        // Actualizar el carrito y limpiar
        $this->updateCart();
        $this->limpiarCarrito();

        $this->dispatch('eliminado-total');
    }


    // //funcion para incrementar cuando solo se elimina 1 item
    // public function stockItem($cant){
    //     //Nota.. aca no se puede parasar la fucnion de devolucionStock.. 
    //     //por que esta gurda todas las cantidades disponibles y aca solo se resta 1 cantidad

    //     $itemsCarrito = Carrito::all();

    //     // Actualizar el stock de cada producto
    //     foreach ($itemsCarrito as $item) {
    //         $producto = Producto::find($item->producto_id);

    //         if ($producto) {
    //             $producto->stock += $cant;
    //             $producto->save();
    //         }
    //     } 
    // }



    // Función para incrementar el stock cuando se elimina una cantidad del carrito
    public function stockItem($productoid, $cant)
    {
        $producto = Producto::find($productoid);

        if ($producto) {
            $producto->stock += $cant;
            $prueba =  $producto->stock;
            $producto->save();
        }
    }


    // metodo para decrementar la cantidad o actualiza la cantidad.
    public function restarCantidad($productoid, $cantidad = 1)
    {
        //utilizar esta devolucion para que se regresen las unidades o item al stock

        // Busca si el producto ya existe en el carrito
        $existe = Carrito::where('producto_id', $productoid)->first();

        if ($existe) {
            // Resta la cantidad y verifica si es menor o igual a cero. si es cero me elimina el item con su funcion
            if ($existe->cantidad - $cantidad <= 0) {

                $this->stockItem($productoid, $existe->cantidad);
                // Si la cantidad es cero o menor, elimina el ítem del carrito
                Carrito::truncate();
                // Actualizar el carrito y limpiar
                $this->updateCart();
                $this->limpiarCarrito();
                $this->titulo = 'Producto Eliminado del Carrito';
                $this->dispatch('eliminar-items');
            } else {
                $this->stockItem($productoid, $cantidad);
                // Si la cantidad es mayor a cero, actualiza la cantidad
                $existe->cantidad -= $cantidad;
                $existe->save();
                $this->titulo = 'Cantidad Reducida';
            }

            // Actualiza el total y cantidad
            $this->updateCart();
            // $this->dispatch('busqueda-ok', $titulo);
        } else {
            // Si el producto no existe en el carrito
            $this->dispatch('Producto-no-existe', 'Producto no encontrado en el carrito');
        }
    }



    // metodo para limpiar toda la infomacion del carrito
    public function limpiarCarrito()
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
        if ($this->total == 0) {
            $this->dispatch('venta-NOaGREGADA', 'AGREGAR PRODUCTOS A LA VENTA');
            return;
        }

        if ($this->efectivo <= 0) {
            $this->dispatch('venta-error', 'INGRESA EL EFECTIVO');
            return;
        }
        if ($this->total > $this->efectivo) {
            $this->dispatch('venta-error', 'EFECTIVO INSUFICIENTE');
            return;
        }
        DB::beginTransaction();

        try {
            //Guardar la venta
            $venta = Venta::create([

                'total' => $this->total,
                'item' => $this->itemsCantidad,
                'cantidadpagada' => $this->efectivo,
                'cambio' => $this->cambio,
                'id_usuario' => Auth::user()->id
            ]);

            // hacer una validacion si ya se guardo la venta
            //luego guardar en detalles de ventas
            if ($venta) {
                //tomar lo que esta en carrito nuevamente para guardar en detalles
                $carritos = Carrito::all();

                foreach ($carritos as $carrito) {
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

            Carrito::truncate();
            $this->limpiarCarrito();
            //$this->dispatch('busqueda-ok', 'Venta registrada con exito');
            $this->dispatch('transaccion-ok');
            // ACA SE ENVIA EL ID VENTA PARA QUE LA APLICACION DE C# PUEDA DETECTAR LA VENTA
            $this->dispatch('imprimir-tickect', $venta->id);
        } catch (Exception $e) {

            // Y por ultimo si se genera un error lo va gardar la $e y se utiliza un roll back para echar el proceso atras
            DB::rollBack();
            $this->dispatch('venta-error', $e->getMessage());
        }
    }

    // Metodo para poder imprimir los ticket
    public function imprimirTicket($sale)
    {
        //cuando se ejecute esto la aplicaion en c# lo va detectar y se manda imprimir los recibos
        return Redirect::to("print://$sale->id");
    }
}
