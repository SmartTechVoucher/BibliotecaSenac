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
        <!-- Dados da pessoa associada -->
        <div class="section-dados-pessoa">
          <label>*Nome<label>
          <div class="form-group">
            <input type="text" value="Gabriel Arruda da Costa" class="input-field" readonly>
          </div>
        </div>

        <!-- Linha com nascimento, apelido e botão -->
        <div class="section-row align-apelido">
          <div class="section-nascimento">
            <label>*Data de Nascimento:<label>
            <div class="form-group">
              <input type="text" value="04/09/2003" class="input-field" readonly>
            </div>
          </div>

          <div class="section-apelido">
            <label>Apelido:<label>
            <div class="form-group">
              <input type="text" value="ThigsDelas" class="input-field" readonly>
            </div>
          </div>

          <div class="section-editar">
            <h2>&nbsp;</h2>
            <div class="form-group">
              <button class="btn-editar" type="button">Editar apelido</button>
            </div>
          </div>
        </div>

        <!-- Dados do usuário -->
        <div class="section-usuario">
          <h2>*Dados do usuário</h2>

          <div class="form-row">
            <div class="form-group1">
              <label>E-mail</label>
              <input type="email" value="amojava@gmail.com" class="input-field" readonly>
            </div>

            <div class="form-group2">
              <label>Senha</label>
              <input type="password" value="************" class="input-field" readonly>
            </div>

            <div class="form-group3">
              <label>Criado em:</label>
              <input type="text" value="13/05/2023" class="input-field" readonly>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include "../../../public/components/usuario/footer/footer.php"; ?>
</body>

</html>
