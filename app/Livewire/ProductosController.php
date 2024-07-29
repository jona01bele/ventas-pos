<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination; // trait para la paginacion

use App\Models\Producto;


class ProductosController extends Component
{
    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait
    

    public $nombre, $codigo, $costo, $precio, $imagen, $stock, $alertas, $categoriaid, 
            $buscador, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 5;
  
    
    //ciclo de vida de los componetes de livewire
    //generalmete se utilizar para inicilazar propiedades o inicilaizar data de nuestros componetes
    public function mount(){

        $this->tituloPagina = 'LISTADO';
        $this->componenteNombre = 'PRODUCTOS';
        $this->categoriaid = 'Elegir';
    }
     
    // metodo para personalizar paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
       //llenar el select con las categorias
       $categorias = Categoria::orderBy('nombre', 'asc')->get();
       
       // si solo deseocolocar la paginacion con la consulta de todos los datos no es necesario el metodo get o all
       // $productos = Producto::paginate($this->paginacion);
        if(strlen($this->buscador) > 0){
            //realiza la busqueda con el nombre
             //$productos = Producto::where('nombre' , 'like', '%' . $this->buscador . '%' )->paginate($this->paginacion);
             //$productos = Producto::where('nombre', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
            // realiza la busqueda con el nombre y con el codigo de barras.
            $productos = Producto::where('nombre', 'like', '%' . $this->buscador . '%' )
            ->orWhere('codigobarras' , 'like', '%' . $this->buscador . '%')
            ->paginate($this->paginacion);
        }
        else{
          //  $productos = Producto::orderBy('id', 'desc')->paginate($this->paginacion);
            $productos = Producto::orderBy('id', 'desc')->paginate($this->paginacion);
        }
       
