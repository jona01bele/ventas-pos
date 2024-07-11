<div class="row mt-3">

    <div class="col-sm-12">
        <!--connect-sorting permite creal el recuadro gris que tiene la targeta-->
        <div class="connect-sorting">
            <h5 class="text-center mb-2">DENOMINACIONES</h5>

            <div class="container">
                <div class="row">
                    {{-- para que me muestre todos los valores que hay en moneda o denominaciones --}}
                    @foreach ($monedas as $moneda)
                        <div class="col-sm mt-2">
                            {{-- <button livewire:click="AEfecivo({{$moneda->valor}})" class="btn btn-block den" style="background: #5c1ac3; color:white"> 
                                {{$moneda->valor >0 ? '$' . number_format($moneda->valor, 2, '.', '' ) : 'Exacto'}}
                            </button> --}}

                            <button wire:click="AEfectivo({{ $moneda->valor }})" class="btn btn-block den"
                                style="background: #5c1ac3; color:white">
                                {{ $moneda->valor > 0 ? '$' . number_format($moneda->valor, 2, '.', '') : 'Exacto' }}
                            </button>

                            <!--Las dos manera de validar son validas-->

                            {{-- <button ewire:click="AEfectivo({{$moneda->valor}})" class="btn btn-block den" style="background: #5c1ac3; color:white;">
                                @if ($moneda->valor > 0)
                                    ${{ number_format($moneda->valor, 2, '.', '') }}
                                @else
                                    Exacto
                                @endif
                            </button> --}}


                        </div>
                    @endforeach
                </div>
            </div>

            <div class="connect-sorting-content mt-4">
                <div class="card simple-title-task ui-sortable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm"
                                    style="background: #5c1ac3; 
                                color:white ">Efectivo
                                    F8
                                </span>
                            </div>
                            <input type="numbre" id="dinero" wire:model="efectivo" wire:keydown.enter="guardarVenta"
                                class="form-control text-center" value="{{ $efectivo }}">
                            <div class="input-group-append">
                                <!--este wire es para cuando se precione el boton deje l imput en 0 -->
                                {{-- <!--se puede hacer de dos formas. a: una funcion  y b: un meto de livewire  que es la que se va utilizar en esta caso --> wire:click="$set('efectivo', 0)" --}}
                                <span wire:click="resetEfectivoYCambio" class="input-group-text"
                                    style="background: #5c1ac3; 
                                 color:white ">
                                    <i class="far fa-dot-circle"></i>
                                </span>
                            </div>
                        </div>

                        <h4 class="text-muted">Cambio: ${{ number_format($cambio, 2) }}</h4>

                        <div class="row justify-content-between mt-5">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($total > 0)
                                    <button onclick="Confirm('', 'limpiarCart', '¿SEGURO QUIERE ELIMINA CARRITO?')"
                                        class="btn  mtmobile" style="background: #5c1ac3; color:white">CANCELAR
                                        F4</button>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($efectivo >= $total && $total > 0)
                                    <button wire:click.prevent="guardarVenta"
                                        class="btn btn-dark btn-md btn-block">GUARDAR F9</button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<style>
    /* Custom style for small SweetAlert toast */
    .

    /* Custom style for small SweetAlert toast */
    .swal2-small-toast {
        width: 100px !important;
        /* Ajusta el tamaño según tus necesidades */
        font-size: 10px !important;
        /* Ajusta el tamaño de la fuente */
    }
</style>

<script>
    // este docuemtn busca mostrar el modal cuando ejecuto las teclas habilitadas y mosmtra 
    //los posibles mensajes validados
    document.addEventListener('DOMContentLoaded', function() {

        // Librería para escuchar todos los eventos del teclado
        var listener = new window.keypress.Listener();

        // Tecla F9 para guardar la venta
        listener.simple_combo("f9", function() {
            @this.dispatch('guardarVenta');
        });

        // Tecla F8 limpiar el contenido de la caja de efectivo y colocar el focus
        listener.simple_combo("f8", function() {
            document.getElementById('dinero').value = "";
            document.getElementById('dinero').focus();
        });

        // // Tecla F4 para cancelar o eliminar todo el carrito o venta
        listener.simple_combo("f4", function() {
            var total = parseFloat(document.getElementById('hiddenTotal').value);
            if (total > 0) {
                // Llamar a Confirm para mostrar el modal de abajo o el swal
                Confirm(0, 'eliminarTodo', 'LOS DATOS SE ELIMINARAN POR COMPLETO');
            } else {
                noty('AGREGA PRODUCTOS A LA VENTA', 2);
            }
        });

        window.Confirm = function(id, nombreEvento, texto) {

            swal({
                title: 'CONFIRMA',
                text: texto,
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                cancelButtonColor: '#fff',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Aceptar'
            }).then(function(result) {
                if (result.value) {
                    @this.dispatch(nombreEvento, {
                        id
                    });
                    swal.close();
                }
            });
        }

    });
    window.addEventListener('venta-error', function(event) {
            noty('EL EFECTIVO NO CORRESPONDE', 2);
    });
    window.addEventListener('venta-NOaGREGADA', function(event) {
            noty('AGREGAR PRODUCTOS A LA VENTA', 2);
    });

    // Si quisiera mostrar el mensaje de error por medio de alertswet
//    window.addEventListener('venta-error', event => {
//         Swal.fire({
//             position: "top-end",
//             type: 'error',
//             title: 'Error',
//             text: event.detail,
//             timer: 2000,
//             confirmButtonText: 'Cerrar'
//            ,
//         });
//     });
</script>
