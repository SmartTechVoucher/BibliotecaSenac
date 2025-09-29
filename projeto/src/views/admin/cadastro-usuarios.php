  <!DOCTYPE html>
  <html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro de usuários</title>

    <?php
    require_once "../../../config/constantes.php";
    ?>

    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="../../../public/css/admin/cadastro-usuarios.css">
    <link rel="stylesheet" href="../../../public/css/global.css">

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

    <!--Cabeçalho-->
    <?php
    include "../../../public/components/admin/header/header-admin.php";
    ?>

    <main>
      <div class="container-main">
        <!-- CORREÇÃO: Action apontando para o router com a ação criarUsuario -->
        <form id="cadastro-form" action="../../../router.php?acao=criarUsuario" method="post" enctype="multipart/form-data">
          <fieldset class="form-section">
            <legend>Informações Pessoais</legend>
            <div id="foto-perfil-container">
              <img id="foto-perfil" src="https://placehold.co/150x150/f0f0f0/888888?text=Sua+Foto" alt="">
              <?php
              InputAdmin(largura: 15, name: "foto-usuario", id: "foto-usuario", tipo: "file", accept: "image/*");
              ?>
            </div>
            <div class="form-row"> 
              <div class="form-grupo">
                <label for="nome">Nome Completo</label>
                <?php
                InputAdmin(largura: 100, name: "nome", id: "nome", tipo: "text", required: true)
                ?>
              </div>
              <div class="form-grupo">
                <label for="cpf">CPF</label>
                <?php
                InputAdmin(largura: 100, name: "cpf", id: "cpf", tipo: "text", required: true)
                ?>
              </div>
            </div>
            <div class="form-row">
              <div class="form-grupo">
                <label for="email">E-mail</label>
                <?php
                InputAdmin(largura: 100, name: "email", id: "email", tipo: "email", required: true);
                ?>
              </div>
              <div class="form-grupo">
                <label for="data_nascimento">Data de Nascimento</label>
                <?php
                InputAdmin(largura: 100, name: "data_nascimento", id: "data_nascimento", tipo: "date", required: true)
                ?>
              </div>
            </div>
            <div class="form-row">
              <div class="form-grupo">
                <label for="telefone">Telefone</label>
                <?php
                InputAdmin(largura: 100, placeholder: "(99) 99999-9999", name: "telefone", id: "telefone", tipo: "tel")
                ?>
              </div>
              <div class="form-grupo">
                <label for="endereco">Endereço Completo</label>
                <?php
                InputAdmin(largura: 100, placeholder: "Ex: Rua das Flores, 123, Centro", name: "endereco", id: "endereco", tipo: "text");
                ?>
              </div>
            </div>
            <div class="form-row">
              <div class="form-grupo">
                <label for="nome_social">Nome Social</label>
                <?php
                InputAdmin(largura: 100, placeholder: "Ex: João", name: "nome_social", id: "nome_social", tipo: "text");
                ?>
              </div>
              <div class="form-grupo">
                <label for="genero">Gênero</label>
                <!-- CORREÇÃO: Values ajustados para match no router -->
                <select id="genero" name="genero" class="select-padrao">
                  <option value="">Selecione</option>
                  <option value="masculino">Masculino</option>
                  <option value="feminino">Feminino</option>
                  <option value="nao_binario">Não Binário</option>
                  <option value="outros">Outros</option>
                  <option value="nao_informar">Prefiro não informar</option>
                </select>
              </div>
            </div>
          </fieldset>
          <fieldset class="form-section">
            <legend>Informações Acadêmicas</legend>
            <div class="form-row">
              <div class="form-grupo">
                <label for="matricula">Nº de Matrícula</label>
                <?php
                InputAdmin(largura: 100, name: "matricula", id: "matricula", tipo: "text")
                ?>
              </div>
              <div class="form-grupo">
                <label for="categoria">Categoria</label>
                <!-- CORREÇÃO: Values ajustados para match no router -->
                <select id="categoria" name="categoria" class="select-padrao" required>
                  <option value="">Selecione</option>
                  <option value="graduacao">Aluno</option>
                  <option value="pos">Docente</option>
                  <option value="extensao">Bibliotecário</option>
                </select>
              </div>
              <div class="form-grupo">
                <label for="unidade_senac">Unidade</label>
                <!-- CORREÇÃO: Values ajustados para match no router -->
                <select id="unidade_senac" name="unidade_senac" class="select-padrao" required>
                  <option value="">Selecione</option>
                  <option value="senac_hub">Senac Hub Academy</option>
                  <option value="senac_dou">Senac Dourados</option>
                  <option value="senac_tres">Senac Três Lagoas</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-grupo">
                <label for="curso">Curso</label>
                <?php
                InputAdmin(largura: 100, name: "curso", id: "curso", tipo: "text")
                ?>
              </div>
              <div class="form-grupo">
                <label for="turma">Turma</label>
                <?php
                InputAdmin(largura: 100, name: "turma", id: "turma", tipo: "text")
                ?>
              </div>
              <div class="form-grupo">
                <label for="data_fim_curso">Data de Término do Curso</label>
                <?php
                InputAdmin(largura: 100, name: "data_fim_curso", id: "data_fim_curso", tipo: "date")
                ?>
              </div>
            </div>
          </fieldset>
          <fieldset class="form-section">
            <legend>Senha do usuário</legend>
            <div class="form-row">
              <div class="form-grupo">
                <label for="senha_usuario">Senha</label>
                <?php
                InputAdmin(largura: 100, name: "senha_usuario", id: "senha_usuario", tipo: "password", required: true)
                ?>
              </div>
              <div class="form-grupo">
                <label for="senha_usuario_confirm">Confirmar senha</label>
                <?php
                InputAdmin(largura: 100, name: "senha_usuario_confirm", id: "senha_usuario_confirm", tipo: "password", required: true)
                ?>
              </div>
            </div>
          </fieldset>
          <fieldset class="form-section">
            <legend>Notas</legend>
            <div class="form-row">
              <div class="form-grupo">
                <textarea name="notas_usuario" id="notas_usuario" cols="30" rows="10" placeholder="Observações sobre o usuário..."></textarea>
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
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    
    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>
    <script src="../../../public/js/admin/cadastro-usuarios.js"></script>

  <?php if (isset($_SESSION['toast'])): ?> 
    <script> document.addEventListener('DOMContentLoaded', () => {
      mostrarToast(
        "<?= addslashes($_SESSION['toast']['mensagem']); ?>",
        "<?= $_SESSION['toast']['tipo']; ?>"
      );
    }); </script>
    <?php unset($_SESSION['toast']); ?>
  <?php endif; ?>

  </body>

  </html>