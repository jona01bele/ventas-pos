<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;

class PermisosContoller extends Component
{
    public $componenteNombre , $titulo, $nombre, $seleccionar_id, $buscador;
    private  $paginacion = 10;

    public function mount()
    {
        $this->titulo = 'Listado';
        $this->componenteNombre = 'Permisos';
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

            $permisos = Permission::where('name', 'like', '%' . $this->buscador . '%')->paginate($this->paginacion);
        } else {
            $permisos = Permission::orderBy('name', 'asc')->paginate($this->paginacion);
        }
        return view('livewire.permisos.componente' , compact('permisos'))
        ->extends('ventapos.layouts.admin')
        ->section('contenido');
    }

     //-----metodo para registrar las permisoss------------------------------------------------------
     public function store()
     {
         $rules = [
             'nombre' => 'required|unique:permissions,name|min:2',
         ];
 
         $messages = [
             'nombre.required' => 'El nombre debe ser ingresado',
             'nombre.unique' => 'La información no puede ser duplicada',
             'nombre.min' => 'Catacteres insuficientes',
         ];
 
 
         $this->validate($rules, $messages);
 
         $permisos = Permission::create([
             'name' => $this->nombre,
         ]);
 
 
       
         $this->limpiar();
         //evento para mostrar el mensaje y cerrar el modal si es caso
         $this->dispatch('permiso-agregado', 'permiso registrado exitosamente');
     }
 
     //funcion para que me muestre los datos en el modal antes de actualizar
     //public function editar($id)   se puede hacer de esta manera la fucnion pero tambien parasando el modelo
     public function editar(Permission $Permission)
     {
         // esto trae todos los datos y funciona pero como buenas practicas podemos optimizar
         //$permiso = Permission::find($id); nota-> si se hace con la funcion actual se cancela esto
        // $permiso = Permission::find($id, ['id', 'name']);  //->metodo optimizado
         $this->nombre = $Permission->name;
         $this->seleccionar_id = $Permission->id;
         // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
         // $this->emit('show-modal', 'show modal!'); --> metodo anterior
         $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
     }
 
     public function update(){
 
         $rules = [
             'nombre' => "required|unique:permissions,name, {$this->seleccionar_id}|min:2",
         ];
 
         $messages = [
             'nombre.required' => 'El nombre debe ser ingresado',
             'nombre.unique' => 'La información no puede ser duplicada',
             'nombre.min' => 'Catacteres insuficientes',
         ];
 
         $this->validate($rules, $messages);
 
         $Permission = Permission::find($this->seleccionar_id);
         $Permission->name =  $this->nombre;
         $Permission->save();
 
         $this->limpiar();
         $this->dispatch('permiso-actualizado');
     }
 
     protected $listeners = ['eliminarFila'];
 
     public function eliminarFila($id) 
     {
 
         // En este caso hay que validar si el el permiso tiene permisos asociados
         // en caso que los tenga no se puede eliminar
 
         // con esta variable  es para obtener la cantidad de roles que esta asociado al permiso
         $contarpermisos = Permission::find($id)->getRoleNames()->count();
         if($contarpermisos >0){
             $this->dispatch('permiso-error' , 'NO SE PUEDE ELIMINAR EL PERMISO PORQUE TIENE ROLES ASOCIADOS');
             return;
         }
 
         Permission::find($id)->delete();
         $this->dispatch('permiso-eliminado');
 
         
     }  
     public function limpiar(){
         $this->nombre = '';
         $this->buscador = '';
         $this->seleccionar_id = 0;
        //para que los errores de validacion se quiten 
        //cualquiera de los de abajo funcionan
        //$this->resetErrorBag();
        $this->resetValidation();
 
     }
     
 }
 


