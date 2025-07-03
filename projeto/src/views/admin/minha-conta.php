<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de relatórios</title>
    <link rel="stylesheet" href="../../../public/css/admin/minha-conta.css">

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

  <?php
  include "../../../public/components/usuario/header/header.php";
  ?>

  <!-- Conteúdo central -->
 
  <div class="quadradoBranco">

        <form class="quadradoCinza">

            Início / Perfil / Minha conta

            <h1>Luciano (786543)</h1>

            <form class="quadradoCinza">

                <div class="linhaCima">

                    <div class="campo">

                        <p>*Perfil de acesso:</p>
                        <input type="text" name="nome">

                    </div>

                    <div class="form-container">
                        <p>*Email</p>
                        <input type="email" id="email" name="email" />


                    </div>
                    <div class="campoSenha">
                        <p>*Senha</p>
                        <input type="password" id="senha" name="senha">
                    </div>

                </div>
                <div class="linhaBaixo">
                    <div class="campo">
                        <p>*Telefone</p>
                        <input type="tel" id="tel" name="tel">
                        &nbsp;
                    </div>
                    <br><button class="edit-btn">Editar Informação</button><br />
                    <div class="esqueciSenha">
                        <br><button class="esqueciSenha">Esqueci Minha Senha</button></br>
                    </div>
                </div>
    </div>
    </form>
    </div>


  <?php
  include "../../../public/components/usuario/footer/footer.php";
  ?>
</body>

</html>