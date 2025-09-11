<?php
    require "../../../config/constantes.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empréstimo</title>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
  <link rel="stylesheet" href="<?php echo $URLBASE?>/public/css/admin/emprestimo.css">
  <link rel="stylesheet" href="../../../public/css/global.css">
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>
    <?php require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; ?>

</head>

<body>
    <?php
        include "../../../public/components/admin/header/header-admin.php"
    ?>
    
    <main>
        <div class="container-main">
            <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">
                <fieldset class="form-section">
                    <legend>
                        <img src="<?php echo $URLBASE?>/public/assets/icons/emprestimo-icon.png" id="fieldset-icon" alt="">
                        Cadastro de emprestimo</legend>
                        <div class="form-row">
                            <label for="nome-usuario">Nome do usuário</label>
                            <?php
                            // $usuariosMock = [
                            //     ['id' => 1, 'nome' => 'José da Silva'],
                            //     ['id' => 2, 'nome' => 'Ana Maria Santos'],
                            //     ['id' => 3, 'nome' => 'Pedro Oliveira'],
                            //     ['id' => 4, 'nome' => 'Fernanda Costa'],
                            //     ['id' => 5, 'nome' => 'Lucas Pereira'],
                            //     ['id' => 6, 'nome' => 'Mariana Almeida'],
                            //     ['id' => 7, 'nome' => 'Rafaela Martins'],
                            //     ['id' => 8, 'nome' => 'Guilherme Souza'],
                            //     ['id' => 9, 'nome' => 'Beatriz Ferreira'],
                            //     ['id' => 10, 'nome' => 'Gabriel Rodrigues'],
                            //     ['id' => 11, 'nome' => 'Juliana Gomes'],
                            //     ['id' => 12, 'nome' => 'Daniel Barbosa'],
                            //     ['id' => 13, 'nome' => 'Carolina Lima'],
                            //     ['id' => 14, 'nome' => 'Thiago Fernandes'],
                            //     ['id' => 15, 'nome' => 'Isabela Rocha'],
                            //     ['id' => 16, 'nome' => 'Artur Nunes'],
                            //     ['id' => 17, 'nome' => 'Laura Dias'],
                            //     ['id' => 18, 'nome' => 'Felipe Castro'],
                            // ];
                            //     renderSelectModal(name:"nome-usuario", label:"Nome do usuário", items:$usuariosMock)
                                InputAdmin(largura:100, placeholder:"Nome completo do usuário", id:"nome-usuario", name:"nome-usuario")
                            ?>
                        </div>
                        <fieldset class="form-section">
                                <legend>Dados do Usuário</legend>
                                <img src="<?php echo $URLBASE?>/public/assets/img/NullUser.jpg" alt="">
                                <?php
                                    
                                ?>
                        </fieldset>
                </fieldset>

            </form>
        </div>
    </main>
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    
    
</body>
</html>