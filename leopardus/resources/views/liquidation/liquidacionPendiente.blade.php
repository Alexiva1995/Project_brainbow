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
                            <th>ID Liquidacion</th>
                            <th>ID Usuario</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Valor USD</th>
                            <th>Billetera</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($liquidaciones as $liquidacion)
                        <tr class="text-center">
                            <td>{{$liquidacion->id}}</td>
                            <td>{{$liquidacion->iduser}}</td>
                            <td>{{$liquidacion->usuario}}</td>
                            <td>{{$liquidacion->email}}</td>
                            <td>{{$liquidacion->total}}</td>
                            <td>{{$liquidacion->wallet_used}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection