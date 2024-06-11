
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
                    <!-- Tabla de productos -->
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #5c1ac3">
                            <tr>
                                <th class="table-th text-white">PRODUCTOS</th>
                                <th class="table-th text-white">CODIGO</th>
                                <th class="table-th text-white">CATEGORIA</th>
                                <th class="table-th text-white">PRECIO</th>
                                <th class="table-th text-white">STOCK</th>
                                <th class="table-th text-white">INV-MINIMO</th>
                                <th class="table-th text-white">IMAGEN</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                            <tr>
                                <td><h6>{{ $producto->nombre }}</h6></td>
                                <td><h6>{{ $producto->codigobarras }}</h6></td>
                                <td> <h6>{{ $producto->categoria->nombre }}</h6></td>
                                <td><h6>{{ $producto->precio }}</h6></td>
                                <td>
                                    @if ($producto->stock > $producto->alertas)
                                       {{ $producto->stock }}
                                       <label class="switch s-success  mb-4 mr-2">
                                        <input type="checkbox" checked="">
                                        <span class="slider round"></span>
                                    </label>
                                    @else
                                        {{ $producto->stock }}
                                        <label class="switch s-danger  mb-4 mr-2">
                                            <input type="checkbox" checked="">
                                            <span class="slider round"></span>
                                        </label>
                                    @endif
                                    {{-- <h6>{{ $producto->stock }}</h6> --}}
                                </td>
                                <td><h6>{{ $producto->alertas }}</h6>
                                </td>
                                <td class="text-center">
                                    <span>
                                        <img src="{{ asset('storage/productos/' . $producto->imagen) }}"
                                            alt="Imagen de ejemplo" height="70px" width="80" class="rounded">
                                    </span>
                                </td>
                                {{-- <td class="text-center">
                                    <span>
                                        <img src="{{$categoria->imagen}}" alt="Imagen de ejemplo" height="70px" width="80" class="rounded">
                                    </span>
                                </td> --}}
                                <td class="text-center">
                                    <!---Aca lo que hace es pasar el id de la categoria al hacer click-->
                                    <a href="javascript:void(0)" wire:click="editar({{ $producto->id }})"
                                        class="btn btn-primary mtmobile" tilte="editar">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <!--a diferencia del boton edit aca se va resolver con js.. nota: aca se puede resolver igual que arriba con: wire:click=delete{$categoria->id}}"-->

                                    <a href="javascript:void(0)" onclick="Confirm('{{ $producto->id }}')"
                                        class="btn btn-primary" title="delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>


                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Paginación -->
                    {{ $productos->links() }}
                </div>
            </div>

        </div>
    </div>
    <!--incluir el Modal-->
    {{-- @include('livewire.categoria.formulario') --}}
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire is fully initialized');




        window.addEventListener('productos-agregado', function(event) {
            $('#theModal').modal('hide');
        });

        window.addEventListener('producto-actualizado', function(event) {
            $('#theModal').modal('hide');
        });

        window.addEventListener('producto-eliminado', function(event) {
            //notificar
        });

        // Eventos personalizados de Livewire
        window.addEventListener('show-modal', function(event) {
            $('#theModal').modal('show');
        });
        // Eventos personalizados de Livewire
        window.addEventListener('show-hide', function(event) {
            $('#theModal').modal('hide');
        });
        // Eventos de javascript-bootstrap ..   para buscar todos los elementos que tengan la clase er
        //para que cuando se cierre el modal o formulario se quiten todos los mensajes de error
        window.addEventListener('hidde.bs.modal', function(event) {
            $('.er').css('display', 'none')
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
