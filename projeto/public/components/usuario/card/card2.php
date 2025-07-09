<?php
  require(__DIR__ . '../../../../../config/constantes.php');
?>

<div class="card-livro">
  <button class="btn-favorito" onclick="this.classList.toggle('clicked')">
    <i class="fa-regular fa-heart icone-heart oco"></i>
    <i class="fa-solid fa-heart icone-heart cheio"></i>
  </button>

  <div class="capa-wrapper">
    <img src="<?= htmlspecialchars($livro['imagem']) ?>" class="capa-livro" alt="Capa do Livro">
    <div class="overlay">
      <p class="descricao-livro"><?= htmlspecialchars($livro['descricao']) ?></p>
    </div>
  </div>

  <div class="conteudo-card">
    <h2 class="titulo-livro"><?= htmlspecialchars($livro['titulo']) ?></h2>
    <p class="autor-livro">Autor: <?= htmlspecialchars($livro['autor']) ?></p>

    <?php
      $link = htmlspecialchars($URLBASE . "/src/views/usuario/livro-info.php?id=" . $livro['id']);
      $disponivel = strtolower($livro['status']) === 'disponível';
    ?>

    <?php if ($disponivel): ?>
      <p class="status-livro disponivel">Disponível</p>
      <button class="btn-reservar" onclick="window.location.href='<?= $link ?>'">Reservar</button>
    <?php else: ?>
      <p class="status-livro indisponivel">Indisponível</p>
      <button class="btn-reservar" disabled onclick="window.location.href='<?= $link ?>'">Reservar</button>
    <?php endif; ?>
  </div>
</div>
