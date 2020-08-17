@extends('layouts.dashboard')

@section('content')
{{-- option datatable --}}
@include('dashboard.componentView.optionDatatable')
@push('custom_js')
<script>
	$(document).ready(function () {
		$('#mytable2').DataTable({
			dom: 'flBrtip',
            responsive: true,
            order: [5, ['desc']]
		});
	});
</script>
@endpush
{{-- alertas --}}
@include('dashboard.componentView.alert')
<section>
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="col-12">
                    <button class="btn btn-primary" data-target="#newBot" data-toggle="modal">Agregar nuevo
                        registro</button>
                </div>
                <div class="table-responsive">
                    <table id="mytable2" class="table zero-configuration">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">
                                    Abierto
                                </th>
                                <th class="text-center">
                                    Alto
                                </th>
                                <th class="text-center">
                                    Bajo
                                </th>
                                <th class="text-center">
                                    Cerrado
                                </th>
                                <th class="text-center">
                                    Bajo - Subio
                                </th>
                                <th class="text-center">
                                    Fecha Bot
                                </th>
                                <th class="text-center">
                                    Fecha Creacion
                                </th>
                                <th>
                                    Accion
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($botbrainbow as $bot)
                            <tr class="text-center">
                                <td>{{$bot->abierto}}</td>
                                <td>{{$bot->alto}}</td>
                                <td>{{$bot->bajo}}</td>
                                <td>{{$bot->cerrado}}</td>
                                <td>
                                    @if ($bot->post_nega == 0)
                                    <i class="fa fa-chevron-down font-small-3 text-danger mr-50"></i>
                                    @else
                                    <i class="fa fa-chevron-up font-small-3 text-success mr-50"></i>
                                    @endif
                                </td>
                                <td>{{date('Y-m-d H:i:s', strtotime($bot->fecha_bot))}}</td>
                                <td>{{date('Y-m-d H:i:s', strtotime($bot->created_at))}}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="update('{{json_encode($bot)}}')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Registro -->
    <div class="modal fade" id="newBot" tabindex="-1" role="dialog" aria-labelledby="newBotLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="newBotLabel">Nuevo registro de bot</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('botbrainbow.save')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Fecha Bot</label>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                        <div class="form-group">
                            <label for="">Hora Bot</label>
                            <input type="time" class="form-control" name="hora">
                        </div>
                        <div class="form-group">
                            <label for="">Abierto</label>
                            <input type="number" step="any" class="form-control" name="abierto">
                        </div>
                        <div class="form-group">
                            <label for="">Alto</label>
                            <input type="number" step="any" class="form-control" name="alto">
                        </div>
                        <div class="form-group">
                            <label for="">Bajo</label>
                            <input type="number" step="any" class="form-control" name="bajo">
                        </div>
                        <div class="form-group">
                            <label for="">Cerrado</label>
                            <input type="number" step="any" class="form-control" name="cerrado">
                            <p class="text-black text-help">
                                Con este valor es que se va a saber si bajo o subio,
                                comparandolo con el valor anterior registrado de la fecha mas cercana a la que se va a
                                registrar.
                            </p>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Editar Registro --}}
    <div class="modal fade" id="editBot" tabindex="-1" role="dialog" aria-labelledby="editBotLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editBotLabel">Editar Registro Bow</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('botbrainbow.update')}}" method="post">
                        <input type="hidden" name="_token" id="token">
                        <input type="hidden" name="idbot" id="idbot">
                        <div class="form-group">
                            <label for="">Abierto</label>
                            <input type="number" step="any" class="form-control" name="abierto" id="abierto">
                        </div>
                        <div class="form-group">
                            <label for="">Alto</label>
                            <input type="number" step="any" class="form-control" name="alto" id="alto">
                        </div>
                        <div class="form-group">
                            <label for="">Bajo</label>
                            <input type="number" step="any" class="form-control" name="bajo" id="bajo">
                        </div>
                        <div class="form-group">
                            <label for="">Cerrado</label>
                            <input type="number" step="any" class="form-control" name="cerrado" id="cerrado">
                            <p class="text-black text-help">
                                Con este valor es que se va a saber si bajo o subio,
                                comparandolo con el valor anterior registrado de la fecha mas cercana a la que se va a
                                registrar.
                            </p>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function update(bot) {
        bot = JSON.parse(bot)
        $('#idbot').val(bot.id)
        $('#abierto').val(bot.abierto)
        $('#alto').val(bot.alto)
        $('#bajo').val(bot.bajo)
        $('#cerrado').val(bot.cerrado)
        $('#token').val('{{ csrf_token() }}')
        $('#editBot').modal('show')
    }
</script>
@endsection