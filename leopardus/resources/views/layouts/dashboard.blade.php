<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    {{-- <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities."> --}}
    {{-- <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app"> --}}
    <meta name="author" content="VALDUSOFT">
    <title>{{$settings->name}}</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('flaticon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    @include('layouts.include.styles')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">
    {{-- header  --}}
    @include('layouts.include.header')

    {{-- menu --}}
    @if (Auth::user()->rol_id == 0)
    @include('layouts.include.sidebar')
    @else
    @include('layouts.include.sidebar2')
    @endauth

    {{-- contenido --}}
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row mt-2">
                <div class="content-header-left col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{$title}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @yield('content')

                {{-- Modal BlackBox --}}
                <div class="modal fade" id="modalBlackBox" tabindex="-1" role="dialog"
                    aria-labelledby="modalBlackBoxTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                        role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalBlackBoxTitle">BlackBox</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 class="text-justify">
                                    Blackbox: Opere directamente en el bróker en donde nuestras redes neuronales pueden
                                    efectuar operaciones, su dinero estará directamente regulado y usted tiene el acceso
                                    de retirar ganancias o capital en cualquier momento, a su vez puede seguir
                                    diariamente sus ganancias sin entregar el capital a la plataforma.
                                </h5>
                                <h5 class="mt-2">FEE MENSUAL:  40% sobre ganancias</h5>
                                <form action="{{route('tienda.blackbox')}}" method="post" class="text-center mt-2">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="price" value="100">
                                    <button type="submit" class="btn btn-success">Comprar</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</body>
<!-- END: Body-->

@include('layouts.include.scripts')

</html>