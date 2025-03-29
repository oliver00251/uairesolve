@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Parceiros</h1>
        
        <!-- Exibir o botão apenas se o usuário for admin -->
        @if(auth()->check() && $isAdmin)
            <a href="{{ route('parceiros.create') }}" class="btn btn-primary">+ Novo Parceiro</a>
        @endif
    </div>
    

    <div class="row">
        @foreach($parceiros as $parceiro)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card p-3 shadow">
                    <div class="d-flex flex-row mb-3">
                        <img src="{{ $parceiro->logo ? asset('storage/' . $parceiro->logo) : asset('img/default-logo.png') }}" 
                             width="70" class="rounded-circle">
                        <div class="d-flex flex-column mx-3">
                            <span class="fw-bold">{{ $parceiro->nome }}</span>
                            <span class="text-black-50">{{ $parceiro->categoria ?? 'Parceiro' }}</span>
                            <span class="ratings">
                                @for ($i = 0; $i < rand(3, 5); $i++)
                                    <i class="fa fa-star"></i>
                                @endfor
                            </span>
                        </div>
                    </div>
                    <h6>{{ Str::limit($parceiro->descricao ?? 'Descrição breve do parceiro.', 100) }}</h6>
                    <div class="d-flex justify-content-between install mt-3">
                        <span>Visualizado {{ rand(100, 500) }} vezes</span>
                        <a href="{{ route('parceiros.show', $parceiro) }}" class="text-primary text-decoration-none">
                            Ver mais <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .ratings i {
        color: rgb(236, 232, 0);
    }
    .install span {
        font-size: 12px;
    }
    .col-lg-4 {
        margin-top: 27px;
    }
    .card {
        border: none;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
