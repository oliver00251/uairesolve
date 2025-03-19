@extends('layouts.app')

@section('content')
@auth
<div class="container mt-4">
   
        <div class="registro_new">
            @include('form.ocorrencia')
        </div>
    
    

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
<div class="alert  text-center d-flex flex-column justify-content-center align-items-center min-vh-100">
    <p class="mb-2">Você precisa estar logado para registrar uma ocorrência.</p>
    <a href="{{ route('login') }}" class="btn btn-primary mt-2">Fazer Login</a>
</div>


@endauth
@endsection
