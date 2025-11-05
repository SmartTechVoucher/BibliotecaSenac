<?php
require_once "../../../config/constantes.php";
<<<<<<< HEAD

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// PEGAR ID DO LIVRO DA URL
// ============================================
$id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 1;

// ============================================
// PEGAR DADOS DO USUÁRIO LOGADO
// ============================================
$id_usuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
$nome_usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Visitante';
$usuario_logado = $id_usuario > 0;

// ============================================
// DADOS DO LIVRO (temporário - depois buscar do banco)
// ============================================
=======
>>>>>>> Dev
$livro = [
    "id" => $id_livro,
    "titulo" => "Simpósio do Barreado",
    "img" => $URLBASE . "/public/assets/img/Simposio.png",
    "desc" => "O livro, o autor aborda a pergunta chave: \"Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?\". Esta pergunta é a razão do \"Simpósio do Barreado\". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná.",
    "autor" => "Dante Mendonça",
    "publicacao" => "Belo Horizonte: do autor, 2024",
    "paginas" => "243",
    "isbn" => "9788536512259",
    "nome_categoria" => "Culinária",
    "nome_area" => "História"
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
    "exemplarQntd" => "3",
    "exemplarDisponiveis" => "4",
    "exemplarEmprestados" => "6",
    "exemplarReservas" => "7"
];

$disponivel = true; // Temporário
$exemplares = true; // Temporário
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

    <!-- ============================================ -->
    <!-- VARIÁVEIS JAVASCRIPT (ESSENCIAL!) -->
    <!-- ============================================ -->
    <script>
        const URLBASE = "<?php echo $URLBASE; ?>";
        const ID_LIVRO = <?php echo $id_livro; ?>;
        const ID_USUARIO = <?php echo $id_usuario; ?>;
        const NOME_USUARIO = "<?php echo addslashes($nome_usuario); ?>";
        const USUARIO_LOGADO = <?php echo $usuario_logado ? 'true' : 'false'; ?>;
        
        console.log('Configurações carregadas:');
        console.log('- URLBASE:', URLBASE);
        console.log('- ID_LIVRO:', ID_LIVRO);
        console.log('- ID_USUARIO:', ID_USUARIO);
        console.log('- USUARIO_LOGADO:', USUARIO_LOGADO);
    </script>
</head>

<body>
    <!-- header  -->
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <!-- Conteúdo Principal -->
    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        
        <div class="containerInfo">
            <!-- info_1 -->
            <img id="livroFoto" src="<?php echo $livro["img"]; ?>" alt="Capa do livro">
            <div class="info_1">
                <div class="livroInfo">
                    <!-- Título e ISBN -->
                    <div class="livroTitulo">
<<<<<<< HEAD
                        <h1><?php echo htmlspecialchars($livro["titulo"]); ?></h1>
                        <p id="livroIsbn">(Livro - 618.92 T157e, Cód. 13.418), ISBN: <?php echo $livro["isbn"]; ?></p>
=======
                        <h1><?php echo $livro["titulo"] ?></h1>
                        <p id="livroIsbn">(Livro-618.92 T157e, Cód. 13.418), ISBN: 9788536512259</p>
>>>>>>> Dev
                    </div>
                    
                    <!-- Avaliação média (atualizada pelo JS) -->
                    <div class="review">
                        <img id="avaliacaoMediaImg" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas3.png" alt="Avaliação média">
                        <p><span id="totalReviews">0</span> avaliações</p>
                    </div>
                    
                    <div class="tags">
                        <h3>Tags:</h3>
                        <div class="tags2">
                            <div class="tag_icone">
                                <p><?php echo htmlspecialchars($livro['nome_categoria']); ?></p>
                            </div>
                            <div class="tag_icone">
                                <p><?php echo htmlspecialchars($livro['nome_area']); ?></p>
                            </div>
                        </div>
                    </div>

                    <p id="livroDescricao"><?php echo htmlspecialchars($livro["desc"]); ?></p>

                    <!-- Botão de Reservar -->
                    <div class="livroReservar">
                        <p><?php echo $disponivel ? 'Disponível' : 'Indisponível'; ?></p>
                        <button id="botaoReserva" onclick="reservaConcluida()" data-status="livre">
                            Reservar
                        </button>
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
<<<<<<< HEAD
        </div>

        <!-- Exemplares -->
