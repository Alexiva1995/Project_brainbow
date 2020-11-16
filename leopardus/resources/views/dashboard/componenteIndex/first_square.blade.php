<div class="col-12">
    {{-- fila 1 --}}
    <section class="mt-2 mb-2">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1" onclick="copyToClipboard('copy')">
                    <div style="border-bottom: solid #E4E4E4 1px; padding: 5px 10px;">
                        <h5 class="text-bold-700 mt-1" style="color: #000D2F;">
                            @if (Auth::user()->status == 1)
                            <i class="fa fa-circle font-small-3 text-success mr-50"></i> ACTIVO
                            @else
                            <i class="fa fa-circle font-small-3 text-danger mr-50"></i> INACTIVO
                            @endif
                        </h5>
                    </div>
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar p-50 m-0" style="background-color: #02E9FE;">
                            <div class="avatar-content">
                                <i class="feather icon-link font-medium-5" style="color: white;"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">Copiar Link</h2>
                        <p class="mb-0">Link de Referir</p>
                        <p style="display:none;" id="copy">
                            {{route('autenticacion.new-register').'?referred_id='.Auth::user()->ID}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2"
                        style="height:100%">
                        <div class="avatar p-50 m-0" style="background-color: #ffffff;">
                            <div class="avatar-content">
                                {{-- <i class="fa fa-money font-medium-5" style="color:#02E9FE;"></i> --}}
                                <img src="{{$data['rangoinfo']['imgRangoActual']}}" alt="" srcset="" height="60">
                            </div>
                        </div>
                        {{-- <h3 class="text-bold-700 mt-1">Rango Actual</h3> --}}
                        <hr>
                        <h5 class="text-bold-700 mt-1 text-center">
                            <small>
                                Proximo Rango: <br> <strong>{{$data['rangoinfo']['requisitoNewRango']}}</strong>
                            </small>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-12 mt-2">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column pb-2"
                        style="height:100%">
                        <h5 class="text-bold-700 mt-1" style="color: #000D2F;">
                            Barra de rentabilidad
                        </h5>
                        <div class="progress progress-bar-primary progress-xl" style="width:100%">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="20"
                                aria-valuemin="0" aria-valuemax="{{$data['rentabilidad']['maximo']}}" style="width:{{$data['rentabilidad']['progre_porc']}}%;">
                                {{$data['rentabilidad']['progre_porc']}} %
                            </div>
                        </div>
                        <div style="width:100%">
                            <h5 class="text-bold-700 mt-1 text-center float-left">
                                <small>
                                    Maximo: <br> <strong>{{$data['rentabilidad']['maximo']}} $</strong>
                                </small>
                            </h5>
                            <h5 class="text-bold-700 mt-1 text-center float-right">
                                <small>
                                    Progreso: <br> <strong>{{$data['rentabilidad']['progreso']}} $</strong>
                                </small>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Inversiones y rentabilidad --}}
            @foreach ($data['inversiones'] as $inversion)
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1 fondoBoxDashboard" style="background: url('{{ $inversion['img'] }}');">
                    <div class="card-header d-flex flex-column align-items-end justify-content-center text-right pb-2">
                        <p class="mb-0 mt-1">
                            Inversion
                            <br>
                            <span class="text-bold-700 mt-1">{{number_format($inversion['inversion'], 2, ',', '.')}}
                                USD</span>
                        </p>
                        <p class="mb-0 mt-2">
                            Rentabilidad <br>
                            <span class="text-bold-700 mt-1">{{number_format($inversion['rentabilidad'], 2, ',', '.')}}
                                USD</span>
                        </p>
                    </div>
                    <small class="ml-2 ">{{date('d/m/Y', strtotime($inversion['fecha_venci']))}} -
                        {{$inversion['estado']}}</small>
                </div>
            </div>
            @endforeach
            {{-- fin Inversiones y rentabilidad --}}
        </div>
    </section>
    {{-- fin fila 1 --}}
</div>