<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" style="background-color: #000D2F;">
    <div class="navbar-header" >
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
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" style="background-color: #000D2F;">
            {{-- INICIO --}}

            {{-- RANKING --}}
            
                        <li class="nav-item">
                <a href="{{url('mioficina/admin')}}" class="nav-link text-white nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle" style="color: #FFFFFF;">
                    <i class="feather icon-bar-chart-2"></i>
                    <span class="title">Inversiones</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" style="background-color: #000D2F;">
                    <li class="nav-item">
                        <a href="{{url('mioficina/tienda')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Inversion</span>
                        </a>
                    </li>

                </ul>
            </li>
            {{--FIN RANKING --}}
            {{-- INICIO BLACKBOX --}}

            {{-- FIN BLACKBOW --}}
            {{-- TRANSACCIONES --}}

            {{--FIN TRANSACCIONES --}}

            {{-- GEONOLOGIA --}}
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle" style="color: #FFFFFF;">
                    <i class="feather icon-users"></i>
                    <span class="title">Mi Red</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" style="background-color: #000D2F;">
                    <li class="nav-item">
                        <a href="{{route('autenticacion.new-register').'?referred_id='.Auth::user()->ID}}"
                            class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Nuevo Usuario</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('referraltree', ['matriz'])}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Árbol Binario</span>
                        </a>
                    </li>
                   
                </ul>
            </li>
            {{-- FIN GENEALOGIA --}}


            {{-- FIN BOT BRAINBOW --}}
        {{--INICIO BILLETERA --}}

        {{-- FIN BILLETERA --}}

        {{-- TICKET --}}
        <li>
      

        {{-- CERRAR SESIÓN --}}
        <li class="nav-item">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link" style="color: #FFFFFF;">
                <i class="feather icon-log-out"></i>
                <span class="title">Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        {{-- FIN CERRAR SESIÓN --}}
        </ul>
    </div>
</div>