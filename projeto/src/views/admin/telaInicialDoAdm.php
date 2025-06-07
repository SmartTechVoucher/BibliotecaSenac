<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela inicial do adm</title>
    <link rel="stylesheet" href="../../../public/css/admin/telaInicialAdm.css">

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
        <p><a href="../usuario/login.php">Sair</a></p>
    </div>   

</div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->   

    <div class="conteiner3">

        <img src="../../../public/assets/icons/Superior esquerdo.png" alt="" class="esquerdo">
        <img src="../../../public/assets/icons/Superior direito.png" alt="" class="direito">

    </div>

    <p class="adm">Administrativo</p>

    <div class="conteiner2">

        <div class="caixa">    
            <a href="./Tela de cadastro de livros.php"><img src="../../../public/assets/icons/Cadastrar livros.png" alt="" class="cadastrarLivro"></a>
        </div>

        <div class="caixa">
            <a href="./telaDeCadastroDeUsuários.php"><img src="../../../public/assets/icons/Cadastrar usuários.png" alt="" class="cadastrarUsuarios"></a>
        </div>
        
        <div class="caixa">
            <a href="./Tela de relatórios.php"><img src="../../../public/assets/icons/Relatorio.png" alt="" class="relatorios"></a>
        </div>
        
        <div class="caixa">
            <a href="./Histórico de empréstimos.php"><img src="../../../public/assets/icons/Empréstimo (1).png" alt="Livro" class="emprestimo"></a>
        </div>
        
        <div class="caixa">
            <a href="Por fazer"><img src="../../../public/assets/icons/Usuários.png" alt="Livro" class="usuario"></a>
        </div>
        
        <div class="caixa">
            <a href="./Tela dos livros cadastrados.php"><img src="../../../public/assets/icons/List Books.png" alt="Livro" class="estoque"></a>
        </div>

    </div>

    <div class="inferiorDireito">

        <img src="../../../public/assets/icons/Inferior direito.png" alt="" class="inferior">

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
        <div><a href="./telaDeCadastroDeUsuários.php">Cadastrar usuários</a></div>
        <div><a href="./Tela de relatórios.php">Relatórios</a></div>
        <div><a href="./Histórico de empréstimos.php">Empréstimos</a></div>
        <div><a href="Por fazer">Usuários cadastrados</a></div>
        <div><a href="./Tela dos livros cadastrados.php">Estoque de livros</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>
    </div>

    <script src="../../../public/js/admin/telaInicialDoAdm.js"></script>

</body>
</html>