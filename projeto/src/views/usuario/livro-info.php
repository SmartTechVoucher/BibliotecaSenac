<?php
require_once "../../../config/constantes.php";
require_once(__DIR__ . '/../../../config/auth-check.php');
protegerPagina();

$usuario = obterUsuarioLogado();


// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pega o ID do livro da URL (ou usa 1 como padrão)
$id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 1;

// Pega informações do usuário logado
$id_usuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$nome_usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Visitante';
$usuario_logado = $id_usuario > 0;

// Dados do livro (temporário - DEVE ser preenchido pelo AJAX, aqui é só um placeholder)
$livro = [
    "id" => $id_livro,
    "titulo" => "Carregando Livro...",
    // Defina o placeholder PHP como vazio ou um caminho real de teste
    "img" => $URLBASE . "/public/uploads/default-capa.jpg", 
    "desc" => "Carregando descrição...",
    "autor" => "Carregando autor...",
    "publicacao" => "Carregando publicação...",
    "paginas" => "...",
    "isbn" => "..."
];

// Dados dos exemplares (o JS irá sobrescrever isso com dados do backend)
$senacCG = ["unidade" => "SenacHub-CG", "exemplarQntd" => "...", "exemplarDisponiveis" => "...", "exemplarEmprestados" => "...", "exemplarReservas" => "..."];
$senacDOU = ["unidade" => "SenacHub-DOU", "exemplarQntd" => "...", "exemplarDisponiveis" => "...", "exemplarEmprestados" => "...", "exemplarReservas" => "..."];
$senacTLG = ["unidade" => "SenacHub-TLG", "exemplarQntd" => "...", "exemplarDisponiveis" => "...", "exemplarEmprestados" => "...", "exemplarReservas" => "..."];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">Informações do livro - Carregando...</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/livro-info.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">

    <script>
        const URLBASE = "<?php echo $URLBASE; ?>";
        const ID_LIVRO = <?php echo $id_livro; ?>;
        const ID_USUARIO = <?php echo $id_usuario; ?>;
        const NOME_USUARIO = "<?php echo addslashes($nome_usuario); ?>";
        const USUARIO_LOGADO = <?php echo $usuario_logado ? 'true' : 'false'; ?>;
    </script>
</head>

<body>
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        
        <div class="containerInfo">
            <img id="livroFoto" src="<?php echo $livro["img"]; ?>" alt="Capa do livro">
            <div class="info_1">
                <div class="livroInfo">
                    <div class="livroTitulo">
                        <h1 id="livroTitulo"><?php echo htmlspecialchars($livro["titulo"]); ?></h1> 
                        <p id="livroIsbn">ISBN: <?php echo $livro["isbn"]; ?></p>
                    </div>
                    
                    <div class="review">
                        <img id="avaliacaoMediaImg" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas3.png" alt="Avaliação média">
                        <p><span id="totalReviews">0</span> avaliações</p>
                    </div>
                    
                    <div class="tags">
                        <h3>Tags:</h3>
                        <div class="tags2">
                            <div class="tag_icone">
                                <p>Culinária</p>
                            </div>
                            <div class="tag_icone">
                                <p>História</p>
                            </div>
                        </div>
                    </div>

                    <p id="livroDescricao"><?php echo htmlspecialchars($livro["desc"]); ?></p>

                    <div class="livroReservar">
                        <p id="statusDisponibilidade">Carregando status...</p>
                        <button id="botaoAcao" data-status="carregando" disabled>Carregar</button>
                    </div>

                    <div class="info_2">
                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/User.png" alt="">
                            <p id="livroAutor"><?php echo htmlspecialchars($livro["autor"]); ?></p> 
                        </div>
                        <div class="publicacao">
                            <h3>Publicação:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Geography.png" alt="">
                            <p id="livroPublicacao"><?php echo htmlspecialchars($livro["publicacao"]); ?></p> 
                        </div>
                        <div class="paginas">
                            <h3>Páginas:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
                            <p id="livroPaginas"><?php echo $livro["paginas"]; ?></p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="containerExemplar">
            <p>Exemplares</p>
            <img src="<?php echo $URLBASE ?>/public/assets/icons/Plus Math.png" alt="" id="abrirExemplares" onclick="alternarExemplar()">
        </div>

        <div id="containerExemplarOpen">
            <p style="text-align: center; padding: 20px; color: #666;">Carregando informações de exemplares...</p>
        </div>

        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
            </div>

            <?php if ($usuario_logado): ?>
                <form id="commentForm">
                    <div class="inputRating">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="" data-value="1">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="" data-value="2">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="" data-value="3">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="" data-value="4">
                    </div>
                    <input type="text" id="comentario-input" placeholder="Escreva sua opinião...">
                    <input type="hidden" name="rating" id="rating-value" value="0">
                    <button type="button" id="comentario-botao">Enviar</button>
                </form>
            <?php else: ?>
                <div style="padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center; margin: 20px 0;">
                    <p style="margin: 0; color: #666;">
                        <a href="<?php echo $URLBASE ?>/src/views/usuario/login.php" style="color: #004A90; text-decoration: underline;">Faça login</a> 
                        para deixar sua avaliação
                    </p>
                </div>
            <?php endif; ?>

            <div id="reviewsContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando comentários...</p>
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>

</html>