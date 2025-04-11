<!-- resources/views/vagas/imagem.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        background: #0D6EFD;
    }
    .post {
        width: 1080px;
        height: 1080px;
        background: linear-gradient(180deg, #0052D4, #4364F7, #6FB1FC);
        border-radius: 0px;
        padding: 60px;
        position: relative;
    }
    .badge {
        position: absolute;
        top: 40px;
        left: 60px;
        background: #fff;
        color: #0D6EFD;
        padding: 14px 30px;
        font-size: 22px;
        font-weight: bold;
        border-radius: 100px;
    }
    .title {
        font-size: 72px;
        color: #fff;
        font-weight: 900;
        margin-top: 5rem;
        margin-bottom: 30px;
    }
    .subtitle {
        font-size: 36px;
        color: #fff;
        font-weight: 600;
        margin-bottom: 40px;
    }
    .info-box {
        background: #fff;
        padding: 40px;
        border-radius: 30px;
        max-width: 80vw;
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .info-box p {
        font-size: 30px;
        color: #333;
        margin-bottom: 20px;
        text-transform: uppercase;
    }
    .cta {
        font-size: 24px;
        color: #808080;
        font-weight: bold;
        margin-top: 20px;
    }
    .footer {
        position: absolute;
        bottom: 40px;
        left: 60px;
        font-size: 22px;
        color: #fff;
    }
    .logo {
        position: absolute;
        bottom: 40px;
        right: 60px;
        font-size: 40px;
        color: #fff;
    }
  </style>
</head>
<body>
  <div class="post">
    <div class="badge">Oportunidade de Emprego</div>

    <div class="title">VAGA DISPONÍVEL</div>
    <div class="subtitle">{{ $vaga->titulo }}</div>

    <div class="info-box">
      <div>
        <p><strong>📍 Local:</strong> {{ $vaga->localizacao }}</p>
        <p><strong>🕒 Periodo:</strong> {{ $vaga->periodo }}</p>
        <p><strong>💼 Tipo:</strong> {{ $vaga->tipo_contrato }}</p>
        <p><strong>✅ Requisitos:</strong> {{ $vaga->requisitos }}</p>
      </div>
      <div class="cta">
        Quer saber mais sobre a vaga <strong>{{ $vaga->titulo }}</strong>?  
        Acesse <strong>uairesolve.com.br</strong> e candidate-se!
      </div>
    </div>

    <div class="footer">uairesolve.com.br</div>
    <div class="logo">💼</div>
  </div>
</body>
</html>
