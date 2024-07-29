<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componenteNombre }}</b>
                </h4>
            </div>

            <div class="widget-content">

                <div class="form-inline">
                    <div class="form-group mr-5">
                        <select wire:model.live="role" class="form-control">
                            <option value="Elegir" selected>==Selecciona el Rol==</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click.prevent="sincronizarTodo()" type="button"
                        class="btn btn-dark mbmobile inblock mr-5">Sincronizar Todo</button>
                    <button onclick="revocar()" type="button" class="btn btn-dark mbmobile mr-5">Revocar
                        Todo</button>

                </div>


                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table striped mt-1">
                                <thead class="text-white" style="background: #5c1ac3">
                                    <tr>
                                        <th class="table-th text-white">ID</th>
                                        <th class="table-th text-white">PERMISOS</th>
                                        <th class="table-th text-white">ROLES CON EL PERMISO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permisos as $permiso)
                                        <tr>
                                            <td>
                                                <h6>{{ $permiso->id }}</h6>
                                            </td>
                                            <td class="text-center">

                                                <div class="n-check">
                                                    <label class="new-control new-checkbox checkbox-primary">
                                                        <input type="checkbox" {{-- este metodo recibe el id del permiso y el nombre del permiso y es verificado si el elemento esta marcado o checked o no. --}}
                                                            wire:change="sincronizarPermiso($('#p' + {{ $permiso->id }}).is(':checked'), '{{ $permiso->name }}')"
                                                           
                                                            id="p{{ $permiso->id }}" value="{{ $permiso->id }}"
                                                            class="new-control-input" {{-- se accede el valor de la columna. si es igual a uno se activa el contro cheked de lo contrario no se hace nada --}}
                                                            {{ $permiso->checked == 1 ? 'checked' : '' }}>
                                                            
                                                        <span class="new-control-indicator"></span>
                                                        <h6>{{ $permiso->name }}</h6>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                {{-- de esta manera se consulta cuantos roles tiene el permiso --}}
                                                {{-- esto en case que sea necesario. es una opcion --}}
                                                <h6>{{ \App\Models\User::permission($permiso->name)->count() }}</h6>
                                                {{-- <h6>{{ $permiso->roles_count }}</h6> --}}

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            {{ $permisos->links() }}
                        </div>
                    </div>
                </div>




            </div>

        </div>
    </div>
    {{-- @include('livewire.asignacions.form') --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.addEventListener('no-valido', function(event) {

            noty('SELECCIONE UN ROL VALIDO' , 2)
        });
        window.addEventListener('permi', function(event) {

            noty('PERMISO ASIGNADO CORRECTAMENTE')
        });
        window.addEventListener('syncall', function(event) { 
            noty('PERMISOS SINCRONIZADOS AL ROL');
        });
        window.addEventListener('quitarPermisos', function(event) {
            noty('TODOS LOS PERMISOS FUERON REVOCADOS')
        });

        window.addEventListener('permieliminado', function(event) {
            noty('PERMISO ELIMINADO CORECTAMENTE')
        });
        window.addEventListener('rol-valido', function(event) {
            noty('ELIGE UN ROL VALIDO', 2)
        });
    });

    function revocar() {
        swal({
            title: 'CONFIRMA',
            text: '¿CONFIRMAR REVOCAR TODOS LOS PERMISOS?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            console.log('hola')
            if (result.value) {
                console.log(result)
                // Emitir el evento 'eliminarFila' con el ID de la categoría
                // @this.dispatchSelf('eliminarFila', id);
                @this.dispatch('revocartodo');
                swal.close();
            }

        });
    };
</script>