       return view('livewire.productos.productos', compact('productos' , 'categorias'))
        ->extends('ventapos.layouts.admin')->section('contenido');
    }

    //-----metodo para registrar los productos--------------------------------------------------
    public function store()
    { 
        // dd( 'el costo sugerido es : ',$this->costo);
        $rules = [
            'nombre' => 'required|unique:productos|min:3',
            'costo' => 'required',
            'precio' => 'required',
            'stock' => 'required',   
            'alertas' => 'required',
            'categoriaid' => 'required|not_in:Elegir' // campo requerido y para que deje seleeccionar donde diga Elegir
        ];

        $mensajes = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.unique' => 'La información no puede ser duplicada',
            'nombre.min' => 'Catacteres insuficientes',
            'costo.required' => 'El costo debe ser ingresado',
            'precio.required' => 'El precio debe ser ingresado',
            'stock.required' => 'El stock debe ser ingresado',
            'alertas.required' => 'Las alertas son requeridas',
            'categoriaid.required' => 'Debe seleccionar la categoria',
            'categoriaid.not_in' => 'Debe seleccionar una opción valida',
        ];

        //ejecuta las validaciones
        $this->validate($rules, $mensajes);

        //---dar formato al costo
        $costoSinFormato = str_replace(',', '', $this->costo);
        $costoSinFormato = str_replace('.', '', $costoSinFormato);
        $costoSinFormato = (float) $costoSinFormato;
        //---dar formato al precio
        $precioSinFormato = str_replace(',', '', $this->precio);
        $precioSinFormato = str_replace('.', '', $precioSinFormato);
        $precioSinFormato = (float) $precioSinFormato;

        // Formatear costo y precio
        
        $producto = Producto::create([
            'nombre' => $this->nombre,
            'codigobarras' => $this->codigo,
            'categoria_id' => $this->categoriaid,
            'costo' => $costoSinFormato,    
            'precio' => $precioSinFormato,
            'stock' => $this->stock,
            'alertas' => $this->alertas,
        ]);

        $clientearchivos = "";

        //validar la imagen
        if ($this->imagen) {
            $clientearchivos = uniqid() . '_.' . $this->imagen->extension();
            $this->imagen->storeAs('public/productos', $clientearchivos); //guarda la imagen en el disco
            $producto->imagen = $clientearchivos;
            $producto->save();  // guarda la imagen en la base de datos
        }

        $this->limpiar();
        //evento para mostrar el mensaje y cerrar el modal si es caso
        $this->dispatch('productos-agregado', 'Producto Registrado');
    }
    
    //--- funcion para traer los datos que se van actualizar.---------
    //public function editar($id) -> se puede hace de esta manera para llamar el codigo pero tambien con el nombre del modelo laravel detecta el codigo
    public function editar(Producto $producto) //-> laravel detecta el id automatico
    {
        // esto trae todos los datos y funciona pero como buenas practicas podemos optimizar
        //$grabar = Producto::find($id);
        //esta linea de codigo se agrega en caso que se coloque $id en la funcion
       // $grabar = Producto::find($id, ['id', 'nombre', 'codigobarras', 'costo', 'precio' , 'stock', 'alertas' ]);  //->metodo optimizado
       // $this->seleccionar_id = $grabar->id;  -> para traer los datos seria asi con el $id en la funcion
       $this->seleccionar_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->codigo = $producto->codigobarras;
        $this->categoriaid = $producto->categoria_id;
        $this->costo = $producto->costo;
        $this->precio = $producto->precio;
        $this->stock = $producto->stock;
        $this->alertas = $producto->alertas;
        $this->imagen = null;
        // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
        // $this->emit('show-modal', 'show modal!'); --> metodo anterior
        $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
    }
    
    //----funcion para actualizar-------
    public function update()
    {
        $rules = [
            'nombre' => "required|min:3|unique:productos,nombre,{$this->seleccionar_id}",
            'costo' => 'required',
            'precio' => 'required',
            'stock' => 'required',   
            'alertas' => 'required',
                'categoriaid' => 'required|not_in:Elegir'
        ];
        $mensaje = [
            'nombre.required' => 'Debe ingresar el nombre',
            'nombre.min' => 'El nombre mínimo debe tener 3 caracteres',
            'nombre.unique' => 'El nombre de la categoría ya existe',
            'costo.required' => 'El costo debe ser ingresado',
            'precio.required' => 'El precio debe ser ingresado',
            'stock.required' => 'El stock debe ser ingresado',
            'alertas.required' => 'Las alertas son requeridas',
            'categoriaid.required' => 'Debe seleccionar la categoria',
            'categoriaid.not_in' => 'Debe seleccionar una opción valida',
        ];
        $this->validate($rules, $mensaje);

        //---dar formato al costo
        $costoSinFormato = str_replace(',', '', $this->costo);
        $costoSinFormato = str_replace('.', '', $costoSinFormato);
        $costoSinFormato = (float) $costoSinFormato;
        //---dar formato al precio
        $precioSinFormato = str_replace(',', '', $this->precio);
        $precioSinFormato = str_replace('.', '', $precioSinFormato);
        $precioSinFormato = (float) $precioSinFormato;

        // Busca el producto según el código
        $producto = Producto::find($this->seleccionar_id);
        $producto->update([
            'nombre' => $this->nombre,
            'codigobarras' => $this->codigo,
            'costo' => $costoSinFormato,
            'categoria_id' => $this->categoriaid,
            'precio' => $precioSinFormato,
            'stock' => $this->stock,
            'alertas' => $this->alertas,

        ]);


        if ($this->imagen) {
            // El método uniqid para dar un valor único
            $archivocliente = uniqid() . '_.' . $this->imagen->extension();
            // Ruta donde se va a depositar la imagen
            $this->imagen->storeAs('public/productos', $archivocliente);

            // Como se va a actualizar la imagen, es necesario borrar la existente
            $imagennombre = $producto->getImagenPath(); // Recupera el nombre del archivo de la imagen que está guardada
            $producto->imagen = $archivocliente; // Asigna la nueva imagen
            $producto->save();

            if ($imagennombre != null) {
                if (file_exists(public_path('storage/productos/' . $imagennombre))) {
                    unlink(public_path('storage/productos/' . $imagennombre));
                }
            }

        }
        // Limpiar
        $this->limpiar();
        // Evento para mostrar el mensaje y cerrar el modal
        $this->dispatch('producto-actualizado', 'Producto Actualizado');
    }

    protected $listeners = ['eliminarFila'];

    public function eliminarFila($id)
    {
        $producto = Producto::find($id);
        // dd($producto);

        if ($producto) {
            // Usar el método getImagenPath() para obtener solo el nombre del archivo
            $nombreimagen = $producto->getImagenPath();
            $producto->delete();  // Eliminar el registro de la base de datos

            // Validar si el nombre de la imagen no es nulo y eliminar la imagen del sistema de archivos
            if ($nombreimagen != null && file_exists(public_path('storage/productos/' . $nombreimagen))) {
                unlink(public_path('storage/productos/' . $nombreimagen));
            }

            $this->limpiar();
            // $this->dispatchBrowserEvent('categoria-eliminada', ['message' => 'Categoria eliminada']);
            $this->dispatch('producto-eliminado', 'Producto eliminado con exito');
        } else {
            // Manejar el caso cuando la categoría no se encuentra
            // $this->dispatchBrowserEvent('categoria-no-encontrada', ['message' => 'Categoria no encontrada']);
            $this->dispatch('producto-no-encontrado', 'Producto no encontrado');
        }
    }


    public function limpiar(){
        $this->nombre = '';
        $this->codigo = '';
        $this->categoriaid= 'Elegir';
        $this->costo = '';    
        $this->precio = '';
        $this->stock = '';
        $this->alertas = '';
        $this->imagen = null;
        $this->seleccionar_id = 0;
        //para que los errores de validacion se quiten 
        //cualquiera de los de abajo funcionan
        //$this->resetErrorBag();
        $this->resetValidation();
    }
}
