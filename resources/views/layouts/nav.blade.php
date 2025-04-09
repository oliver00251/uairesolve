{{-- NAVBAR GERAL --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary" href="/">UaiResolve</a>

        <!-- Botão hamburguer (só aparece no mobile) -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu desktop -->
        <div class="collapse navbar-collapse justify-content-end d-none d-lg-flex" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ocorrencias.index') }}">
                        <i class="fas fa-map-marker-alt text-primary"></i> Publicações
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vagas.index') }}">
                        <i class="fas fa-briefcase text-primary"></i> Vagas de Emprego
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.instagram.com/uairesolveoficial/" target="_blank">
                        <i class="fab fa-instagram text-primary"></i> Fale Conosco
                    </a>
                </li>

                @auth
                <li class="nav-item dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-user"></i> Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- MENU MOBILE: Offcanvas lateral (só aparece no mobile) --}}
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column gap-3">
        <a href="{{ route('ocorrencias.index') }}" class="btn btn-primary w-100 rounded-pill">
            <i class="fas fa-map-marker-alt"></i> Publicações
        </a>
        <a href="{{ route('vagas.index') }}" class="btn btn-primary w-100 rounded-pill">
            <i class="fas fa-briefcase"></i> Vagas de Emprego
        </a>
        <a href="https://www.instagram.com/uairesolveoficial/" class="btn btn-primary w-100 rounded-pill" target="_blank">
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
