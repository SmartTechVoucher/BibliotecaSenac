<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../../public/css/components/card_livros.css">
    <link rel="stylesheet" href="../../../public/css/usuario/filtro-livros.css">

</head>

<?php
include "../../../public/components/header/header.php";
?>

<body>


    <div class="container">

        <div class="superior">
            <div class="menu_direita">
                <label for="area">Área</label>
                <select name="area" id="area" class="selecionar">
                    <option value="">SELECIONE</option>
                    <option value="Ciência_Sociais_Aplicadas">Ciência Sociais Aplicadas</option>
                    <option value="Economia">Economia</option>
                    <option value="Multidiciplinar">Multidiciplinar</option>
                    <option value="Ciências_Agrârias">Ciências Agrârias</option>
                </select>



                <label for="Categoria/Tags">Categoria/Tags</label>
                <select name="Categoria/Tags" id="Categoria/Tags" class="selecionar">
                    <option value="">SELECIONE</option>
                    <option value="Literatura">Literatura</option>
                    <option value="Folheto">Folheto</option>
                    <option value="Artigo_Periódico">Artigo Periódico</option>
                    <option value="Livro">Livro</option>
                </select>



            </div>

            <div class="grid_card">
                <div class="saude">
                    <h1>Tecnologia</h1>

                    <a href="" class="saiba_mais2">Saiba mais</a>
                </div>

                <div class="cards_livros">

                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php include "../../../public/components/card/card-componente.php"; ?>
                    <?php endfor; ?>

                </div>

                <div class="saude">
                    <h1>Saúde</h1>

                    <a href="" class="saiba_mais2">Saiba mais</a>
                </div>



                <div class="card_livro_saude">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php include "../../../public/components/card/card-componente.php"; ?>
                    <?php endfor; ?>
                </div>

                <div class="saude">
                    <h1>Gestão</h1>

                    <a href="" class="saiba_mais2">Saiba mais</a>
                </div>

                <div class="card_livro_gestao">

                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php include "../../../public/components/card/card-componente.php"; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="inferior">
            <button class="arrow_left control" aria-label="Previous_image">
                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#000000">
                    <path d="M400-80 0-480l400-400 56 57-343 343 343 343-56 57Z" />
                </svg>
            </button>

            <button class="arrow_right control" aria-label="Next_image">
                <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#000000">
                    <path d="m304-82-56-57 343-343-343-343 56-57 400 400L304-82Z" />
                </svg>
            </button>

            <div class="gallery-wrapper">
                <div class="gallery">
                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="Item current-item">



                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="item">
                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="item">
                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="item">
                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="item">
                    <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
                        alt="Beach_Images" class="item">

                </div>
            </div>
        </div>

        <?php include "../../../public/components/footer/footer.php" ?>

        <script src="./../../../public/js/usuario/filtro-livros.js"></script>

</body>

</html>