<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use App\Models\Categoria;
use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination;  // trait para la paginacion
use Livewire\Component;
use Illuminate\Support\Facades\Storage; // para poder manejar archivos o imagenes dentro de nuestro proyecto con laravel

class CategoriasController extends Component
{
    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait

    // propiedades o variable que vamos a utilizar en la vista
    public $nombre, $imagen, $buscador, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 2; // variable privada para la paginacin y una base 5
    //#[Layout('ventapos.layouts.admin')] // voy a probar con esta como apcion ya que el metodo ->layout() no me quiere funcionar

    public function mount()
    {
        $this->tituloPagina = 'Listado';
        $this->componenteNombre = 'Categorias';
    }

    // metodo para personalizar paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        //dd($this->buscador);
        //strlen metodo de php que te cuenta lo que hay en la peticion en este caso lo que se escriba en la caja de texto
        if (strlen($this->buscador) > 0) {

            $categorias = Categoria::where('nombre', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
        } else {
            $categorias = Categoria::orderBy('id', 'desc')->paginate($this->paginacion);
        }
        //$categorias = Categoria::all();
        return view('livewire.categoria.categorias', ['categorias' => $categorias])
            ->extends('ventapos.layouts.admin')->section('contenido');
        // de las dos forma deberia funcinar esto es una prueba
        //return view('livewire.categorias', ['categorias' => $categorias]);
    }

    //-----metodo para registrar las categorias------------------------------------------------------
    public function store()
    {
        $rules = [
            'nombre' => 'required|unique:categorias|min:3',
        ];

        $mensajes = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.unique' => 'La información no puede ser duplicada',
            'nombre.min' => 'Catacteres insuficientes',
        ];


        $this->validate($rules, $mensajes);

        $categoria = Categoria::create([
            'nombre' => $this->nombre
        ]);


        $clientearchivos = "";

        if ($this->imagen) {
            $clientearchivos = uniqid() . '_.' . $this->imagen->extension();
            $this->imagen->storeAs('public/categorias', $clientearchivos);
            $categoria->imagen = $clientearchivos;
            $categoria->save();
        }

