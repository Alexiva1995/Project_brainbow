@extends('layouts.dashboard')

@section('content')
@php
use Carbon\Carbon;
$fecha = Carbon::now();
$activo = false;
if ($fecha->dayOfWeek >= 1 && $fecha->dayOfWeek <= 2) { $activo=true; } 
@endphp 

{{-- option datatable --}}
@include('dashboard.componentView.optionDatatable')

{{-- alertas --}}
@include('dashboard.componentView.alert')


<div class="card">
    <div class="card-content">
        <div class="card-body">
            <!-- Candlestick Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Candlestick Chart</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div id="candlestick-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="table-responsive">
                    <table id="mytable" class="table zero-configuration">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Correo</th>
                                <th>Descripción</th>
                                <th>Comisión</th>
                                <th>Retiro</th>
                                <th>Fee</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallets as $wallet)
                            <tr>
                                <td class="text-center">{{ $wallet->id }}</td>
                                <td class="text-center">{{date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                                <td class="text-center">{{ $wallet->correo }}</td>
                                <td class="text-center">{{ $wallet->descripcion }}</td>
                                <td class="text-center"> 
                                    @if ($moneda->mostrar_a_d)
                                        {{$moneda->simbolo}} {{$wallet->debito}}
                                    @else
                                        {{$wallet->debito}} {{$moneda->simbolo}}
                                    @endif
                                </td>
                                <td class="text-center"> 
                                    @if ($moneda->mostrar_a_d)
                                        {{$moneda->simbolo}} {{$wallet->credito}}
                                    @else
                                        {{$wallet->credito}} {{$moneda->simbolo}}
                                    @endif
                                </td>
                                <td class="text-center"> 
                                    @if ($moneda->mostrar_a_d)
                                        {{$moneda->simbolo}} {{$wallet->descuento}}
                                    @else
                                        {{$wallet->descuento}} {{$moneda->simbolo}}
                                    @endif
                                </td>
                                <td class="text-center"> 
                                    @if ($moneda->mostrar_a_d)
                                        {{$moneda->simbolo}} {{$wallet->balance}}
                                    @else
                                        {{$wallet->balance}} {{$moneda->simbolo}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            </div>
        </div>
        {{-- @if (Auth::user()->rol_id != 0)
        <div class="col-xs-12 col-sm-6">
            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#myModalRetiro">Retiro</button>
        </div>
        @endif --}}
    </div>
</div>

@include('wallet/componentes/formRetiro', ['disponible' => Auth::user()->wallet_amount, 'tipowallet' => 1])
@include('wallet/componentes/formTransferencia')

@push('page_vendor_js')
<script src="{{asset('app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
@endpush

@push('custom_js')
<script>
  function graficaBot(dataBot) {
    dataBot = JSON.parse(dataBot)
    let data = [];
    dataBot.forEach(element => {
      console.log(element.fecha);
      data.push({
        x: new Date(element.fecha.year, element.fecha.month, element.fecha.day, element.fecha.hour, element.fecha.minute, element.fecha.second),
        y: element.valores
      })
    });
    console.log(data);
    var $primary = '#7367F0',
    $success = '#28C76F',
    $danger = '#EA5455',
    $warning = '#FF9F43',
    $info = '#00cfe8',
    $label_color_light = '#dae1e7';

  var themeColors = [$primary, $success, $danger, $warning, $info];
    var candleStickOptions = {
    chart: {
      height: 350,
      type: 'candlestick',
    },
    colors: themeColors,
    series: [{
      data: data
    }],
    xaxis: {
      type: 'datetime'
    },
    yaxis: {
      tickAmount: 5,
      tooltip: {
        enabled: true
      }
    }
  }
  var candleStickChart = new ApexCharts(
    document.querySelector("#candlestick-chart"),
    candleStickOptions
  );
  candleStickChart.render();
  }
</script>
@php
@endphp
<script>
  $.get('../botbrainbow/get_brainbow', function (data) {
    graficaBot(data)
  })
  
</script>
@endpush
@endsection