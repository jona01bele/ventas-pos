<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-tittle text-center"><b>{{ $nombreComponente }}</b></h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="row">
                            <div class="col sm 12">
                                <h6>Elige el Usuario</h6>
                                <div class="form-group">
                                    <select wire:model.live="usuario_id" class="form-control">
                                        <option  value="0">Todos</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <h6>Tipo de Reporte</h6>
                                <div class="form-group">
                                    <select wire:model.live="tipoReporte" class="form-control">
                                        <option value="0">Ventas del dia </option>
                                        <option value="1">Ventas por fecha</option>
                                    </select>
                                </div>
                            </div>  

                            <div class="col-sm-12 mt-2">
                                <h6>Fecha desde</h6>
                                <div class="fomr-group">
                                    <!--la clase  flatpicker es para agregar un pluguin para crear un calendario-->
                                    <input type="text" placeholder="Click para elegir" wire:model.live="fechaDesde"
                                        class="form-control flatpickr">
                                </div>
                            </div>

                            <div class="col-sm-12 mt-2">
                                <h6>Fecha hasta</h6>
                                <div class="fomr-group">
                                    <!--la clase  flatpicker es para agregar un pluguin para crear un calendario-->
                                    <input type="text" placeholder="Click para elegir" wire:model.live="fechaHasta"
                                        class="form-control flatpickr">
                                </div>
                            </div>

                            <div class="col-sm-12 mt-2">
                                <button wire:click="$refrescar" class="btn btn-dark btn-block">
                                    Consultar
                                </button>

                                <!--la validacion que esta en la clase a continucacion-> es para que cuando no exista
                                     informacion desabilite el boton de lo contrario que no haga nada-->
                                <a class="btn btn-dark btn-block {{ count($datos) < 1 ? 'disabled' : '' }}"
                                    href="{{ url('reporte/pdf' . '/' . $usuario_id . '/' . $tipoReporte . '/' . $fechaDesde . '/' . $fechaHasta) }}"
                                    target="_bank">
                                    Generar PDF
                                </a>

                                <a class="btn btn-dark btn-block {{ count($datos) < 1 ? 'disabled' : '' }}  "
                                    href="{{ url('reporte/excel' . '/' . $usuario_id . '/' . $tipoReporte . '/' . $fechaDesde . '/' . $fechaHasta) }}"
                                    target="_bank">
                                    Exportar a Excel
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12 col-md-9">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #5c1ac3">
                                    <tr>
                                        <th class="table-th text-white">FOLIO</th>
                                        <th class="table-th text-white text-cente">TOTAL</th>
                                        <th class="table-th text-white text-cente">ITEMS</th>
                                        <th class="table-th text-white text-cente">ESTADO</th>
                                        <th class="table-th text-white text-cente">USUARIO</th>
                                        <th class="table-th text-white text-cente">FECHA</th>
                                        <th class="table-th text-white text-cente" width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($datos) < 1)
                                        <tr>
                                            <td colspan="7">
                                                <h5>Sin resultado</h5>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($datos as $dato)
                                        <tr>
                                            <td><h6 class="text-center">{{ $dato->id }}</h6></td>
                                            <td><h6 class="text-center">{{ number_format($dato->total, 2) }}</h6></td>
                                            <td><h6 class="text-center">{{ $dato->item }}</h6></td>
                                            <td><h6 class="text-center">{{ $dato->estado }}</h6></td>
                                            <td><h6 class="text-center">{{ $dato->usuario }}</h6></td>
                                            <td><h6 class="text-center">{{ \Carbon\Carbon::parse($dato->created_at)->format('d-m-y') }}</h6></td>
                                            <td width="50px">
                                                <h6>
                                                    <button wire:click.prevent="optenerDetalles({{ $dato->id }})"
                                                        class="btn btn-dark btn-sm">
                                                       list
                                                    </button>
                                                </h6>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.reporte.ventadetalles')
</div>

<script>
    // para trabajar en el pligin del calendario o darle configuracion a l acalendario
        document.addEventListener('DOMContentLoaded', function() {
        flatpickr(document.getElementsByClassName('flatpickr'), {
            enabletime: false,
            dateFormat: 'y-m-d',
            locale: {
                firsDayofweek: 1,

                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                        "Domingo",
                        "Lunes",
                        "Martes",
                        "Miércoles",
                        "Jueves",
                        "Viernes",
                        "Sábado",
                    ],
                },

                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                },


            }
        })


    });

    window.addEventListener('show-modal', function(event) {
            $('#theModal').modal('show');
        });
</script>
