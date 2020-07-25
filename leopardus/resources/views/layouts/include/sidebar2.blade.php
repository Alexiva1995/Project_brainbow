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
            <li class="nav-item">
                <a href="{{url('mioficina/admin')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                    <i class="feather icon-home"></i>
                    <span class="title">Balance General</span>
                </a>
            </li>
            {{-- RANKING --}}
            <li class="nav-item">
                <a href="{{url('mioficina/tienda')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                    <i class="feather icon-shopping-cart"></i>
                    <span class="title">Inversiones</span>
                </a>
            </li>
            {{--FIN RANKING --}}
            {{-- TRANSACCIONES --}}
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle" style="color: #FFFFFF;">
                    <i class="feather icon-activity"></i>
                    <span class="title">Mi Negocio</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" style="background-color: #000D2F;">
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/transactions/networkorders')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Ordenes de Red</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/transactions/personalorders')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Ordenes Personales</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/wallet/cobros')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Retiros</span>
                        </a>
                    </li>
                </ul>
            </li>
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
                            <span class="title">Árbol de Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/directrecords')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Registros Directos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/networkrecords')}}" class="nav-link" style="color: #FFFFFF;">
                            <i class="feather icon-circle"></i>
                            <span class="title">Registros en Red</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- FIN GENEALOGIA --}}

        {{--INICIO BILLETERA --}}
        <li class="nav-item">
            <a href="{{url('mioficina/admin/wallet/')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                <i class="feather icon-trending-up"></i>
                <span class="title">Billetera</span>
                <span class="arrow"></span>
            </a>
          {{--  <ul class="sub-menu" style="background-color: #000D2F;">
                <li class="nav-item">
                    <a href="" class="nav-link" style="color: #FFFFFF;">
                        <i class="feather icon-circle"></i>
                        <span class="title">Billetera Cash</span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{url('mioficina/admin/wallet/puntos')}}" class="nav-link">
                        <i class="feather icon-circle"></i>
                        <span class="title">Historial de Puntos</span>
                    </a>
                </li> 
            </ul>--}}
        </li>
        {{-- FIN BILLETERA --}}

        {{--<li>

            <a href="{{route('publicidad.user')}}" class="nav-link nav-toggle" style="color: #FFFFFF;">
                <i class="feather icon-share"></i>
                <span class="title">Publicidad</span>
            </a>

        </li>--}}

        {{-- INFORMES --}}
        <li>
            <a href="javascript:;" class="nav-link nav-toggle" style="color: #FFFFFF;">
                <i class="feather icon-file-text"></i>
                <span class="title">Informes</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu" style="background-color: #000D2F;">
                <li class="nav-item">
                    <a href="{{url('mioficina/admin/info/activacion')}}" class="nav-link" style="color: #FFFFFF;">
                        <i class="feather icon-circle"></i>
                        <span class="title">Activacion</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('mioficina/admin/info/comisiones')}}" class="nav-link" style="color: #FFFFFF;">
                        <i class="feather icon-circle"></i>
                        <span class="title">Comisiones</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('mioficina/admin/info/liquidacion')}}" class="nav-link" style="color: #FFFFFF;">
                        <i class="feather icon-circle"></i>
                        <span class="title">Liquidaciones</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- FIN GESTION DE PERFILES --}}
        {{-- INICIO TICKETS --}}
        {{-- <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="feather icon-message-square"></i>
                <span class="title">Soporte</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="{{url('mioficina/admin/ticket/ticket')}}" class="nav-link">
                        <i class="feather icon-circle"></i>
                        <span class="title">Generar Tickets</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('mioficina/admin/ticket/misticket')}}" class="nav-link">
                        <i class="feather icon-circle"></i>
                        <span class="title">Mis Tickets</span>
                    </a>
                </li>
            </ul>
        </li> --}}
        {{-- FIN TICKETS --}}

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