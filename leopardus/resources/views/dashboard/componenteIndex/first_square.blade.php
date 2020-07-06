<div class="col-12">
    {{-- fila 1 --}}
    <section class="mt-2 mb-2">
        <div class="row">
            {{-- Estado Binario --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div
                            class="avatar @if ($data['activoBinario']) bg-rgba-success @else bg-rgba-danger @endif p-50 m-0">
                            <div class="avatar-content">
                                <i
                                    class="feather icon-bold font-medium-5 @if ($data['activoBinario']) text-success @else text-danger @endif"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">
                            @if ($data['activoBinario'])
                            Activo
                            @else
                            Inactivo
                            @endif
                        </h2>
                        <p class="mb-0">
                            <h6>
                                Estado Binario
                            </h6>
                            <h6>
                                <small>Lado activo de registro binario</small>
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li class="d-inline-block mr-2">
                                    <fieldset>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="customRadio"
                                                id="customRadio1" @if (Auth::user()->ladoregistrar == 'D') checked
                                            @endif onclick="updateSideBinary('D')">
                                            <label class="custom-control-label" for="customRadio1">Derecha</label>
                                        </div>
                                    </fieldset>
                                </li>
                                <li class="d-inline-block mr-2">
                                    <fieldset>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="customRadio"
                                                id="customRadio2" @if (Auth::user()->ladoregistrar == 'I') checked
                                            @endif onclick="updateSideBinary('I')">
                                            <label class="custom-control-label" for="customRadio2">Izquierda</label>
                                        </div>
                                    </fieldset>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>
            {{-- fin estado binario --}}
            {{-- Rentabilidad --}}
            <div class="col-lg-3 col-sm-6 col-12">
                {{-- <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-activity text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['progresoDiario']}} %</h2>
                        <p class="mb-0">Rent</p>
                    </div>
                    <div class="card-body">
                        <div class="progress progress-bar-primary progress-xl">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="{{$data['progresoDiario']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width:40%">
                                {{$data['progresoDiario']}} %
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-credit-card text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{(Auth::user()->rentabilidad != null) ? Auth::user()->rentabilidad : 0}} $</h2>
                        <p class="mb-0">Rentabilidad</p>
                    </div>
                </div>
            </div>
            {{-- fin Rentabilidad --}}
            {{-- Inversion --}}
            <div class="col-lg-3 col-sm-6 col-12">
                {{-- <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-activity text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['progresoDiario']}} %</h2>
                        <p class="mb-0">Rent</p>
                    </div>
                    <div class="card-body">
                        <div class="progress progress-bar-primary progress-xl">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="{{$data['progresoDiario']}}" aria-valuemin="0" aria-valuemax="100"
                                style="width:40%">
                                {{$data['progresoDiario']}} %
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-credit-card text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['inversion']}} $</h2>
                        <p class="mb-0">Inversion</p>
                    </div>
                </div>
            </div>
            {{-- fin Inversion --}}
            {{-- Membresia --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar p-50 m-0">
                            <div class="avatar-content">
                                <img class="img-fluid" src="{{$data['membresia']['img']}}" alt="img placeholder">
                            </div>
                        </div>
                        <h3 class="text-bold-700 mt-1">{{$data['membresia']['nombre']}}</h3>
                        <p class="mb-0">Membresia</p>
                    </div>
                </div>
            </div>
            {{-- fin Membresia --}}
        </div>
    </section>
    {{-- fin fila 1 --}}
    {{-- fila 2 --}}
    <section class="mt-2 mb-2">
        <div class="row">
            {{-- link de referido --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1" onclick="copyToClipboard('copy')">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-info p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-link text-info font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">Copiar Link</h2>
                        <p class="mb-0">Link de Referido</p>
                        <p style="display:none;" id="copy">
                            {{route('autenticacion.new-register').'?referred_id='.Auth::user()->ID}}
                        </p>
                    </div>
                </div>
            </div>
            {{-- fin link de referido --}}
            {{-- puntos a la Izquierda --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-chevron-left text-danger font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['puntos']['izquierdos']}}</h2>
                        <p class="mb-0">Puntos Izquierdos</p>
                    </div>
                </div>
            </div>
            {{-- fin puntos a la izquierda --}}
            {{-- puntos a la derecha --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-chevron-right text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['puntos']['derechos']}}</h2>
                        <p class="mb-0">Puntos Derechos</p>
                    </div>
                </div>
            </div>
            {{-- fin puntos a la derecha --}}
            {{-- billetera --}}
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card h-100 mt-1 mb-1">
                    <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
                        <div class="avatar bg-rgba-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-credit-card text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">{{$data['billetera']}} $</h2>
                        <p class="mb-0">Billetera</p>
                    </div>
                </div>
            </div>
            {{-- fin billetera --}}
        </div>
    </section>
    {{-- fin fila 2 --}}
</div>



{{-- <div class="col-lg-3 col-sm-6 col-12">
    <div class="card h-100 mt-1 mb-1">
        <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
            <div class="avatar bg-rgba-danger p-50 m-0">
                <div class="avatar-content">
                    <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                </div>
            </div>
            <h2 class="text-bold-700 mt-1">36%</h2>
            <p class="mb-0">Quarterly Sales</p>
        </div>
    </div>
</div>
<div class="col-lg-3 col-sm-6 col-12">
    <div class="card h-100 mt-1 mb-1">
        <div class="card-header d-flex flex-column align-items-center justify-content-center pb-2">
            <div class="avatar bg-rgba-warning p-50 m-0">
                <div class="avatar-content">
                    <i class="feather icon-package text-warning font-medium-5"></i>
                </div>
            </div>
            <h2 class="text-bold-700 mt-1">97.5K</h2>
            <p class="mb-0">Orders Received</p>
        </div>
    </div>
</div> --}}