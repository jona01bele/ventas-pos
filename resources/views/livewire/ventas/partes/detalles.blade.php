<div class="connect-sorting">

    <div class="connect-sorting-content">
        <div class="card simple-title-task ui-sortable-handle">
            <div class="card-body">
                <!--ESTA TABLA SOLO SE VA MOSTRAR SI HAY ELEMENTOS EN EL CARRITO-->
                @if ($total > 0)

                    <div class="table-responsive tblscroll" style="max-height: 650px; overflow:hidden">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: purple">
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

                                            @if (count($carrito->attributes) > 0)
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
                                        <td>
                                            <input type="number" id="r{{ $carrio->id }}"
                                                wire:change="actualizarCantidad({{ $carrito->id }}, $('#r' + {{ $carrito->id }}).val())"
                                                style="font-size: 1rem!important" class="form-control text-center"
                                                value="{{ $carrito->cantidad }}">
                                        </td>
                                        <td class="center">
                                            <h6>
                                                ${{ number_format($carrito->precio * $carrito->cantidad, 2) }}
                                            </h6>
                                        </td>
                                        <td class="text-center">

                                            <button
                                                onclick="confirm('{{ $carrito->id }}', 'removeItem', '¿COMFIRMA ELIMINAR REGISTRO')"
                                                class="btn btn-dark mbmobile">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button wire:click.prevent="restarCantidad({{ $carrito->cantidad }})"
                                                class="btn btn-danger mbmobile">
                                                <i class="fas fa-trash-minus"></i>
                                            </button>
                                            <button wire:click.prevent="incrementarCantidad({{ $carrito->cantidad }})"
                                                class="btn btn-danger mbmobile">
                                                <i class="fas fa-trash-minus"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @else()
                    <h5 class="text-center text-muted">AGREGAR PRODUCTOS A LA VENTA</h5>
                @endif

                <div wire:loading.inline wire:target="guardarVenta">
                    <h4 class="text-danger text-center">Guardando venta...</h4>
                </div>
            </div>
        </div>
    </div>
</div>
