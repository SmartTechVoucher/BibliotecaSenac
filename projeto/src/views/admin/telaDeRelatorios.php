<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de relatórios</title>
    <?php
    require_once "../../../config/constantes.php";
    ?>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/global.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/telaRelatorios.css">

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
        <form id="cadastro-form" action="#" method="post" enctype="multipart/form-data">
            <fieldset class="form-section">
                <legend> <img src="../../../public/assets/icons/relatorio.png" alt="">Gerar relatórios</legend>
                <!-- ideia -->
                <!-- <p>Configure os parâmetros para gerar seu relatório personalizado</p> -->

                <div class="form-row">

                    <div class="form-grupo">
                        <label for="genero"><img src="../../../public/assets/icons/relatorio.png" alt="" class="icons">Sobre:</label>
                        <select id="genero" name="genero" class="select-padrao">
                            <option value="">Selecione</option>
                            <option value="">Acervo</option>
                            <option value="">Aquisição</option>
                            <option value="">Empréstimos</option>
                            <option value="">Usuários</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="genero"><img src="<?php echo $URLBASE ?>/public/assets/icons/exportar.png" alt="" class="icons">Exportar/salvar como:</label>
                        <select id="genero" name="genero" class="select-padrao">
                            <option value="">Selecione</option>
                            <option value="">PDF</option>
                            <option value="">Png/Jpeg</option>
                            <option value="">Texto</option>
                            <option value="">Excel</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="unidade_senac"><img src="<?php echo $URLBASE ?>/public/assets/icons/unidade.png" alt="" class="icons">Unidade:</label>
                        <select id="unidade_senac" name="unidade_senac" class="select-padrao" required>
                            <option value="">Selecione</option>
                            <option value="senac_hub">Senac Hub Academy</option>
                            <option value="senac_dou">Senac Dourados</option>
                            <option value="senac_tres">Senac Três Lagoas</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="inicio"><img src="<?php echo $URLBASE ?>/public/assets/icons/calendario-antes.png" alt="" class="icons">Começando de:</label>
                        <input type="date" id="inicio" name="inicio" class="input-date-padrao">
                    </div>

                    <div class="form-grupo">
                        <label for="fim"><img src="<?php echo $URLBASE ?>/public/assets/icons/calendario-depois.png" alt="" class="icons">Até:</label>
                        <input type="date" id="fim" name="fim" class="input-date-padrao">
                    </div>

                    <div class="form-grupo">
                        <label for="unidade_senac"><img src="<?php echo $URLBASE ?>/public/assets/icons/ordenagem.png" alt="" class="icons">Ordenagem</label>
                        <select id="unidade_senac" name="unidade_senac" class="select-padrao" required>
                            <option value="">Selecione</option>
                            <option value="">Data crescente</option>
                            <option value="">Data decrescente</option>
                            <option value="">Crescente</option>
                            <option value="">Decrescente</option>
                        </select>
                    </div>




                </div>

                <div class="botao-container">
                    <button type="button">Cancelar</button>
                    <button type="submit" class="botao-cancelar">Gerar relatório</button>
                </div>

            </fieldset>








    </div>

    <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé-->


    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    <script src="../../../public/js/admin/telaDeRelatorios.js"></script>

</body>

</html>