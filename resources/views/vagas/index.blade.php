@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-3">Vagas de Emprego</h2>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                @forelse($vagas as $vaga)
                    <div class="col-12 col-md-3 mb-3 d-flex">
                        <div class="card vaga-card w-100 d-flex flex-column">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <small></small>
                                        <small class="text-muted ms-auto">⏳{{ $vaga->created_at->diffForHumans() }}</small>
                                    </div>
                                    <h6 class="mt-2 card-title fw-bold">
                                        {{ preg_replace('/^\d+\s-\s/', '', $vaga->titulo) }}
                                    </h6>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-map-marker-alt"></i> {{ strtoupper($vaga->localizacao == 'Não encontrado' ? 'N/A' : $vaga->localizacao ) }}
                                    </p>
                                    <p class="card-text">{{ Str::limit($vaga->descricao, 80) }}</p>
                                </div>
                                <div class="mt-auto">
                                    <a href="{{ route('vagas.show', $vaga) }}" class="btn btn-success btn-sm">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Nenhuma vaga disponível no momento.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .vaga-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .vaga-card:hover {
        transform: translateY(-2px);
    }

    .vaga-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .origem-badge {
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .btn-add-vaga {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: #0B5ED7 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    a.btn.btn-add-vaga {
        display: flex;
        border-radius: 100%;
        margin-bottom: 3vh;
        opacity: 0.7;
    }
</style>
