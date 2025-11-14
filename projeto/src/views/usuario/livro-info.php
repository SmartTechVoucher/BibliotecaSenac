<?php
require_once "../../../config/constantes.php";
<<<<<<< HEAD
require_once "../../../src/model/usuario/LivroInfoModel.php";

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pega o ID do livro da URL
$id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0;

// Se não tiver ID válido, redireciona
if ($id_livro <= 0) {
    header("Location: " . $URLBASE . "/src/views/usuario/index.php");
    exit;
}

// Buscar dados do livro no banco
$model = new LivroInfoModel();
$livro = $model->getLivroById($id_livro);

// Se livro não existir, redireciona
if (!$livro) {
    header("Location: " . $URLBASE . "/src/views/usuario/index.php");
    exit;
}

// Buscar exemplares por unidade
$exemplares = $model->getExemplaresPorUnidade($id_livro);

// Buscar totais gerais
$totais = $model->getTotaisExemplares($id_livro);

// Buscar tags
$tags = $model->getTagsLivro($id_livro);

// Informações do usuário logado
$id_usuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$nome_usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Visitante';
$usuario_logado = $id_usuario > 0;

// Verificar se usuário já tem reserva ativa
$tem_reserva_ativa = false;
if ($usuario_logado) {
    $tem_reserva_ativa = $model->verificarReservaAtiva($id_usuario, $id_livro);
}

// Definir caminho da imagem
$caminho_imagem = !empty($livro['foto']) 
    ? $URLBASE . '/public/' . $livro['foto']
    : $URLBASE . '/public/uploads/default-capa.jpg';

// Verificar disponibilidade
$livro_disponivel = $totais['disponiveis'] > 0;
=======

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
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
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
<<<<<<< HEAD
        const TEM_RESERVA_ATIVA = <?php echo $tem_reserva_ativa ? 'true' : 'false'; ?>;
=======
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
    </script>
</head>

<body>
    <!-- header  -->
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <!-- conteudo da pagina -->
    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        
        <div class="containerInfo">
<<<<<<< HEAD
            <!-- Foto do livro -->
            <img id="livroFoto" 
                 src="<?php echo $caminho_imagem; ?>" 
                 alt="Capa do livro <?php echo htmlspecialchars($livro['titulo']); ?>"
                 onerror="this.onerror=null; this.src='<?php echo $URLBASE; ?>/public/uploads/default-capa.jpg';">
            
=======
            <!-- info_1 -->
            <img id="livroFoto" src="<?php echo $livro["img"]; ?>" alt="Capa do livro">
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
            <div class="info_1">
                <div class="livroInfo">
                    <div class="livroTitulo">
                        <h1><?php echo htmlspecialchars($livro["titulo"]); ?></h1>
<<<<<<< HEAD
                        <p id="livroIsbn">ISBN: <?php echo htmlspecialchars($livro["isbn"]); ?></p>
                    </div>
                    
                    <div class="review">
                        <img id="avaliacaoMediaImg" 
                             src="<?php echo $URLBASE ?>/public/assets/icons/estrelas0.png" 
                             alt="Avaliação média">
                        <p><span id="totalReviews">0</span> avaliações</p>
                    </div>
                    
                    <?php if (!empty($tags)): ?>
=======
                        <p id="livroIsbn">(Livro - 618.92 T157e, Cód. 13.418), ISBN: <?php echo $livro["isbn"]; ?></p>
                    </div>
                    
                    <div class="review">
                        <img id="avaliacaoMediaImg" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas3.png" alt="Avaliação média">
                        <p><span id="totalReviews">0</span> avaliações</p>
                    </div>
                    
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
                    <div class="tags">
                        <h3>Tags:</h3>
                        <div class="tags2">
                            <?php foreach ($tags as $tag): ?>
                                <div class="tag_icone">
                                    <p><?php echo htmlspecialchars($tag); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
<<<<<<< HEAD
                    <?php endif; ?>

                    <p id="livroDescricao">
                        <?php echo htmlspecialchars($livro["resumo"] ?? 'Sem descrição disponível'); ?>
                    </p>

                    <!-- Botão de reservar -->
=======

                    <p id="livroDescricao"><?php echo htmlspecialchars($livro["desc"]); ?></p>

                    <!-- botao de reservar -->
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
                    <div class="livroReservar">
                        <p><?php echo $livro_disponivel ? 'Disponível' : 'Indisponível'; ?></p>
                        <button id="botaoReserva" 
                                onclick="reservaConcluida()" 
                                data-status="<?php echo $tem_reserva_ativa ? 'reservado' : 'livre'; ?>"
                                style="background: <?php echo $tem_reserva_ativa ? '#F68B1F' : '#004A90'; ?>;"
                                <?php echo (!$livro_disponivel && !$tem_reserva_ativa) ? 'disabled' : ''; ?>>
                            <?php 
                            if ($tem_reserva_ativa) {
                                echo 'Livro Reservado';
                            } elseif ($livro_disponivel) {
                                echo 'Reservar';
                            } else {
                                echo 'Sem Exemplares';
                            }
                            ?>
                        </button>
                    </div>

                    <div class="info_2">
                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/User.png" alt="">
