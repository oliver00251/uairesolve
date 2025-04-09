@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-login px-2">
    <div class="card shadow card_register d-flex flex-lg-row flex-column overflow-hidden">
        <!-- Lado Esquerdo: Animação -->
        <div class="d-flex justify-content-center align-items-center bg-light p-3 animation-box">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
<dotlottie-player src="https://lottie.host/6ad93df3-c606-4634-bc9a-717abedd08de/SLtFqdNctQ.lottie" background="transparent" speed="0.7" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
        </div>

        <!-- Lado Direito: Formulário -->
        <div class="p-4 flex-fill form-box overflow-auto">
            <!-- Erros -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nome</label>
                    <input type="text" name="name" class="form-control input-custom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">E-mail</label>
                    <input type="email" name="email" class="form-control input-custom" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Senha</label>
                    <input type="password" name="password" class="form-control input-custom" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirme a Senha</label>
                    <input type="password" name="password_confirmation" class="form-control input-custom" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Criar Conta</button>
            </form>

            <div class="mt-4 text-center small">
                <p>Já tem uma conta? <a href="{{ route('login') }}" class="text-primary fw-bold">Faça login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .bg-login {
        background: linear-gradient(180deg, #0052D4, #4364F7, #6FB1FC) !important;
    }

    .card_register {
        width: 100%;
        max-width: 850px;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        animation: fadeIn 0.8s ease-in-out;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .animation-box {
        background: #f5f5f5;
        flex: 1;
        min-height: 320px;
    }

    .form-box {
        flex: 1;
        max-height: 90vh;
        overflow-y: auto;
    }

    .form-box::-webkit-scrollbar {
        display: none;
    }

    .form-box {
        scrollbar-width: none;
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

    @media (max-width: 767.98px) {
        .animation-box {
            padding: 1rem;
        }

        .form-box {
            padding: 2rem 1.5rem;
        }
    }
</style>
