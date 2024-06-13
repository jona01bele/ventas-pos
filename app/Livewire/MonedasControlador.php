<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination; // trait para la paginacion
use App\Models\Denominacion;

class MonedasControlador extends Component
{
   


    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait
    

    public $tipomoneda, $valor, $imagen,
            $buscador, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 5;
  
    
    //ciclo de vida de los componetes de livewire ... es el primer metodo que se ejecuta
    //generalmete se utilizar para inicilazar propiedades o inicilaizar data de nuestros componetes
    public function mount(){

        $this->tituloPagina = 'LISTADO';
        $this->componenteNombre = 'DENOMINACIONES';
        $this->seleccionar_id = 0;
        $this->tipomoneda= 'Elegir';
    }
     
    // metodo para personalizar paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
       $denominaciones = Denominacion::where('tipomoneda')->get();
       // si solo deseocolocar la paginacion con la consulta de todos los datos no es necesario el metodo get o all
       // $productos = Producto::paginate($this->paginacion);
        if(strlen($this->buscador) > 0){
            //realiza la busqueda con el nombre
             //$productos = Producto::where('nombre' , 'like', '%' . $this->buscador . '%' )->paginate($this->paginacion);
             //$productos = Producto::where('nombre', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
            // realiza la busqueda con el nombre y con el codigo de barras.
            $monedas = Denominacion::where('tipomoneda', 'like', '%' . $this->buscador . '%' )
            ->orWhere('valor' , 'like', '%' . $this->buscador . '%')
            ->paginate($this->paginacion);
        }
        else{
          //  $productos = Producto::orderBy('id', 'desc')->paginate($this->paginacion);
            $monedas = Denominacion::orderBy('tipomoneda', 'asc')->paginate($this->paginacion);
        }
       
