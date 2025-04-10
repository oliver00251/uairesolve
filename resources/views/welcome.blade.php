@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center vh-100 text-center"
        style="background: linear-gradient(180deg, #0052D4, #4364F7, #6FB1FC); color: #fff; margin-top:-10vh;">
        <div>
            <div class="d-flex justify-content-center">
                <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                <dotlottie-player src="https://lottie.host/dac7d0c1-7521-4fd8-b978-885aa8d72c84/r2CGvLlNpc.lottie"
                    background="transparent" speed="1" style="width: 300px; height: 300px" loop
                    autoplay></dotlottie-player>
            </div>
            <!-- Título -->
            <h1 class="display-4 fw-bold mb-3">Ajude a melhorar nossa cidade</h1>
            <p class="lead mb-4">Registre problemas e acompanhe a solução em tempo real.</p>
           
            <div class="d-block d-lg-none">
                <!-- Botão de ação para Ocorrências -->
                <a href="{{ route('ocorrencias.index') }}"
                    class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary btn-mobile-full">
                    <i class="fas fa-map-marker-alt"></i> Publicações
                </a>

                <!-- Botão de ação para Vagas de Emprego -->
                <a href="{{ route('vagas.index') }}"
                    class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary btn-mobile-full">
                    <i class="fas fa-briefcase"></i> Vagas de Emprego
                </a>
                <!-- Botão Impacto -->
                <a href="{{ route('impacto.publico') }}" class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary btn-mobile-full">
                    <i class="fas fa-chart-line"></i>Dados da Cidade
                </a>
                
                <!-- Botão de ação fale conosco -->
                <a href="https://www.instagram.com/uairesolveoficial/"
                    class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary btn-mobile-full" target="_blank">
                    <i class="fab fa-instagram"></i> Fale Conosco
                </a>
            </div>

        </div>
    </div>
    <style>
        @media (max-width: 768px) {
            .btn-mobile-full {
                min-width: 65vw !important;
            }

        }
    </style>
@endsection
