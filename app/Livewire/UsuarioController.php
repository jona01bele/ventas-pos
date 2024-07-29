<?php

namespace App\Livewire;

use Livewire\WithFileUploads;  //trait Para subir las imagenes con livewire
use Livewire\WithPagination;  // trait para la paginacion
use Livewire\Component;
use App\Models\User;
use App\Models\Venta;   // la finalidad es validar si el usuario tene ventas
use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;

class UsuarioController extends Component
{
    use WithFileUploads; // uso de trait    
    use WithPagination; // uso de trait


    // propiedades o variable que vamos a utilizar en la vista
    public $nombre, $imagen, $buscador, $seleccionar_id, $tituloPagina, $componenteNombre;
    public $telefono, $estado, $password, $arcivoCargado, $perfil, $email;
    private  $paginacion = 2; // variable privada para la paginacin y una base 5
    //#[Layout('ventapos.layouts.admin')] // voy a probar con esta como apcion ya que el metodo ->layout() no me quiere funcionar

    public function mount()
    {
        $this->tituloPagina = 'Listado';
        $this->componenteNombre = 'Usuarios';
        $this->estado = 'Elegir';
    }

    public function render()
    {

        $roles = Role::orderBy('name', 'asc')->get();
        //dd($this->buscador);
        //strlen metodo de php que te cuenta lo que hay en la peticion en este caso lo que se escriba en la caja de texto
        if (strlen($this->buscador) > 0) {

            $usuarios = User::where('name', 'like', '%' . $this->buscador . '%')->select('*')->orderBy('name', 'desc')->paginate($this->paginacion);
        } else {
            $usuarios = User::select('*')->orderBy('name', 'asc')->paginate($this->paginacion);
        }
        //$categorias = Categoria::all();
        return view('livewire.usuario.usuario', compact('usuarios', 'roles'))
            ->extends('ventapos.layouts.admin')->section('contenido');
        // de las dos forma deberia funcinar esto es una prueba
        //return view('livewire.categorias', ['categorias' => $categorias]);



        return view('livewire.usuario');
    }


