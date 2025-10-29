<?php
require "../../../config/constantes.php";


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empréstimo</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/emprestimo.css">
    <link rel="stylesheet" href="../../../public/css/global.css">

    <!-- Components -->
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>
    <?php require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; ?>
</head>

<body>
    <?php include "../../../public/components/admin/header/header-admin.php"; ?>

    <main>
        <div class="container-main">
            <fieldset class="form-section">
                <legend>
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/emprestimo-icon.png" id="fieldset-icon" alt="">
                    Cadastro de Empréstimo
                </legend>

                <!-- Barra de pesquisa -->
                <div id="barra-pesquisa">
                    <label for="searchBox">Pesquisar usuário:</label>
                    <input type="text" id="searchBox" onkeyup="buscarUsuarios(this.value)" placeholder="Digite o nome, e-mail ou CPF...">
                    <div id="results"></div>
                </div>

                <!-- Card do usuário -->
                <div id="userCard">
                    <h3>Selecione um usuário</h3>
                    <p id="placeholder">Os dados aparecerão aqui após a seleção.</p>
                </div>

                <div id="loanSection">
                <label for="isbnInput">Registrar empréstimo</label>
                <div class="loan-input-group">
                    <input type="text" id="isbnInput" placeholder="Digite o ISBN do livro..." disabled>
                    <button id="cancelLoanBtn" class="cancel-btn" disabled>Cancelar</button>
                    <button id="confirmLoanBtn" disabled>Confirmar</button>
                    
                </div>
                <div id="loanMessage"></div>
            </div>

            <div id="loanTableContainer">
        </div>

    <div id="loanPaginationContainer"></div>

            </fieldset>
        </div>
    </main>

    <script src="<?php echo $URLBASE ?>/public/js/admin/emprestimo.js"></script>

    <?php include "../../../public/components/admin/footer/footer-admin.php"; ?>
</body>
</html>
