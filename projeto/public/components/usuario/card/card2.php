<?php
$id_livro = $livro['id_livro'] ?? $livro['id'] ?? 0;
$is_favorito_inicial = $livro['favorito'] ?? false; 
?>
<div class="card-livro">
  
  <button class="btn-favorito <?= $is_favorito_inicial ? 'clicked' : '' ?>" data-id-livro="<?= $id_livro ?>" onclick="toggleFavorito(this, <?= $id_livro ?>)">
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
<script>
  /**
 * Alterna (adiciona/remove) um livro dos favoritos via Fetch API.
 * * @param {HTMLElement} buttonElement O elemento <button> clicado.
 * @param {number} livroId O ID do livro a ser favoritado/desfavoritado.
 */
function toggleFavorito(buttonElement, livroId) {
    // Reverte a classe visual temporariamente para feedback imediato,
      buttonElement.classList.toggle('clicked');
    
    // Obtém a ação baseada no estado visual temporário
    const isNowFavorited = buttonElement.classList.contains('clicked');
    
    fetch('/projeto/src/controller/usuario/FavoritarController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_livro: livroId,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Falha na resposta do servidor.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Se o backend confirmou a ação:
            const message = data.action === 'added' 
                ? 'Livro adicionado aos favoritos!' 
                : 'Livro removido dos favoritos.';
            mostrarToast(message);
        } else {
            // Se o backend falhou, revertemos a classe visual e mostramos o erro
            buttonElement.classList.toggle('clicked');
            mostrarToast(data.message || 'Erro desconhecido ao favoritar.', 'error');
        }
    })
    .catch(error => {
        // Se houver erro de rede, revertemos a classe visual
        buttonElement.classList.toggle('clicked');
        console.error('Erro de rede ou JSON:', error);
        mostrarToast('Erro de comunicação com o servidor.', 'error');
    });
}
</script>