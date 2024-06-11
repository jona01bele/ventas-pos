<?php

namespace App\Livewire;
use Livewire\Component;
//use App\Models\Categoria;
use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination; // trait para la paginacion

use App\Models\Producto;


class ProductosController extends Component
{
    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait
    

    // public $nombre, $codigo, $costo, $precio, $stock, $alertas, $categoriaid, 
    public $buscador, $seleccionar_id, $tituloPagina, $componenteNombre;
    private  $paginacion = 2;
  

    //ciclo de vida de los componetes de livewire
    //generalmete se utilizar para inicilazar propiedades o inicilaizar data de nuestros componetes
    public function mount(){

        $this->tituloPagina = 'LISTADO';
        $this->componenteNombre = 'PRODUCTOS';
        //$this->categoriaid = 'Elegir';
    }
     
    // metodo para personalizar paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
        //llenar el select con las categorias
       // $categorias = Categoria::orderBy('nombre', 'asc')->get();


       // si solo deseocolocar la paginacion con la consulta de todos los datos no es necesario el metodo get o all
       // $productos = Producto::paginate($this->paginacion);
        if(strlen($this->buscador) > 0){
            //realiza la busqueda con el nombre
             //$productos = Producto::where('nombre' , 'like', '%' . $this->buscador . '%' )->paginate($this->paginacion);
             $productos = Producto::where('nombre', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
            // realiza la busqueda con el nombre y con el codigo de barras.
            // $productos = Producto::where('nombre', 'like', '%' . $this->buscador . '%' )
            // ->orWhere('codigobarras' , 'like', '%' . $this->buscador . '%')
            // ->paginate($this->paginacion);
        }
        else{
          //  $productos = Producto::orderBy('id', 'desc')->paginate($this->paginacion);
            $productos = Producto::orderBy('id', 'desc')->paginate($this->paginacion);
        }

       return view('livewire.productos.productos', ['productos' => $productos])
        ->extends('ventapos.layouts.admin')->section('contenido');
    }
}
