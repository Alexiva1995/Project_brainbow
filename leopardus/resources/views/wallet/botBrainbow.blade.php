@extends('layouts.dashboard')

@section('content')

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
                            <h4 class="card-title">Bot Brainbow</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div id="candlestick-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


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