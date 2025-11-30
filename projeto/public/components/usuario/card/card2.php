<div class="card-livro">
  <button class="btn-favorito" onclick="this.classList.toggle('clicked'); mostrarToast('Livro adicionado aos favoritos!')">
    <i class="fa-regular fa-heart icone-heart oco"></i>
    <i class="fa-solid fa-heart icone-heart cheio"></i>
  </button>

  <div class="capa-wrapper">
    <?php if (!empty($livro['imagem']) || !empty($livro['foto'])): ?>
        <img src="<?= htmlspecialchars($livro['imagem'] ?? $livro['foto']) ?>" 
            class="capa-livro" 
            alt="Capa do Livro">
    <?php else: ?>
        <div class="sem-capa" style="width: 100%; height: 300px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
            Sem capa
        </div>
    <?php endif; ?>
    <div class="card-overlay">
      <p class="descricao-livro"><?= htmlspecialchars($livro['descricao'] ?? $livro['resumo_livro'] ?? 'Sem descrição disponível') ?></p>
    </div>
  </div>

  <div class="conteudo-card">
    <h2 class="titulo-livro"><?= htmlspecialchars($livro['titulo']) ?></h2>
    <p class="autor-livro">Autor: <?= htmlspecialchars($livro['autor']) ?></p>

    <?php 
    $status = strtolower($livro['status'] ?? 'indisponível');
    $id_livro = $livro['id_livro'] ?? $livro['id'] ?? 0;
    ?>

    <?php if ($status === 'disponível'): ?>
      <p class="status-livro disponivel">Disponível</p>
    <?php else: ?>
      <p class="status-livro indisponivel">Indisponível</p>
    <?php endif; ?>

    <!-- BOTÃO SEMPRE "VER MAIS" -->
    <button class="btn-reservar" onclick="window.location.href='<?= $URLBASE ?>/src/views/usuario/livro-info.php?id=<?= $id_livro ?>'">
      Ver mais
    </button>
  </div>
</div>