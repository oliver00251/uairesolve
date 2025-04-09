@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center vh-100 text-center" 
     style="background: linear-gradient(180deg, #0052D4, #4364F7, #6FB1FC); color: #fff; margin-top:-10vh;">
    <div>
        <div class="d-flex justify-content-center">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/dac7d0c1-7521-4fd8-b978-885aa8d72c84/r2CGvLlNpc.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
        </div>
        <!-- Título -->
        <h1 class="display-4 fw-bold mb-3">Ajude a melhorar nossa cidade</h1>
        <p class="lead mb-4">Registre problemas urbanos e acompanhe a solução em tempo real.</p>
        
        <!-- Botão de ação para Ocorrências -->
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary">
            <i class="fas fa-map-marker-alt"></i> Visualizar Publicações
        </a>

        <!-- Botão de ação para Vagas de Emprego -->
        <a href="{{ route('vagas.index') }}" class="btn btn-light mt-3 btn-lg px-4 rounded-pill text-primary">
            <i class="fas fa-briefcase"></i> Vagas de Emprego
        </a>
    </div>
</div>
@endsection