       return view('livewire.monedas.monedas', compact('monedas', 'denominaciones'))
        ->extends('ventapos.layouts.admin')->section('contenido');
    }

    //-----metodo para registrar los productos--------------------------------------------------
    public function store()
    { 
        // dd( 'el costo sugerido es : ',$this->costo);
        $rules = [
            'tipomoneda' => 'required|min:3',
            'valor' => 'required|min:3',
        ];

        $mensajes = [
            'tipomoneda.required' => 'ingrse el tipo de moneda',
            'tipomoneda.min' => 'Catacteres insuficientes',
            'valor.required' => 'Debe ingresar un valor',
            'valor.min' => 'Valor insuficiente',
            
        ];

        //ejecuta las validaciones
        $this->validate($rules, $mensajes);

        //---dar formato a la moneda
         $monedaFormato = str_replace(',', '', $this->valor);
         $monedaFormato = str_replace('.', '', $monedaFormato);
         $monedaFormato = (float) $monedaFormato;

        // Formatear costo y precio
        
        $denominacion = Denominacion::create([
            'tipomoneda' => $this->tipomoneda,
            'valor' => $monedaFormato,
        ]);

        $monedaarchivo = "";

        //validar la imagen
        if ($this->imagen) {
            $monedaarchivo = uniqid() . '_.' . $this->imagen->extension();
            $this->imagen->storeAs('public/monedas', $monedaarchivo); //guarda la imagen en el disco
            $denominacion->imagen = $monedaarchivo;
            $denominacion->save();  // guarda la imagen en la base de datos
        }

        $this->limpiar();
        //evento para mostrar el mensaje y cerrar el modal si es caso
        $this->dispatch('denominacion-agregada', 'Denominación Registrada');
    }
    
    //--- funcion para traer los datos que se van actualizar.---------
    //public function editar($id) -> se puede hace de esta manera para llamar el codigo pero tambien con el nombre del modelo laravel detecta el codigo
    public function editar(Denominacion $moneda) //-> laravel detecta el id automatico
    {
        // esto trae todos los datos y funciona pero como buenas practicas podemos optimizar
        //$grabar = Producto::find($id);
        //esta linea de codigo se agrega en caso que se coloque $id en la funcion
       // $grabar = Producto::find($id, ['id', 'nombre', 'codigobarras', 'costo', 'precio' , 'stock', 'alertas' ]);  //->metodo optimizado
       // $this->seleccionar_id = $grabar->id;  -> para traer los datos seria asi con el $id en la funcion
       $this->seleccionar_id = $moneda->id;
        $this->tipomoneda = $moneda->tipomoneda;
        $this->valor = $moneda->valor;
        $this->imagen = null;
        // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
        // $this->emit('show-modal', 'show modal!'); --> metodo anterior
        $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
    }
    
    //----funcion para actualizar-------
    public function update()
    {
        $rules = [
            'tipomoneda' => 'required|min:3',
            'valor' => 'required|min:3',
        ];

        $mensajes = [
            'tipomoneda.required' => 'ingrse el tipo de moneda',
            'tipomoneda.min' => 'Catacteres insuficientes',
            'valor.required' => 'Debe ingresar un valor',
            'valor.min' => 'Valor insuficiente',
            
        ];

        $this->validate($rules, $mensajes);
        //---dar formato a la moneda
         $monedaFormato = str_replace(',', '', $this->valor);
         $monedaFormato = str_replace('.', '', $monedaFormato);
         $monedaFormato = (float) $monedaFormato;

        // Busca el producto según el código
        $denominacion = Denominacion::find($this->seleccionar_id);
        $denominacion->update([
            'tipomoneda' => $this->tipomoneda,
            'valor' => $monedaFormato,
        ]);


        if ($this->imagen) {
            // El método uniqid para dar un valor único
            $monedaarchivo = uniqid() . '_.' . $this->imagen->extension();
            // Ruta donde se va a depositar la imagen
            $this->imagen->storeAs('public/monedas', $monedaarchivo);

            // Como se va a actualizar la imagen, es necesario borrar la existente
            $imagennombre = $denominacion->getImagenPath(); // Recupera el nombre del archivo de la imagen que está guardada
            $denominacion->imagen = $monedaarchivo; // Asigna la nueva imagen
            $denominacion->save();

            if ($imagennombre != null) {
                if (file_exists(public_path('storage/monedas/' . $imagennombre))) {
                    unlink(public_path('storage/monedas/' . $imagennombre));
                }
            }

        }
        // Limpiar
        $this->limpiar();
        // Evento para mostrar el mensaje y cerrar el modal
        $this->dispatch('denomimacion-actualizada', 'Denominación Actualizada');
    }

    protected $listeners = ['eliminarFila'];

    public function eliminarFila($id)
    {
        $denominacion = Denominacion::find($id);
        // dd($producto);

        if ($denominacion) {
            // Usar el método getImagenPath() para obtener solo el nombre del archivo
            $nombreimagen = $denominacion->getImagenPath();
            $denominacion->delete();  // Eliminar el registro de la base de datos

            // Validar si el nombre de la imagen no es nulo y eliminar la imagen del sistema de archivos
            if ($nombreimagen != null && file_exists(public_path('storage/monedas/' . $nombreimagen))) {
                unlink(public_path('storage/monedas/' . $nombreimagen));
            }

            $this->limpiar();
            // $this->dispatchBrowserEvent('categoria-eliminada', ['message' => 'Categoria eliminada']);
            $this->dispatch('moneda-eliminada', 'Moneda eliminada con exito');
        } else {
            // Manejar el caso cuando la categoría no se encuentra
            // $this->dispatchBrowserEvent('categoria-no-encontrada', ['message' => 'Categoria no encontrada']);
            $this->dispatch('moneda-no-encontraa', 'Moneda no encontrada');
        }
    }


    public function limpiar(){
        $this->tipomoneda = 'Elegir';
        $this->valor = '';        
        $this->imagen = null;
        $this->seleccionar_id = 0;
    }
}
