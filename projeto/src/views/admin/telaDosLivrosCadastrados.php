<?php
   require "../../../config/constantes.php";
   
   require_once __DIR__ . '/../../../src/controller/admin/ListarLivrosController.php';
   
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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros cadastrados</title>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --azulCabecalho: #001E3E;
            --branco: white;
            --fundoPadrao: #EDEDED;
            --verde: #28a745;
            --azul: #007bff;
            --amarelo: #ffc107;
            --vermelho: #dc3545;
            --cinza: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: var(--fundoPadrao);
            font-family: 'Poppins', sans-serif;
        }

        .container-main {
            box-shadow: 0px 7px 8px rgba(0, 0, 0, 0.1);
            background: #fbfaff;
            padding: 20px;
            width: 90%;
            border-radius: 8px;
            margin: 20px auto 50px;
        }

        #livro-titulomaster {
            text-align: center;
            color: var(--azulCabecalho);
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-busca {
            margin-bottom: 20px;
        }

        .controle {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
        }

        .busca {
            background-color: #ffffff;
            width: 100%;
            border-radius: 23px;
            height: 40px;
            border: 1px solid #ababab;
            padding: 5px 15px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        .busca:focus {
            outline: none;
            border-color: var(--azul);
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        .botao {
            border: none;
            background: var(--azul);
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .botao:hover {
            background: #0056b3;
        }

        .botao img {
            width: 20px;
            height: 20px;
            filter: brightness(0) invert(1);
        }

        .filtros-avancados {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .label-filtro {
            font-weight: 600;
            color: var(--azulCabecalho);
            font-size: 14px;
        }

        .select-filtro {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            min-width: 150px;
            transition: border-color 0.3s;
        }

        .select-filtro:focus {
            outline: none;
            border-color: var(--azul);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .select-filtro:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-limpar-filtro {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.3s;
        }

        .btn-limpar-filtro:hover {
            background: #c82333;
        }

        #livros-por-aparecer {
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            color: #495057;
        }

        .filtro-ativo {
            color: var(--azul);
            font-weight: 600;
            font-size: 14px;
        }

        .sem-resultados {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .sem-resultados p {
            margin: 10px 0;
        }

        .sem-resultados a {
            color: var(--azul);
            text-decoration: none;
            font-weight: 600;
        }

        .livro-container {
            background-color: white;
            width: 100%;
            display: flex;
            padding: 20px;
            align-items: center;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .livro-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        #livro-img {
            width: 120px;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        .livro-informacoes {
            margin-left: 25px;
            display: flex;
            flex-direction: column;
            flex: 1;
            gap: 10px;
        }

        #livro-titulo {
            font-size: 20px;
            font-weight: 600;
            color: var(--azulCabecalho);
            margin: 0;
        }

        .livro-isbn {
            color: #6c757d;
            font-size: 14px;
        }

        #livro-exemplares {
            background-color: #f8f9fa;
            padding: 12px;
            display: flex;
            justify-content: space-around;
            border-radius: 5px;
            gap: 15px;
            flex-wrap: wrap;
            border: 1px solid #dee2e6;
        }

        #livro-exemplares span {
            font-size: 14px;
            color: #495057;
        }

        #livro-exemplares b {
            color: var(--azulCabecalho);
        }

        .livro-acoes {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-acao {
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-editar-estoque {
            background: var(--verde);
        }

        .btn-editar-estoque:hover {
            background: #218838;
        }

        .btn-editar-livro {
            background: var(--amarelo);
            color: #fbfbfbff;
        }

        .btn-editar-livro:hover {
            background: #e0a800;
        }

        .btn-deletar {
            background: var(--vermelho);
        }

        .btn-deletar:hover {
            background: #c82333;
        }

        .paginacao {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }

        .btn-pagina {
            padding: 8px 14px;
            text-decoration: none;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            color: #495057;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-pagina:hover {
            background: #f8f9fa;
            border-color: var(--azul);
            color: var(--azul);
        }

        .btn-pagina.ativo {
            background: var(--azul);
            color: white;
            border-color: var(--azul);
            font-weight: 600;
        }

        .info-paginacao {
            margin-left: 15px;
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--azulCabecalho);
            font-size: 20px;
        }

        .close-modal {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #000;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--azulCabecalho);
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--azul);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .form-group input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }

        .btn-cancelar {
            background: var(--cinza);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-cancelar:hover {
            background: #5a6268;
        }

        .btn-salvar {
            background: var(--azul);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-salvar:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
            .container-main {
                width: 95%;
                padding: 15px;
            }

            .livro-container {
                flex-direction: column;
                text-align: center;
            }

            #livro-img {
                margin-bottom: 15px;
            }

            .livro-informacoes {
                margin-left: 0;
            }

            #livro-exemplares {
                flex-direction: column;
                gap: 8px;
            }

            .filtros-avancados {
                flex-direction: column;
                align-items: stretch;
            }

            .select-filtro {
                width: 100%;
            }

            .form-row {
                flex-direction: column;
            }

            .livro-acoes {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    
 <!--Cabeçalho-->
 <?php include "../../../public/components/admin/header/header-admin.php"; ?>
   
<div class="container-main">
    <h2 id="livro-titulomaster">Listagem de livros cadastrados</h2>
    
    <!-- FORMULÁRIO -->
    <form method="GET" action="" class="form-busca">
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

    <p id="livros-por-aparecer">
        Mostrando <?php echo count($livros); ?> de <?php echo number_format($total_livros); ?> livros encontrados
        <?php if (!empty($busca_atual)): ?>
            <span class="filtro-ativo">(Busca: "<?php echo htmlspecialchars($busca_atual); ?>")</span>
        <?php endif; ?>
        <?php if (!empty($filtro_tipo) && !empty($filtro_valor)): ?>
            <span class="filtro-ativo">(Filtro aplicado)</span>
        <?php endif; ?>
    </p>
    
    <?php if (empty($livros)): ?>
        <div class="sem-resultados">
            <p>📚 Nenhum livro encontrado.</p>
            <p>Tente ajustar os filtros de busca ou <a href="?">ver todos os livros</a>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($livros as $livro): ?>
            <div class="livro-container">
                <?php
                $caminho_imagem = !empty($livro['foto']) 
                    ? $URLBASE . '/public/' . $livro['foto']
                    : $URLBASE . '/public/uploads/default-capa.jpg';
                ?>
                <img id="livro-img" 
                     src="<?php echo $caminho_imagem; ?>" 
                     alt="<?php echo htmlspecialchars($livro['titulo']); ?>"
                     onerror="this.onerror=null; this.src='<?php echo $URLBASE; ?>/public/uploads/default-capa.jpg';"
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
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- PAGINAÇÃO -->
    <?php if ($total_paginas > 1): ?>
        <div class="paginacao">
            <?php
            $params = $_GET;
            unset($params['pagina']);
            $base_url = '?' . http_build_query($params);
            ?>
            
            <a href="<?php echo $base_url . '&pagina=1'; ?>" class="btn-pagina">« Primeira</a>
            
            <?php if ($pagina_atual > 1): ?>
                <a href="<?php echo $base_url . '&pagina=' . ($pagina_atual - 1); ?>" class="btn-pagina">‹ Anterior</a>
            <?php endif; ?>
            
            <?php
            $inicio = max(1, $pagina_atual - 2);
            $fim = min($total_paginas, $pagina_atual + 2);
            for ($i = $inicio; $i <= $fim; $i++):
            ?>
                <a href="<?php echo $base_url . '&pagina=' . $i; ?>" 
                   class="btn-pagina <?php echo ($i == $pagina_atual) ? 'ativo' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="<?php echo $base_url . '&pagina=' . ($pagina_atual + 1); ?>" class="btn-pagina">Próxima ›</a>
            <?php endif; ?>
            
            <a href="<?php echo $base_url . '&pagina=' . $total_paginas; ?>" class="btn-pagina">Última »</a>
            
            <span class="info-paginacao">
                Página <?php echo $pagina_atual; ?> de <?php echo $total_paginas; ?>
            </span>
        </div>
    <?php endif; ?>
</div>

<!-- RODAPÉ -->
<?php include "../../../public/components/admin/footer/footer-admin.php"; ?>

<!-- MODAL EDITAR ESTOQUE -->
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

<!-- MODAL EDITAR LIVRO -->
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
// Opções dos filtros e formulários
const opcoesFiltro = <?php echo json_encode([
    'area' => $areas,
    'idioma' => $idiomas,
    'ano' => $anos,
    'autor' => $autores,
    'categoria' => $categorias,
    'editora' => $editoras,
    'documento' => $documentos
], JSON_UNESCAPED_UNICODE); ?>;

function atualizarFiltroValor() {
    const tipoSelect = document.getElementById('filtro-tipo');
    const valorSelect = document.getElementById('filtro-valor');
    const tipo = tipoSelect.value;
    
    valorSelect.innerHTML = '<option value="">Selecione...</option>';
    
    if (!tipo || !opcoesFiltro[tipo]) {
        valorSelect.disabled = true;
        return;
    }
    
    valorSelect.disabled = false;
    
    opcoesFiltro[tipo].forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        
        if (item.id == '<?php echo htmlspecialchars($filtro_valor); ?>') {
            option.selected = true;
        }
        
        valorSelect.appendChild(option);
    });
}

// ========== MODAL EDITAR ESTOQUE ==========
function abrirModalEstoque(id, titulo, total, disponiveis, emprestados, reservas) {
    document.getElementById('estoque-id-livro').value = id;
    document.getElementById('estoque-titulo').value = titulo;
    document.getElementById('estoque-total').value = total;
    document.getElementById('estoque-disponiveis').value = disponiveis;
    document.getElementById('estoque-emprestados').value = emprestados;
    document.getElementById('estoque-reservas').value = reservas;
    document.getElementById('modal-estoque').style.display = 'flex';
}

function fecharModalEstoque() {
    document.getElementById('modal-estoque').style.display = 'none';
}

document.getElementById('modal-estoque').addEventListener('click', function(e) {
    if (e.target === this) fecharModalEstoque();
});

document.getElementById('form-estoque').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Estoque atualizado com sucesso!');
            fecharModalEstoque();
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao atualizar estoque.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
});

