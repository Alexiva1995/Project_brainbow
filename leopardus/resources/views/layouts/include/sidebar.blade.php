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

            @if (Auth::user()->ID == 1)
            {{-- INICIO INVERSIONES --}}
                        <li class="nav-item">
                <a href="{{url('mioficina/admin')}}" class="nav-link text-white nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('inversiones.admin')}}" class="nav-link text-white">
                    <i class="feather icon-bar-chart-2"></i>
                    <span class="title">Inversiones</span>
                </a>
            </li>
            {{-- FIN INVERSIONES --}}
            {{-- INICIO BOT BRAINBOW --}}

            {{-- FIN BOT BRAINBOW--}}
            {{-- INICIO BLACKBOX --}}

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
                            <span class="title">Binario</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('referraltree', ['tree'])}}" class="nav-link text-white">
                            <i class="feather icon-circle"></i>
                            <span class="title">Unilevel</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('autenticacion.new-register').'?referred_id='.Auth::user()->ID}}"
                            class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Nuevo Usuario</span>
                        </a>
                    </li>
                   
                </ul>
            </li>
            {{-- FIN RED DE USUARIO --}}
            @endif
            {{--INICIO BILLETERA --}}
            
           
        {{-- FIN BILLETERA --}}
        {{--INICIO TICKET --}}
       
        {{-- FIN TICKET --}}
        {{-- LISTA DE USUARIOS--}}
       
        {{-- FIN LISTA DE USUARIOS --}}
        {{--INICIO CONFIGURARION BONOS--}}

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