<?php
require __DIR__ . '/../../../../config/constantes.php';
?>

<button class="cbmenu-icon" id="menu-toggle">
    <i class="fas fa-bars"></i>
</button>

<div class="cabecalho">
    <div class="cbleft">


        <img class="icsenac" src="<?php echo $URLBASE ?>/public/assets/icons/SenacIcon 1.png" alt="Icone Hub academy">
    </div>

    <div id="menu-links">
        <nav>

            <ul class="navbar-desktop-top">

                <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/home.png" alt=""><a href="<?php echo $URLBASE ?>">Início</a></li>
                <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>
                <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>

            </ul>


            <div class="entrar-mobile">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <div class="perfil-logado" onclick="toggleMenu(event)">
                        <img src="../projeto/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                        <span class="nome-usuario">Bem-vindo, <?php echo $_SESSION['usuario']['nome'] ?? 'Usuário'; ?></span>
                        <div class="menu-dropdown" id="menuPerfil">
                            <a href="<?php echo $URLBASE ?>/projeto/src/views/usuario/minha-conta-usuario.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png"> Meu Perfil</a>
                            <a href="logout.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/sair.png" alt="">Sair</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button onclick="redirectToPage()" class="button-entrar">
                        <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path>
                            </g>
                        </svg>
                        <span>Entrar</span>
                    </button>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <div class="cbright" id="botao-entrar">
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="perfil-logado" onclick="toggleMenu(event)">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                <span class="nome-usuario">Bem-vindo, <?php echo $_SESSION['usuario']['nome'] ?? 'Usuário'; ?></span>
                <div class="menu-dropdown" id="menuPerfil">
                    <a href="<?php echo $URLBASE ?>/src/views/usuario/minha-conta-usuario.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png" alt="" class="perfil-header-inicial"> Meu Perfil</a>
                    <a href="logout.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/sair.png" alt="">Sair</a>
                </div>
            </div>
        <?php else: ?>
            <button onclick="redirectToPage()" class="button-entrar">
                <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path>
                    </g>
                </svg>
                <span>Entrar</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<div id="menu-lateral" class="menu-lateral">



    <div class="menu-sanduiche">

        <ul class="navbar-desktop">
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/home.png" alt=""><a href="<?php echo $URLBASE ?>/e">Início</a></li>

            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/livro-menu.png" alt=""><a href="">livre para usuar</a></li>
            <!-- CORRIGIR CAMINHO QUANDO TIVER UMA TELA ESPECIFICA PARA LIVROS -->
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/livro-menu.png" alt=""><a href="<?php echo $URLBASE ?>/src/views/usuario/filtro-livros.php">Livros</a></li>
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/perfil.png" alt=""><a href="<?php echo $URLBASE ?>/src/views/usuario/minha-conta-usuario.php">Meu Perfil</a></li>
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/PesquisaIcon.png" alt=""><a href="<?php echo $URLBASE ?>/src/views/usuario/filtro-livros.php">Pesquisar Livros</a></li>
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/ContatoIcon.png" alt=""><a href="https://ww3.ms.senac.br/">Contato</a></li>
            <li class="menu-li"><img src="<?php echo $URLBASE ?>/public/assets/icons/atividades.png" alt=""><a href="<?php echo $URLBASE ?>/src/views/usuario/minha-atividade.php">Minha Atividade</a></li>

        </ul>
    </div>
</div>

<div id="overlay" class="overlay"></div>