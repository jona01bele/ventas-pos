<?php

namespace App\Livewire;

use Livewire\Component;

class BuscarController extends Component
{

    // lo unico que va hacer este controlador es retornar la vista
    public $search;
   
    public function render()
    {
        return view('livewire.buscar');
    }
}
