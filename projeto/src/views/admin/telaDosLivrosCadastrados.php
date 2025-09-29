<?php
   require "../../../config/constantes.php";
   
   require_once __DIR__ . '/../../../src/controller/admin/ListarLivrosController.php';
   
   $controller = new ListarLivrosController();
   $dados = $controller->prepararDadosView();
   $livros = $dados['livros'];
   $unidades = $dados['unidades'];
   $busca_atual = $dados['busca_atual'];
   $unidade_selecionada = $dados['unidade_selecionada'];
   $paginacao = $dados['paginacao'];
   $pagina_atual = $paginacao['pagina_atual'];
   $total_paginas = $paginacao['total_paginas'];
   $total_livros = $paginacao['total_livros'];
?>     

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros cadastrados</title>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="../../../public/css/admin/telaDosLivrosCadastrados.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>
    
 <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->    
 <?php   
    include "../../../public/components/admin/header/header-admin.php";
  ?>
   
<div class="container-main">
    <h2 id="livro-titulomaster">Listagem de livros cadastrados</h2>
    <form method="GET" action="">

        <div class="controle">
            <input type="text" name="busca" class="busca" placeholder="Pesquise por título ou ISBN do livro" value="<?php echo htmlspecialchars($busca_atual); ?>">

            <button type="submit" class="botao">
                <img src="../../../public/assets/icons/Buscar.png" alt="Imagem de lupa">
            </button>

            <div class="controle2">
                <label class="unidade">Unidade:</label>
                <select name="unidade" id="">
                    <option value="">Selecione</option>
                    <?php foreach ($unidades as $unidade): ?>
                        <option value="<?php echo $unidade['id']; ?>" <?php echo ($unidade_selecionada == $unidade['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($unidade['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>    

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
                    <span>ISBN: <a href=""><?php echo htmlspecialchars($livro['isbn']); ?></a></span>
                    <div id="livro-exemplares">
                        <span>Total de exemplares: <?php echo $livro['total_exemplares']; ?></span>
                        <span>Disponíveis: <b><?php echo $livro['disponiveis']; ?></b></span>
                        <span>Emprestados: <b><?php echo $livro['emprestados']; ?></b></span>
                        <span>Reservas: <b><?php echo $livro['reservas']; ?></b></span>
                    </div>
                    <div class="livro-acoes">
                        <button class="btn-editar-estoque" onclick="abrirModalEstoque(<?php echo $livro['id_livro']; ?>, '<?php echo addslashes($livro['titulo']); ?>', <?php echo $livro['total_exemplares']; ?>, <?php echo $livro['disponiveis']; ?>, <?php echo $livro['emprestados']; ?>, <?php echo $livro['reservas']; ?>)">
                            ✏️ Editar Estoque
                        </button>
                    </div>
                </div>
                <!-- <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt=""> -->
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
                          font-size: 14px;">
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
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>

    <!-- Modal para editar estoque -->
    <div id="modal-estoque" class="modal-estoque" style="display: none;">
        <div class="modal-estoque-content">
            <div class="modal-estoque-header">
                <h3>Editar Estoque do Livro</h3>
                <span class="close-modal" onclick="fecharModalEstoque()">&times;</span>
            </div>
            <form id="form-estoque" method="POST" action="../../../router.php?acao=atualizarEstoque">
                <input type="hidden" id="estoque-id-livro" name="id_livro" value="">

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

                <div class="modal-estoque-actions">
                    <button type="button" class="btn-cancelar" onclick="fecharModalEstoque()">Cancelar</button>
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal-estoque {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-estoque-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-estoque-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .modal-estoque-header h3 {
            margin: 0;
            color: #333;
        }

        .close-modal {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
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
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input[readonly] {
            background-color: #f5f5f5;
            color: #666;
        }

        .modal-estoque-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .btn-cancelar {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-cancelar:hover {
            background: #5a6268;
        }

        .btn-salvar {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-salvar:hover {
            background: #0056b3;
        }

        .livro-acoes {
            margin-top: 10px;
        }

        .btn-editar-estoque {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }

        .btn-editar-estoque:hover {
            background: #218838;
        }
    </style>

    <script>
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

    <script src="../../../public/js/admin/telaDosLivrosCadastrados.js"></script>

</body>
</html>