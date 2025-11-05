<?php
require_once "../../../config/constantes.php";

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

// Dados do livro (temporário - depois buscar do banco)
$livro = [
    "id" => $id_livro,
    "titulo" => "Simpósio do Barreado",
    "img" => $URLBASE . "/public/assets/img/Simposio.png",
    "desc" => "O livro, o autor aborda a pergunta chave: \"Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?\". Esta pergunta é a razão do \"Simpósio do Barreado\". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná. Realizado ficticiamente em Porto de Cima, o simpósio reuniu especialistas de ontem e de hoje, daqui e de muitos lugares, em acaloradas discussões que naturalmente, terminaram em confraternização em volta da mesa.",
    "autor" => "Fulano",
    "publicacao" => "Belo Horizonte: do autor, 2024",
    "paginas" => "243",
    "isbn" => "9788536512259"
];

$senacCG = [
    "unidade" => "SenacHub-CG",
    "exemplarQntd" => "5",
    "exemplarDisponiveis" => "0",
    "exemplarEmprestados" => "5",
    "exemplarReservas" => "1"
];
$senacDOU = [
    "unidade" => "SenacHub-DOU",
    "exemplarQntd" => "3",
    "exemplarDisponiveis" => "1",
    "exemplarEmprestados" => "2",
    "exemplarReservas" => "0"
];
$senacTLG = [
    "unidade" => "SenacHub-TLG",
    "exemplarQntd" => "1",
    "exemplarDisponiveis" => "0",
    "exemplarEmprestados" => "1",
    "exemplarReservas" => "2"
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações do livro - <?php echo htmlspecialchars($livro["titulo"]); ?></title>
    
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
    <!-- header  -->
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <!-- conteudo da pagina -->
    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        
        <div class="containerInfo">
            <!-- info_1 -->
            <img id="livroFoto" src="<?php echo $livro["img"]; ?>" alt="Capa do livro">
            <div class="info_1">
                <div class="livroInfo">
                    <div class="livroTitulo">
                        <h1><?php echo htmlspecialchars($livro["titulo"]); ?></h1>
                        <p id="livroIsbn">(Livro - 618.92 T157e, Cód. 13.418), ISBN: <?php echo $livro["isbn"]; ?></p>
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

                    <!-- botao de reservar -->
                    <div class="livroReservar">
                        <p>Disponível</p>
                        <button id="botaoReserva" onclick="reservaConcluida()" data-status="livre">Reservar</button>
                    </div>

                    <div class="info_2">
                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/User.png" alt="">
                            <p><?php echo htmlspecialchars($livro["autor"]); ?></p>
                        </div>
                        <div class="publicacao">
                            <h3>Publicação:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Geography.png" alt="">
                            <p><?php echo htmlspecialchars($livro["publicacao"]); ?></p>
                        </div>
                        <div class="paginas">
                            <h3>Páginas:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
                            <p><?php echo $livro["paginas"]; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- exemplares -->
        <div class="containerExemplar">
            <p>Exemplares</p>
            <img src="<?php echo $URLBASE ?>/public/assets/icons/Plus Math.png" alt="" id="abrirExemplares" onclick="alternarExemplar()">
        </div>

        <div id="containerExemplarOpen">
            <!-- Senac Hub Academy -->
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Exemplares</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo $senacCG["unidade"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarQntd"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarDisponiveis"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarEmprestados"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarReservas"] ?></div>
            </div>

            <!-- Senac De Dourados -->
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Exemplares</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo $senacDOU["unidade"] ?></div>
                <div class="gridB"><?php echo $senacDOU["exemplarQntd"] ?></div>
                <div class="gridB"><?php echo $senacDOU["exemplarDisponiveis"] ?></div>
                <div class="gridB"><?php echo $senacDOU["exemplarEmprestados"] ?></div>
                <div class="gridB"><?php echo $senacDOU["exemplarReservas"] ?></div>
            </div>

            <!-- Senac de Três Lagoas -->
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Exemplares</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo $senacTLG["unidade"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarQntd"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarDisponiveis"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarEmprestados"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarReservas"] ?></div>
            </div>
        </div>

        <!-- comentarios -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
            </div>

            <?php if ($usuario_logado): ?>
                <!-- Formulário de comentário (só aparece se estiver logado) -->
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
                <!-- Mensagem para visitantes não logados -->
                <div style="padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center; margin: 20px 0;">
                    <p style="margin: 0; color: #666;">
                        <a href="<?php echo $URLBASE ?>/src/views/usuario/login.php" style="color: #004A90; text-decoration: underline;">Faça login</a> 
                        para deixar sua avaliação
                    </p>
                </div>
            <?php endif; ?>

            <!-- Template de comentário (invisível) -->
            <div class="comment_2" id="commentTemplate" style="display:none;">
                <div class="commentName">
                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas1.png" alt="">
                    </div>
                    <h3 class="commentTitulo">Nome do Usuário</h3>
                </div>
                <p class="commentUserinfo">Feito em: dd/mm/yyyy</p>
                <p class="commentConteudo">Comentário do usuário aqui...</p>
            </div>

            <!-- Container onde os comentários do backend serão carregados -->
            <div id="reviewsContainer">
                <p style="text-align: center; color: #666; padding: 20px;">Carregando comentários...</p>
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>

</html>