<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela inicial do adm</title>
    <?php
    require_once "../../../config/constantes.php";
  ?>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
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
    
 <?php   
    include "../../../public/components/admin/header/header-admin.php";
  ?>

    <p class="adm">Administrativo</p>

    <div class="conteiner2">

        <div class="caixa">    
            <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeCadastroDeLivros.php"><img src="../../../public/assets/icons/Cadastrar livros.png" alt="" class="cadastrarLivro"></a>
        </div>

        <div class="caixa">
            <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeCadastroDeUsuarios.php"><img src="../../../public/assets/icons/Cadastrar usuários.png" alt="" class="cadastrarUsuarios"></a>
        </div>
        
        <div class="caixa">
            <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeRelatorios.php"><img src="../../../public/assets/icons/Relatorio.png" alt="" class="relatorios"></a>
        </div>
        
        <div class="caixa">
            <a href="<?php echo $URLBASE ?>/src/views/admin/emprestimo.php"><img src="../../../public/assets/icons/Empréstimo (1).png" alt="Livro" class="emprestimo"></a>
        </div>
        
        <div class="caixa">
            <a href="<?php echo $URLBASE ?>/src/views/admin/usuarios-cadastrados.php"><img src="../../../public/assets/icons/Usuários.png" alt="Livro" class="usuario"></a>
        </div>
        
        <div class="caixa">
            <a href="<?php echo $URLBASE ?>/src/views/admin/telaDosLivrosCadastrados.php"><img src="../../../public/assets/icons/List Books.png" alt="Livro" class="estoque"></a>
        </div>
        
    </div>

   
            
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    
   <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/telaInicialDoAdm.js"></script>

</body>
</html>