<div>

    <style>
        .search-container {
            display: flex;
            /* Make elements side-by-side */
            align-items: center;
            /* Align vertically */
        }
    </style>
    <!--este es el buscador improvisado ya que el buscador original por razones no concretada no me funciono en esta operacion  -->
    <div class="search-container mb-3 col-sm-4">

        <input type="text" id="codigo" class="form-control "
            style="background: rgb(225, 219, 224); height: 40px; border-radius: 20px" placeholder="Buscar..."
            wire:keydown.enter="escanearCodigo($event.target.value)">

        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-search toggle-search">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>

    </div>

    <div class="connect-sorting">

        <!--incluir el controlador de la manera como se encuentra escrito-->
        <span
            style="color: rgb(138, 5, 138); font-size: 110%; font-family: 'Times New Roman' ">{{ $titulo }}</span>

        <div class="connect-sorting-content">
            <div class="card simple-title-task ui-sortable-handle">
                <div class="card-body">
                    <!--ESTA TABLA SOLO SE VA MOSTRAR SI HAY ELEMENTOS EN EL CARRITO-->
                    @if ($total > 0)

                        <div class="table-responsive tblscroll" style="max-height: 650px; overflow:hidden">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #5c1ac3">
                                    <tr>
                                        <th width="10%"></th>
                                        <th class="table-th text-left text-white">DESCRIPCION</th>
                                        <th class="table-th text-center  text-white">PRECIO</th>
                                        <th width="13%" class="table-th text-center  text-white">CANT</th>
                                        <th class="table-th text-center  text-white">IMPORTE</th>
                                        <th class="table-th text-center  text-white">ACCCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carritos as $carrito)
                                        <tr>
                                            <td class="text-center table-th">
                                                <!--esta imagen solo se va mostrar si nuestro carrito en la posicion de esa iteracion tiene imagen -->

                                                {{-- @if (count($carrito->attributes) == 0)
                                                <span>
                                                    <img src="{{ asset('storage/productos/' . $carrito->attributes[0]) }}"
                                                        alt="imagen del producto" height="90" width="90"
                                                        class="rounded">
                                                </span>
                                            @endif --}}

                                                @if (!empty($carrito->attributes) && count($carrito->attributes) > 0)
                                                    <span>
                                                        <img src="{{ asset('storage/productos/' . $carrito->attributes[0]) }}"
                                                            alt="imagen del producto" height="90" width="90"
                                                            class="rounded">
                                                    </span>
                                                @endif


                                            </td>

                                            <td>
                                                <h6>{{ $carrito->nombre }}</h6>
                                            </td>
                                            <td class="text-center">${{ number_format($carrito->precio, 2) }}</td>
                                            {{-- <td>
                                                <input type="number" id="r{{ $carrito->id }}"
                                                    wire:change="ActualizarCantidad({{ $carrito->id }}, $('#r' + {{ $carrito->id }}).val())"
                                                    style="font-size: 1rem!important" class="form-control text-center"
                                                    value="{{ $carrito->cantidad }}">
                                            </td> --}}

                                            <td>
                                                <input type="number" id="r{{ $carrito->id }}"
                                                       wire:change="actualizarCantidad({{ $carrito->producto_id }}, $event.target.value)"
                                                       style="font-size: 1rem!important" class="form-control text-center"
                                                       value="{{ $carrito->cantidad }}">
                                            </td>



                                            <td class="center">
                                                <h6>
                                                    ${{ number_format($carrito->precio * $carrito->cantidad, 2) }}
                                                </h6>
                                            </td>
                                            <td class="text-center">

                                                <button onclick="confirmacion('{{ $carrito->producto_id }}')"
                                                    class="btn btn-secondary mbmobile">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>

                                                {{-- <a href="javascript:void(0)"
                                                    onclick="confirmacion('{{ $carrito->producto_id }}')"
                                                    class="btn btn-primary" title="eliminar">
                                                    <i class="far fa-trash-alt"></i>
                                                </a> --}}

                                                <button
                                                    wire:click.prevent="restarCantidad({{ $carrito->producto_id }})"
                                                    class="btn  mbmobile" style="background: red; color: white">
                                                    -

                                                </button>

                                                <button
                                                    wire:click.prevent="incrementarCantidad({{ $carrito->producto_id }})"
                                                    class="btn btn-success mbmobile">
                                                    +
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>


                        </div>
                    @else
                        <h5 class="text-center text-muted">AGREGAR PRODUCTOS A LA VENTA</h5>
                    @endif

                    <div wire:loading.inline wire:target="guardarVenta">
                        <h4 class="text-danger text-center">Guardando venta...</h4>
                    </div>




                </div>
            </div>

        </div>


    </div>
</div>

<script>
    //este evento es para que se limpie la caja de testo guando el buscador realice su funcion
    document.addEventListener('livewire:init', () => {

        // esta es la manera como se escucha desde el controlador en el video o livewire 2
        // Livewire.on('escanearCodigo', (event) => {
        //     $('#codigo').val('');
        // });
        // esta es la manera como lo estoy escuchando con livewire 3
        //La idea es limpiar la caja limpiar la caja de texto o el buscador cuando se ejecute la busqueda 
        window.addEventListener('escanearCodigo', function(event) {
            $('#codigo').val('');
        });


        //valida que la caja no este vacia y que el producto exista
        window.addEventListener('Producto-no-existe', function() {
            if ($('#codigo').val() === '') {
                noty('INGRESE UN CODIGO VALIDO', 2);
            } else {
                noty('EL PRODUCTO NO EXISTE', 2);
            }
        });

        window.addEventListener('No-stock', function(event) {
            noty('STOCK INSUFICIENTE', 2);
        });

        // cuando se anexa productos al carrito
        window.addEventListener('busqueda-ok', function(event) {
            noty('PRODUCTO AGREGADO');
        });
        window.addEventListener('eliminado-ok', function(event) {
            noty('PRODUCTO ELIMINADO', 2);
        });

        window.addEventListener('eliminado-total', function(event) {
            noty('CARRITO ELIMINADO EXITOSAMENTE', 2);
        });

        window.addEventListener('eliminar-items', function(event) {
            noty('ITEM ELIMINADO EXITOSAMENTE', 2);
        });
        window.addEventListener('transaccion-ok', function(event) {
            swal("Transacion!", "Exitosa!", "success")
        });

        window.addEventListener('Cantidad-actualizada', function(event) {
            noty('CANTIDAD ACTUALIZADA')
        });

        // window.addEventListener('imprimir-tickect', function(vantaId) {
        //     window.open("print: //" + vantaId , '_blank' )
        // });

        window.addEventListener('imprimir-tickect', (event) => {
            setTimeout(() => {
                try {
                    const ventaId = event.detail;
                    window.open(`/imprimir/${ventaId}`, '_blank');
                } catch (error) {
                    console.error('Error al imprimir el ticket:', error);
                    alert(
                        'Ocurrió un error al imprimir el ticket. Por favor, inténtalo nuevamente.');
                }
            }, 500);
        });


    });

    function confirmacion(productoid) {
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
                @this.dispatch('eliminarItem', {
                    productoid
                });
                swal.close();
            }

        });
    };
</script>
