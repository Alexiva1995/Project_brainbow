<div class="col-12">
    {{-- fila 1 --}}
    <section class="mt-2 mb-2">
        <div class="row">
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
                        <h3 class="text-bold-700 mt-1">
                            @if (Auth::user()->status == 1)
                                Activo
                            @else
                                Inactivo
                            @endif
                        </h3>
                        <p class="mb-0">Estado</p>

                    </div>
                </div>
            </div>
            {{-- fin Membresia --}}
            {{-- Rentabilidad --}}
            <div class="col-lg-3 col-sm-6 col-12">
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
