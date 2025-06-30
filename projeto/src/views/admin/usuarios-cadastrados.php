<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    include "../../../public/components/header/header.php";
  ?>
  
  <main class="tabela">

    <title>Gerenciamento de Usuários</title>
    <div class="container-main">
      <div class="botoesFiltro">
      <button id= "regularesBotao" class="tab-link active" onclick="openTab(event, 'regulares')">Regulares</button>
      <button id="bloqueadosBotao" class="tab-link" onclick="openTab(event, 'bloqueados')">Bloqueados</button>
    </div>

    <div class="container">

      <h2>Usuários Cadastrados</h2>
      <label for="search"><strong>Pesquisar:</strong></label>
      <input type="text" id="search" placeholder="Pesquisar usuário..." onkeyup="filterTable()">
    
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
              <th>Ação</th>
            </tr>
          </thead>
          <tbody id="userTable"></tbody>
        </table>
      </div>
    
      <div id="bloqueados" class="tab-content">
        <table>
          <thead>

          <tbody id="blockedTable"></tbody>
        </table>
      </div>
    </div>
       
    <div id="userModal" class="modal"> <!-- janela que contém dados do usuario -->
      <div class="modal-content">
        <h3>Dados do Usuário</h3>
        <div class="dados1">
          <img src="<?php echo $URLBASE?>/public/assets/img/NullUser.jpg" alt="" id="userImg">
          <div class="dados1.5">
            <p><strong>Nome:</strong> <span id="userName"></span></p>
            <p><strong>Nome social:</strong> <span id="userNameSocial"></span></p>
            <p><strong>Nascimento:</strong> <span id="userNascimento"></span></p>
            <p><strong>Sexo:</strong> <span id="userSexo"></span></p>
          </div>
        </div>
                
        <div class="dados2">
          <p><strong>CPF:</strong> <span id="userCPF"></span></p>
          <p><strong>Matrícula:</strong> <span id="userRegistration"></span></p>
          <p><strong>Unidade:</strong> <span id="userUnidade"></span></p>
          <p><strong>Telefone:</strong> <span id="userTelefone"></span></p>
          <p><strong>Situação:</strong> <span id="userStatus"></span></p>
        </div>
        
        <button onclick="blockUser()">Bloquear</button>
        <button onclick="closeModal()">Fechar</button>
      </div>
    </div>
      
    <script src = "/BibliotecaSenac/projeto/public/js/admin/usuarios-cadastrados.js"></script>
  </main>
  <!-- footer  -->
  <?php   
    include "../../../public/components/footer/footer.php";
  ?>
</body>
</html>