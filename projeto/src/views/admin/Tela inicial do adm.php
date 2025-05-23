<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela inicial do adm</title>
    <link rel="stylesheet" href="../../../public/css/Tela inicial do adm.css">

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
        <p><a href="./Tela de login/view/login/index.html">Sair</a></p>
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
            <img src="../../../public/assets/icons/Vector (4).png" alt="Livro" class="Livro">
            <img src="../../../public/assets/icons/Vector (5).png" alt="Sinal de adição" class="adicao">
            <p class="cadastro">Cadastrar <br> livros</p>
        </div>

        <div class="caixa">
            <img src="../../../public/assets/icons/Vector (3).png" alt="Usuário" class="user">
            <p class="cadastroDeUsuario">Cadastrar usuários</p>
        </div>
        
        <div class="caixa">
            <img src="../../../public/assets/icons/Vector (1).png" alt="Livro" class="vectorRelatorio">
            <p class="relatorios">Relatórios</p>
        </div>
        
        <div class="caixa">
            <img src="../../../public/assets/icons/Vector (2).png" alt="Livro" class="emprestimo">
            <p class="Emprestimo">Empréstimos</p>
        </div>
        
        <div class="caixa">
            <img src="../../../public/assets/icons/Vector.png" alt="Livro" class="usuario">
            <p class="Usuario">Usuários</p>
        </div>
        
        <div class="caixa">
            <img src="../../../public/assets/icons/Icon Book.png" alt="Livro" class="estoque">
            <p class="Estoque">Estoque de livros</p>
        </div>
        
        <div class="caixa">
            <img src="../../../public/assets/icons/Vector (1).png" alt="Livro" class="vectorRenova">
            <p class="renovacoes">Renovações</p>
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
        <div><a href="./Tela de cadastro de usuários.php">Cadastrar usuários</a></div>
        <div><a href="./Tela de relatórios.php">Relatórios</a></div>
        <div><a href="./emprestimo.php">Empréstimos</a></div>
        <div>Usuários</div>
        <div>Estoque de livros</div>
        <div>Renovações</div>
        <hr>
        <div><a href="./Tela de login/view/login/index.html">Logout</a></div>
    </div>

    <div class="overlayCadastro">
        <p><a href="./Tela de cadastro de livros/biblioteca.html" class="item">Cadastrar livro</a></p>
        <hr>
        
        <p><a href="./Tela dos livros cadastrados/biblioteca.html" class="item">Ver livros cadastrados</a></p>
        <hr>
        
        <p><a href="./Tela dos livros cadastrados/biblioteca.html" class="item">Excluir livro</a></p>
        <hr>
        
        <p class="item">Editar informações de um livro</p>
    </div>

    <!--Relatórios-->  <!--Relatórios-->  <!--Relatórios-->
    <div id="overlayRelatorio">
        <p class="relatorio">Ver livros emprestados</p>
        <hr>
        <p class="relatorio">Ver livros não devolvidos</p>
    </div>
    <!--Relatórios-->  <!--Relatórios-->  <!--Relatórios-->

    <script src="../../../public/js/Tela inicial do adm.js"></script>

</body>
</html>