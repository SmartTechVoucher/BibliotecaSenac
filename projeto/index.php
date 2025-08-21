<?php

session_start();



require __DIR__ . '/config/constantes.php';


include_once __DIR__ . '/src/model/usuario/livro-model.php';

// Agora instancia a classe
$model = new LivroModel();
$livros = $model->getLivrosMock();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo á Biblioteca SENAC HUB ACADEMY!</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/tela-inicial.css">
    <link rel="stylesheet" href="./public/css/components/usuario/card2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>
</head>

<body>

    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                mostrarToast("<?php echo addslashes($_SESSION['toast']['mensagem']); ?>", "<?php echo $_SESSION['toast']['tipo']; ?>");
            });
        </script>
    <?php unset($_SESSION['toast']);
    endif; ?>

    <div class="conteiner">
        <div class="cabecalho">
            <div class="cbleft">
                <button class="cbmenu-icon" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>

                <img class="icsenac" src="../projeto/public/assets/icons/SenacIcon 1.png" alt="Icone Hub academy">
            </div>

            <div id="menu-links">
                <nav>

                    <ul class="navbar-desktop-top">

                        <li class="menu-li"><img src="./public/assets/icons/home.png" alt=""><a href="./index.php">Início</a></li>
                        <li class="menu-li"><img src="./public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>
                        <li class="menu-li"><img src="./public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>

                    </ul>



                    <div id="menu-lateral" class="menu-lateral">
                        <ul class="navbar-desktop">

                            <li class="menu-li"><img src="./public/assets/icons/home.png" alt=""><a href="./index.php">Início</a></li>
                            <li class="menu-li"><img src="./public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>
                            <li class="menu-li"><img src="./public/assets/icons/livro-menu.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Livros</a></li>

                        </ul>

                        <div class="menu-sanduiche">
                            <ul class="navbar-desktop">
                                <!-- <li class="menu-li"><img src="./public/assets/icons/perfil.png" alt=""><a href="../projeto/src/views/usuario/minha-conta-usuario.php">Minha Conta</a></li> -->
                                <li class="menu-li"><img src="./public/assets/icons/PesquisaIcon.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Pesquisar Livros</a></li>
                                <li class="menu-li"><img src="./public/assets/icons/PesquisaIcon.png" alt=""><a href="../projeto/src/views/usuario/filtro-livros.php">Pesquisar Livros</a></li>

                            </ul>
                        </div>
                    </div>


                    <div class="entrar-mobile">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <div class="perfil-logado" onclick="toggleMenu(event)">
                                <img src="../projeto/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                                <span class="nome-usuario">Bem-vindo, <?php echo $_SESSION['usuario']['nome'] ?? 'Usuário'; ?></span>
                                <div class="menu-dropdown" id="menuPerfil">
                                    <a href="../projeto/src/views/usuario/minha-conta-usuario.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png"> Meu Perfil</a>
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
                        <img src="../projeto/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                        <span class="nome-usuario">Bem-vindo, <?php echo $_SESSION['usuario']['nome'] ?? 'Usuário'; ?></span>
                        <div class="menu-dropdown" id="menuPerfil">
                            <a href="../projeto/src/views/usuario/minha-conta-usuario.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png" alt="" class="perfil-header-inicial"> Meu Perfil</a>
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


        <div class="geralinfo">
            <div class="info">

                <img src="../projeto/public/assets/icons/fotoSenac 1.png" alt="Foto do Senac" class="senacFoto">
                <div class="letreiro">
                    <div class="letras">
                        <h1 class="letras1">Bem-vindo a Biblioteca</h1>
                        <h1 class="letras2">SENAC HUB ACADEMY.</h1>
                    </div>
                    <p class="frase">"O ensino do futuro do mundo: pessoas inovando pela <br>transformação do Brasil"</p>

                </div>
            </div>



            <form class="barrapesquisa">
                <input type="text" class="pesquisa" placeholder="Pesquise por um livro" id="campo-input" autocomplete="off"> <button type="button" class="botaops" id="lupaId" onclick="focusInput()" tabindex="0"><img src="../projeto/public/assets/icons/lupa.svg" alt="Buscar"></button>
                <div class="listagem">
                    <ul></ul>
                </div>
            </form>
        </div>


        <div class="generos-livros">
            <h1 class="gen-title">Gêneros de Livros</h1>
            <div class="gen">
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="./public/assets/icons/tecnologia.svg" alt="" class="genicon1">
                    </div>
                    <h2 class="gentitle">Tecnologia</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="../projeto/public/assets/icons/saude.svg" alt="" class="genicon2">
                    </div>
                    <h2 class="gentitle">Saúde</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="../projeto/public/assets/icons/gestao.svg" alt="" class="genicon3">
                    </div>
                    <h2 class="gentitle">Gestão</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="../projeto/public/assets/icons/Designer-teste.png" alt="" class="genicon4">
                    </div>
                    <h2 class="gentitle">Design</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="../projeto/public/assets/icons/Livros-teste.png" alt="" class="genicon5">
                    </div>
                    <h2 class="gentitle">Educação</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="../projeto/public/assets/icons/Livros-teste.png" alt="" class="genicon5">
                    </div>
                    <h2 class="gentitle">Educação</h2>
                </div>
            </div>
        </div>


        <div class="container-estante">

            <div class="sup">
                <h1 class="title">Livros</h1>
            </div>
            <div class="estante">

                <div class="livros">
                    <div class="primeiraFileira">
                        <?php
                        // Renderiza do índice 3 até 6 (4 cards)
                        for ($i = 3; $i < 7 && $i < count($livros); $i++):
                            $livro = $livros[$i];
                        ?>
                            <div class="livroEstante1">
                                <?php include "./public/components/usuario/card/card2.php"; ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="prateleira"></div>

                    <div class="segundaFileira">
                        <?php
                        // Renderiza do índice 3 até 6 (4 cards)
                        for ($i = 3; $i < 8 && $i < count($livros); $i++):
                            $livro = $livros[$i];
                        ?>
                            <div class="livroEstante1">
                                <?php include "./public/components/usuario/card/card2.php"; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>

        <div id="overlay" class="overlay"></div>





        <?php include(__DIR__ . '/public/components/usuario/footer/footer.php'); ?>

    </div>


    <script src="./public/js/usuario/tela-inicial.js" defer></script>

</body>

</html>