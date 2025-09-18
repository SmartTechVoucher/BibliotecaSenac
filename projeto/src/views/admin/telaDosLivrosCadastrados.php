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

    <p id="livros-por-aparecer">Livros por aparecer:</p>
    
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
                     onerror="this.src='<?php echo $URLBASE; ?>/public/assets/img/Simposio.png'; console.log('Fallback usado para: <?php echo addslashes($livro['titulo']); ?> - Caminho original: <?php echo addslashes($caminho_imagem); ?>');"
                     style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px;">
                <div class="livro-informacoes">
                    <span id="livro-titulo"><?php echo htmlspecialchars($livro['titulo']); ?></span>
                    <span>ISBN: <a href=""><?php echo htmlspecialchars($livro['isbn']); ?></a></span>
                    <div id="livro-exemplares">
                        <span>Total de exemplares: <?php echo $livro['total_exemplares']; ?></span>
                        <span>Disponíveis: <b><?php echo $livro['disponiveis']; ?></b></span>
                        <span>Emprestados: <b><?php echo $livro['emprestados']; ?></b></span>
                        <span>Reservas: <b><?php echo $livro['reservas']; ?></b></span>
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
                Total: <strong><?php echo number_format($total_livros); ?></strong> livros
            </span>
        </div>
    <?php endif; ?>
 </div>
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    <script src="../../../public/js/admin/telaDosLivrosCadastrados.js"></script>

</body>
</html>