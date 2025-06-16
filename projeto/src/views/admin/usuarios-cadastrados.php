<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Usuários cadastrados</title>
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
  <main>

    <title>Gerenciamento de Usuários</title>
    <div class="container-main">
      <div class="botoesFiltro">
      <button id= "regularesBotao" class="tab-link active" onclick="openTab(event, 'regulares')">Regulares</button>
      <button id="bloqueadosBotao" class="tab-link" onclick="openTab(event, 'bloqueados')">Bloqueados</button>
    </div>

    <div class="container">

      <h2>Usuários Cadastrados</h2>
      <label for="search"><label for="">Pesquisar:</label for=""></label>
      <input readonly type="text" id="search" placeholder="Pesquisar usuário..." onkeyup="filterTable()">
    
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
        <div class="dados-container">
          <img src="<?php echo $URLBASE?>/public/assets/img/NullUser.jpg" alt="" id="userImg">
          <div class="dados">
            <div id= "dados-texto"><label for="">Nome:</label for=""> <input readonly id="userName"></input></div>
            <div id= "dados-texto"><label for="">Nome social:</label for=""> <input readonly id="userNameSocial"></input></div>
            <div id= "dados-texto"><label for="">Nascimento:</label for=""> <input type="date" readonly id="userNascimento"></input></div>
            <div id= "dados-texto"><label for="">Sexo:</label for=""> <select disabled id="userSexo">
              <option value="masculino">Masculino</option>
              <option value="feminino">Feminino</option>
              <option value="outro">Outro</option>
            </select></div>
            <div id= "dados-texto"><label for="">CPF:</label for=""> <input readonly id="userCPF"></input></div>
          </div>
        </div>
        
       <div class="dados2">
          <div id= "dados-texto"><label for="">Matrícula:</label> <input readonly id="userRegistration"></input></div>
          <div id= "dados-texto"><label for="">Unidade:</label> <input readonly id="userUnidade"></input></div>
          <div id= "dados-texto"><label for="">Telefone:</label> <input readonly id="userTelefone"></input></div>
          <div id= "dados-texto"><label for="">Situação:</label> <input readonly id="userStatus"></input></div>
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