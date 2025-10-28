<?php

$livro = [
    "titulo" => "Simpósio do Barreado",
    "img" => "../../../public/assets/img/Simposio.png",
    "desc" => "O livro, o autor aborda a pergunta chave: \"Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?\". Esta pergunta é a razão do \"Simpósio do Barreado\". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná. Realizado ficticiamente em Porto de Cima, o simpósio reuniu especialistas de ontem e de hoje, daqui e de muitos lugares, em acaloradas discussões que naturalmente, terminaram em confraternização em volta da mesa. O barreado tem indicação geográfica (IG) na categoria \"indicação de procedência\" desde 2022 e é"
];


require_once "../../../config/constantes.php";

$livro = [
    "titulo" => "Simpósio do Barreado",
    "img" => "../../../public/assets/img/Simposio.png",
    "desc" => "O livro, o autor aborda a pergunta chave: \"Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?\". Esta pergunta é a razão do \"Simpósio do Barreado\". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná. Realizado ficticiamente em Porto de Cima, o simpósio reuniu especialistas de ontem e de hoje, daqui e de muitos lugares, em acaloradas discussões que naturalmente, terminaram em confraternização em volta da mesa.",
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
    <title>Informações do livro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/livro-info.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
</head>

<body>
    <!-- header  -->
    <?php
        include "../../../public/components/usuario/header/header.php";
    ?>


    <!-- conteudo da pagina -->
    <div class="containerConteudo">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
        <div class="containerInfo">
            <!-- info_1 -->
            <img id="livroFoto" src="<?php echo $URLBASE ?>/public/assets/img/Simposio.png" alt="livro.jpg">
            <div class="info_1">

                <div class="livroInfo">
                    <div class="livroTitulo">
                        <h1><?php echo $livro["titulo"] ?></h1>
                        <p id="livroIsbn">(Livro - 618.92 T157e, Cód. 13.418), ISBN: 9788536512259</p>
                    </div>
                    <div class="review">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/estrelas3.png" alt="">
                        <p>3 reviews</p>
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
                    <!-- botao de reservar -->

                    <p id="livroDescricao"><?php echo $livro["desc"] ?></p>

                    <!-- botao de reservar -->
                    <div class="livroReservar">
                        <p>Disponível</p>
                        <button id="botaoReserva" onclick="reservaConcluida()" data-status="livre">Reservar</button>
                    </div>

                    <!-- botao de reservar -->
                    <div class="info_2">

                        <div class="autor">
                            <h3>Autor:</h3>
                            <img src="../../../public/assets/icons/User.png" alt="">
                            <p>Fulano</p>
                        </div>
                        <div class="publicacao">

                            <h3>Publicação:</h3>
                            <img src="../../../public/assets/icons/Geography.png" alt="">
                            <p>Belo Horizonte: do autor, 2024</p>
                        </div>
                        <div class="paginas">

                            <h3>Páginas:</h3>
                            <img src="../../../public/assets/icons/Read.png" alt="">
                            <p>243</p>
                        </div>
                    </div>
                </div>
               
            </div>

            <!-- info_2 -->

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
 <!-- Senac de Três LAgoas -->
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
                <img src="../../../public/assets/icons/Read.png" alt="">
            </div>
            <form id="commentForm" action="/submit-comment" method="POST">
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

            <!-- comentario do usuario -->
            <div class="comment_2" id="commentTemplate" style="display:none;">
                <div class="commentName">
                    <div class="estrela-placeholder-container">
                        <img class="estrela-placeholder" src="BibliotecaSenac/projeto/public/assets/icons/estrelas1.png" alt="">
                    </div>

                    <h3 class="commentTitulo">Nome do Usuário</h3>
                </div>
                <p class="commentUserinfo">Feito em: dd/mm/yyyy</p>
                <p class="commentConteudo">Comentário do usuário aqui...</p>
            </div>


            <div id="reviewsContainer">

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
            </div>
        </div>
    </div>

    <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>


    <script src="<?php echo $URLBASE ?>/public/js/usuario/livro-info.js">
        alternarExemplar()
    </script>
</body>

</html>