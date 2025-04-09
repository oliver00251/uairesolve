<nav class="navbar navbar-light bg-white shadow-sm fixed-top d-lg-none">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold text-primary" href="/">UaiResolve</a>

        <!-- Botão que abre o menu lateral -->
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Offcanvas lateral -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column gap-3">
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-primary w-100 rounded-pill">
            <i class="fas fa-map-marker-alt"></i> Publicações
        </a>
        <a href="{{ route('vagas.index') }}" class="btn btn-primary w-100 rounded-pill">
            <i class="fas fa-briefcase"></i> Vagas de Emprego
        </a>
        <a href="https://www.instagram.com/uairesolveoficial/" target="_blank" class="btn btn-primary w-100 rounded-pill">
            <i class="fab fa-instagram"></i> Fale Conosco
        </a>

        @auth
            <hr>
            <a href="/dashboard" class="btn btn-outline-secondary w-100">
                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </a>
        @endauth
    </div>
</div>
