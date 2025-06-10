<?php

$livro = [
    "titulo" => "Simpósio do Barreado",
    "img" => "../../../public/assets/img/Simposio.png",
    "desc" => "O livro, o autor aborda a pergunta chave: \"Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?\". Esta pergunta é a razão do \"Simpósio do Barreado\". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná. Realizado ficticiamente em Porto de Cima, o simpósio reuniu especialistas de ontem e de hoje, daqui e de muitos lugares, em acaloradas discussões que naturalmente, terminaram em confraternização em volta da mesa. O barreado tem indicação geográfica (IG) na categoria \"indicação de procedência\" desde 2022 e é"
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../public/css/livro-info.css">
</head>

<body>
    <!-- header  -->
    <?php
    include "../../../public/components/header/header.php";
    ?>


    <!-- conteudo da pagina -->
    <div class="containerConteudo">
        <div class="containerInfo">
            <!-- info_1 -->
            <img id="livroFoto" src="../../../public/assets/img/Simposio.png" alt="livro.jpg">
            <div class="info_1">

                <div class="livroInfo">
                    <div class="livroTitulo">
                        <h1><?php echo $livro["titulo"] ?></h1>
                        <p id="livroIsbn">(Livro - 618.92 T157e, Cód. 13.418), ISBN: 9788536512259</p>
                    </div>
                    <div class="review">
                        <img src="../../../public/assets/icons/nota.png" alt="">
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


                    <p id="livroDescricao">O livro , o autor aborda a pergunta chave: "Afinal, o barreado nasceu em Paranaguá, Antonina ou Morretes?". Esta pergunta é a razão do "Simpósio do Barreado". O livro mostra as origens e a receita do mais tradicional prato culinário do Paraná. Realizado ficticiamente em Porto de Cima, o simpósio reuniu especialistas de ontem e de hoje, daqui e de muitos lugares, em acaloradas discussões que naturalmente, terminaram em confraternização em volta da mesa. O barreado tem indicação geográfica (IG) na categoria "indicação de procedência" desde 2022 e é </p>
                    <div class="lerMais">
                        <img src="../../../public/assets/icons/arrow.png" alt="">
                        <p>Leia mais</p>
                    </div>
                    <!-- botao de reservar -->
                    <div class="livroReservar">
                        <p>Disponível</p>
                        <button class="botaoReserva">Reservar</button>
                    </div>

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

                <!-- botao de reservar -->
                <!-- <div class="livroReservar">
                        <p>Disponível</p>
                        <button class="botaoReserva">Reservar</button>
                    </div> -->

            </div>

            <!-- info_2 -->

        </div>

        <!-- exemplares -->
        <div class="containerExemplar">
            <p>Exemplares</p>
            <img src="../../../public/assets/icons/Plus Math.png" alt="">
        </div>

        <!-- comentarios -->
        <div class="containerComentarios">
            <div class="comment_1">
                <p>Comentários</p>
                <img src="../../../public/assets/icons/Read.png" alt="">
            </div>
            <input type="text" placeholder="Escreva sua opinião...">

            <div class="comment_2" id="comment_2id">
                <div class="commentName">
                    <img src="../../../public/assets/icons/estrelas.png" alt="">
                    <h3 id="commentTitulo">Uma obra prima</h3>
                </div>
                <p id="commentUserinfo">Feito por: <a href="url">Neymar JR</a> 25/02/2023</p>
                <p id="commentConteudo">O Simpósio do Barreado é uma obra-prima que transcende as páginas e mergulha o leitor nas tradições e sabores do litoral paranaense. Dante Mendonça habilmente entrelaça história, ficção e humor enquanto desvenda a intrigante origem do Barreado. As aquarelas do autor enriquecem a experiência, transportando-nos para as pitorescas paisagens costeiras. Uma leitura essencial para os amantes da gastronomia e da cultura regional. 👏🎨</p>
            </div>
            <div class="comment_2">
                <div class="commentName">
                    <img src="../../../public/assets/icons/estrelas.png" alt="">
                    <h3 id="commentTitulo">Bom</h3>
                </div>
                <p id="commentUserinfo">Feito por: <a href="url">Rodrigo Fato</a> 10/02/2023</p>
                <p id="commentConteudo">achei massa 👍</p>
            </div>
            <div class="comment_2">
                <div class="commentName">
                    <img src="../../../public/assets/icons/estrelas.png" alt="">
                    <h3 id="commentTitulo">Bala</h3>
                </div>
                <p id="commentUserinfo">Feito por: <a href="url">Matheus</a> 01/02/2023</p>
                <p id="commentConteudo">achei interessante a maneira q o livro retrata os fatos</p>
            </div>
            <div class="comment_2">
                <div class="commentName">
                    <img src="../../../public/assets/icons/estrelas.png" alt="">
                    <h3 id="commentTitulo">Top</h3>
                </div>
                <p id="commentUserinfo">Feito por: <a href="url">Andrey Hipolito</a> 16/11/2022</p>
                <p id="commentConteudo">a nao sei oq q nao sei oq lá</p>
            </div>
        </div>
    </div>

    <?php
    include "../../../public/components/footer/footer.php";
    ?>
</body>

</html>