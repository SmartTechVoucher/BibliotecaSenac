<?php
require(__DIR__ . '/../../../config/constantes.php');
require_once(__DIR__ . '/../../../config/auth-check.php');
protegerPagina(); // Garante que só usuários logados acessem

$usuario = obterUsuarioLogado();

// Valores com fallback seguro
$nome = htmlspecialchars($usuario['nome'] ?? '—');
$id = htmlspecialchars($usuario['id'] ?? '—');
$email = htmlspecialchars($usuario['email'] ?? '—');
$dataNascimento = !empty($usuario['data_nascimento']) 
    ? date('d/m/Y', strtotime($usuario['data_nascimento'])) 
    : '—';
$nomeSocial = htmlspecialchars($usuario['nome_social'] ?? '');
$criadoEm = !empty($usuario['criado_em']) 
    ? date('d/m/Y H:i', strtotime($usuario['criado_em'])) 
    : '—';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do Usuário</title>

  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/minha-conta-usuario.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <?php include "../../../public/components/usuario/header/header.php"; ?>
  <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>

  <main class="perfil-container2">
    <div class="perfil-card">
      <div class="perfil-header">
        <h1><?= $nome ?> (<?= $id ?>)</h1>
        <p class="situacao">Situação: <span class="status-regular">Regular</span></p>
      </div>

      <div class="perfil-content">
        <div class="primeira-linha">
          <div class="campo-nome">
            <label>*Nome</label>
            <input type="text" value="<?= $nome ?>" class="input-field" readonly>
          </div>

          <div class="campo-nascimento">
            <label>*Data de Nascimento:</label>
            <input type="text" value="<?= $dataNascimento ?>" class="input-field" readonly>
          </div>

          <div class="campo-apelido">
            <label>Nome Social:</label>
            <input type="text" value="<?= !empty($nomeSocial) ? $nomeSocial : '—' ?>" class="input-field" readonly>
          </div>

          <div class="campo-botao">
            <label>&nbsp;</label>
            <button class="btn-editar" type="button">Editar nome social</button>
          </div>
        </div>

        <div class="section-title">
          <h2>*Dados do usuário</h2>
        </div>

        <div class="segunda-linha">
          <div class="campo-email">
            <label>E-mail</label>
            <input type="email" value="<?= $email ?>" class="input-field" readonly>
          </div>

          <div class="campo-senha">
            <label>Senha</label>
            <input type="password" value="************" class="input-field" readonly>
          </div>

          <div class="campo-criado">
            <label>Criado em:</label>
            <input type="text" value="<?= $criadoEm ?>" class="input-field" readonly>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include "../../../public/components/usuario/footer/footer.php"; ?>
  <script src="<?php echo $URLBASE ?>/public/js/usuario/editar-apelido.js"></script>
  <script src="<?php echo $URLBASE ?>/public/js/components/header.js"></script>
</body>
</html>
