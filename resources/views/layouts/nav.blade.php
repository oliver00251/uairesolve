<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary" href="/">UaiResolve</a>

        <!-- Botão para mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Itens do Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <!-- Outros itens do menu, caso necessário -->
            </ul>

            @auth
                <!-- Ícone de Notificações -->
                <a class="nav-link position-relative me-3 d-none" href="#">
                    <i class="fas fa-bell fs-5"></i>
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span>
                </a>

                <!-- Botão de Ocorrência com Dropdown (ao lado do Perfil) -->
               {{--  <div class="dropdown me-3">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="ocorrenciaDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-clipboard-list"></i> Ocorrência
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="ocorrenciaDropdown">
                
                        <li>
                            <a class="dropdown-item" href="{{ route('ocorrencias.index', ['filtro' => 'O']) }}"><i class="fas fa-exclamation-circle"></i> Ocorrências</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('ocorrencias.index', ['filtro' => 'S']) }}"><i class="fas fa-lightbulb"></i> Sugestões</a>
                        </li>
                        <li>
                            <a class="dropdown-item d-none" href="{{ route('ocorrencias.index', ['filtro' => 'I']) }}"><i class="fas fa-user-check"></i> Indicações</a>
                        </li>
                        <li>
                            <a class="dropdown-item d-none" href="{{ route('ocorrencias.index', ['filtro' => 'D']) }}"><i class="fas fa-bullhorn"></i> Denúncias</a>
                        </li>
                    </ul>
                    
                </div> --}}

                <!-- Perfil do Usuário -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-user"></i> Meu Perfil</a></li>
{{--                         <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Configurações</a></li>
 --}}                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </a>
            @endauth
        </div>
    </div>
</nav>
