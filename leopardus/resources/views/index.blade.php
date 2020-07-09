@extends('layouts.home')

@section('content')
	<!-- banner -->
    <div class="ind-row1">
        <div class="banner">
            <div class="content clearfix">
                <div class="text">
                    <p aos="fade-up" aos-easing="ease" aos-duration="1000" aos-delay="100">Redes neuronales y robótica uniendo los mejores <br> fondos de inversiones a nivel internacional <br/> para generar transacciones seguras.</p>
                    <p aos="fade-up" aos-easing="ease" aos-duration="1000" aos-delay="200">Todos nuestros sistemas y aplicaciones <br/> se conectan a blockchain para ser confirmadas y seguras.</p>
                    <a class="banBut" href="#" aos="fade-up" aos-easing="ease" aos-duration="1000" aos-delay="300" target="_blank">MAINNET</a>
                </div>
                <div class="pic">
                    <div class="con">
                        <img class="img1" src="{{ asset('templateHome/images/ban_pic01.png') }}">
                        <img class="img2" src="{{ asset('templateHome/images/ban_pic02.png') }}">
                        <img class="img3" src="{{ asset('templateHome/images/ban_pic03.png') }}">
                    </div>
                </div>
                <div class="banicon">
                    <img src="{{ asset('templateHome/images/icon03.png') }}">
                </div>
            </div>
        </div>

        <div class="ind-row2" id="features">
            <div class="content">
                <div class="main">
                    <div class="box clearfix" aos="fade-up" aos-easing="ease" aos-duration="2000" aos-delay="200">
                        <div class="pic">
                             <img src="{{ asset('templateHome/images/advantage_pic01.png') }}">
                        </div>
                        <div class="text">
                            <h1>Confirmación en cadena de bloques</h1>
                            <p>Brainbow la primera empresa que crea todas sus aplicaciones y transacciones con fondos a nivel internacional, generando transacciones seguras y confirmadas por blockchain.</p>
                        </div>
                    </div>

                    <div class="box clearfix" aos="fade-up" aos-easing="ease" aos-duration="2000" aos-delay="200">
                        <div class="pic">
                            <img src="{{ asset('templateHome/images/advantage_pic02.png') }}">
                        </div>
                        <div class="text">
                            <h1>Apalancados por inteligencia artificial y algoritmos</h1>
                            <p>Todos nuestros procesos son creados a través de redes neuronales, somos una empresa 100% automatizada</p>
                        </div>
                    </div>

                    <div class="box clearfix" aos="fade-up" aos-easing="ease" aos-duration="2000" aos-delay="200">
                        <div class="pic">
                            <img src="{{ asset('templateHome/images/advantage_pic03.png') }}">
                        </div>
                        <div class="text">
                            <h1>Smart contracts based on Smart Links</h1>
                            <p>Smart links es una nueva iniciativa de brainbow, se crea como mecanismo tipo oráculo para anular las limitaciones de conocimiento en el contrato inteligente. Nuestros contratos no son únicamente con clientes e inversionistas, también con cada uno de nuestros proveedores.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ind-row3" id="application">
        <canvas id="J_dotLine" class="bg"></canvas>
        <div class="content1400">
            <div class="title wow fadeInUp animation">
                <h1>Escenarios Aplicables</h1>
            </div>
            <div class="scenariosMain">
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon01.png') }}">
                    </div>
                    <div class="text">
                        <p>Redes neuronales, bigdata, blockchain unidos para generar una empresa confiable.</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon02.png') }}">
                    </div>
                    <div class="text">
                        <p>Transacciones digitales en manos de algoritmos seguros.</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon03.png') }}">
                    </div>
                    <div class="text">
                        <p>Soporte 52 horas de la semana disponible y chats inteligentes.</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon04.png') }}">
                    </div>
                    <div class="text">
                        <p>Contratos por cada transacción entre clientes y proveedores seguros.</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon05.png') }}">
                    </div>
                    <div class="text">
                        <p>Colaboración internacional en medios rentables y sostenibles.</p>
                    </div>
                </div>
                <div class="box">
                    <div class="icon">
                        <img src="{{ asset('templateHome/images/scenarios_icon06.png') }}">
                    </div>
                    <div class="text">
                        <p>Sistema de red de mercadeo con estudios de sostenibilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ind-usage" id="scenarios">
    	<div class="title wow fadeInUp animation">
            <h1>Escenarios de Uso</h1>
        </div>
        <div class="content">
            <div class="usage-nav">
                <div class="swiper-container usage-nav-container swiper-no-swiping">
                    <div class="swiper-wrapper">
                    	<div class="swiper-slide">
                    		<div class="box">
                    			<div class="con">
                    				<div class="icon">
                    					<img class="img1" src="{{ asset('templateHome/images/usage_icon01.png') }}">
                                        <img class="img2" src="{{ asset('templateHome/images/usage_icon01_on.png') }}">
                                    </div>
                                    <div class="text">
                                        <p style="font-size: 13px;">Envío a fondos de inversiones (inmobiliarios, acciones, clubs deportivos y mercados financieros)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="box">
                                <div class="con">
                                    <div class="icon">
                                        <img class="img1" src="{{ asset('templateHome/images/usage_icon02.png') }}">
                                        <img class="img2" src="{{ asset('templateHome/images/usage_icon02_on.png') }}">
                                    </div>
                                    <div class="text">
                                        <p style="font-size: 13px;">Publicación de rendimientos, cuentas y seguimiento a través de algoritmos en nuestro backoffice</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="box">
                                <div class="con">
                                    <div class="icon">
                                        <img class="img1" src="{{ asset('templateHome/images/usage_icon03.png') }}">
                                        <img class="img2" src="{{ asset('templateHome/images/usage_icon03_on.png') }}">
                                    </div>
                                    <div class="text">
                                        <p style="font-size: 13px;">Contratos verificados por redes neuronales y confirmados a través de BlockChain</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-prev usage-prev"></div>
                    <div class="swiper-button-next usage-next"></div>
                </div>
            </div>
        </div> 
    </div>

    <div class="ind-row8">
	    <div class="content">
	    	<div class="entrance">
	            <a class="ind-butDiv" href="{{ route('autenticacion.new-register') }}">
	                <img class="i1" src="{{ asset('templateHome/images/b_icon01.png') }}">
	                <img class="i2" src="{{ asset('templateHome/images/b_icon01_on.png') }}">
	                Únete a Brainbow Capital
	            </a>
	        </div>
	        <br>
	        <h1>TECNOLOGÍA QUE UNE</h1><br>
	        <p>REDES NEURONALES – BLOCKCHAIN – ALGORITMOS – BIGDATA<br>
				Para generar un ecosistema sostenible de transacciones reales y verificadas</p>       
	    </div>
	</div>
@endsection