// ========== MODAL EDITAR LIVRO ==========
function popularSelects() {
    // Popular autor
    const autorSelect = document.getElementById('edit-autor');
    autorSelect.innerHTML = '<option value="">Selecione o autor</option>';
    opcoesFiltro.autor.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        autorSelect.appendChild(option);
    });

    // Popular categoria
    const categoriaSelect = document.getElementById('edit-categoria');
    categoriaSelect.innerHTML = '<option value="">Selecione a categoria</option>';
    opcoesFiltro.categoria.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        categoriaSelect.appendChild(option);
    });

    // Popular editora
    const editoraSelect = document.getElementById('edit-editora');
    editoraSelect.innerHTML = '<option value="">Selecione a editora</option>';
    opcoesFiltro.editora.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        editoraSelect.appendChild(option);
    });

    // Popular idioma
    const idiomaSelect = document.getElementById('edit-idioma');
    idiomaSelect.innerHTML = '<option value="">Selecione o idioma</option>';
    opcoesFiltro.idioma.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        idiomaSelect.appendChild(option);
    });

    // Popular área
    const areaSelect = document.getElementById('edit-area');
    areaSelect.innerHTML = '<option value="">Selecione a área</option>';
    opcoesFiltro.area.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        areaSelect.appendChild(option);
    });

    // Popular tipo documento
    const documentoSelect = document.getElementById('edit-documento');
    documentoSelect.innerHTML = '<option value="">Selecione o tipo</option>';
    opcoesFiltro.documento.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nome;
        documentoSelect.appendChild(option);
    });
}

