<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livros</title>
    <?php
    require_once "../../../config/constantes.php";
    ?>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="../../../public/css/admin/telaCadastroLivros.css">
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>

    <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->
    <?php
    include "../../../public/components/admin/header/header-admin.php";
    ?>
    <div class="container-main">
        <form id="cadastro-form" action="#" method="post">

            <fieldset class="form-section">
                <legend>Cadastro de livro</legend>
                <div class="form-coluna">
                    <label for="titulo-livro">Titulo</label>
                    <?php
                    InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                    ?>
                    <label for="autor-livro">Autor</label>
                    <?php
                    InputAdmin(largura: 100, name: "autor-livro", id: "autor-livro", required: true)
                    ?>
                    <label for="titulo-livro">Editora</label>
                    <?php
                    InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                    ?>
                    <label for="codigo-livro">Código do livro</label>
                    <?php
                    InputAdmin(largura: 100, name: "codigo-livro", id: "codigo-livro", required: true)
                    ?>
                    <label for="idioma-livro">Idioma</label>
                    <?php
                    InputAdmin(largura: 100, name: "idioma-livro", id: "idioma-livro")
                    ?>
                    <label for="categoria-livro">Categoria</label>
                    <?php
                    InputAdmin(largura: 100, name: "categoria-livro", id: "categoria-livro")
                    ?>
                    <label for="area-livro">Area</label>
                    <?php
                    InputAdmin(largura: 100, name: "area-livro", id: "area-livro")
                    ?>
                    <label for="publicacao-livro">Ano</label>
                    <?php
                    InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date")
                    ?>
                    <label for="capa-livro">Capa</label>
                    <?php
                    InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file")
                    ?>

                </div>
                <div class="form-coluna">
                    <textarea name="resumo-livro" id="resumo-livro" cols="30" rows="10"></textarea>
                    <textarea name="notas-livro" id="notas-livro" cols="30" rows="10"></textarea>
                    <label for="tipo-documento">Tipo de documento</label>
                    <select id="tipo-documento" name="tipo-documento" class="input-admin" required>
                        <option value="">Selecione</option>
                        <option value="tipo-documento-livro">Livro</option>
                        <option value="tipo-documento-ebook">eBook</option>
                        <option value="tipo-documento-revista">Revista</option>
                    </select>

            </fieldset>

        </form>
    </div>

    <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/telaDeCadastroDeLivros.js"></script>

</body>

</html>