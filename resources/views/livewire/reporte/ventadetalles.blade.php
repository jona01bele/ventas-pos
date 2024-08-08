<!--este es un modal que ha sido cortado y modificado en dos partes el header y el footer-->

<!--wire:ignore.self este codigo busca que le componente no se cierre cada vez que se renderice-->
<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #5c1ac3 ">
                <h5 class="modal-title text-white">
                    <!--colocar el nombre de componente..... luego sigue una validacion si es editar o registrar-->
                    <b>Detalle de venta # {{$ventaId}}</b>
                </h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
                <!--la idea de este codigo es para que cuando el servidor tarde en dar la respuesta y a traves de la directiva loades (wire:loading)  de livewire aparezca el mensaje-->
                <h6 class="text-center text warning" wire:loading>Cargando...</h6> 
            </div>
            <div class="modal-body">
                 
                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #5c1ac3">
                            <tr>
                                <th class="table-th text-white">FOLIO</th>
                                <th class="table-th text-white">PRODUCTO</th>
                                <th class="table-th text-white">PRECIO</th>
                                <th class="table-th text-white">CANT</th>
                                <th class="table-th text-white">IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detalles as $detalle) 
                            <tr>
                                <td><h6 class="text-center">{{$detalle->id}}</h6></td>
                                <td><h6 class="text-center">{{$detalle->producto}}</h6></td>
                                <td><h6 class="text-center">{{number_format($detalle->precio, 2)}}</h6></td>
                                <td><h6 class="text-center">{{number_format($detalle->cantidad, 0)}}</h6></td>
                                <td><h6 class="text-center">{{number_format($detalle->precio * $detalle->cantidad, 2)}}</h6></td>                               
                                
                            </tr>
                            @endforeach
                            
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3"><h5 class="text-center">TOTALES</h5></td>
                                <td><h5 class="text-center">{{$contarDetalles}}</h5></td>
                                <td><h5 class="text-center"> {{number_format($sumaDetalles, 2)}} </h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer">

                <!--esto es para cerrar el modal : data-dismiss="modal"-->
                <button type="button"  class="btn btn-light close-btn text-info"
                    data-dismiss="modal">Cerrar   
                </button>
            </div>
        </div>
    </div>
</div>

<!--#5c1ac3-->
