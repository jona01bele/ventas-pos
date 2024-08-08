<div class="row sales layout-top-spacing">
    <di class="col-sm-12">
        <div style="background: rgb(210, 210, 221) " class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-center">
                    <b> Corte de Caja</b>
                </h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Usuario</label>
                            <select wire:model="usuario_id" class="form-control">
                                <option value="0" disabled>Elegir</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('usuarioid')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>

                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha Inicial</label>
                            <input type="date" wire:model.lazy="fechaInicial" class="form-control">
                            @error('fechaInicial')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Fecha Final</label>
                            <input type="date" wire:model.lazy="fechaFina" class="form-control">
                            @error('fechaFina')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-----------Botones para consulta y para imprimir--------------------------------->

                    <div class="col-sm-12 col-md-3 align-self-center d-flex justify-content-around">
                        @if ($usuario_id > 0 && $fechaInicial !== null && $fechaFina !== null)
                            <button type="button" wire:click.prevent="consultar()"
                                class="btn btn-dark">Consultar</button>
                        @endif
                        @if ($total > 0)
                            <button type="button" wire:click.prevent="print()" class="btn btn-dark">Imprimir</button>
                        @endif
                    </div>

                </div>

            </div>

            {{-- tabla detalles --}}

            <div class="row mt-5">
                <div class="col-ms-12 col-md-4 mbmobile">
                    <div class="connect-sorting" style="background: blueviolet">
                        <h5 class="text-white">Ventas Tolales: ${{ number_format($total, 2) }}</h5>
                        <h5 class="text-white">Articulos: {{ $items }}</h5>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: blueviolet">
                                <tr>
                                    <th class="table-th text-center text-white">FOLIO</th>
                                    <th class="table-th text-center text-white">TOTAL</th>
                                    <th class="table-th text-center text-white">ITEMS</th>
                                    <th class="table-th text-center text-white">FECHA</th>
                                    <th class="table-th text-center text-white"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($total <= 0)
                                    <tr>
                                        <td colspan="5">
                                            <h6 class="text-center">
                                                No hay ventas en la fecha seleccionada</h6>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($venta as $vent)
                                        <tr>
                                            <td class="text-center">
                                                <h6>{{ $vent->id }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ number_format($vent->total, 2) }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $vent->item }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $vent->created_at }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <button wire:click.prevent="vistaDetalles({{ $vent->id }})"
                                                    class="btn btn-dark btn-sm">
                                                    icono
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{-- @foreach ($venta as $vent)
                                    <tr>
                                        <td class="text-center"><h6>{{$vent->id}}</h6></td>
                                        <td class="text-center"><h6>{{number_format($vent->total,2)}}</h6></td>
                                        <td class="text-center"><h6>{{$vent->items}}</h6></td>
                                        <td class="text-center"><h6>{{$vent->create_at}}</h6></td>
                                        <td class="text-center">
                                            <button wire:click.prevent="vistaDetalles({{$vent}})" class="btn btn-dark btn-sm"> 
                                                icono list
                                            </button>
                                        </td>
                                    </tr>
                                    
                                @endforeach --}}

                                {{-- @if ($venta && $venta->count()) --}}
                                {{-- @foreach ($venta as $vent)
                                        <tr>
                                            <td class="text-center">
                                                <h6>{{ $vent->id }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ number_format($vent->total, 2) }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $vent->item }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $vent->created_at }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <button wire:click.prevent="vistaDetalles({{ $vent->id }})"
                                                    class="btn btn-dark btn-sm">
                                                    icono 
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                {{-- @else
                                    <tr>
                                        <td colspan="5" class="text-center">No hay ventas disponibles.</td>
                                    </tr>
                                @endif --}}

                           </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </di>
    @include('livewire.cortecaja.modaldetalle')
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire is fully initialized');


        // Eventos personalizados de Livewire
        window.addEventListener('show-modal', function(event) {
            $('#modal-detalle').modal('show');
        });

        // window.addEventListener('categoria-agregada', function(event) {
        //     $('#theModal').modal('hide');
        // });

        // window.addEventListener('categoria-actualizada', function(event) {
        //     $('#theModal').modal('hide');
        // });
    });
</script>