<<<<<<< HEAD
                            <p><?php echo htmlspecialchars($livro["autor_nome"] ?? 'Desconhecido'); ?></p>
=======
                            <p><?php echo htmlspecialchars($livro["autor"]); ?></p>
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
                        </div>
                        <div class="publicacao">
                            <h3>Publicação:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Geography.png" alt="">
<<<<<<< HEAD
                            <p><?php 
                                $publicacao = [];
                                if (!empty($livro['editora_nome'])) {
                                    $publicacao[] = htmlspecialchars($livro['editora_nome']);
                                }
                                if (!empty($livro['ano_publicacao'])) {
                                    $publicacao[] = $livro['ano_publicacao'];
                                }
                                echo !empty($publicacao) ? implode(', ', $publicacao) : 'Não informado';
                            ?></p>
=======
                            <p><?php echo htmlspecialchars($livro["publicacao"]); ?></p>
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
                        </div>
                        <div class="paginas">
                            <h3>Páginas:</h3>
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
<<<<<<< HEAD
                            <p><?php echo $livro["numero_paginas"] ?? 'N/A'; ?></p>
=======
                            <p><?php echo $livro["paginas"]; ?></p>
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
                        </div>
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Exemplares -->
=======
        <!-- exemplares -->
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
        <div class="containerExemplar">
            <p>Exemplares (<?php echo $totais['total']; ?> total)</p>
            <img src="<?php echo $URLBASE ?>/public/assets/icons/Plus Math.png" 
                 alt="Expandir" 
                 id="abrirExemplares" 
                 onclick="alternarExemplar()">
        </div>

<<<<<<< HEAD
        <div id="containerExemplarOpen" style="display: none;">
            <?php if (empty($exemplares)): ?>
                <p style="text-align: center; padding: 20px; color: #666;">
                    Nenhum exemplar cadastrado para este livro.
                </p>
            <?php else: ?>
                <?php foreach ($exemplares as $ex): ?>
                <div class="containerGrid">
                    <div class="gridA"><u><b>Unidade</b></u></div>
                    <div class="gridA"><b>Exemplares</b></div>
                    <div class="gridA"><b>Disponível</b></div>
                    <div class="gridA"><b>Emprestados</b></div>
                    <div class="gridA"><b>Reservados</b></div>

                    <div class="gridB"><?php echo htmlspecialchars($ex["unidade"]); ?></div>
                    <div class="gridB"><?php echo $ex["total_exemplares"]; ?></div>
                    <div class="gridB"><?php echo $ex["disponiveis"]; ?></div>
                    <div class="gridB"><?php echo $ex["emprestados"]; ?></div>
                    <div class="gridB"><?php echo $ex["reservados"]; ?></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
=======
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
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
        </div>

        <!-- Comentários -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
            </div>

            <?php if ($usuario_logado): ?>
                <!-- Formulário de comentário (só aparece se estiver logado) -->
                <form id="commentForm">
                    <div class="inputRating">
<<<<<<< HEAD
                        <img class="estrela-input" 
                             src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" 
                             alt="1 estrela" 
                             data-value="1">
                        <img class="estrela-input" 
                             src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" 
                             alt="2 estrelas" 
                             data-value="2">
                        <img class="estrela-input" 
                             src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" 
                             alt="3 estrelas" 
                             data-value="3">
                        <img class="estrela-input" 
                             src="<?php echo $URLBASE ?>/public/assets/icons/livro-info-estrela.png" 
                             alt="4 estrelas" 
                             data-value="4">
                    </div>
                    <input type="text" 
                           id="comentario-input" 
                           placeholder="Escreva sua opinião..."
                           maxlength="500">
                    <input type="hidden" name="rating" id="rating-value" value="0">
                    <button type="button" id="comentario-botao">Enviar</button>
                </form>
            <?php else: ?>
                <!-- Mensagem para visitantes não logados -->
                <div style="padding: 20px; background: #f0f0f0; border-radius: 8px; text-align: center; margin: 20px 0;">
                    <p style="margin: 0; color: #666;">
                        <a href="<?php echo $URLBASE ?>/src/views/usuario/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                           style="color: #004A90; text-decoration: underline; font-weight: 600;">
                            Faça login
                        </a> 
                        para deixar sua avaliação
                    </p>
                </div>
            <?php endif; ?>

            <!-- Container onde os comentários serão carregados dinamicamente -->
            <div id="reviewsContainer">
                <p style="text-align: center; color: #666; padding: 20px;">
                    Carregando comentários...
                </p>
=======
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
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

<<<<<<< HEAD
    <!-- Script do livro-info -->
=======
>>>>>>> 907c8e87a9e6c7a36e2e6a59d2d87e14bae9174f
    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>

</html>