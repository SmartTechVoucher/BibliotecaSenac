<?php
require "../../../config/constantes.php";
include "../../../public/components/admin/button/button-admin.php";

// Lista de livros (simulação) - depois so substituir pelo resultado do banco 😛
$livros = [];
for ($i = 1; $i <= 50; $i++) {
    $livros[] = [
        "titulo" => "O Pequeno Príncipe $i",
        "isbn" => "8583769238590205TY$i",
        "total" => rand(5, 10),
        "disponiveis" => rand(1, 7),
        "emprestados" => rand(0, 3),
        "reserva" => rand(0, 2)
    ];
}

// Configuração da paginação
$porPagina = 18; 
$totalLivros = count($livros);
$totalPaginas = ceil($totalLivros / $porPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

// Fatia os livros para a página atual
$livrosPagina = array_slice($livros, $offset, $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros cadastrados</title>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="../../../public/css/admin/telaDosLivrosCadastrados.css">
</head>

<body>
    <?php include "../../../public/components/admin/header/header-admin.php"; ?>

    <div class="container-main">
        <h2 id="livro-titulomaster">Listagem de livros cadastrados</h2>

        <form>
            <div class="controle">
                <input type="text" class="busca" placeholder="Pesquise por título ou ISBN do livro">
                <button type="submit" class="botao">
                    <img src="../../../public/assets/icons/Buscar.png" alt="Buscar">
                </button>
                <div class="controle2">
                    <label class="unidade">Unidade:</label>
                    <select>
                        <option value="">Selecione</option>
                        <option>Dourados</option>
                        <option>Três Lagoas</option>
                        <option>Ponta Porã</option>
                        <option>Corumbá</option>
                        <option>Campo Grande</option>
                    </select>
                </div>
            </div>
        </form>

        <p id="livros-por-aparecer">Mostrando <?php echo count($livrosPagina); ?> de <?php echo $totalLivros; ?> livros</p>

        <div class="livros-grid">
            <?php foreach ($livrosPagina as $livro): ?>
                <div class="livro-container">
                    <img id="livro-img" src="<?php echo $URLBASE ?>/public/assets/img/Simposio.png" alt="Capa do livro">
                    <div class="livro-informacoes">
                        <span id="livro-titulo"><?php echo $livro['titulo']; ?></span>
                        <span>ISBN: <a href="#"><?php echo $livro['isbn']; ?></a></span>
                        <div id="livro-exemplares">
                            <span>Total: <?php echo $livro['total']; ?></span>
                            <span>Disponíveis: <b><?php echo $livro['disponiveis']; ?></b></span>
                            <span>Emprestados: <b><?php echo $livro['emprestados']; ?></b></span>
                            <span>Reserva: <b><?php echo $livro['reserva']; ?></b></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

       
    >
<div class="paginacao">
    <?php if ($paginaAtual > 1): ?>
        <form method="get" style="display:inline;">
            <input type="hidden" name="pagina" value="<?php echo $paginaAtual - 1; ?>">
            <?php botao("&laquo; ", "submit", "#004A90", "14px"); ?>
        </form>
    <?php endif; ?>

    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
        <form method="get" style="display:inline;">
            <input type="hidden" name="pagina" value="<?php echo $p; ?>">
            <?php 
                if ($p == $paginaAtual) {
                    // Botão ativo (destaque)
                    botao($p, "submit", "#2563eb", "10px");
                } else {
                    // Botão normal
                    botao($p, "submit", "#004A90", "10px");
                }
            ?>
        </form>
    <?php endfor; ?>

    <?php if ($paginaAtual < $totalPaginas): ?>
        <form method="get" style="display:inline;">
            <input type="hidden" name="pagina" value="<?php echo $paginaAtual + 1; ?>">
            <?php botao(" &raquo;", "submit", "#004A90", "14px"); ?>
        </form>
    <?php endif; ?>
</div>
