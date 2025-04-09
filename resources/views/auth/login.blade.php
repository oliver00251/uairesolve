@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4 card_login" >
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
        <div class="d-flex justify-content-center">
            <dotlottie-player src="https://lottie.host/f8f5fd6c-a605-4dc4-87e1-6b1262710549/YHfstIxz3Z.lottie" background="transparent" speed="0.5" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>

        </div>
        <h2 class="text-center mb-4">Login</h2>

        <!-- Exibição de erros -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Exibição de mensagens de sucesso ou erro da sessão -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <div class="mt-3 text-center">
            <p>Não tem uma conta? <a href="{{ route('register') }}">Registre-se</a></p>
        </div>

    </div>
</div>
@endsection
<style>
    .card_login{
    width: 400px;
    min-height: 100vh !important;
    display: flex !important;
    justify-content: center !important;
    }
</style>