        $this->limpiar();
        //evento para mostrar el mensaje y cerrar el modal si es caso
        $this->dispatch('categoria-agregada', 'Categoriar Registrada');
    }


    //----Funcion para editar------------------------------------------
    //aqui se recibe el id enviado desde el click para buscar la informacion que se va a mostrar en el modal.. pero aun no actualiza
    public function editar($id)
    {
        // esto trae todos los datos y funciona pero como buenas practicas podemos optimizar
        //$grabar = Categoria::find($id);
        $grabar = Categoria::find($id, ['id', 'nombre', 'imagen']);  //->metodo optimizado
        $this->nombre = $grabar->nombre;
        $this->seleccionar_id = $grabar->id;
        $this->imagen = null;
        // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
        // $this->emit('show-modal', 'show modal!'); --> metodo anterior
        $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
    }

    // ------- Metodo actualizar categoria-------------
    // public function update(){
    //     $rules = [
    //         'nombre' => "required|min:3|unique:categorias,nombre,{$this->seleccionar_id}"
    //     ];
    //     $mensaje =[
    //         'nombre.required' => 'Debe ingrear el nombre',
    //         'nombre.min' => 'El nombre minimo debe tener 3 caracteres',
    //         'nombre.unique'=>'El nnombre de la categoria ya existe'
    //     ];
    //     $this->validate($rules , $mensaje);

    //     //busca la categoria segun el codigo
    //     $categoria = Categoria::find($this->seleccionar_id);
    //     $categoria->update([
    //         'nombre' => $this->nombre
    //     ]);

    //     if($this->imagen){
    //         // el metodo uniquid para dar un valor unico..
    //         $archivocliente = uniqid() . '_.' . $this->imagen->extension();
    //         //ruta donde se va depositar la imagen
    //         $this->imagen->storeAs('public/categorias',$archivocliente);

    //         // como se va actuyalizar la imagen es necesario borrar la exsitente
    //         $imagennombre = $categoria->imagen; // recupera la imagen que esta guardada
    //         $categoria->imagen = $archivocliente; // aca se le asigna la nueva imagen
    //         $categoria->save();

    //         if($imagennombre != null){
    //             if(file_exists('storage/categorias/' . $imagennombre)){
    //                 unlink('storage/categorias/' . $imagennombre);
    //             }
    //         }

    //         //--limpiar
    //         $this->limpiar();
    //         //evento para mostrar el mensaje y cerrar el modal
    //         $this->dispatch('categoria-actualizada','Categoria Actualizada');

    //     };

    // }

    public function update()
    {
        $rules = [
            'nombre' => "required|min:3|unique:categorias,nombre,{$this->seleccionar_id}"
        ];
        $mensaje = [
            'nombre.required' => 'Debe ingresar el nombre',
            'nombre.min' => 'El nombre mínimo debe tener 3 caracteres',
            'nombre.unique' => 'El nombre de la categoría ya existe'
        ];
        $this->validate($rules, $mensaje);

        // Busca la categoría según el código
        $categoria = Categoria::find($this->seleccionar_id);
        $categoria->update([
            'nombre' => $this->nombre
        ]);

        if ($this->imagen) {
            // El método uniqid para dar un valor único
            $archivocliente = uniqid() . '_.' . $this->imagen->extension();
            // Ruta donde se va a depositar la imagen
            $this->imagen->storeAs('public/categorias', $archivocliente);

            // Como se va a actualizar la imagen, es necesario borrar la existente
            $imagennombre = $categoria->getImagenPath(); // Recupera el nombre del archivo de la imagen que está guardada
            $categoria->imagen = $archivocliente; // Asigna la nueva imagen
            $categoria->save();

            if ($imagennombre != null) {
                if (file_exists(public_path('storage/categorias/' . $imagennombre))) {
                    unlink(public_path('storage/categorias/' . $imagennombre));
                }
            }

            // Limpiar
            $this->limpiar();
            // Evento para mostrar el mensaje y cerrar el modal
            $this->dispatch('categoria-actualizada', 'Categoria Actualizada');
        }
    }


    public function limpiar()
    {
        $this->nombre = "";
        $this->imagen = null;
        $this->seleccionar_id = 0;
    }

    // en este caso el evento se esta desarrollando desde el fronend y debe ser escuchado en el backend
    // funcion para escuchar el evento

    protected $listeners = ['eliminarFila'];

    //esta funcion puede ser un poco mas optimisada ejemplo:
    // public function eliminarFila(Categoria $categoria) {
    //  y se elimina este codigo $categoria = Categoria::find($id);  }       

    // public function eliminarFila($id)
    // {
    //     $categoria = Categoria::find($id);
    //    // dd($categoria);

    //     if ($categoria) {
    //         $nombreimagen = $categoria->imagen;
    //         $categoria->delete();  // hasta aca ya se elimina el registro de la base de datos con el metodo delete de elocuent...
    //         //pero falta eliminar la imagne del del rpoyecyo o disco para no tener archivos basuras.


    //         //1) se valida si esta en la baase de datos 
    //         if ($nombreimagen != null) {
    //             // se utilizar el metodo unlink para eliminar la imgen que esta con la ruta dada
    //             unlink('storage/categorias/' . $nombreimagen);                   
    //         }

    //         $this->limpiar();
    //         //$this->dispatchBrowserEvent('categoria-eliminada', ['message' => 'Categoria eliminada']);
    //         $this->dispatch('categoria-eliminada','Categoria eliminada con exito');

    //     } else {
    //         // Manejar el caso cuando la categoría no se encuentra
    //         //$this->dispatchBrowserEvent('categoria-no-encontrada', ['message' => 'Categoria no encontrada']);
    //         $this->dispatch('categoria-no-encontrada','Categoria no encontrada');
    //     }
    // }

    public function eliminarFila($id)
    {
        $categoria = Categoria::find($id);
        // dd($categoria);

        if ($categoria) {
            // Usar el método getImagenPath() para obtener solo el nombre del archivo
            $nombreimagen = $categoria->getImagenPath();
            $categoria->delete();  // Eliminar el registro de la base de datos

            // Validar si el nombre de la imagen no es nulo y eliminar la imagen del sistema de archivos
            if ($nombreimagen != null && file_exists(public_path('storage/categorias/' . $nombreimagen))) {
                unlink(public_path('storage/categorias/' . $nombreimagen));
            }

            $this->limpiar();
            // $this->dispatchBrowserEvent('categoria-eliminada', ['message' => 'Categoria eliminada']);
            $this->dispatch('categoria-eliminada', 'Categoria eliminada con exito');
        } else {
            // Manejar el caso cuando la categoría no se encuentra
            // $this->dispatchBrowserEvent('categoria-no-encontrada', ['message' => 'Categoria no encontrada']);
            $this->dispatch('categoria-no-encontrada', 'Categoria no encontrada');
        }
    }
}
