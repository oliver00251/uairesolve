@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5" style=" margin-top: 6rem !important">

    

    <div class="row" style=" margin-top: 6rem !important">
        
        <div class="col-md-4 mb-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-primary">Meu Perfil</h5>
                    <p><strong>Nome:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Registrado em:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
<!-- Exibindo as métricas para administradores -->
@if($isAdmin)
<div class="card shadow-lg border-0 rounded-3 col-md-12 mb-4 ">
    <div class="card-body">
        <h5 class="card-title text-primary">Métricas de Ocorrências</h5>
        <div class="row">
            <div class="col-md-4">
                <h6>Total de Ocorrências</h6>
                <p>{{ $totalOcorrencias }}</p>
            </div>
            <div class="col-md-4">
                <h6>Ocorrências Abertas</h6>
                <p>{{ $ocorrenciasAbertas }}</p>
            </div>
            <div class="col-md-4">
                <h6>Ocorrências Resolvidas</h6>
                <p>{{ $ocorrenciasResolvidas }}</p>
            </div>
        </div>
    </div>
</div>
@endif
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">Ocorrências</h5>

                    <!-- Para o admin, mostrar todas as ocorrências -->
                    @if ($isAdmin)
                    <div class="table-responsive">
                        <table id="ocorrenciasTable" class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Data de Criação</th>
                                    <th>Publicada por</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ocorrencias as $ocorrencia)
                                <tr>
                                    <td>{{ $ocorrencia->titulo }}</td>
                                    <td>{{ $ocorrencia->status }}</td>
                                    <td>{{ $ocorrencia->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $ocorrencia->user ? $ocorrencia->user->name : 'Usuário desconhecido' }}</td>
                                    <td><a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-primary btn-sm">Ver Detalhes</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Para usuários comuns, mostrar apenas o título e a data -->
                    @if ($minhasOcorrencias->isEmpty() && $ocorrenciasComentadas->isEmpty())
                    <p>Não há ocorrências registradas no sistema.</p>
                    @else
                    <div class="table-responsive">
                        <table id="ocorrenciasTable" class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($minhasOcorrencias as $ocorrencia)
                                <tr>
                                    <td>{{ $ocorrencia->titulo }}</td>
                                    <td>{{ $ocorrencia->created_at->format('d/m/Y') }}</td> <!-- Data sem hora -->
                                    <td><a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-primary btn-sm">Ver Detalhes</a></td>
                                </tr>
                                @endforeach
                                @foreach ($ocorrenciasComentadas as $ocorrencia)
                                <tr>
                                    <td>{{ $ocorrencia->titulo }}</td>
                                    <td>{{ $ocorrencia->created_at->format('d/m/Y') }}</td> <!-- Data sem hora -->
                                    <td><a href="{{ route('ocorrencias.show', $ocorrencia) }}" class="btn btn-primary btn-sm">Ver Detalhes</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
