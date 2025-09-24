<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tela de cadastro de usuários</title>
  <?php
  require_once "../../../config/constantes.php";

  // Segurança de sessão
  ini_set('session.cookie_lifetime', 0);
  ini_set('session.use_only_cookies', 1);
  ini_set('session.cookie_httponly', 1);
  if (session_status() === PHP_SESSION_NONE) session_start();

  // Capturar dados do toast
  $toastData = $_SESSION['toast'] ?? null;
  if (isset($_SESSION['toast'])) unset($_SESSION['toast']);

  // Capturar dados do formulário preenchidos anteriormente
  $formData = $_SESSION['form_data'] ?? [];
  if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']);
  ?>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
  <link rel="stylesheet" href="../../../public/css/admin/cadastro-usuarios.css">
  <link rel="stylesheet" href="../../../public/css/global.css">

  <?php include "../../../public/components/admin/input/input-admin.php"; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
  <!--Cabeçalho-->
  <?php include "../../../public/components/admin/header/header-admin.php"; ?>

  <main>
    <div class="container-main">
      <!-- FORM APONTANDO PARA O ROUTER -->
      <form id="cadastro-form" action="<?php echo $URLBASE ?>/router/router.php?acao=criarUsuario" method="post" enctype="multipart/form-data">
        <fieldset class="form-section">
          <legend>Informações Pessoais</legend>
          <div id="foto-perfil-container">
            <img id="foto-perfil" src="<?= $formData['foto-usuario'] ?? 'https://placehold.co/150x150/f0f0f0/888888?text=Sua+Foto' ?>" alt="">
            <?php InputAdmin(largura: 15, name: "foto-usuario", id: "foto-usuario", tipo: "file", accept: "image/*"); ?>
          </div>
          <div class="form-row"> 
            <div class="form-grupo">
              <label for="nome">Nome Completo *</label>
              <?php InputAdmin(largura: 100, name: "nome", id: "nome", tipo: "text", required: true, valor: $formData['nome'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="cpf">CPF *</label>
              <?php InputAdmin(largura: 100, name: "cpf", id: "cpf", tipo: "text", required: true, valor: $formData['cpf'] ?? ''); ?>
            </div>
          </div>
          <div class="form-row">
            <div class="form-grupo">
              <label for="email">E-mail *</label>
              <?php InputAdmin(largura: 100, name: "email", id: "email", tipo: "email", required: true, valor: $formData['email'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="data_nascimento">Data de Nascimento *</label>
              <?php InputAdmin(largura: 100, name: "data_nascimento", id: "data_nascimento", tipo: "date", required: true, valor: $formData['data_nascimento'] ?? ''); ?>
            </div>
          </div>
          <div class="form-row">
            <div class="form-grupo">
              <label for="telefone">Telefone</label>
              <?php InputAdmin(largura: 100, placeholder: "(99) 99999-9999", name: "telefone", id: "telefone", tipo: "tel", valor: $formData['telefone'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="endereco">Endereço Completo</label>
              <?php InputAdmin(largura: 100, placeholder: "Ex: Rua das Flores, 123, Centro", name: "endereco", id: "endereco", tipo: "text", valor: $formData['endereco'] ?? ''); ?>
            </div>
          </div>
          <div class="form-row">
            <div class="form-grupo">
              <label for="nome_social">Nome Social</label>
              <?php InputAdmin(largura: 100, placeholder: "Ex: João", name: "nome_social", id: "nome_social", tipo: "text", valor: $formData['nome_social'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="genero">Gênero</label>
              <select id="genero" name="genero" class="select-padrao">
                <option value="">Selecione</option>
                <?php 
                  $genero = $formData['genero'] ?? '';
                  $opcoesGenero = ['Masculino','Feminino','Não binario','Outros','Não informar'];
                  foreach($opcoesGenero as $opcao){
                      $sel = ($genero === $opcao) ? 'selected' : '';
                      echo "<option value='$opcao' $sel>$opcao</option>";
                  }
                ?>
              </select>
            </div>
          </div>
        </fieldset>

        <!-- Mantém todas as outras seções do formulário iguais, só adicionando repopulação -->
        <!-- Informações Acadêmicas -->
        <fieldset class="form-section">
          <legend>Informações Acadêmicas</legend>
          <div class="form-row">
            <div class="form-grupo">
              <label for="matricula">Nº de Matrícula</label>
              <?php InputAdmin(largura: 100, name: "matricula", id: "matricula", tipo: "text", valor: $formData['matricula'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="categoria">Categoria *</label>
              <select id="categoria" name="categoria" class="select-padrao" required>
                <option value="">Selecione</option>
                <?php 
                  $categoria = $formData['categoria'] ?? '';
                  $opcoesCat = ['Aluno','Docente','Bibliotecario'];
                  foreach($opcoesCat as $opcao){
                      $sel = ($categoria === $opcao) ? 'selected' : '';
                      echo "<option value='$opcao' $sel>$opcao</option>";
                  }
                ?>
              </select>
            </div>
            <div class="form-grupo">
              <label for="unidade_senac">Unidade *</label>
              <select id="unidade_senac" name="unidade_senac" class="select-padrao" required>
                <option value="">Selecione</option>
                <?php 
                  $unidade = $formData['unidade_senac'] ?? '';
                  $opcoesUnidade = ['Senac Hub Academy','Senac Dourados','Senac Três Lagoas'];
                  foreach($opcoesUnidade as $opcao){
                      $sel = ($unidade === $opcao) ? 'selected' : '';
                      echo "<option value='$opcao' $sel>$opcao</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-grupo">
              <label for="curso">Curso</label>
              <?php InputAdmin(largura: 100, name: "curso", id: "curso", tipo: "text", valor: $formData['curso'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="turma">Turma</label>
              <?php InputAdmin(largura: 100, name: "turma", id: "turma", tipo: "text", valor: $formData['turma'] ?? ''); ?>
            </div>
            <div class="form-grupo">
              <label for="data_fim_curso">Data de Término do Curso</label>
              <?php InputAdmin(largura: 100, name: "data_fim_curso", id: "data_fim_curso", tipo: "date", valor: $formData['data_fim_curso'] ?? ''); ?>
            </div>
          </div>
        </fieldset>

        <!-- Senha -->
        <fieldset class="form-section">
          <legend>Senha do usuário</legend>
          <div class="form-row">
            <div class="form-grupo">
              <label for="senha_usuario">Senha *</label>
              <?php InputAdmin(largura: 100, name: "senha_usuario", id: "senha_usuario", tipo: "password", required: true); ?>
            </div>
            <div class="form-grupo">
              <label for="senha_usuario_confirm">Confirmar senha *</label>
              <?php InputAdmin(largura: 100, name: "senha_usuario_confirm", id: "senha_usuario_confirm", tipo: "password", required: true); ?>
            </div>
          </div>
        </fieldset>

        <!-- Notas -->
        <fieldset class="form-section">
          <legend>Notas</legend>
          <div class="form-row">
            <div class="form-grupo">
              <textarea name="notas_usuario" id="notas_usuario" cols="30" rows="10" placeholder="Observações administrativas sobre o usuário..."><?= htmlspecialchars($formData['notas_usuario'] ?? '') ?></textarea>
            </div>
          </div>
        </fieldset>

        <div class="botao-container">
          <button type="submit">Salvar Usuário</button>
          <button type="button" class="botao-cancelar">Cancelar</button>
        </div>
      </form>
    </div>
  </main>

  <!--Rodapé-->
  <?php include "../../../public/components/admin/footer/footer-admin.php"; ?>

  <!-- Scripts -->
  <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>
  <script src="../../../public/js/admin/cadastro-usuarios.js"></script>

  <!-- Toast -->
  <?php if ($toastData): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        mostrarToast("<?php echo addslashes($toastData['mensagem']); ?>", "<?php echo $toastData['tipo']; ?>");
      });
    </script>
  <?php endif; ?>
</body>
</html>
