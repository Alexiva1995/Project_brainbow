@extends('layouts.dashboard')

@section('content')

{{-- option datatable --}}
@include('dashboard.componentView.optionDatatable')

{{-- alertas --}}
@include('dashboard.componentView.alert')


<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive">
                <table id="mytable" class="table zero-configuration">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Invertido</th>
                            <th>Plan</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Finalizacion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inversiones as $inversion)
                        <tr class="text-center">
                            <td>{{$inversion->id}}</td>
                            <td>{{$inversion->usuario}}</td>
                            <td>{{$inversion->correo}}</td>
                            <td>$ {{$inversion->invertido}}</td>
                            <td>{{$inversion->plan}}</td>
                            <td>{{date('d-m-Y', strtotime($inversion->fecha_inicio))}}</td>
                            <td>{{date('d-m-Y', strtotime($inversion->fecha_fin))}}</td>
                            <td>
                                @if ($inversion->status == 0)
                                    Esperando Pago
                                @else
                                    Activa
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection