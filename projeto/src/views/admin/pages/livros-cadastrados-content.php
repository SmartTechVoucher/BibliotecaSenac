<?php
   require "../../../config/constantes.php";
   
   require_once __DIR__ . '/../../../controller/admin/ListarLivrosController.php';
   
   $controller = new ListarLivrosController();
   $dados = $controller->prepararDadosView();
   
   // Dados dos livros e paginação
   $livros = $dados['livros'];
   $busca_atual = $dados['busca_atual'];
   $paginacao = $dados['paginacao'];
   $pagina_atual = $paginacao['pagina_atual'];
   $total_paginas = $paginacao['total_paginas'];
   $total_livros = $paginacao['total_livros'];
   
   // Filtros avançados
   $filtro_tipo = $dados['filtro_tipo'] ?? '';
   $filtro_valor = $dados['filtro_valor'] ?? '';
   
   // Opções para os selects
   $areas = $dados['areas'] ?? [];
   $idiomas = $dados['idiomas'] ?? [];
   $anos = $dados['anos'] ?? [];
   $autores = $dados['autores'] ?? [];
   $categorias = $dados['categorias'] ?? [];
   $editoras = $dados['editoras'] ?? [];
   $documentos = $dados['documentos'] ?? [];
?>  

<form method="GET" action="">
    <div class="controle">
            <input type="text" 
                   name="busca" 
                   class="busca" 
                   placeholder="Pesquise por título ou ISBN do livro" 
                   value="<?php echo htmlspecialchars($busca_atual); ?>">

            <button type="submit" class="botao">
                <img src="../../../public/assets/icons/Buscar.png" alt="Buscar">
            </button>
        </div>
   <!-- FILTROS AVANÇADOS -->
        <div class="filtros-avancados">
            <label class="label-filtro">Filtrar por:</label>
            
            <select name="filtro_tipo" id="filtro-tipo" class="select-filtro" onchange="atualizarFiltroValor()">
                <option value="">Todos os livros</option>
                <option value="area" <?php echo $filtro_tipo === 'area' ? 'selected' : ''; ?>>Área</option>
                <option value="idioma" <?php echo $filtro_tipo === 'idioma' ? 'selected' : ''; ?>>Idioma</option>
                <option value="ano" <?php echo $filtro_tipo === 'ano' ? 'selected' : ''; ?>>Ano de Publicação</option>
                <option value="autor" <?php echo $filtro_tipo === 'autor' ? 'selected' : ''; ?>>Autor</option>
                <option value="categoria" <?php echo $filtro_tipo === 'categoria' ? 'selected' : ''; ?>>Categoria</option>
                <option value="editora" <?php echo $filtro_tipo === 'editora' ? 'selected' : ''; ?>>Editora</option>
                <option value="documento" <?php echo $filtro_tipo === 'documento' ? 'selected' : ''; ?>>Tipo de Documento</option>
            </select>

            <select name="filtro_valor" id="filtro-valor" class="select-filtro">
                <option value="">Selecione...</option>
            </select>

            <?php if (!empty($filtro_tipo) && !empty($filtro_valor)): ?>
                <a href="?busca=<?php echo urlencode($busca_atual); ?>" class="btn-limpar-filtro" title="Limpar filtro">
                    ✖ Limpar
                </a>
            <?php endif; ?>
        </div>
</form>

<p id="livros-por-aparecer">Mostrando <?php echo count($livros); ?> de <?php echo number_format($total_livros); ?> livros encontrados</p>

<?php if (empty($livros)): ?>
    <p>Nenhum livro encontrado. Tente ajustar os filtros de busca.</p>
<?php else: ?>
    <?php foreach ($livros as $livro): ?>
        <div class="livro-container">
            <?php
            // Usa imagem do livro se disponível, senão usa default
            $caminho_imagem = '';
            if (!empty($livro['foto'])) {
                $caminho_imagem = $URLBASE . '/public/' . $livro['foto'];
            } else {
                $caminho_imagem = $URLBASE . '/public/uploads/default-capa.jpg';
            }
            ?>
            <img id="livro-img" src="<?php echo $caminho_imagem; ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>"
                title="Caminho: <?php echo $caminho_imagem; ?> | Foto no banco: <?php echo htmlspecialchars($livro['foto']); ?>"
                onerror="this.onerror=null; this.src='<?php echo $URLBASE; ?>/public/uploads/default-capa.jpg'; console.log('Imagem não encontrada, usando fallback para: <?php echo addslashes($livro['titulo']); ?> - Caminho original: <?php echo addslashes($caminho_imagem); ?>');"
                style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;"
                loading="lazy">
            <div class="livro-informacoes">
                    <span id="livro-titulo"><?php echo htmlspecialchars($livro['titulo']); ?></span>
                    <span class="livro-isbn">ISBN: <?php echo htmlspecialchars($livro['isbn']); ?></span>
                    
                    <div id="livro-exemplares">
                        <span>Total: <?php echo $livro['total_exemplares']; ?></span>
                        <span>Disponíveis: <b><?php echo $livro['disponiveis']; ?></b></span>
                        <span>Emprestados: <b><?php echo $livro['emprestados']; ?></b></span>
                        <span>Reservas: <b><?php echo $livro['reservas']; ?></b></span>
                    </div>
                    
                    <div class="livro-acoes">
                        <button class="btn-acao btn-editar-estoque" 
                                onclick="abrirModalEstoque(
                                    <?php echo $livro['id_livro']; ?>, 
                                    '<?php echo addslashes($livro['titulo']); ?>', 
                                    <?php echo $livro['total_exemplares']; ?>, 
                                    <?php echo $livro['disponiveis']; ?>, 
                                    <?php echo $livro['emprestados']; ?>, 
                                    <?php echo $livro['reservas']; ?>
                                )">
                            ✏️ Editar Estoque
                        </button>
                        
                        <button class="btn-acao btn-editar-livro" 
                                onclick="abrirModalEditarLivro(<?php echo $livro['id_livro']; ?>)">
                            📝 Editar Livro
                        </button>
                        
                        <button class="btn-acao btn-deletar" 
                                onclick="confirmarDeletar(<?php echo $livro['id_livro']; ?>, '<?php echo addslashes($livro['titulo']); ?>')">
                            🗑️ Deletar
                        </button>
                    </div>
                </div>
            <!-- <img id="livro-pontos" src="<?php echo $URLBASE ?>/public/assets/icons/pontinhos.png" alt=""> -->
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($total_paginas > 1): ?>
    <div class="paginacao" style="text-align: center; margin: 20px 0; padding: 10px; border-top: 1px solid #eee; margin-top: 30px;">
        <?php
        // Mantém parâmetros de busca e unidade nos links
        $params = $_GET;
        unset($params['pagina']); // Remove pagina atual para base
        $base_url = '?' . http_build_query($params);
        ?>

        <!-- Primeira página -->
        <a href="<?php echo $base_url . '&pagina=1'; ?>"
            style="margin: 0 5px; padding: 8px 12px; text-decoration: none; border: 1px solid #ccc; border-radius: 4px; color: #333; font-size: 14px;">
            « Primeira
        </a>

        <!-- Anterior -->
        <?php if ($pagina_atual > 1): ?>
            <a href="<?php echo $base_url . '&pagina=' . ($pagina_atual - 1); ?>"
                style="margin: 0 5px; padding: 8px 12px; text-decoration: none; border: 1px solid #ccc; border-radius: 4px; color: #333; font-size: 14px;">
                ‹ Anterior
            </a>
        <?php endif; ?>

        <!-- Números de página -->
        <?php
        $inicio = max(1, $pagina_atual - 2);
        $fim = min($total_paginas, $pagina_atual + 2);
        for ($i = $inicio; $i <= $fim; $i++):
        ?>
            <a href="<?php echo $base_url . '&pagina=' . $i; ?>"
                style="margin: 0 2px; padding: 8px 12px; text-decoration: none;
                          <?php echo ($i == $pagina_atual) ? 'background: #007bff; color: white; border-radius: 4px; font-weight: bold;' : 'border: 1px solid #ccc; border-radius: 4px; color: #333;'; ?>
                    font-size: 14px">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Próxima -->
        <?php if ($pagina_atual < $total_paginas): ?>
            <a href="<?php echo $base_url . '&pagina=' . ($pagina_atual + 1); ?>"
                style="margin: 0 5px; padding: 8px 12px; text-decoration: none; border: 1px solid #ccc; border-radius: 4px; color: #333; font-size: 14px;">
                Próxima ›
            </a>
        <?php endif; ?>

        <!-- Última página -->
        <a href="<?php echo $base_url . '&pagina=' . $total_paginas; ?>"
            style="margin: 0 5px; padding: 8px 12px; text-decoration: none; border: 1px solid #ccc; border-radius: 4px; color: #333; font-size: 14px;">
            Última »
        </a>

        <!-- Info de paginação -->
        <span style="margin-left: 20px; font-size: 14px; color: #666; font-weight: normal; vertical-align: middle;">
            Página <?php echo $pagina_atual; ?> de <?php echo $total_paginas; ?> -
            Mostrando <?php echo ($pagina_atual - 1) * 10 + 1; ?> a <?php echo min($pagina_atual * 10, $total_livros); ?> de <strong><?php echo number_format($total_livros); ?></strong> livros
            <?php if (!empty($busca_atual)): ?>
                (Busca: "<?php echo htmlspecialchars($busca_atual); ?>")
            <?php endif; ?>
            <?php if (!empty($unidade_selecionada)): ?>
                (Unidade: <?php echo htmlspecialchars($unidades[array_search($unidade_selecionada, array_column($unidades, 'id'))]['nome'] ?? ''); ?>)
            <?php endif; ?>
        </span>
    </div>
<?php endif; ?>
</div>