    // metodo para personalizar paginacion
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }


    //-----metodo para registrar las categorias------------------------------------------------------
    public function store()
    {
        $rules = [
            'nombre' => 'required|min:3',
            'email' => 'required|unique:users',
            // Para que la selección de user sea diferente de elegir -> not_in:Elegir
            'estado' => 'required|not_in:Elegir',
            'perfil' => 'required|not_in:Elegir',
            'password' => 'required|min:3', // Cambiado de 'password' a 'password'
        ];

        $messages = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.min' => 'Caracteres insuficientes',
            'email.required' => 'El email debe ser ingresado',
            'email.unique' => 'Este email ya se encuentra registrado',
            'estado.required' => 'El estado debe ser ingresado',
            'estado.not_in' => 'Ingrese un dato válido',
            'perfil.required' => 'El perfil debe ser ingresado', // Corregido "perfill"
            'perfil.not_in' => 'Ingrese un dato válido',
            'password.required' => 'Ingresar password', // Cambiado de 'password' a 'password'
            'password.min' => 'Caracteres insuficientes', // Corregido "caracteres" en plural
        ];

        $this->validate($rules, $messages);


        $usuarios = User::create([
            'name' => $this->nombre,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'perfil' => $this->perfil,
            'telefono' => $this->telefono,
            'estado' => $this->estado

        ]);


        $usuarioarchivos = "";

        if ($this->imagen) {
            $usuarioarchivos = uniqid() . '_.' . $this->imagen->extension();
            $this->imagen->storeAs('public/usuarios', $usuarioarchivos); // hasta qui se guarda el archivo en nuestro sistema o proyecto
            $usuarios->imagen = $usuarioarchivos;
            $usuarios->save(); // aqui se guarda la imagen en la base de datos
        }

        $this->limpiar();
        //evento para mostrar el mensaje y cerrar el modal si es caso
        $this->dispatch('usuario-agregado', 'Categoriar Registrada');
    }


    //----Funcion para editar------------------------------------------
    //aqui se recibe el id enviado desde el click para buscar la informacion que se va a mostrar en el modal.. pero aun no actualiza
    public function editar(User $usuario)
    {

        $this->nombre = $usuario->name;
        $this->seleccionar_id = $usuario->id;
        $this->telefono = $usuario->telefono;
        $this->perfil = $usuario->perfil;
        $this->estado = $usuario->estado;
        $this->email = $usuario->email;
        $this->password = '';
        // este es un evento emitido desde el backend y este debe escucharse desde el frontend con javascrip en este caso
        // $this->emit('show-modal', 'show modal!'); --> metodo anterior
        $this->dispatch('show-modal', 'show modal!'); //-->> metodo actualizado,, para mostrar el modal
    }

    // ------- Metodo actualizar categoria-------------


    public function update()
    {
        $rules = [
            //que el email se valide pero filtre el id del usuario
            // esto es para que valide que no exit el email y filtre el qie estamos editando
            'email' => "required|email|unique:users,email,{$this->seleccionar_id}",
            'nombre' => 'required|min:3',
            //para que la seleccion de user sea diferente de elegir -> not_in:Elegir
            'estado' => 'required|not_in:Elegir',
            'perfil' => 'required|not_in:Elegir',
            'password' => 'required|min:3',
        ];

        $messages = [
            'nombre.required' => 'El nombre debe ser ingresado',
            'nombre.min' => 'Catacteres insuficientes',
            'email.required' => 'El email debe ser ingresado',
            'email.unique' => 'Este email ya se encuentra registrado',
            'estado.required' => 'El estado debe ser ingresado',
            'estado.not_in' => 'ingrese un dato valido',
            'perfil.required' => 'El perfill debe ser ingresado',
            'perfil.not_in' => 'ingrese un dato valido',
            'password.required' => 'Ingresar password',
            'password.min' => 'caracteres insuficiente'
        ];


        $this->validate($rules, $messages);

        // Busca el ususario según el código
        $usuarios = User::find($this->seleccionar_id);
        $usuarios->update([
            'name' => $this->nombre,
            'email' => $this->email,
            'passsword' => bcrypt($this->password),
            'perfil' => $this->perfil,
            'telefono' => $this->telefono,
            'estado' => $this->estado

        ]);

        if ($this->imagen) {
            // El método uniqid para dar un valor único
            $archivousuario = uniqid() . '_.' . $this->imagen->extension();
            // Ruta donde se va a depositar la imagen
            $this->imagen->storeAs('public/usuarios', $archivousuario);

            // Como se va a actualizar la imagen, es necesario borrar la existente
            $imagennombre = $usuarios->getImagenPath(); // Recupera el nombre del archivo de la imagen que está guardada
            $usuarios->imagen = $archivousuario; // Asigna la nueva imagen
            $usuarios->save();

            if ($imagennombre != null) {
                if (file_exists(public_path('storage/usuarios/' . $imagennombre))) {
                    unlink(public_path('storage/usuarios/' . $imagennombre));
                }
            }

            // Limpiar
            $this->limpiar();
            // Evento para mostrar el mensaje y cerrar el modal
            $this->dispatch('usuario-actualizado');
        }
    }


    public function limpiar()
    {
        $this->nombre = "";
        $this->imagen = null;
        $this->seleccionar_id = 0;
        $this->password = '';
        $this->telefono = '';
        $this->buscador = '';
        $this->estado = 'Elegir';
        //para que los errores de validacion se quiten 
        //cualquiera de los de abajo funcionan
        //$this->resetErrorBag();
        $this->resetValidation();
        // este metodo es par que cuando se elimine un dato y este en una pagina de la tabla diferente a la primera teniendo encuanta el paginado..
        //esta vuelva a la principal
        $this->resetPage();
    }

    // en este caso el evento se esta desarrollando desde el fronend y debe ser escuchado en el backend
    // funcion para escuchar el evento

    protected $listeners = ['eliminarFila'];


    public function eliminarFila($id)
    {

        // para eliminar primero hay que verificar que el usuario tenga ventas asociadas
        $usuarios = User::find($id);

        if ($usuarios) {
            //para saber si tiene venta con un conteo es suficiente
            $venta = Venta::where('id_usuario', $id)->count();

            if ($venta > 0) {
                $this->dispatch('usuarioConventas');
            } else {
                // Usar el método getImagenPath() para obtener solo el nombre del archivo
                $nombreimagen = $usuarios->getImagenPath();
                $usuarios->delete();  // Eliminar el registro de la base de datos

                // Validar si el nombre de la imagen no es nulo y eliminar la imagen del sistema de archivos
                if ($nombreimagen != null && file_exists(public_path('storage/usuarios/' . $nombreimagen))) {
                    unlink(public_path('storage/usuarios/' . $nombreimagen));
                }

                $this->limpiar();
                // $this->dispatchBrowserEvent('categoria-eliminada', ['message' => 'Categoria eliminada']);
                $this->dispatch('usuario-eliminado', 'Usuario eliminado correctamente');
            }
        } else {
            // Manejar el caso cuando la categoría no se encuentra
            // $this->dispatchBrowserEvent('categoria-no-encontrada', ['message' => 'Categoria no encontrada']);
            $this->dispatch('usuario-no-encontrado', 'Categoria no encontrada');
        }





        if ($usuarios) {
        }
    }
}
