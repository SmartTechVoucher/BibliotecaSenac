<?php
    require "../../../config/constantes.php"
?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de empréstimos</title>
        <link rel="stylesheet" href="/BibliotecaSenac/projeto/public/css/admin/historico-emprestimo.css">
    </head>

 <body>
    <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->   
    <div class="conteiner">      
            
        <div class="menuAdm">
            <img src="<?php echo $URLBASE?>/public/assets/icons/Menu adm.png" alt="" class="hamburguer">
            <img src="<?php echo $URLBASE?>/public/assets/img/LogoHub_academy.png" alt="Imagem do logo" class="logoSenacHub">
        </div>

        <div class="titulo"> 
            <h2>Biblioteca Senac</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <div class="icone"><img src="<?php echo $URLBASE?>/public/assets/icons/Icon perfil.png" alt="Ícone de pessoa" id="iconeComandante"> </div>    

        <div class="minhaConta">
            <p>Minha conta</p>
            <a href="../usuario/login.php">Sair</a>
        </div>

    </div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->   

    <div class="conteiner3">      
        <img src="<?php echo $URLBASE?>/public/assets/icons/Superior esquerdo.png" alt="" class="esquerdo">
        <img src="<?php echo $URLBASE?>/public/assets/icons/Superior direito.png" alt="" class="direito">
    </div>

    <div class="main-container">
        <h2>Histórico de Empréstimos</h2>   
                
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Exemplar</th>
                        <th>Leitor</th>
                        <th>Data</th>
                        <th>Prazo</th>
                        <th>Devolução</th>
                    </tr>
                <tbody id="userTable"></tbody>
                </thead>
            </table>
    </div>
    

 

     
     
    <div id="menu" class="menu">

        <div><a href="./Tela de cadastro de usuários.php">Cadastrar usuários</a></div>
        <div><a href="./Tela de cadastro de livros.php">Cadastrar livros</a></div>
        <div><a href="Por fazer">Usuários cadastrados</a></div>
        <div><a href="./Tela de relatórios.php">Relatórios</a></div>
        <div><a href="./Tela inicial do adm.php">Tela inicial</a></div>
        <div><a href="./Tela dos livros cadastrados.php">Estoque de livros</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>
    <?php   
    include "../../../public/components/footer/footer.php";
    ?>

    <script src="../../../public/js/admin/historico-emprestimo.js"></script>

 </body>
</html>



   