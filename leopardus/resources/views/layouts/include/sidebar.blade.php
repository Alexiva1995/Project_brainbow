<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">

    <div class="navbar-header">

        <ul class="nav navbar-nav flex-row">

            {{-- <li class="nav-item mr-auto"> --}}

            {{-- <a class="navbar-brand" href="" href="" style="width: 100%;margin: 0px; margin-top: 1rem;">

                <div class="brand-logo2" style="width: 100%;">

                    <img src="https://comunidadlevelup.com/assets/imgLanding/logo.png" style="width: 100%;">
                   

                </div>

            </a> --}}

            {{-- </li> --}}



            <!--    <li class="nav-item nav-toggle">

                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">

                    <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>

                    <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"

                        data-ticon="icon-disc"></i>

                </a>

            </li>-->

        </ul>

    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item d-flex justify-content-center">

                <div>

                    <div id="diseng" class="color-example"
                        style="background: url('{{ asset('avatar/'.Auth::user()->avatar) }}')">

                    </div>

                    <h5 class="text-center">Hola {{Auth::user()->user_nicename}}</h5>

                    <h6 class="text-center">{{Auth::user()->user_email}}</h6>

                </div>

            </li>

            {{-- INICIO --}}

            <li class="nav-item">
                <a href="{{url('mioficina/admin')}}" class="nav-link nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->ID == 1)
            {{-- INICIO INVERSIONES --}}
            <li class="nav-item">
            <li class="nav-item">
                <a href="{{route('inversiones.admin')}}" class="nav-link">
                    <i class="feather icon-bar-chart-2"></i>
                    <span class="title">Inversiones</span>
                </a>
            </li>
            </li>
            {{-- FIN INVERSIONES --}}
            {{-- RED DE USUARIO --}}
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="feather icon-users"></i>
                    <span class="title">Red</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="{{route('referraltree', ['matriz'])}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">Matriz</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/directrecords')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">Directos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('mioficina/admin/network/networkrecords')}}" class="nav-link">
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
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="feather icon-trending-up"></i>
                    <span class="title">Financiero</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    {{-- <li class="nav-item">
                        <a href="{{url('mioficina/admin/wallet/')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">
                                <small>Billetera</small>
                            </span>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a href="{{route('liquidacion')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">
                                <small>Liquidación Comisiones</small>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('liquidacion.inversion')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">
                                <small>Liquidación Inversiones</small>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('liquidacion.pendientes')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">
                                <small>Liquidaciones Pendientes</small>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('liquidacion.realizadas')}}" class="nav-link">
                            <i class="feather icon-circle"></i>
                            <span class="title">
                                <small>Liquidaciones Realizadas</small>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- FIN BILLETERA --}}
            {{-- LISTA DE USUARIOS--}}
            <li class="nav-item">
                <a href="{{url('mioficina/admin/userrecords')}}" class="nav-link">
                    <i class="fa fa-list-alt"></i>
                    <span class="title">Lista de Usuarios</span>
                </a>
            </li>
            {{-- FIN LISTA DE USUARIOS --}}
            {{-- CERRAR SESIÓN --}}
            <li class="nav-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
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