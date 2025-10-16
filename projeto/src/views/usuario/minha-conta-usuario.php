<?php
require(__DIR__ . '/../../../config/constantes.php');
require_once(__DIR__ . '/../../../config/auth-check.php');
protegerPagina();

$usuario = obterUsuarioLogado();

// Debug temporário - veja o que está vindo do banco
// error_log("Dados do usuário: " . json_encode($usuario));

// IMPORTANTE: Use os nomes corretos das colunas do banco
$nome = htmlspecialchars($usuario['nome'] ?? '—');
$id = htmlspecialchars($usuario['id_usuario'] ?? $usuario['id'] ?? '—'); // Tenta ambos
$email = htmlspecialchars($usuario['email'] ?? '—');

// Data de nascimento
$dataNascimento = '—';
if (!empty($usuario['data_nascimento'])) {
    $dataNascimento = date('d/m/Y', strtotime($usuario['data_nascimento']));
}

// Nome social (apelido)
$nomeSocial = htmlspecialchars($usuario['nome_social'] ?? $usuario['apelido'] ?? '');

// Criado em - tenta 'created_at' ou 'criado_em'
$criadoEm = '—';
if (!empty($usuario['data_criacao'])) {
    $criadoEm = date('d/m/Y', strtotime($usuario['data_criacao']));
} elseif (!empty($usuario['data_atualizacao'])) {
    $criadoEm = date('d/m/Y', strtotime($usuario['data_atualizacao']));
}


// Situação
$situacao = htmlspecialchars($usuario['situacao'] ?? 'Regular');
$situacaoClass = match(strtolower($situacao)) {
    'ativo', 'regular' => 'status-regular',
    'irregular', 'bloqueado' => 'status-irregular',
    'suspenso' => 'status-suspenso',
    default => 'status-regular'
};

 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de <?= $nome ?></title>

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
        <h1><?= $nome ?></h1>
        <p class="situacao">Situação: <span class="<?= $situacaoClass ?>"><?= $situacao ?></span></p>
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
            <input type="text" value="<?php echo !empty($usuario['nome_social']) ? htmlspecialchars($usuario['nome_social']) : '—'; ?>" class="input-field" readonly>
          </div>


          <div class="campo-botao">
            <label>&nbsp;</label>
            <button class="btn-editar" id="btn-editar-nome-social" type="button">Editar nome social</button>
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
  
  <script>const URLBASE = '<?php echo $URLBASE; ?>';</script>
  <script src="<?php echo $URLBASE ?>/public/js/usuario/editar-apelido.js"></script>
  <script src="<?php echo $URLBASE ?>/public/js/components/header.js"></script>
</body>
</html>