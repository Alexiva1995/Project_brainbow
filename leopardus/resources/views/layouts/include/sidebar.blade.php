<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true"
    style="background-color: #000D2F;">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <a class="navbar-brand" href="" href="" style="width: 100%; margin: 0px;">
                <div class="brand-logo2" style="width: 100%;">
                    <img src="{{ asset('assets/imgLanding/logo-dashboard.png') }}" style="width: 100%;">
                </div>
            </a>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"
            style="background-color: #000D2F;">
              {{-- <li class="nav-item d-flex justify-content-center">
                <div>
                    <div id="diseng" class="color-example"
                        style="background: url('{{ asset('avatar/'.Auth::user()->avatar) }}')">
    </div>
    <h5 class="text-center text-white">Hola {{Auth::user()->user_nicename}}</h5>
    <h6 class="text-center text-white">{{Auth::user()->user_email}}</h6>
</div>
</li> --}}
            {{-- INICIO --}}
            <li class="nav-item">
                <a href="{{url('mioficina/admin')}}" class="nav-link text-white nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->ID == 1)
            {{-- INICIO INVERSIONES --}}
            <li class="nav-item">
                <a href="{{route('inversiones.admin')}}" class="nav-link text-white">
                    <i class="feather icon-bar-chart-2"></i>
                    <span class="title">Inversiones</span>
                </a>
            </li>
            {{-- FIN INVERSIONES --}}
            {{-- INICIO BOT BRAINBOW --}}
            <li class="nav-item">
                <a href="{{route('botbrainbow.index')}}" class="nav-link text-white">
                    <i class="feather icon-activity"></i>
                    <span class="title">Bot Brainbow</span>
                </a>
            </li>
            {{-- FIN BOT BRAINBOW--}}
            {{-- INICIO BLACKBOX --}}
            <li class="nav-item">
                {{-- @if ($blackboxcheck == 0)
                <a href="javascript:;" class="nav-link text-white" onclick="$('#modalBlackBox').modal('show')">
                    <i class="feather icon-package"></i>
                    <span class="title">Blackbox</span>
                </a>
                @else --}}
                <a href="{{route('blackbox.log')}}" class="nav-link text-white">
                    <i class="feather icon-package"></i>
                    <span class="title">Blackbox</span>
                </a>    
                {{-- @endif  --}}
            </li>
            {{-- FIN BLACKBOW --}}
            {{-- RED DE USUARIO --}}
            <li class="nav-item">
                <a href="javascript:;" class="nav-link text-white nav-toggle">
                    <i class="feather icon-users"></i>
                    <span class="title">Red</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" style="background-color: #000D2F;">
                    <li class="nav-item">
                        <a href="{{route('referraltree', ['matriz'])}}" class="nav-link text-white">
                            <i class="feather icon-circle"></i>
                            <span class="title">Matriz</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/directrecords')}}" class="nav-link text-white">
                            <i class="feather icon-circle"></i>
                            <span class="title">Directos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/networkrecords')}}" class="nav-link text-white">
                            <i class="feather icon-circle"></i>
                            <span class="title">Red</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- FIN RED DE USUARIO --}}
            @endif
            {{--INICIO BILLETERA --}}
            <li class="nav-item">
                <a href="javascript:;" class="nav-link text-white nav-toggle">
                    <i class="feather icon-trending-up"></i>
                    <span class="title">Financiero</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" style="background-color: #000D2F;">
                    {{-- <li class="nav-item">
                        <a href="{{url('mioficina/admin/wallet/')}}" class="nav-link text-white">
                    <i class="feather icon-circle"></i>
                    <span class="title">
                        <small>Billetera</small>
                    </span>
                    </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{route('liquidacion')}}" class="nav-link text-white">
                    <i class="feather icon-circle"></i>
                    <span class="title">
                        <small>Liquidación Comisiones</small>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('liquidacion.inversion')}}" class="nav-link text-white">
                    <i class="feather icon-circle"></i>
                    <span class="title">
                        <small>Liquidación Inversiones</small>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('liquidacion.pendientes')}}" class="nav-link text-white">
                    <i class="feather icon-circle"></i>
                    <span class="title">
                        <small>Liquidaciones Pendientes</small>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('liquidacion.realizadas')}}" class="nav-link text-white">
                    <i class="feather icon-circle"></i>
                    <span class="title">
                        <small>Liquidaciones Realizadas</small>
                    </span>
                </a>
            </li>
        </ul>
        </li>
        {{-- FIN BILLETERA --}}
        {{--INICIO TICKET --}}
        <li class="nav-item">
            <a href="{{route('todosticket')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                <i class="feather icon-mail"></i>
                <span class="title">Tickets</span>
                {{-- <span class="arrow"></span> --}}
            </a>
        </li>
        {{-- FIN TICKET --}}
        {{-- LISTA DE USUARIOS--}}
        <li class="nav-item">
            <a href="{{url('mioficina/admin/userrecords')}}" class="nav-link text-white">
                <i class="fa fa-list-alt"></i>
                <span class="title">Lista de Usuarios</span>
            </a>
        </li>
        {{-- FIN LISTA DE USUARIOS --}}
        {{--INICIO CONFIGURARION BONOS--}}
        <li class="nav-item">
            <a href="{{route('bonosetting.index')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                <i class="feather icon-settings"></i>
                <span class="title">Configurar Bonos</span>
                {{-- <span class="arrow"></span> --}}
            </a>
        </li>
        {{-- FIN CONFIGURARION BONOS--}}
        {{-- CERRAR SESIÓN --}}
        <li class="nav-item">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                class="nav-link text-white">
                <i class="feather icon-log-out"></i>
                <span class="title">Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        </ul>
    </div>
</div>