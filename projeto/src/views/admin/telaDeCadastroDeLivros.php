<?php
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../model/usuario/LivroModel.php';
$livro_model = new LivroModel();

$autores = $livro_model->getOpcoesSelect('autores');
$editoras = $livro_model->getOpcoesSelect('unidades');  
$idiomas = $livro_model->getOpcoesSelect('idiomas');
$categorias = $livro_model->getOpcoesSelect('categorias');
$areas = $livro_model->getOpcoesSelect('areas');
$documentos = $livro_model->getOpcoesSelect('documentos');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livros</title>

    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/telaCadastroLivros.css">
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>
    <?php require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; ?>

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
        <form id="cadastro-form" action="<?php echo $URLBASE ?>/router.php?acao=cadastrarLivro" method="post" enctype="multipart/form-data">

            <fieldset class="form-section">
                <legend>Cadastro de livro</legend>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="titulo-livro">Titulo *</label>
                        <?php
                        InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                        ?>
                    </div>
                    
                    <!-- Campo para número de páginas (obrigatório no schema de livros) -->
                    <div class="form-grupo">
                        <label for="numero-paginas">Número de Páginas *</label>
                        <?php InputAdmin(largura: 100, name: "numero-paginas", id: "numero-paginas", tipo: "number", required: true) ?>
                    </div>

                    <div class="form-grupo">
                        <?php
                        renderSelectModal('autor', 'Autor', $autores);
                        ?>

                    </div>
                    <div class="form-grupo">

                        <?php

                        renderSelectModal('editora', 'Editora', $editoras);
                        ?>
                    </div>




                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="isbn-livro">ISBN *</label>
                        <?php
                        InputAdmin(largura: 100, name: "isbn-livro", id: "isbn-livro", required: true)
                        ?>
                    </div>
                    <div class="form-grupo">
                        <?php


                        renderSelectModal('idioma', 'Idioma', $idiomas);
                        ?>
                    </div>
                    <div class="form-grupo">
                        <?php


                        renderSelectModal('categoria', 'Categoria', $categorias);
                        ?>

                    </div>
                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <?php


                        renderSelectModal('area', 'Área', $areas);
                        ?>

                    </div>
                    <div class="form-grupo">
                        <label for="publicacao-livro">Ano *</label>
                        <?php
                        InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date")
                        ?>
                    </div>
                    <div class="form-grupo">
                        <label for="capa-livro">Capa *</label>
                        <?php
                        InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file")
                        ?>
                    <div id="preview-container" style="margin-top: 10px;">
                        <img id="preview-capa" src="" alt="Pré-visualização da capa" style="max-width: 200px; max-height: 300px; display: none; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    </div>
                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="resumo-livro">Resumo *</label>
                        <textarea name="resumo-livro" id="resumo-livro" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-grupo">
                        <label for="notas-livro">Notas *</label>
                        <textarea name="notas-livro" id="notas-livro" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-grupo">
                        <label for="tipo-documento">Tipo de documento *</label>
                        <?php renderSelectModal('tipo-documento', 'Tipo de documento', $documentos); ?>
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
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    <script src="<?php echo $URLBASE ?>/public/js/admin/telaDeCadastroDeLivros.js"></script>

</body>
    <!-- Modal de confirmação de cadastro de livro -->
    <div id="success-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Livro Registrado com Sucesso!</h2>
            <p id="success-message"></p>
            <div class="modal-buttons">
                <button class="btn-cancelar" onclick="closeSuccessModal()">Continuar Cadastrando</button>
                <button class="btn-salvar" onclick="goToBooksList()">Ver Lista de Livros</button>
            </div>
        </div>
    </div>

    <script>
    function showSuccessModal(message) {
        document.getElementById('success-message').textContent = message;
        document.getElementById('success-modal').style.display = 'flex';
    }

    function closeSuccessModal() {
        document.getElementById('success-modal').style.display = 'none';
    }

    function goToBooksList() {
        window.location.href = '<?php echo $URLBASE ?>/src/views/admin/telaDosLivrosCadastrados.php';
    }
    </script>

</html>