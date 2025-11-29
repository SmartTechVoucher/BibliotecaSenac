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

    <style>
        /* Estilos para o sistema de avaliações */
        .rating-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }

        .estrela-input {
            cursor: pointer;
            width: 35px;
            height: 35px;
            transition: transform 0.2s, filter 0.2s;
            filter: grayscale(100%);
        }

        .estrela-input:hover,
        .estrela-input.selected {
            transform: scale(1.2);
            filter: grayscale(0%);
        }

        .estrela-input.selected {
            filter: grayscale(0%) brightness(1.2);
        }

        #commentForm {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
        }

        #comentario-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            margin-top: 15px;
            resize: vertical;
            min-height: 60px;
        }

        #comentario-botao {
            background: #004A90;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        #comentario-botao:hover {
            background: #003162;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        #comentario-botao:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .comment_2 {
            background: #eeeeee;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-left: 4px solid #004A90;
        }

        .commentName {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .estrela-display {
            width: 100px;
            height: auto;
        }

        .commentTitulo {
            color: #004A90;
            font-size: 1.1rem;
            margin: 0;
            font-weight: 600;
        }

        .commentUserinfo {
            font-size: 0.85rem;
            color: #666;
            margin: 5px 0 15px 0;
        }

        .commentConteudo {
            color: #333;
            line-height: 1.6;
            margin: 0;
        }

        .estatisticas-avaliacoes {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .media-estrelas {
            text-align: center;
        }

        .media-numero {
            font-size: 3rem;
            font-weight: bold;
            color: #004A90;
            line-height: 1;
        }

        .media-texto {
            font-size: 0.9rem;
            color: #666;
        }

        .barras-distribuicao {
            flex: 1;
        }

        .barra-estrela {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .barra-label {
            width: 60px;
            font-size: 0.9rem;
            color: #666;
        }

        .barra-progresso {
            flex: 1;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .barra-preenchimento {
            height: 100%;
            background: #ffc107;
            transition: width 0.3s ease;
        }

        .barra-numero {
            width: 40px;
            text-align: right;
            font-size: 0.85rem;
            color: #666;
        }

        .minha-avaliacao-card {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }

        .minha-avaliacao-card h4 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }

        .btn-editar-avaliacao,
        .btn-deletar-avaliacao {
            padding: 6px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 10px;
            transition: all 0.2s;
        }

        .btn-editar-avaliacao {
            background: #2196f3;
            color: white;
        }

        .btn-editar-avaliacao:hover {
            background: #1976d2;
        }

        .btn-deletar-avaliacao {
            background: #f44336;
            color: white;
        }

        .btn-deletar-avaliacao:hover {
            background: #d32f2f;
        }

        .sem-comentarios {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .loading-comentarios {
            text-align: center;
            padding: 30px;
            color: #666;
        }

        .loading-comentarios::after {
            content: '';
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0%, 100% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
        }
    </style>

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
                    
                    
                    <!-- Tags (categoria) -->
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
                <p>Comentários e Avaliações</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="Ícone comentários">
            </div>

            <!-- Estatísticas de Avaliações -->
            <div id="estatisticasContainer" style="display: none;">
                <!-- Será preenchido pelo JavaScript -->
            </div>

            <!-- Minha Avaliação (se existir) -->
            <div id="minhaAvaliacaoContainer">
                <!-- Será preenchido pelo JavaScript -->
            </div>

            <?php if ($usuario_logado): ?>
                <!-- Formulário de comentário (só aparece se logado) -->
                <form id="commentForm">
                    <h4 style="margin: 0 0 15px 0; color: #004A90;">
                        <span id="formTitulo">Deixe sua avaliação</span>
                    </h4>
                    
                    <div class="rating-container">
                        <span style="color: #666; font-size: 0.9rem;">Sua nota:</span>
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 1" data-value="1">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 2" data-value="2">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 3" data-value="3">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 4" data-value="4">
                        <img class="estrela-input" src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" alt="Estrela 5" data-value="5">
                        <span id="estrelasSelecionadas" style="color: #004A90; font-weight: 600;">0 estrelas</span>
                    </div>
                    
                    <textarea id="comentario-input" placeholder="Escreva sua opinião sobre este livro... (opcional)"></textarea>
                    
                    <input type="hidden" id="rating-value" value="0">
                    <input type="hidden" id="id-avaliacao-edicao" value="">
                    
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button type="button" id="comentario-botao">Enviar Avaliação</button>
                        <button type="button" id="cancelar-edicao" style="display: none; background: #dc3545;">Cancelar Edição</button>
                    </div>
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

            <!-- Lista de Avaliações -->
            <div id="reviewsContainer">
                <div class="loading-comentarios">Carregando comentários</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

    <!-- JavaScript -->
    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>

</html>