
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
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/modal.css">
</head>
<body>

    <!-- CABEÇALHO -->
    <header>
        <!-- Menu hamburguer -->
        <div class="menu-icon" id="menu-icone" onclick="toggleMenu()">
            <img src="../../../public/assets/icons/MenuIconeHamburguer2.svg" alt="Menu">
        </div>

        <!-- Logo do Senac -->
        <div class="iconeSenac">
            <img id="icone_top" src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo">
        </div>

        <!-- Menu lateral -->
        <nav id="nav-menu">
            <ul>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/telaInicialDoAdm.php">
                        <!-- <img src="php echo $URLBASE /public/assets/icons/telaInicialDoAdm.png"  alt=""> -->
                        Início</a>
                </li>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeCadastroDeLivros.php">Cadastrar Livros</a>
                    </li>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/telaDosLivrosCadastrados.php">Lista de Livros Cadastrados</a>
                </li>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeCadastroDeUsuarios.php">Cadastrar Usuários</a>
                </li>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/usuarios-cadastrados.php">Lista de Usuários Cadastrados</a>
                </li>
                
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/telaDeCadastroDeLivros.php">Cadastrar Empréstimos</a>
                </li>
                <li>
                    <a href="<?php echo $URLBASE ?>/src/views/admin/historico-emprestimo.php">Histórico de Empréstimos</a>
                </li>

            </ul>
        </nav>

        <!-- Título central -->
        <div class="titulo"> 
            <h2>Biblioteca</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <!-- Ícone e menu do perfil -->
        <div class="iconepessoa"  id="icone-pessoa" onclick="toggleMenuPerfil()">
            <div class="menu-icon2">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icon perfil.png" alt="Ícone de pessoa">
            </div>
            
            <div class="perfil-container">
                <span>Bem-vindo, Gabriel!</span>
            </div>

            <div id="nav-menu-perfil">
                <ul>
                    <li>
                        <a href="#">
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png" alt="">
                            Perfil</a></li>
                
                    <li>
                        <a href="#" onclick="confirmarSaida(event)">
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/sair.png" alt="">
                            Sair</a></li>
                    </ul>
            </div>
        </div>
    </header>

    <!-- Linha decorativa -->
    <div class="linhaazul"></div>

    <!-- Ícones de canto -->
    <div class="iconesSuperiores">
        <img src="<?php echo $URLBASE ?>/public/assets/img/Superior esquerdo.svg" alt="Superior esquerdo" class="esquerdo">
        <img src="<?php echo $URLBASE ?>/public/assets/img/Superior direito.svg" alt="Superior direito" class="direito">
    </div>

    <!-- Modal de confirmação -->
    <?php
        require_once __DIR__ . '/../../../components/usuario/modal/modal.php';
        echo renderModal('confirmModal', 'Confirmação', 'Você tem certeza que deseja sair?');
    ?>

    <script>
        const baseUrl = '<?php echo $URLBASE; ?>';
    </script>
    <!-- JS -->
    <script src="<?php echo $URLBASE ?>/public/js/usuario/header.js"></script>
</body>
</html>