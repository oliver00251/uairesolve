@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 div_pr bg-login">
    <div class="card shadow p-4 card_login">
        <!-- Animação -->
        <div class="d-flex justify-content-center mb-3">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player 
                src="https://lottie.host/f8f5fd6c-a605-4dc4-87e1-6b1262710549/YHfstIxz3Z.lottie" 
                background="transparent" 
                speed="0.5" 
                style="width: 220px; height: 220px" 
                 autoplay>
            </dotlottie-player>
        </div>

        <!-- Exibição de erros -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mensagens de sessão -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Formulário -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">E-mail</label>
                <input type="email" name="email" placeholder="Digite seu e-mail" class="form-control input-custom" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Senha</label>
                <input type="password" name="password" placeholder="Digite sua senha" class="form-control input-custom" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="mt-4 text-center small">
            <p>Não tem uma conta? <a href="{{ route('register') }}" class="text-primary fw-bold">Cadastre-se</a></p>
        </div>
    </div>
</div>
@endsection

<style>
    .bg-login {
        background: linear-gradient(180deg, #0052D4, #4364F7, #6FB1FC) !important;
    }

    .card_login {
        width: 100%;
        max-width: 400px;
        border: none;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        animation: fadeIn 0.8s ease-in-out;
    }
   
    .input-custom {
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: 0.2s ease;
    }

    .input-custom:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 480px) {
        .card_login {
            padding: 2rem 1.5rem;
        }

        .bg-login {
            padding: 1rem;
        }
        .div_pr{
        margin-top: -70px;
    }

    }
</style>
