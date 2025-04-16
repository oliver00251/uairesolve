@extends('layouts.app')

@section('title', 'Rede Comunitária | UAi Resolve')

@section('content')

<!-- Hero Section -->
<section class="hero-section py-5 text-center bg-light">
  <div class="container">
    <h1 class="display-5 fw-bold">🤝<br> Rede Comunitária <br>UAI RESOLVE</h1>
    <p class="lead mt-3">Porque transformar a cidade começa por quem vive nela</p>
    <p class="mt-3">Você já percebeu que quem mais conhece os problemas do bairro… é quem mora nele?</p>
    <p>Foi pensando nisso que criamos a Rede Comunitária UAi Resolve — uma estrutura colaborativa que coloca moradores, líderes locais, escolas, igrejas, associações e jovens no centro da transformação social.

    </p>
    <a href="#formulario" class="btn btn-warning btn-lg mt-4">Quero Participar</a>
  </div>
</section>

<!-- O que é a Rede Comunitária -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">🌱 O que é a Rede Comunitária?</h2>
    <p class="text-center">
      É uma rede de lideranças populares e cidadãos ativos, organizada por bairros e zonas da cidade, com o objetivo de:
    </p>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">Mapear necessidades reais do território</li>
      <li class="list-group-item">Criar uma ponte entre moradores e poder público</li>
      <li class="list-group-item">Gerar dados sociais e comportamentais</li>
      <li class="list-group-item">Apoiar projetos locais com base nos desafios da comunidade</li>
    </ul>
  </div>
</section>

<!-- Como Funciona -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">🧭 Como funciona na prática?</h2>
    <p class="text-center">
      Você se inscreve como voluntário(a) ou líder local e a gente te conecta com um grupo por bairro.
    </p>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card h-100 text-center p-4 border-0 shadow-sm">
          <div class="fs-1 mb-3">📝</div>
          <h5>Cadastra-se</h5>
          <p>Inscreva-se como voluntário ou líder comunitário da sua região.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card h-100 text-center p-4 border-0 shadow-sm">
          <div class="fs-1 mb-3">🔗</div>
          <h5>Conecta-se</h5>
          <p>Se conecta com um grupo da sua comunidade para transformar o bairro.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card h-100 text-center p-4 border-0 shadow-sm">
          <div class="fs-1 mb-3">📊</div>
          <h5>Monitora</h5>
          <p>Recebe materiais, mentorias e começa a monitorar e registrar os problemas da comunidade.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card h-100 text-center p-4 border-0 shadow-sm">
          <div class="fs-1 mb-3">🚀</div>
          <h5>Transforma</h5>
          <p>Participa ativamente da solução dos problemas locais junto à sua rede.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Quem Pode Participar -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">🫱🏽‍🫲🏿 Quem pode participar?</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">✔️ Moradores com vontade de fazer a diferença</li>
          <li class="list-group-item">✔️ Estudantes e professores que queiram envolver a escola</li>
          <li class="list-group-item">✔️ Comerciantes que acreditam na força do bairro</li>
        </ul>
      </div>
      <div class="col-md-4">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">✔️ ONGs, conselhos e grupos de base</li>
          <li class="list-group-item">✔️ Jovens que querem construir o próprio futuro</li>
        </ul>
      </div>
      <div class="col-md-4 d-flex align-items-center justify-content-center">
        <a href="#formulario" class="btn btn-warning btn-lg">Quero me engajar</a>
      </div>
    </div>
  </div>
</section>

<!-- Porque isso é importante -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">🧠 Por que isso é importante?</h2>
    <p class="text-center">
      Porque o problema não é só o buraco na rua. É o jovem que não vê perspectiva. É a mãe que não tem onde deixar o filho. 
      É a praça abandonada virando esconderijo. É o desânimo que vira desistência. E ninguém vai entender isso melhor do que quem vive a realidade todos os dias.
    </p>
  </div>
</section>

<!-- O que você ganha participando -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">📈 O que você ganha participando?</h2>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">✔️ Certificado de liderança comunitária</li>
      <li class="list-group-item">✔️ Acesso a relatórios e dados do seu bairro</li>
      <li class="list-group-item">✔️ Formação em participação social e cidadania</li>
      <li class="list-group-item">✔️ Prioridade em oportunidades e editais do UAi Resolve</li>
      <li class="list-group-item">✔️ Fazer parte da história da transformação da sua cidade</li>
    </ul>
  </div>
</section>

<!-- Formulário -->
<section class="py-5" id="formulario">
  <div class="container">
    <h2 class="text-center mb-4">📬 Quero entrar para a rede!</h2>
    <p class="text-center">Você não precisa ser político. Não precisa ter cargo. Só precisa acreditar que dá pra fazer melhor — junto.</p>
    <div class="row justify-content-center">
      <div class="col-md-8">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('rede-comunitaria.cadastrar') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" name="nome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" name="bairro" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="contato" class="form-label">E-mail ou WhatsApp</label>
            <input type="text" name="contato" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-warning w-100">Cadastrar</button>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection
