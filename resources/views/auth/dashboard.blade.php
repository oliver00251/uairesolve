@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Minha Dashboard</h2>

    <!-- Informações do Usuário -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Meu Perfil</h5>
            <p><strong>Nome:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Registrado em:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Minhas Ocorrências -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Minhas Ocorrências</h5>
            @if ($minhasOcorrencias->isEmpty())
                <p>Você ainda não registrou nenhuma ocorrência.</p>
            @else
                <ul class="list-group">
                    @foreach ($minhasOcorrencias as $ocorrencia)
                        <li class="list-group-item">
                            <a href="{{ route('ocorrencias.show', $ocorrencia) }}">
                                {{ $ocorrencia->titulo }} - {{ $ocorrencia->created_at->format('d/m/Y H:i') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Ocorrências que Comentei -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Ocorrências que Comentei</h5>
            @if ($ocorrenciasComentadas->isEmpty())
                <p>Você ainda não comentou em nenhuma ocorrência.</p>
            @else
                <ul class="list-group">
                    @foreach ($ocorrenciasComentadas as $ocorrencia)
                        <li class="list-group-item">
                            <a href="{{ route('ocorrencias.show', $ocorrencia) }}">
                                {{ $ocorrencia->titulo }} - {{ $ocorrencia->created_at->format('d/m/Y H:i') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