=======

            

        </div>

        
>>>>>>> Dev
        <div class="containerExemplar">
            <p>Exemplares</p>
            <img src="<?php echo $URLBASE ?>/public/assets/icons/Plus Math.png" 
                 alt="Expandir" 
                 id="abrirExemplares" 
                 onclick="alternarExemplar()">
        </div>

<<<<<<< HEAD
        <div id="containerExemplarOpen" style="display: none;">
            <!-- Senac Hub Academy -->
=======
        <div id="containerExemplarOpen">
           
>>>>>>> Dev
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Total</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo $senacCG["unidade"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarQntd"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarDisponiveis"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarEmprestados"] ?></div>
                <div class="gridB"><?php echo $senacCG["exemplarReservas"] ?></div>
            </div>
<<<<<<< HEAD
            
            <!-- Senac De Dourados -->
=======
           
>>>>>>> Dev
            <div class="containerGrid">
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Total</b></div>
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
                <div class="gridA"><b>Total</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB"><?php echo $senacTLG["unidade"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarQntd"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarDisponiveis"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarEmprestados"] ?></div>
                <div class="gridB"><?php echo $senacTLG["exemplarReservas"] ?></div>
            </div>
<<<<<<< HEAD
=======

>>>>>>> Dev
        </div>

        <!-- Sistema de Comentários -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Read.png" alt="">
            </div>

            <?php if ($usuario_logado): ?>
                <!-- Formulário de comentário (só aparece se logado) -->
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

            <!-- Container onde os comentários serão carregados -->
            <div id="reviewsContainer">
<<<<<<< HEAD
                <p style="text-align: center; color: #666; padding: 20px;">Carregando comentários...</p>
=======

            </div>
            <div class="comment_2" id="comment_2id">
                <div class="commentName">
                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas4.png" alt="">
                    </div>

                    <h3 id="commentTitulo">Neymar JR</h3>
                </div>
                <p id="commentUserinfo">Feito em: 25/02/2023</p>

                <p id="commentConteudo">O Simpósio do Barreado é uma obra-prima que transcende as páginas e mergulha o leitor nas tradições e sabores do litoral paranaense. Dante Mendonça habilmente entrelaça história, ficção e humor enquanto desvenda a intrigante origem do Barreado. As aquarelas do autor enriquecem a experiência, transportando-nos para as pitorescas paisagens costeiras. Uma leitura essencial para os amantes da gastronomia e da cultura regional. 👏🎨</p>
            </div>
            <div class="comment_2">
                <div class="commentName">
                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas4.png" alt="">

                    </div>

                    <h3 id="commentTitulo">Rodrigo Fato</h3>
                </div>
                <p id="commentUserinfo">Feito em: 10/02/2023</p>
                <p id="commentConteudo">achei massa 👍</p>
            </div>
            <div class="comment_2">
                <div class="commentName">

                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas4.png" alt="">
                    </div>

                    <h3 id="commentTitulo">Matheus</h3>
                </div>
                <p id="commentUserinfo">Feito em: 01/02/2023</p>

                <p id="commentConteudo">achei interessante a maneira q o livro retrata os fatos</p>
            </div>
            <div class="comment_2">
                <div class="commentName">

                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="<?php echo $URLBASE ?>/public/assets/icons/estrelas4.png" alt="">

                    </div>

                    <h3 id="commentTitulo">Andrey Hipolito</h3>
                </div>
                <p id="commentUserinfo">Feito em: 16/11/2022</p>

                <p id="commentConteudo">a nao sei oq q nao sei oq lá</p>
>>>>>>> Dev
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>

    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js"></script>
</body>
</html>