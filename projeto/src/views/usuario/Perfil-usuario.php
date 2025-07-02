<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../../../public/css/usuario/perfil-usuario.css">
  <link rel="stylesheet" href="../../../public/css/components/modal.css">
</head>

<body>

  <?php
  include "../../../public/components/usuario/header/header.php";
  ?>

  <!-- Conteúdo central -->
  <main class="content-wrapper">
    <div class="box">
      <div class="status">Situação: <span class="regular">Regular</span></div>

      <div class="grid">
        <div class="card" data-card="notificacoes">
          <span class="icon"><img src="../../../public/assets/icons/notificacao.png" alt=""></span>
          <span class="title">Notificações</span>
          <div class="hover-content">
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Vovô virou semente" />
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Solo" />
          </div>
        </div>

        <div class="card" data-card="favoritos">
          <span class="icon">⭐</span>
          <span class="title">Favoritos</span>
          <div class="hover-content">
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Vovô virou semente" />
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Solo" />
          </div>
        </div>

        <div class="card" data-card="recomendacoes">
          <span class="icon">📚</span>
          <span class="title">Recomendações</span>
          <div class="hover-content">
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Vovô virou semente" />
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Solo" />
          </div>
        </div>

        <div class="card" data-card="emprestimos">
          <span class="icon">📖</span>
          <span class="title">Empréstimos</span>
          <div class="hover-content">
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Vovô virou semente" />
            <img src="../../../public/assets/img/Rectangle 4167.png" alt="Solo" />
          </div>
        </div>


      </div>
    </div>
  </main>

  <?php
  include "../../../public/components/usuario/footer/footer.php";
  ?>
</body>

</html>