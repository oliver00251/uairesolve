@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-3">Detalhes da Vaga</h2>

    <div class="row">
        <div class="container">
            <div class="card vaga-card">
                <div class="card-body">
                    <h5 class="fw-bold">{{ preg_replace('/^\d+\s-\s/', '', $vaga->titulo) }}
                    </h5>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt"></i> {{ strtoupper($vaga->localizacao ?? 'N/A') }}

                    </p>
                    <p><strong>Descrição:</strong> {{ $vaga->descricao }}</p>
                    <p><strong>Modalidade:</strong> {{ $vaga->modalidade ?? 'N/A' }}</p>
                    <p><strong>Tipo de Contrato:</strong> {{ $vaga->tipo_contrato ?? 'N/A' }}</p>
                    <p><strong>Requisitos:</strong> {{ $vaga->requisitos }}</p>
                    <p><strong>Benefícios:</strong> {{ $vaga->beneficios }}</p>
                    <p><strong>Origem:</strong> {{ $vaga->origem ?? 'N/A' }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vagas.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                        <a href="{{$vaga->link }}" class="btn text-white btn-sm"  style="background: #0D6EFD" >Candidatar</a>
                    </div>
                    
                </div>
                @if (auth()->check() && auth()->user()->tipo == 'admin')
                
                <a href="{{ route('gerar.imagem-vaga', ['id' => $vaga->id]) }}" class="btn btn-sm btn-outline-success" download>
                    Baixar imagem da postagem
                </a>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* Cartão de Detalhes de Vaga */
    .vaga-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    .vaga-card:hover {
        transform: translateY(-2px);
    }
</style>
