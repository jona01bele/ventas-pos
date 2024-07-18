<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination; 
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;

class RolesController extends Component
{
    use WithPagination;

    public $componenteNombre , $titulo, $nombre, $seleccionar_id, $buscador;
    private  $paginacion = 5;

    public function mount()
    {
        $this->titulo = 'Listado';
        $this->componenteNombre = 'Roles';
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

            $roles = Role::where('name', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
        } else {
            $roles = Role::orderBy('name', 'asc')->paginate($this->paginacion);
        }

        return view('livewire.roles.componente' , compact('roles') )
        ->extends('ventapos.layouts.admin')
        ->section('contenido');
    }

    //-----metodo para registrar las roless------------------------------------------------------
    public function store()
    {
        $rules = [
            'nombre' => 'required|unique:roles,name|min:2',
        ];

        $messages = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.unique' => 'La información no puede ser duplicada',
            'nombre.min' => 'Catacteres insuficientes',
        ];


        $this->validate($rules, $messages);

        $Roles = Role::create([
            'name' => $this->nombre,
        ]);


      
        $this->limpiar();
        //evento para mostrar el mensaje y cerrar el modal si es caso
        $this->dispatch('rol-agregado', 'Rol registrado exitosamente');
    }

    //funcion para que me muestre los datos en el modal antes de actualizar
    //public function editar($id)   se puede hacer de esta manera la fucnion pero tambien parasando el modelo
    public function editar(Role $role)
    {
        // esto trae todos los datos y funciona pero como buenas practicas podemos optimizar
        //$rol = role::find($id); nota-> si se hace con la funcion actual se cancela esto
       // $rol = Role::find($id, ['id', 'name']);  //->metodo optimizado
        $this->nombre = $role->name;
        $this->seleccionar_id = $role->id;
        // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
        // $this->emit('show-modal', 'show modal!'); --> metodo anterior
        $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
    }

    public function update(){

        $rules = [
            'nombre' => "required|unique:roles,name, {$this->seleccionar_id}|min:2",
        ];

        $messages = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.unique' => 'La información no puede ser duplicada',
            'nombre.min' => 'Catacteres insuficientes',
        ];

        $this->validate($rules, $messages);

        $role = Role::find($this->seleccionar_id);
        $role->name =  $this->nombre;
        $role->save();

        $this->limpiar();
        $this->dispatch('rol-actualizado');
    }

    protected $listeners = ['eliminarFila'];

    public function eliminarFila($id) 
    {

        // En este caso hay que validar si el el rol tiene permisos asociados
        // en caso que los tenga no se puede eliminar

        // con esta variable vamo a guardar la cantidad de permisos asociados al rol
        $contarpermisos = Role::find($id)->permissions->count();
        if($contarpermisos >0){
            $this->dispatch('rol-error' , 'NO SE PUEDE ELIMINAR EL ROL PORQUE TIENE PERMISOS ASOCIADOS');
            return;
        }

        Role::find($id)->delete();
        $this->dispatch('rol-eliminado');

        
    }  
    public function limpiar(){
        $this->nombre = '';
        $this->buscador = '';
        $this->seleccionar_id = 0;

    }
    
}
