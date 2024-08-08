<div wire:ignore.self id="modal-detalle" class="modal fade">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-tittle text-white">
                    <b>Detalle de venta</b>
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt1">
                        <thead class="text-white" style="background: blueviolet">
                            <tr>
                                <th class="table-th text-center text-white">PRODUCTO</th>
                                <th class="table-th text-center text-white">CANT</th>
                                <th class="table-th text-center text-white">PRECIO</th>
                                <th class="table-th text-center text-white">IMPORTE</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- @foreach ($detalles as $detalle)
                                <tr>
                                    <td class="text-center"><h6>{{$detalle->producto}}</h6></td>
                                    <td class="text-center"><h6>{{$detalle->cantidad}}</h6></td>
                                    <td class="text-center"><h6>{{number_format($detalle->precio,2)}}</h6></td>
                                    <td class="text-center"><h6>{{number_format($detalle->cantidad * $detalle->precio , 2)}}</h6></td>
                                </tr>
                                
                            @endforeach --}}

                            @if ($detalle && $detalle->count())
                                @foreach ($detalle as $det)
                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $det->producto }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $det->cantidad }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ number_format($det->precio, 2) }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ number_format($det->cantidad * $det->precio, 2) }}</h6>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No hay detalles disponibles.</td>
                                </tr>
                            @endif

                        </tbody>
                        <tfoot>
                            <td class="text-rigth">
                                <h6 style="color: blueviolet">TOTALES</h6>
                            </td>
                            <td class="text-center">
                                @if ($detalle)
                                    <h6 style="color: blueviolet">{{ $detalle->sum('cantidad') }}</h6>
                                @endif
                            </td>
                            @if ($detalle)
                                {{-- falta arreglar por que no me esta haciendo el acumulado  el total--}}
                                @php $mitotal = 0; @endphp
                                @foreach ($detalle as $deta)
                                    @php
                                       
                                        $mitotal += $deta->cantidad * $deta->precio;
                                    @endphp
                                    
                                @endforeach
                                
                                <td colspan="2" class="text-right">
                                    <h6 style="color: blueviolet ; margin-right: 30px">${{ number_format($mitotal, 2) }}</h6>
                                </td>

                            @endif
                            
                        </tfoot>
                        
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
