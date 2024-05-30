<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use App\Models\Categoria;
use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination;  // trait para la paginacion
use Livewire\Component;
use Illuminate\Support\Facades\Storage; // para poder manejar archivos o imagenes dentro de nuestro proyecto con laravel

class CategoriasComponent extends Component
{
    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait

    // propiedades o variable que vamos a utilizar en la vista
    public $nombre, $imagen, $buscar, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 5; // variable privada para la paginacin y una base 5
    // #[Layout('ventapos.layouts.admin')] // voy a probar con esta como apcion ya que el metodo ->layout() no me quiere funcionar

    public function render()
    {
        $categorias = Categoria::all();
        return view('livewire.categoria.categorias' , compact('categorias'));  
        // de las dos forma deberia funcinar esto es una prueba
        //return view('livewire.categorias', ['categorias' => $categorias]);
    }
}
