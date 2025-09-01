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
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,50;0,300;0,400;0,500;0,700;0,900;1,50;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,50;0,300;0,400;0,500;0,700;0,900;1,50;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

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
                    <div class="form-grupo">
                        <label for="titulo-livro">Titulo</label>
                        <?php
                        InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                        ?>
                    </div>
                    <div class="form-grupo">
                        <label for="autor-livro">Autor</label>
                        <select name="autor-livro" id="autor-livro" class="input-admin">
                            <option value="" disabled selected>Selecione um(a) autor(a)...</option>
                            <?php
                            $autores = [
                                ['code' => '0', 'name' => 'Vinicius De Moraes'],
                                ['code' => '1', 'name' => 'Rafael Vinicius'],
                                ['code' => '2', 'name' => 'Pamela Taga'],
                                ['code' => '3', 'name' => 'Alberto Hainstien'],
                                ['code' => '4', 'name' => 'Cristiano Dourado'],
                                ['code' => '5', 'name' => 'Paloma Celulares'],
                                ['code' => '6', 'name' => 'Ronaldo Nazario'],
                                ['code' => '7', 'name' => 'Roberto Carnes']
                            ];
                            foreach ($autores as $aut) {
                                echo '<option value="' . htmlspecialchars($aut['code']) . '">' . htmlspecialchars($aut['name']) . '</option>';
                            }
                            ?>

                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="editora-livro">Editora</label>
                        <?php
                        InputAdmin(largura: 100, name: "editora-livro", id: "editora-livro", required: true)
                        ?>
                    </div>




                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="isbn-livro">ISBN</label>
                        <?php
                        InputAdmin(largura: 100, name: "isbn-livro", id: "isbn-livro", required: true)
                        ?>
                    </div>
                    <div class="form-grupo">
                        <label for="idioma-livro">Idioma</label>
                        <select name="idioma-livro" id="idioma-livro" class="input-admin">
                            <option value="" disabled selected>Selecione um idioma...</option>
                            <?php
                            $idioma = [
                                ['code' => '0', 'name' => 'Português BR'],
                                ['code' => '1', 'name' => 'Inglês'],
                                ['code' => '2', 'name' => 'Espanhol'],
                                ['code' => '3', 'name' => 'Francês'],
                                ['code' => '4', 'name' => 'Alemão'],
                                ['code' => '5', 'name' => 'Japonês'],
                                ['code' => '6', 'name' => 'Chinês'],
                                ['code' => '7', 'name' => 'Ronaldo'],
                                ['code' => '8', 'name' => 'Italiano'],
                                ['code' => '9', 'name' => 'Holandês'],
                                ['code' => '10', 'name' => 'Sueco'],
                                ['code' => '11', 'name' => 'Árabe'],
                                ['code' => '12', 'name' => 'Russo'],
                                ['code' => '13', 'name' => 'Coreano'],
                                ['code' => '14', 'name' => 'Hindi'],
                                ['code' => '15', 'name' => 'Grego'],
                                ['code' => '16', 'name' => 'Polonês'],
                                ['code' => '17', 'name' => 'Vietnamita']
                            ];
                            foreach ($idioma as $idi) {
                                echo '<option value="' . htmlspecialchars($idi['code']) . '">' . htmlspecialchars($idi['name']) . '</option>';
                            }
                            ?>

                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="categoria-livro">Categoria</label>
                        <select name="categoria-livro" id="categoria-livro" class="input-admin" size="6">
                            <?php
                            $categoria = [
                                ['code' => '0', 'name' => 'Português BR'],
                                ['code' => '1', 'name' => 'Inglês'],
                                ['code' => '2', 'name' => 'Espanhol'],
                                ['code' => '3', 'name' => 'Francês'],
                                ['code' => '4', 'name' => 'Alemão'],
                                ['code' => '5', 'name' => 'Japonês'],
                                ['code' => '6', 'name' => 'Chinês'],
                                ['code' => '7', 'name' => 'Ronaldo'],
                                ['code' => '8', 'name' => 'Italiano'],
                                ['code' => '9', 'name' => 'Holandês'],
                                ['code' => '10', 'name' => 'Sueco'],
                                ['code' => '11', 'name' => 'Árabe'],
                                ['code' => '12', 'name' => 'Russo'],
                                ['code' => '13', 'name' => 'Coreano'],
                                ['code' => '14', 'name' => 'Hindi'],
                                ['code' => '15', 'name' => 'Grego'],
                                ['code' => '16', 'name' => 'Polonês'],
                                ['code' => '17', 'name' => 'Vietnamita']
                            ];
                            foreach ($categoria as $cate) {
                                echo '<option value="' . htmlspecialchars($cate['code']) . '">' . htmlspecialchars($cate['name']) . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="area-livro">Area</label>
                        <select name="area-livro" id="area-livro" class="input-admin">
                            <?php
                            $area = [
                                ['code' => '0', 'name' => 'Português BR'],
                                ['code' => '1', 'name' => 'Inglês'],
                                ['code' => '2', 'name' => 'Espanhol'],
                                ['code' => '3', 'name' => 'Francês'],
                                ['code' => '4', 'name' => 'Alemão'],
                                ['code' => '5', 'name' => 'Japonês'],
                                ['code' => '6', 'name' => 'Chinês'],
                                ['code' => '7', 'name' => 'Ronaldo'],
                                ['code' => '8', 'name' => 'Italiano'],
                                ['code' => '9', 'name' => 'Holandês'],
                                ['code' => '10', 'name' => 'Sueco'],
                                ['code' => '11', 'name' => 'Árabe'],
                                ['code' => '12', 'name' => 'Russo'],
                                ['code' => '13', 'name' => 'Coreano'],
                                ['code' => '14', 'name' => 'Hindi'],
                                ['code' => '15', 'name' => 'Grego'],
                                ['code' => '16', 'name' => 'Polonês'],
                                ['code' => '17', 'name' => 'Vietnamita']
                            ];
                            foreach ($area as $are) {
                                echo '<option value="' . htmlspecialchars($are['code']) . '">' . htmlspecialchars($are['name']) . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="form-grupo">
                        <label for="publicacao-livro">Ano</label>
                        <?php
                        InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date")
                        ?>
                    </div>
                    <div class="form-grupo">
                        <label for="capa-livro">Capa</label>
                        <?php
                        InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file")
                        ?>
                    </div>
                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="resumo-livro">Resumo</label>
                        <textarea name="resumo-livro" id="resumo-livro" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-grupo">
                        <label for="notas-livro">Notas</label>
                        <textarea name="notas-livro" id="notas-livro" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-grupo">
                        <label for="tipo-documento">Tipo de documento</label>
                        <select id="tipo-documento" name="tipo-documento" class="input-admin" required>
                            <option value="">Selecione</option>
                            <option value="tipo-documento-livro">Livro</option>
                            <option value="tipo-documento-ebook">eBook</option>
                            <option value="tipo-documento-revista">Revista</option>
                        </select>
                    </div>
                </div>





            </fieldset>
            <div class="botao-container">
                <?php
                botao(texto: "Registrar", tipo: "submit");
                botao(texto: "Cancelar", tipo: "reset", cor: "#d00");
                ?>
            </div>


        </form>
    </div>

    <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/telaDeCadastroDeLivros.js"></script>

</body>

</html>