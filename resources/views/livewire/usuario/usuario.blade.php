<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componenteNombre }} | {{ $tituloPagina }}</b>
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
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #5c1ac3">
                            <tr>
                                <th class="table-th text-white">USUARIO</th>
                                <th class="table-th text-white">TELEFONO</th>
                                <th class="table-th text-white">CORREO</th>
                                <th class="table-th text-white">PERFIL</th>
                                <th class="table-th text-white">ESTAD0</th>
                                <th class="table-th text-white">IMAGEN</th>
                                <th class="table-th text-white">ACCIONES</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <h6>{{ $usuario->name }}</h6>
                                    </td>

                                    <td>
                                        <h6>{{ $usuario->telefono }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $usuario->email }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $usuario->perfil }}</h6>
                                    </td>
                                    <td>
                                        <span style="margin: 0%; padding: 5%"
                                            class="btn btn-{{ $usuario->estado == 'ACTIVO' ? 'success' : 'danger' }}">{{ $usuario->estado }}</span>
                                    </td>

                                    {{-- <td class="text-center">
                                        <span>
                                            <img src="{{asset('storage/categorias/' . $categoria->imagen)}}"
                                                alt="Imagen de ejemplo" height="70px" width="80" class="rounded">
                                        </span>
                                    </td> --}}
                                    <td class="text-center">
                                        <span>
                                            <img src="{{ $usuario->imagen }}" alt="Imagen de ejemplo" height="70px"
                                                width="80" class="rounded">
                                        </span>
                                    </td>


                                    <td class="text-center">
                                        <!---Aca lo que hace es pasar el id de la categoria al hacer click-->
                                        <a href="javascript:void(0)" wire:click="editar({{ $usuario->id }})"
                                            class="btn btn-primary mtmobile" tilte="editar">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <!--a diferencia del boton edit aca se va resolver con js.. nota: aca se puede resolver igual que arriba con: wire:click=delete{$categoria->id}}"-->

                                        <a href="javascript:void(0)" onclick="Confirm('{{ $usuario->id }}')"
                                            class="btn btn-primary" title="delete">
                                            <i class="far fa-trash-alt"></i>
                                        </a>


                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $usuarios->links() }}
                </div>
            </div>

        </div>
    </div>
    <!--incluir el Modal-->
    @include('livewire.usuario.formulario')


</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire is fully initialized');


        // Eventos personalizados de Livewire
        window.addEventListener('show-modal', function(event) {
            $('#theModal').modal('show');
        });

        window.addEventListener('usuario-agregado', function(event) {
            $('#theModal').modal('hide');
            swal("Registro!", "Exitoso!", "success")
        });
        window.addEventListener('usuarioConventas', function(event) {
            $('#theModal').modal('hide');
            noty(':/Negativo , el usuario posee ventas asociada', 2)
        })

        //esta es una manera de escuchar el evento pero con el mensaje enviado del evento
        //que no es lo que se ha hecho anterior mente.

        // window.addEventListener('usuario-eliminado', function(msg) {
        //     const mensaje = msg.detail;
        //     const mens =  mensaje[0];
        //     noty(mens);
        // })
        
        //esto es una manera de hacer igual  al codigo de arriba
        window.addEventListener('usuario-eliminado', ({detail: [mens] }) => {
             noty(mens);
        })

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
