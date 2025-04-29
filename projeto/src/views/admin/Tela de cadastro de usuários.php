<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro de usuários</title>
    <link rel="stylesheet" href="../../../public/css/Tela de cadastro de usuários.css">

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
    
 <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->   
    <div class="conteiner">     
        
        <div class="menuAdm">
            <img src="../../../public/assets/icons/Menu adm.png" alt="" class="hamburguer">
            <img src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo" class="logoSenacHub">
        </div>
            
        <div class="titulo"> 
            <h2>Biblioteca Senac</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <div class="icone"><img src="../../../public/assets/icons/Icon perfil.png" alt="Ícone de pessoa" id="iconeComandante"> </div>    

        <div class="minhaConta">
            <p>Minha conta</p>
            <a href="./Tela de login/view/login/index.html">Sair</a>
        </div>     
  
    </div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->   

     <div class="conteiner3">

        <div class="superior">
            <img src="../../../public/assets/icons/Superior esquerdo.png" alt="Superior esquerdo" class="esquerdo">
        </div>

        <div class="superior">
            <img src="../../../public/assets/icons/Superior direito.png" alt="Superior direito" class="direito">
        </div>

    </div>

    <form class="quadradoCinza">       

        <div class="input">
            <img src="../../../public/assets/icons/Identidade.png" alt="">
            <input type="text" class="nome" placeholder="Nome completo">    
        </div>
       
        <div class="input" id="nomeSocial1">
            <input type="text" class="nomeSocial" placeholder="Nome social">
        </div>

        <div class="opcional"><p>Opcional</p></div>
        
        <div class="input">
            <img src="../../../public/assets/icons/Arroba.png" alt="arroba">
            <input type="email" class="email" placeholder="Email">
        </div>
        
        <div class="input">
            <img src="../../../public/assets/icons/Ícone_Matrícula.png" alt="matrícula">
            <input type="text" class="matricula" placeholder="Matrícula">
        </div>
        
        <div class="input">
            <img src="../../../public/assets/icons/cpf.png" alt="">
            <input type="number" class="cpf" placeholder="CPF*" required min="11" max="11">
        </div>
        
        <div class="input">
            <img src="../../../public/assets/icons/Cadeado.png" alt="">
            <input type="password" class="senha" placeholder="Senha">
        </div>

        <div class="div">
            <button class="botao" type="submit">Cadastrar</button>
        </div>
        
    </form>

    <div class="inferior">

        <div class="direito">
            <img src="../../../public/assets/icons/Inferior direito.png" alt="">
        </div>     

    </div>
            
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    <div class="rodape">

        <div class="logoFecomercio"><img src="../../../public/assets/icons/Fecomercio.png" alt="" class="Logo" ></div>
        
        <div><img src="../../../public/assets/icons/Livro.png" alt="" class="Libro">
            <div class="copyright">Senac MS Copyright © <br></div>
            <div class="todos"> 2024. Todos os Direitos Reservados</div>   
        </div>
        
    </div>

     <div id="menu" class="menu">

        <div><a href="./Tela de cadastro de livros.php">Cadastrar livros</a></div>
        <div><a href="#">Relatórios</a></div>
        <div>Empréstimos</div>
        <div>Usuários</div>
        <div><a href="#">Estoque de livros</a></div>
        <div>Renovações</div>
        <hr>
        <div><a href="#">Logout</a></div>

    </div>

    <script src="../../../public/js/Tela de cadastro de usuários.js"></script>

</body>
</html>