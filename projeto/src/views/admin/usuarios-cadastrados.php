<?php
require_once "../../../config/constantes.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <title>Usuários cadastrados</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/input-admin.css">
  <?php include "../../../public/components/admin/input/input-admin.php"; ?>
  <link rel="stylesheet" href="/BibliotecaSenac/projeto/public/css/admin/usuarios-cadastrados.css" />
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
  include "../../../public/components/admin/header/header-admin.php";
  ?>
  <main>

    <title>Gerenciamento de Usuários</title>
    <div class="container-main">
      <div class="botoesFiltro">
        <button class="bloqueadosBotao tab-link active" onclick="abrirTab(event)">Regulares</button>
        <button class="bloqueadosBotao tab-link" onclick="abrirTab(event)">Bloqueados</button>
      </div>

      <div class="container">

        <h2>Usuários Cadastrados</h2>
        <label for="search"><label for="">Pesquisar:</label for=""></label>
        <?php
        InputAdmin("text", "Escreva senha", 70, null, "breguenaite")
        ?>

        <div id="regulares" class="tab-content" style="display: block;"> <!-- tabela que lista usuarios regulares -->

          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th>Nº de Matrícula</th>
                <th>Unidade</th>
                <th>Situação</th>
                <th>Telefone</th>
                <th>Situação</th>
              </tr>
            </thead>
            <tbody id="userTable"></tbody>
          </table>
          <div class="pagination-controls">
            <button id="backButton" onclick="mudarPagina(-1)" disabled>Voltar</button>
            <button id="forwardButton" onclick="mudarPagina(1)">Avançar</button>
          </div>
        </div>

        <div id="bloqueados" class="tab-content" style="display: none;">
          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th>Nº de Matrícula</th>
                <th>Unidade</th>
                <th>Situação</th>
                <th>Telefone</th>
                <th>Situação</th>
              </tr>
            </thead>
            <tbody id="blockedTable"></tbody>
          </table>
          <div class="pagination-controls">
            <button id="backBlockedButton" onclick="trocarParaPaginaDosBloqueados(-1)" disabled>Voltar</button>
            <button id="forwardBlockedButton" onclick="trocarParaPaginaDosBloqueados(1)">Avançar</button>
          </div>
        </div>

      </div>



      <!-- Modal de confirmação de salvamento -->
      <div id="confirmModalSalvar" class="modal">
        <div class="modal-content">
          <div class="confirm-content">
            <div class="confirm-icon">✓</div>
            <h3>Dados salvos com sucesso!</h3>
            <p>As alterações foram salvas no banco de dados.</p>
            <button onclick="fecharModalConfirmacao()" class="btn-confirm-ok">OK</button>
          </div>
        </div>
      </div>

      <!-- Modal de erro -->
      <div id="errorModalSalvar" class="modal">
        <div class="modal-content">
          <div class="error-content">
            <div class="error-icon">⚠</div>
            <h3>Erro ao salvar dados</h3>
            <p id="errorMessage">Ocorreu um erro ao salvar as alterações.</p>
            <button onclick="fecharModalErro()" class="btn-error-ok">OK</button>
          </div>
        </div>
      </div>

      <div id="userModal" class="modal"> <!-- janela que contém dados do usuario -->
        <div class="modal-content">
          <h3>Dados do Usuário</h3>
          <div class="dados-container">
            <img src="<?php echo $URLBASE ?>/public/assets/img/NullUser.jpg" alt="" id="userImg">
            <div class="dados">
              <div id="dados-texto"><label for="">Nome:</label for=""> <input readonly class="inputs-editaveis" id="userName"></input></div>
              <div id="dados-texto"><label for="">Nome social:</label for=""> <input readonly class="inputs-editaveis" id="userNameSocial"></input></div>
              <div id="dados-texto"><label for="">Nascimento:</label for=""> <input type="date" readonly class="inputs-editaveis" id="userNascimento"></input></div>
              <div id="dados-texto"><label for="">Sexo:</label for=""> <select disabled id="userSexo">
                  <option value="Masculino">Masculino</option>
                  <option value="Feminino">Feminino</option>
                  <option value="Não binario">Não binário</option>
                  <option value="Outros">Outros</option>
                  <option value="Não informar">Não informar</option>
                </select></div>
              <div id="dados-texto"><label for="">CPF:</label for=""> <input readonly class="inputs-editaveis" id="userCPF"></input></div>
            </div>
          </div>

          <div class="details-section">
            <h4>Dados de Matrícula</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userRegistration">Matrícula:</label>
                <input readonly class="inputs-editaveis" id="userRegistration" value="20230001">
              </div>
              <div id="dados-texto">
                <label for="userUnidade">Unidade:</label>
                <input readonly class="inputs-editaveis" id="userUnidade" value="Unidade Central">
              </div>
              <div id="dados-texto">
                <label for="userStatus">Situação:</label>
                <input readonly class="inputs-editaveis" id="userStatus" value="Ativo">
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Dados de RG</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userRgNumero">Número:</label>
                <input readonly class="inputs-editaveis" id="userRgNumero" value="12.345.678-9">
              </div>
              <div id="dados-texto">
                <label for="userRgOrgaoEmissor">Órgão Emissor:</label>
                <input readonly class="inputs-editaveis" id="userRgOrgaoEmissor" value="SSP/SP">
              </div>
              <div id="dados-texto">
                <label for="userRgUF">UF:</label>
                <input readonly class="inputs-editaveis" id="userRgUF" value="SP">
              </div>
              <div id="dados-texto">
                <label for="userRgPais">País:</label>
                <input readonly class="inputs-editaveis" id="userRgPais" value="Brasil">
              </div>
              <div id="dados-texto">
                <label for="userRgDataEmissao">Data de Emissão:</label>
                <input type="date" readonly class="inputs-editaveis" id="userRgDataEmissao" value="2008-03-20">
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Filiação</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userNomePai">Nome do Pai:</label>
                <input readonly class="inputs-editaveis" id="userNomePai" value="Nome do Pai do Usuário">
              </div>
              <div id="dados-texto">
                <label for="userNomeMae">Nome da Mãe:</label>
                <input readonly class="inputs-editaveis" id="userNomeMae" value="Nome da Mãe do Usuário">
              </div>
              <div id="dados-texto">
                <label for="userResponsavel">Responsável:</label>
                <input readonly class="inputs-editaveis" id="userResponsavel" value="Nome do Responsável">
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Contato</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userTelResidencial">Telefone Residencial:</label>
                <input readonly class="inputs-editaveis" id="userTelResidencial" value="(11) 2233-4455">
              </div>
              <div id="dados-texto">
                <label for="userTelComercial">Telefone Comercial:</label>
                <input readonly class="inputs-editaveis" id="userTelComercial" value="(11) 5566-7788">
              </div>
              <div id="dados-texto">
                <label for="userCelular">Celular:</label>
                <input readonly class="inputs-editaveis" id="userCelular" value="(11) 98765-4321">
              </div>
              <div id="dados-texto">
                <label for="userOutroTelefone">Outro Telefone:</label>
                <input readonly class="inputs-editaveis" id="userOutroTelefone" value="">
              </div>
              <div id="dados-texto">
                <label for="userEmail">E-mail:</label>
                <input readonly class="inputs-editaveis" id="userEmail" value="usuario@exemplo.com">
              </div>
              <div id="dados-texto">
                <label for="userHomepage">Homepage:</label>
                <input readonly class="inputs-editaveis" id="userHomepage" value="www.siteusuario.com">
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Dados Profissionais</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userProfissao">Profissão:</label>
                <input readonly class="inputs-editaveis" id="userProfissao" value="Desenvolvedor Web">
              </div>
              <div id="dados-texto">
                <label for="userCargo">Cargo:</label>
                <input readonly class="inputs-editaveis" id="userCargo" value="Desenvolvedor Sênior">
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Dados Complementares</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userCurso">Curso:</label>
                <input readonly class="inputs-editaveis" id="userCurso" value="">
              </div>
              <div id="dados-texto">
                <label for="userTurma">Turma:</label>
                <input readonly class="inputs-editaveis" id="userTurma" value="">
              </div>
              <div id="dados-texto">
                <label for="userDataFimCurso">Fim do Curso:</label>
                <input type="date" readonly class="inputs-editaveis" id="userDataFimCurso" value="">
              </div>
              <div id="dados-texto">
                <label for="userNotas">Observações:</label>
                <textarea readonly class="inputs-editaveis" id="userNotas" rows="3" value=""></textarea>
              </div>
            </div>
          </div>

          <div class="details-section">
            <h4>Endereço</h4>
            <div class="dados-texto-container">
              <div id="dados-texto">
                <label for="userEndResidencial">Endereço Residencial:</label>
                <input readonly class="inputs-editaveis" id="userEndResidencial" value="Rua das Acácias, 100, Bairro Jardim, Cidade - UF">
              </div>
              <div id="dados-texto">
                <label for="userEndComercial">Endereço Comercial:</label>
                <input readonly class="inputs-editaveis" id="userEndComercial" value="Av. Comercial, 500, Centro, Outra Cidade - UF">
              </div>
            </div>
          </div>
          <div class="modal-buttons">
            <button id="botao-bloquear" type="button" value="1">Bloquear usuário</button>
            <div id="edicao-usuario">
              <button id="botao-edicao" type="button" value="true">Editar dados</button>
              <button id="cancelar-edicao" type="button" onclick="editUserCancel()">Cancelar edição</button>
            </div>

            <button type="button" onclick="fecharModal()">Fechar</button>
          </div>

        </div>
      </div>

      <script>
        // Desabilitar modal de navegação IMEDIATAMENTE e PERMANENTEMENTE
        (function() {
            // Desabilitar completamente
            window.onbeforeunload = null;
            delete window.onbeforeunload;

            // Sobrescrever a propriedade
            Object.defineProperty(window, 'onbeforeunload', {
                set: function() { return null; },
                get: function() { return null; },
                configurable: false
            });

            // Capturar qualquer tentativa de beforeunload
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = undefined;
                return undefined;
            }, true);
        })();

        // Tornar URLBASE disponível para o JavaScript
        const URLBASE = "<?php echo $URLBASE ?>";
        console.log('URLBASE definida na página:', URLBASE);

        // Função para verificar se o botão funciona
        function testarBotao() {
            console.log('🎯 === BOTÃO TESTE CLICADO ===');
            console.log('URLBASE disponível:', URLBASE);
            console.log('Tentando chamar alternarEdicao...');

            if (window.gerenciadorUsuarios) {
                console.log('✅ GerenciadorUsuarios existe, chamando alternarEdicao...');
                window.gerenciadorUsuarios.alternarEdicao();
            } else {
                console.error('❌ GerenciadorUsuarios não encontrado!');
                alert('Erro: Sistema não inicializado');
            }
        }


        // Função global abrirTab para ser chamada pelo HTML
        function abrirTab(event) {
            console.log('Abrindo tab:', event.target.textContent.trim());
            if (window.gerenciadorUsuarios) {
                window.gerenciadorUsuarios.abrirTab(event);
            }
        }

        // Função global para fechar modal
        function fecharModal() {
            if (window.gerenciadorUsuarios) {
                window.gerenciadorUsuarios.fecharModal();
            }
        }

        // Função global para editar usuário (cancelar)
        function editUserCancel() {
            if (window.gerenciadorUsuarios) {
                window.gerenciadorUsuarios.alternarEdicao();
            }
        }

      </script>
      <script src="<?php echo $URLBASE ?>/public/js/admin/usuarios-cadastrados-v2.js"></script>
  </main>
  <!-- footer  -->
  <?php
  include "../../../public/components/admin/footer/footer-admin.php";
  ?>

</body>

</html>
