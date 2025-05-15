<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/BibliotecaSenac/projeto/public/css/usuarios_cadastrados.css" />
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
  
    <div class="conteiner">     
        
        <div class="menuAdm">
            <img src="../Icones/Menu adm.png" alt="" class="hamburguer">
            <img src="../Icones/Logo senac hub.png" alt="Imagem do logo" class="logoSenacHub">
        </div>
            
        <div class="titulo"> 
            <h2>Biblioteca Senac</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <div class="icone">
            <img src="../Icones/Icon perfil.png" alt="Ícone de pessoa">
        </div>    
  
    </div>
  

    <div class="conteiner3">

        <div class="superior">
            <img src="./Ícones/Superior esquerdo.png" alt="Superior esquerdo" class="esquerdo">
        </div>

        <div class="superior">
            <img src="./Ícones/Superior direito.png" alt="Superior direito" class="direito">
        </div>

    </div>
  
  <title>Gerenciamento de Usuários</title>
</head>

<body>
  <main>

  <div class="botoesFiltro">
    <button id= "regularesBotao" class="tab-link active" onclick="openTab(event, 'regulares')">Regulares</button>
    <button id="bloqueadosBotao" class="tab-link" onclick="openTab(event, 'bloqueados')">Bloqueados</button>
  </div>

  <div class="container">
    
    <h2>Usuários Cadastrados</h2>
  
    <label for="search"><strong>Pesquisar:</strong></label>
    <input type="text" id="search" placeholder="Pesquisar usuário..." onkeyup="filterTable()">
  
  
    <div id="regulares" class="tab-content" style="display: block;">
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Nº de Matrícula</th>
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

</main>
    
    
    
    <div id="userModal" class="modal">
      <div class="modal-content">
        <h3>Detalhes do Usuário</h3>
        <p><strong>Nome:</strong> <span id="userName"></span></p>
        <p><strong>Matrícula:</strong> <span id="userRegistration"></span></p>
        <p><strong>Situação:</strong> <span id="userStatus"></span></p>
        <button onclick="blockUser()">Bloquear</button>
        <button onclick="closeModal()">Fechar</button>
      </div>
    </div>
    
  <script src = "/BibliotecaSenac/projeto/public/js/usuarios_cadastrados.js"></script>
</body>
</html>