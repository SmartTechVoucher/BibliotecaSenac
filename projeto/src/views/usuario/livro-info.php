<?php
require_once "../../../config/constantes.php";
require_once(__DIR__ . '/../../../config/auth-check.php');
protegerPagina();

$usuario = obterUsuarioLogado();

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aceita tanto 'id' quanto 'id_livro'
$id_livro = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0);

// Se não houver ID válido, redireciona para home
if ($id_livro <= 0) {
    header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
    exit;
}

// Pega informações do usuário logado
$id_usuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$nome_usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Visitante';
$usuario_logado = $id_usuario > 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">Carregando... - Biblioteca SENAC</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/livro-info.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">

    <!-- Variáveis JavaScript -->
    <script>
        const URLBASE = "<?php echo $URLBASE; ?>";
        const ID_LIVRO = <?php echo $id_livro; ?>;
        const ID_USUARIO = <?php echo $id_usuario; ?>;
        const NOME_USUARIO = "<?php echo addslashes($nome_usuario); ?>";
        const USUARIO_LOGADO = <?php echo $usuario_logado ? 'true' : 'false'; ?>;
    </script>
</head>

<body>
    <!-- Header -->
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <!-- Conteúdo da página -->
    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        
        <div class="containerInfo">
            <!-- Foto do livro -->
            <img id="livroFoto" src="" alt="Capa do livro" style="max-width: 350px;">
            
            <div class="info_1">
                <div class="livroInfo">
                    <!-- Título e ISBN -->
                    <div class="livroTitulo">
                        <h1 id="livroTitulo">Carregando...</h1> 
                        <p id="livroIsbn">ISBN: Carregando...</p>
                    </div>
                    
                    <!-- Avaliações -->
                    <div class="review">
                        <img id="avaliacaoMediaImg" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas3.png" alt="Avaliação média">
                        <p><span id="totalReviews">0</span> avaliações</p>
                    </div>
                    
                    <!-- Tags (categoria) - Inicialmente escondido -->
                    <div class="tags" style="display: none;">
                        <h3>Tags:</h3>
                        <div class="tags2">
                            <!-- Será preenchido pelo JavaScript -->
                        </div>
                    </div>

                    <!-- Descrição -->
                    <p id="livroDescricao">Carregando descrição...</p>

                    <!-- Botão de Reservar -->
                    <div class="livroReservar">
                        <p id="statusDisponibilidade">Carregando...</p>
                        <button id="botaoAcao" disabled>Carregando...</button>
                    </div>

                    <!-- Informações adicionais -->
                    <div class="info_2">
                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/User.png" alt="Ícone autor">
                            <p id="livroAutor">Carregando...</p> 
                        </div>
                        <div class="publicacao">
                            <h3>Publicação:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Geography.png" alt="Ícone publicação">
                            <p id="livroPublicacao">Carregando...</p> 
                        </div>
                        <div class="paginas">
                            <h3>Páginas:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="Ícone páginas">
                            <p id="livroPaginas">...</p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Comentários -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="Ícone comentários">
            </div>

            <?php if ($usuario_logado): ?>
                <!-- Formulário de comentário (só aparece se logado) -->
                <form id="commentForm">
                    <div class="inputRating">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 1" data-value="1">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 2" data-value="2">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 3" data-value="3">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 4" data-value="4">
                    </div>
                    <input type="text" id="comentario-input" placeholder="Escreva sua opinião...">
                    <input type="hidden" name="rating" id="rating-value" value="0">
                    <button type="button" id="comentario-botao">Enviar</button>
                </form>
            <?php else: ?>
                <!-- Mensagem para não logados -->
                <div style="padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center; margin: 20px 0;">
                    <p style="margin: 0; color: #666;">
                        <a href="<?php echo $URLBASE ?>/src/views/usuario/login.php" style="color: #004A90; text-decoration: underline;">Faça login</a> 
                        para deixar sua avaliação
                    </p>
                </div>
            <?php endif; ?>

            <!-- Container onde os comentários serão carregados -->
            <div id="reviewsContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando comentários...</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

    <!-- JavaScript -->
    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>

</html>