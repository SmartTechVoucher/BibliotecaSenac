<?php
require "../../../config/constantes.php"


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabecalho</title>
    <link rel="stylesheet" href="<?php echo $URLBASE?>/public/css/style.css">
</head>
<body>

    <!-- CABEÇALHO CABEÇALHO CABEÇALHO CABEÇALHO CABEÇALHO-->
    <header>

        <!-- JUNTO COM O JS, AQUI É PARA APARECER A NAV BAR QUANDO PASSAR O MOUSE EM CIMA E SOME QUANDO TIRA O MOUSE -->
        <div class="menu-icon" onmouseover="toggleMenu(true)" onmouseleave="toggleMenu(false)">
            <img src="../../../public/assets/icons/Menu adm.png" alt="Menu">
        </div>

        <!-- ICONE DO SENAC, ESSE DO LADO DE ONDE FICA A NAV BAR -->
        <div class="iconeSenac">
            <img id="icone_top" src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo">
        </div>

        <!-- NAVBAR QUE APARECE "OS CAMINHOS" QUE O USUARIO PODE IR -->
        <nav id="nav-menu" onmouseover="toggleMenu(true)" onmouseleave="toggleMenu(false)">
            <ul>
                <li><a href="../../../index.php">🏠 Início</a></li>
                <li><a href="livros.php">🔍 Pesquisa</a></li>
                <li><a href="contato.php">📞 Contato</a></li>
            </ul>
        </nav>

        <!-- NOME QUE FICA NO MEIO DO CABEÇALHO -->
        <div class="titulo"> 
            <h2>Biblioteca</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <!-- ÍCONE DO PERFIL DE USUÁRIO -->
        <div class="iconepessoa">
            <img src="../../../public/assets/icons/Icon perfil.png" alt="Ícone de pessoa">
            <p>Bem-vindo, Gabriel!</p>
        </div>  

    </header>

    <div class="linhaazul"></div>


    <!-- ÍCONES DOS QUADRADOS SUPERIORES DIREITO E ESQUERDO -->

    <div class="iconesSuperiores">

        <img src="<?php echo $URLBASE?>/public/assets/img/Superior esquerdo.svg" alt="Superior esquerdo" class="esquerdo">
        <img src="<?php echo $URLBASE?>/public/assets/icons/Superior esquerdo.png" alt="Superior direito" class="direito">
    
    </div>
   
    <!-- ARQUIVO JS PARA EXPORTAR -->

    <script src="<?php echo $URLBASE?>/public/js/header.js"></script>

</body>
</html>