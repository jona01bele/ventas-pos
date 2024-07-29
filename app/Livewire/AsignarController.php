<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;


class AsignarController extends Component
{
    use WithPagination; // uso de trait

    // propiedades publicar
    public $role, $permisosSelected = [], $old_permissions= [], $componenteNombre;
    private  $paginacion = 10;


    public function mount()
    {
        $this->role = 'Elegir';
        $this->componenteNombre = 'Asignar Permisos';
    }
     // metodo para personalizar paginacion
     public function paginationView()
     {
         return 'vendor.livewire.bootstrap';
     }

    public function render()
    {
        // los permisos par dibujar en la tabla
        //Optenr todos los nombre y id de los permisos y ademas se anexa una tercera columna que por defecto tiene valor cero y se llama cheked
        $permisos = Permission::select('name', 'id', DB::raw("0 as checked"))
        //->withCount('roles')
        ->orderBy('name' , 'asc')->paginate($this->paginacion);
        
        
        // este condicional es para que cuando cambie o tome un rol en el select, es decir que sea diferente de "Elegir" 
        //me muestre todos lo permisos que tiene asociado
        if($this->role != 'Elegir'){
            $lista = Permission::join('role_has_permissions as rp', 'rp.permission_id', 'permissions.id')
                ->where('rp.role_id', $this->role)->pluck('permissions.id')->toArray();
            $this->old_permissions = $lista;
        }

        if($this->role != 'Elegir'){
            foreach($permisos as $permiso){
                $role = Role::find($this->role);
                $tienePermiso = $role->hasPermissionto($permiso->name);
                if($tienePermiso){
                    $permiso->checked = 1;
                }

            }
        }
        //los roles para dibujar en el select
        $roles = Role::orderBy('name', 'asc')->get();
        return view('livewire.asignar.componente' , compact('roles', 'permisos'))
        ->extends('ventapos.layouts.admin')->section('contenido');
    }

  
    protected $listeners = ['revocartodo']; 

    // public function revocartodo(){
    //     //validar si se esta seleccionando un rol
    //     if($this->role != 'Elegir'){
    //         $this->dispatch('no-valido');
    //         return; // return vacio para detener el flujo del proceso
    //     }
    //     //en caso que se selecciono el rol.. buscar
    //     $rol = Role::find($this->role);
    //     $rol->syncPermissions([0]);   // de esta menera se quita todos los permisos que esta seleccionado en el select
        
    //     $this->dispatch('quitarPermisos');
    // }

    public function revocartodo()
{
    // Validar si se está seleccionando un rol
    if ($this->role == 'Elegir') {
        $this->dispatch('no-valido');
        return; // return vacío para detener el flujo del proceso
    }
    
    // Buscar el rol por ID
    $rol = Role::find($this->role);
    
    // Verificar si el rol existe
    if (!$rol) {
        $this->dispatch('no-valido');
        return;
    }
    
    // Revocar todos los permisos del rol
    $rol->syncPermissions([]); // Pasando un array vacío para eliminar todos los permisos
    
    $this->dispatch('quitarPermisos');
}


    // funcion busca asignar o sincronizar todo los permisos que hay con el rol de un solo click
    public function sincronizarTodo(){
        //validar si se esta seleccionando un rol
        if($this->role == 'Elegir'){
            $this->dispatch('no-valido');
            return; // return vacio para detener el flujo del proceso
        }
        $rol = Role::find($this->role);
        $permisos = Permission::pluck('id')->toArray();
        $rol->syncPermissions($permisos);
        $this->dispatch('syncall');
    }

    // esta funcion busca sincronizar un solo permiso con en el rol
    public function sincronizarPermiso($state, $permisoName){
      
        //validar si se esta seleccionando un rol
        if($this->role != 'Elegir'){
            $rollName = Role::find($this->role);
            if($state){
                $rollName->givePermissionTo($permisoName);
                $this->dispatch('permi');
            }else{
                $rollName->revokePermissionTo($permisoName);
                $this->dispatch('permieliminado');
                
            }
        }else{
            $this->dispatch('rol-valido');
        }
    }

}
