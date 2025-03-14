@extends('layouts.app')

@section('content')
@auth
<div class="container mt-4">
    @if($registro == 'O')
        <div class="registro_new">
            @include('form.ocorrencia')
        </div>
    @endif
    @if($registro == 'S')
        <div class="registro_new">
            @include('form.sugestao')
        </div>
    @endif

    <style>
        /* Estilos gerais para a classe .registro_new */
        .registro_new {
            padding: 3rem; /* Definido para desktop */
        }

        /* Estilos para telas menores que 768px (Mobile) */
        @media (max-width: 768px) {
            .registro_new {
                padding: 1rem; /* Ajuste para mobile */
            }

            .alert {
                padding-top: 12rem !important;
            }
        }
    </style>
</div>
@else
<div class="alert alert-warning text-center mt-4 min-vh-100">
    Você precisa estar logado para registrar uma ocorrência. <br>
    <a href="{{ route('login') }}" class="btn btn-primary mt-2">Fazer Login</a>
</div>
@endauth
@endsection
