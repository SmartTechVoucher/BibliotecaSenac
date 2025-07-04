<?php
require "../../../config/constantes.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabecalho</title>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/header.css">
</head>
<body>

    <!-- CABEÇALHO -->
    <header>
        <!-- Menu hamburguer -->
        <div class="menu">
            <img src="../../../public/assets/icons/Menu adm.png" alt="Menu">
            <img id="icone_top" src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo">
        </div>

        <!-- Logo do Senac -->
        <!-- <div class="iconeSenac">
            <img id="icone_top" src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo">
        </div> -->

        <!-- Menu lateral -->
        <!-- <nav id="nav-menu" onmouseover="toggleMenu(true)" onmouseleave="toggleMenu(false)">
            <ul>
                <li><a href="../../../index.php">🏠 Início</a></li>
                <li><a href="<?php echo $URLBASE ?>/src/views/usuario/filtro-livros.php">🔍 Pesquisa</a></li>
                <li><a href="contato.php">📞 Contato</a></li>
            </ul>
        </nav> -->

        <!-- Título central -->
        <div class="titulo"> 
            <h2>Biblioteca</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <!-- Ícone e menu do perfil -->
        <div class="iconepessoa">
            <div class="menu-icon2">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icon perfil.png" alt="Ícone de pessoa">
            </div>
            
            <!-- <div class="perfil-container">
                <span>Bem-vindo, Gabriel!</span>
                <div id="nav-menu-perfil">
                    <ul>
                        <li><a href="#">👤 Meu Perfil</a></li>
                        <li><a href="<?php echo $URLBASE ?>/src/views/usuario/Perfil-usuario.php">📖 Área do Leitor</a></li>
                        <li><a href="#" onclick="confirmarSaida(event)">🚪 Sair</a></li>
                    </ul>
                </div>
            </div> -->
        </div>
    </header>

    <!-- Linha decorativa -->
    <div class="linhaazul"></div>

    <!-- Ícones de canto -->
    <div class="iconesSuperiores">
        <img src="<?php echo $URLBASE ?>/public/assets/icons/Superior esquerdo.png" alt="Superior esquerdo" class="esquerdo">
        <img src="<?php echo $URLBASE ?>/public/assets/icons/Superior direito.png" alt="Superior direito" class="direito">
    </div>

    <!-- Modal de confirmação -->
    <?php
        require_once __DIR__ . '../../../../public/components/modal/modal.php';
        echo renderModal('confirmModal', 'Confirmação', 'Você tem certeza que deseja sair?');
    ?> 

    <script>
        const baseUrl = '<?php echo $URLBASE; ?>';
    </script>
    <!-- JS -->
    <script src="<?php echo $URLBASE ?>/public/js/usuario/header.js"></script>
</body>
</html>
