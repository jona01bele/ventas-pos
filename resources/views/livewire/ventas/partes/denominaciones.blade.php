<div class="row mt-3">
    

    <div class="col-sm-12">
        <!--connect-sorting permite creal el recuadro gris que tiene la targeta-->
        <div class="connect-sorting">
            <h5 class="text-center mb-2">DENOMINACIONES</h5>

            <div class="container">
                <div class="row">
                    {{-- para que me muestre todos los valores que hay en moneda o denominaciones --}}
                    @foreach ( $monedas as $moneda )
                        <div class="col-sm mt-2">
                            <button livewire:click.prevent="AEfecivo({{$moneda->valor}})" class="btn btn-block den" style="background: #5c1ac3; color:white"> 
                                {{$moneda->valor >0 ? '$' . number_format($moneda->valor, 2, '.', '' ) : 'Exacto'}}
                            </button>
                        </div>
                    @endforeach
                </div>  
            </div>

            <div class="connect-sorting-content mt-4">
                <div class="card simple-title-task ui-sortable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm" style="background: #5c1ac3; 
                                color:white ">Efectivo F8
                                </span>
                            </div>
                            <input type="numbre" id="dinero" 
                            wire:model="efectivo" 
                            wire:keydown.enter="guardarVenta"
                            class="form-control text-center" value="{{$efectivo}}">
                            <div class="input-group-append">
                                <!--este wire es para cuando se precione el boton deje l imput en 0 -->
                               <!--se puede hacer de dos formas. a: una funcion  y b: un meto de livewire  que es la que se va utilizar en esta caso -->
                                <span wire:click="$set('efectivo', 0)" class="input-group-text" style="background: #5c1ac3; 
                                 color:white ">
                                 <i class="far fa-dot-circle"></i>

                                </span>
                            </div>
                        </div>

                        <h4 class="text-muted">Cambio: ${{number_format($cambio, 2)}}</h4>

                        <div class="row justify-content-between mt-5">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if($total >0)
                                    <button onclick="Confirm('', 'limpiarCart', '¿SEGURO QUIERE ELIMINA CARRITO?')" 
                                    class="btn  mtmobile" style="background: #5c1ac3; color:white">CANCELAR F4</button>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if($efectivo >= $total && $total>0)
                                   <button wire:click.prevent="guardarVenta" class="btn btn-dark btn-md btn-block">GUARDAR F9</button>
                                   @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
