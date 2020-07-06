@extends('layouts.dashboard')

@section('content')

{{-- alertas --}}
@include('dashboard.componentView.alert')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Artículos de la Tienda</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-12 mt-3 mb-3 ">
                    <form action="{{route('tienda.inversion')}}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="customRange3">Inversion</label>
                            <input type="range" class="custom-range" name="inversion" min="100" max="10000" step="100" id="customRange3"
                                onchange="medidas()" required>
                            <label class="col-12 text-center">
                                <span class="float-left">100</span>
                                <span class="" id="medida">5000</span>
                                <span class="float-right">10000</span>
                            </label>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-info">Invertir</button>
                        </div>
                    </form>
                </div>
                @foreach ($productos as $item)
                <div class="col-md-4 col-sm-12">
                    <div class="card ecommerce-card">
                        <div class="card-content">
                            <div class="item-img text-center">
                                <img class="img-fluid" src="{{$item->imagen}}" alt="{{$item->post_title}}">
                            </div>
                            <div class="card-body">
                                <div class="item-name">
                                    <span>
                                        {{$item->post_title}}
                                    </span>
                                </div>
                                <div>
                                    <p class="item-description">
                                        {{$item->post_content}}
                                    </p>
                                </div>
                            </div>
                            <div class="item-options text-center">
                                <div class="item-wrapper">
                                    <div class="item-cost">
                                        <h6 class="item-price">
                                            ${{$item->meta_value}}
                                        </h6>
                                    </div>
                                </div>
                                <div class="btn btn-info mt-1 text-white" onclick="detalles('{{json_encode($item)}}')">
                                    <i class="feather icon-shopping-cart"></i>
                                    <a class="view-in-cart">Comprar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>



{{-- modales --}}
@include('tienda.modalCompra')
{{-- @include('tienda.modalCupon') --}}

<script>
    function medidas() {
        let medida = $('#customRange3').val();
        $('#medida').text(medida)
    }

    function detalles(product) {
        product = JSON.parse(product)
        
        $('#idproducto').val(product.ID)
        $('#img').attr('src', product.imagen)
        $('#title').html(product.post_title)
        $('#title2').val(product.post_title)
        $('#content').html(product.post_content)
        $('#price').html('$ ' + product.meta_value)
        $('#price2').val(product.meta_value)
        $('#myModal1').modal('show')
    }

    function validarCupon() {
        let cupon = $('#cupon').val();
        let url = '{{route('tienda-verificar-cupon')}}'
        let token = '{{ csrf_token() }}'
        $.post(url, {
            '_token': token,
            'cupon': cupon
        }).done(function (response) {
            let data = JSON.parse(response)
            if (data.msj != '') {
                alert(data.msj)
            } else {
                $("#tipo1").val(data.tipo)
                $("#producto" + 1).val(data.paquete)
                $("#total" + 1).val(data.precio)
                $("#myModalLabel1").text('Cupon del Producto ' + data.paquete)
                $("#idproducto" + 1).val(data.idpaquete)
                $("#restante" + 1).val(0)
                $("#btn" + 1).text('Recibir Cupon')
                $("#cupon" + 1).val(data.cupon)
                $("#myModal" + 1).modal('show')
            }
        })
    }
</script>

@endsection