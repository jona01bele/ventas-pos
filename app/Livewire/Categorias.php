<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use App\Models\Categoria;
use Livewire\WithFileUploads;  //trait Para subir las imagenes las imagenes
use Livewire\WithPagination;  // trait para la paginacion
use Livewire\Component;
use Illuminate\Support\Facades\Storage; // para poder manejar archivos o imagenes dentro de nuestro proyecto

class Categorias extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $nombre, $imagen, $buscar, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 5; // variable privada para la paginacin y una base 5
    // #[Layout('ventapos.layouts.admin')] // voy a probar con esta como apcion ya que el metodo ->layout() no me quiere funcionar

    public function render()
    {
        $categorias = Categoria::all();
        return view('livewire.categorias' , compact('categorias'));  
        // de las dos forma deberia funcinar esto es una prueba
        //return view('livewire.categorias', ['categorias' => $categorias]);
    }
}
