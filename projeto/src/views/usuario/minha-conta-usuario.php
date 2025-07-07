<?php
require(__DIR__ . '/../../../config/constantes.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do Usuário</title>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/minha-conta-usuario.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/modal.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
</head>

<body>

  <?php include "../../../public/components/usuario/header/header.php"; ?>

  <main class="perfil-container2">
    <div class="perfil-card">
      <div class="perfil-header">
        <h1>Gabriel Arruda (786543)</h1>
        <p class="situacao">Situação: <span class="status-regular">Regular</span></p>
      </div>

      <div class="perfil-content">
        <!-- Primeira linha: Nome, Data de Nascimento, Apelido e Botão -->
        <div class="primeira-linha">
          <div class="campo-nome">
            <label>*Nome</label>
            <input type="text" value="Gabriel Arruda" class="input-field" readonly>
          </div>

          <div class="campo-nascimento">
            <label>*Data de Nascimento:</label>
            <input type="text" value="04/09/2003" class="input-field" readonly>
          </div>

          <div class="campo-apelido">
            <label>Apelido:</label>
            <input type="text" value="ThigsDelas" class="input-field" readonly>
          </div>

          <div class="campo-botao">
            <label>&nbsp;</label>
            <button class="btn-editar" type="button">Editar apelido</button>
          </div>
        </div>

        <!-- Título da seção -->
        <div class="section-title">
          <h2>*Dados do usuário</h2>
        </div>

        <!-- Segunda linha: E-mail, Senha e Criado em -->
        <div class="segunda-linha">
          <div class="campo-email">
            <label>E-mail</label>
            <input type="email" value="amojava@gmail.com" class="input-field" readonly>
          </div>

          <div class="campo-senha">
            <label>Senha</label>
            <input type="password" value="************" class="input-field" readonly>
          </div>

          <div class="campo-criado">
            <label>Criado em:</label>
            <input type="text" value="13/05/2023" class="input-field" readonly>
          </div>
        </div>
      </div>
    </div>

  <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>

  </main>

  <?php include "../../../public/components/usuario/footer/footer.php"; ?>
  
  <script src="<?php echo $URLBASE ?>/public/js/usuario/editar-apelido.js"></script>
</body>

</html>