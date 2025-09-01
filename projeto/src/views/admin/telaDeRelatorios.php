<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de relatórios</title>
    <?php
    require_once "../../../config/constantes.php";
    ?>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
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
                <legend>Gerar relatórios</legend>

                <div class="form-row">

                    <div class="form-grupo">
                        <label for="genero">Sobre:</label>
                        <select id="genero" name="genero" class="input-admin">
                            <option value="">Selecione</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="nao_binario">Não Binário</option>
                            <option value="outros">Outros</option>
                            <option value="nao_informar">Prefiro não informar</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="genero">Exportar/salvar como:</label>
                        <select id="genero" name="genero" class="input-admin">
                            <option value="">Selecione</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="nao_binario">Não Binário</option>
                            <option value="outros">Outros</option>
                            <option value="nao_informar">Prefiro não informar</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="unidade_senac">Unidade</label>
                        <select id="unidade_senac" name="unidade_senac" class="input-admin" required>
                            <option value="">Selecione</option>
                            <option value="senac_hub">Senac Hub Academy</option>
                            <option value="senac_dou">Senac Dourados</option>
                            <option value="senac_tres">Senac Três Lagoas</option>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="inicio">Começando de:</label>
                        <input type="date" id="inicio" name="inicio">

                        <label for="fim">Até:</label>
                        <input type="date" id="fim" name="fim">

                    </div>
                    <div class="form-grupo">
                        <label for="unidade_senac">Ordenagem</label>
                        <select id="unidade_senac" name="unidade_senac" class="input-admin" required>
                            <option value="">Selecione</option>
                            <option value="senac_hub">Senac Hub Academy</option>
                            <option value="senac_dou">Senac Dourados</option>
                            <option value="senac_tres">Senac Três Lagoas</option>
                        </select>
                    </div>

                </div>
            </fieldset>



            <div class="form-grupo">

                <?php
                InputAdmin(largura: 100, name: "matricula", id: "matricula", tipo: "text")
                ?>
            </div>








            <div class="botao-container">

                <button type="button" class="botao-cancelar">Cancelar</button>
                <button type="submit">Emitir</button>
            </div>
        </form>
    </div>






    <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé-->


    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    <script src="../../../public/js/admin/telaDeRelatorios.js"></script>

</body>

</html>