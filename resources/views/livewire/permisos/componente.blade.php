<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componenteNombre }} | {{ $titulo }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <!--esto es para anular la accion por defecto del href o del enlace-->
                        <a href="javascript:void(0)" class="tabmenu bg-indigo-500" data-toggle="modal"
                            data-target="#theModal">Registrar</a>
                    </li>
                </ul>
            </div>
            @include('comun.buscador')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #5c1ac3">
                            <tr>
                                <th class="table-th text-white">ID</th>
                                <th class="table-th text-white">DESCRIPCIÓN</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permisos as $permiso)
                                <tr>
                                    <td>
                                        <h6>{{ $permiso->id }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $permiso->name }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="editar({{ $permiso->id }})"
                                            class="btn btn-primary mtmobile" tilte="Editar Registro">
                                            <i class="far fa-edit"></i>
                                        </a>

                                        <a href="javascript:void(0)" onclick="Confirm('{{ $permiso->id }}')"
                                            class="btn btn-primary" title="delete">
                                            <i class="far fa-trash-alt"></i>
                                        </a>

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
    @include('livewire.permisos.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.addEventListener('permiso-agregado', function(event) {
            $('#theModal').modal('hide');
            noty('REGISTRO EXITOSO')
        });
        window.addEventListener('permiso-actualizado', function(event) {
            $('#theModal').modal('hide');
            noty('ACTUALIZACIÓN EXITOSA')
        });
        window.addEventListener('permiso-eliminado', function(event) {
            noty('OPERACION EXITOSA')
        });
        window.addEventListener('permiso-existencia', function(event) {
            noty('permiso NO ENCONTRADO')
        });
        window.addEventListener('permiso-error', function(event) {
            noty('cambiar de acuendo al contexto')
        });
        window.addEventListener('hide-modal', function(event) {
            $('#theModal').modal('hide');
            noty('Cambiar de acuerdo al contexto')
        });
        window.addEventListener('show-modal', function(event) {
            $('#theModal').modal('show');

        });

    });

    function Confirm(id) {
        swal({
            title: 'CONFIRMA',
            text: '¿CONFIRMAR ELIMINAR REGISTRO?',
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
                @this.dispatch('eliminarFila', {
                    id
                });
                swal.close();
            }

        });
    };

 
       
</script>