async function abrirModalEditarLivro(id) {
    try {
        // Buscar dados do livro
        const response = await fetch(`../../../router.php?acao=buscarLivro&id_livro=${id}`);
        const result = await response.json();
        
        if (!result.sucesso) {
            alert('Erro ao buscar dados do livro: ' + result.mensagem);
            return;
        }
        
        const livro = result.livro;
        
        // Popular selects
        popularSelects();
        
        // Preencher formulário
        document.getElementById('edit-id-livro').value = livro.id_livro;
        document.getElementById('edit-titulo').value = livro.titulo;
        document.getElementById('edit-autor').value = livro.id_autor;
        document.getElementById('edit-isbn').value = livro.isbn;
        document.getElementById('edit-categoria').value = livro.id_categoria;
        document.getElementById('edit-paginas').value = livro.numero_paginas;
        document.getElementById('edit-editora').value = livro.id_unidade;
        document.getElementById('edit-idioma').value = livro.id_idioma;
        document.getElementById('edit-area').value = livro.id_area;
        document.getElementById('edit-documento').value = livro.id_documento;
        document.getElementById('edit-publicacao').value = livro.data_publicacao || '';
        document.getElementById('edit-resumo').value = livro.descricao || '';
        document.getElementById('edit-notas').value = livro.notas || '';
        
        // Abrir modal
        document.getElementById('modal-editar-livro').style.display = 'flex';
        
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao carregar dados do livro: ' + error.message);
    }
}

function fecharModalEditarLivro() {
    document.getElementById('modal-editar-livro').style.display = 'none';
}

document.getElementById('modal-editar-livro').addEventListener('click', function(e) {
    if (e.target === this) fecharModalEditarLivro();
});

document.getElementById('form-editar-livro').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Livro atualizado com sucesso!');
            fecharModalEditarLivro();
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao atualizar livro.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
});

// ========== DELETAR LIVRO ==========
async function confirmarDeletar(id, titulo) {
    if (!confirm(`Tem certeza que deseja DELETAR o livro "${titulo}"?\n\nEsta ação não pode ser desfeita!`)) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('id_livro', id);
        
        const response = await fetch('../../../router.php?acao=deletarLivro', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.sucesso) {
            alert(result.mensagem || 'Livro deletado com sucesso!');
            window.location.reload();
        } else {
            alert('Erro: ' + (result.mensagem || 'Erro ao deletar livro.'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro de conexão: ' + error.message);
    }
}

// Inicializa
document.addEventListener('DOMContentLoaded', atualizarFiltroValor);
</script>

</body>
</html>