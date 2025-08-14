<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tela de cadastro de usuários</title>
  <?php
  require_once "../../../config/constantes.php";
  ?>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
  <link rel="stylesheet" href="../../../public/css/admin/telaDeCadastroDeUsuarios.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/input-admin.css">
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

  <main>
    <div class="container-main">
      <form id="cadastro-form" action="" method="">
        <fieldset class="form-section">
          <legend>Informações Pessoais</legend>
          <div id="foto-perfil-container">
            <img id="foto-perfil" src="<?php echo $URLBASE ?>/public/assets/img/NullUser.jpg" alt="">
            <?php
            InputAdmin(15, null, "foto-user", tipo:"file");
            ?>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="nome">Nome Completo</label>
              <?php
              InputAdmin("text", 100, null, "nome");
              ?>
            </div>
            <div class="form-group">
              <label for="cpf">CPF</label>
              <?php
              InputAdmin("text", 100, null, "cpf");
              ?>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="email">E-mail</label>
                <?php
                InputAdmin("email", 100, null, "email");
                ?>
              </div>
              <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <?php
                InputAdmin("date", 100, null, "data_nascimento");
                ?>
              </div>
            </div>

            <div class="form-group">
              <label for="telefone">Telefone</label>
              <?php
              InputAdmin("tel", 100, "(99) 99999-9999", "telefone");
              ?>
            </div>

            <div class="form-group">
              <label for="endereco">Endereço Completo</label>
              <?php
              InputAdmin(tipo: "text", largura: 100, placeholder: "Ex: Rua das Flores, 123, Centro", name: "endereco", id: "endereco");
              ?>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="nome_social">Nome Social</label>
                <?php
              InputAdmin(tipo: "text", largura: 100, placeholder: "Ex: João", name: "nome_social", id: "nome_social");
              ?>
              </div>
              <div class="form-group">
                <label for="genero">Gênero</label>
                <select id="genero" name="genero">
                  <option value="">Selecione</option>
                  <option value="masculino">Masculino</option>
                  <option value="feminino">Feminino</option>
                  <option value="nao_binario">Não Binário</option>
                  <option value="outros">Outros</option>
                  <option value="nao_informar">Prefiro não informar</option>
                </select>
              </div>
            </div>
          </div>
        </fieldset>


      </form>
    </div>
  </main>

  <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé--> <!--Rodapé-->


  <?php
  include "../../../public/components/usuario/footer/footer.php";
  ?>
  <script src="../../../public/js/admin/telaDeCadastroDeUsuarios.js"></script>

</body>

</html>