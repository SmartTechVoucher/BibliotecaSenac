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
    <a href="<?php echo $URLBASE ?>/src/views/usuario/livro-info.php">
      <div class="overlay">
        <p class="descricao-livro"><?= htmlspecialchars($livro['descricao']) ?></p>
      </div>
    </a>
  </div>

  <div class="conteudo-card">
    <h2 class="titulo-livro"><?= htmlspecialchars($livro['titulo']) ?></h2>
    <p class="autor-livro">Autor: <?= htmlspecialchars($livro['autor']) ?></p>

    <?php if (strtolower($livro['status']) === 'disponível'): ?>
      <p class="status-livro disponivel">Disponível</p>
      <button class="btn-reservar">Reservar</button>
    <?php else: ?>
      <p class="status-livro indisponivel">Indisponível</p>
      <button class="btn-reservar" disabled >Reservar</button>
    <?php endif; ?>
  </div>

</div>


<!-- 
<div class="card-livro">

  <button class="btn-favorito" onclick="this.classList.toggle('clicked')">
    <i class="fa-regular fa-heart icone-heart oco"></i>
    <i class="fa-solid fa-heart icone-heart cheio"></i>
  </button>

  <div class="capa-wrapper">
    <img src="https://covers.odilo.io/publicms/Recep__o_e_conex_o__a_arte_de_cuidar_e_encantar_com_Ang_lica_Furtado/cachola_cast_8_318x451.jpg" class="capa-livro" alt="Capa do Livro">
    <div class="overlay">
      <p class="descricao-livro">
        O início da saga mágica do jovem bruxo Harry Potter.</p>
    </div>
  </div>
  <div class="conteudo-card">
    <h2 class="titulo-livro">Harry Potter e a Pedra Filosofal</h2>
    <p class="autor-livro">Autor: J.K. Rowling</p>
    <p class="status-livro indisponivel">
      Indisponível    </p>

    <button class="btn-reservar">Reservar</button>
  </div>
</div>


</html> -->