<!-- Modal para editar estoque -->
<div id="modal-estoque" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Estoque do Livro</h3>
            <span class="close-modal" onclick="fecharModalEstoque()">&times;</span>
        </div>
        <form id="form-estoque" method="POST" action="../../../router.php?acao=atualizarEstoque">
            <input type="hidden" id="estoque-id-livro" name="id_livro">

            <div class="form-group">
                <label for="estoque-titulo">Título do Livro:</label>
                <input type="text" id="estoque-titulo" readonly>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="estoque-total">Total de Exemplares:</label>
                    <input type="number" id="estoque-total" name="total_exemplares" min="1" required>
                </div>
                <div class="form-group">
                    <label for="estoque-disponiveis">Disponíveis:</label>
                    <input type="number" id="estoque-disponiveis" name="disponiveis" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="estoque-emprestados">Emprestados:</label>
                    <input type="number" id="estoque-emprestados" name="emprestados" min="0" required>
                </div>
                <div class="form-group">
                    <label for="estoque-reservas">Reservas:</label>
                    <input type="number" id="estoque-reservas" name="reservas" min="0" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="fecharModalEstoque()">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

    <div id="modal-editar-livro" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Dados do Livro</h3>
            <span class="close-modal" onclick="fecharModalEditarLivro()">&times;</span>
        </div>
        <form id="form-editar-livro" method="POST" action="../../../router.php?acao=editarLivro" enctype="multipart/form-data">
            <input type="hidden" id="edit-id-livro" name="id_livro">
            <input type="hidden" name="ajax" value="1">

            <div class="form-group">
                <label for="edit-titulo">Título:</label>
                <input type="text" id="edit-titulo" name="titulo-livro" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-autor">Autor:</label>
                    <select id="edit-autor" name="autor" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-isbn">ISBN:</label>
                    <input type="text" id="edit-isbn" name="isbn-livro" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-categoria">Categoria:</label>
                    <select id="edit-categoria" name="categoria" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-paginas">Páginas:</label>
                    <input type="number" id="edit-paginas" name="numero-paginas" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-editora">Editora:</label>
                    <select id="edit-editora" name="editora" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-idioma">Idioma:</label>
                    <select id="edit-idioma" name="idioma" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-area">Área:</label>
                    <select id="edit-area" name="area" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-documento">Tipo:</label>
                    <select id="edit-documento" name="tipo-documento" required>
                        <option value="">Carregando...</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-publicacao">Data de Publicação:</label>
                <input type="date" id="edit-publicacao" name="publicacao-livro">
            </div>

            <div class="form-group">
                <label for="edit-resumo">Descrição:</label>
                <textarea id="edit-resumo" name="resumo-livro"></textarea>
            </div>

            <div class="form-group">
                <label for="edit-notas">Notas:</label>
                <textarea id="edit-notas" name="notas-livro"></textarea>
            </div>

            <div class="form-group">
                <label for="edit-capa">Nova Capa (opcional):</label>
                <input type="file" id="edit-capa" name="capa-livro" accept="image/*">
                <small style="color: #6c757d;">Deixe em branco para manter a capa atual</small>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="fecharModalEditarLivro()">Cancelar</button>
                <button type="submit" class="btn-salvar">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<script>
    const opcoesFiltro = <?php echo json_encode([
        'area' => $areas,
        'idioma' => $idiomas,
        'ano' => $anos,
        'autor' => $autores,
        'categoria' => $categorias,
        'editora' => $editoras,
        'documento' => $documentos
    ], JSON_UNESCAPED_UNICODE); ?>;

    let currentLivroId = null;

    function abrirModalEstoque(id, titulo, total, disponiveis, emprestados, reservas) {
        document.getElementById('estoque-id-livro').value = id;
        document.getElementById('estoque-titulo').value = titulo;
        document.getElementById('estoque-total').value = total;
        document.getElementById('estoque-disponiveis').value = disponiveis;
        document.getElementById('estoque-emprestados').value = emprestados;
        document.getElementById('estoque-reservas').value = reservas;

        document.getElementById('modal-estoque').style.display = 'flex';
        currentLivroId = id;
    }

    function fecharModalEstoque() {
        document.getElementById('modal-estoque').style.display = 'none';
        currentLivroId = null;
    }

    // Fechar modal ao clicar fora
    document.getElementById('modal-estoque').addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModalEstoque();
        }
    });

    // Submit do formulário via AJAX
    document.getElementById('form-estoque').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        console.log('Enviando dados do formulário:', Object.fromEntries(formData));

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            console.log('Status da resposta:', response.status);
            const result = await response.json();
            console.log('Resultado da resposta:', result);

            if (result.sucesso) {
                alert(result.mensagem || 'Estoque atualizado com sucesso!');
                fecharModalEstoque();
                // Recarregar a página para mostrar os novos valores
                window.location.reload();
            } else {
                alert('Erro: ' + (result.mensagem || 'Erro ao atualizar estoque.'));
            }
        } catch (error) {
            console.error('Erro completo:', error);
            alert('Erro de conexão: ' + error.message);
        }
    });
</script>

<script src="<?php echo $URLBASE ?>/public/js/admin/telaDosLivrosCadastrados.js"></script>