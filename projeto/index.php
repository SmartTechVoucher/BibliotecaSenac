<?php
// tela-inicial.php
require_once __DIR__ . '/config/constantes.php';

// Segurança: configurar cookie de sessão antes de iniciar
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);

// Evita warnings se já iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/src/model/usuario/livro-model.php';
$model = new LivroModel();
$livros = $model->getLivrosMock();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Biblioteca SENAC HUB ACADEMY!</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/tela-inicial.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/card2.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js" defer></script>
</head>
<body>

<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            mostrarToast("<?php echo addslashes($_SESSION['toast']['mensagem']); ?>", "<?php echo $_SESSION['toast']['tipo']; ?>");
        });
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<div class="conteiner">
    <div class="cabecalho">
        <div class="cbleft">
            <img class="icsenac" src="<?php echo $URLBASE ?>/public/assets/icons/SenacIcon 1.png" alt="Icone Hub academy">
        </div>

        <div class="cbmenu-icon">
            <i id="menu-toggle" class="fas fa-bars"></i>
        </div>

        <div class="cbquite" id="menu-links">
            <nav>
                <ul class="navbar">
                    <li><a href="<?php echo $URLBASE ?>/index.php">Início</a></li>
                    <li><a href="<?php echo $URLBASE ?>/src/views/usuario/filtro-livros.php">Livros</a></li>
                    <li><a href="https://api.whatsapp.com/send?phone=5567999492638">Contato</a></li>
                    <li><a href="<?php echo $URLBASE ?>/src/views/usuario/login.php">Logar</a></li>
                </ul>

                <div class="entrar-mobile">
                    <?php if (!empty($_SESSION['usuario_id'])): ?>
                        <div class="perfil-logado" onclick="toggleMenu(event)">
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                            <span class="nome-usuario">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></span>
                            <div class="menu-dropdown" id="menuPerfil">
                                <a href="<?php echo $URLBASE ?>/src/views/usuario/minha-conta-usuario.php">
                                    <img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png" alt=""> Meu Perfil
                                </a>
                                <a href="<?php echo $URLBASE ?>/router/router.php?acao=logout">
                                    <img src="<?php echo $URLBASE ?>/public/assets/icons/sair.png" alt=""> Sair
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button onclick="window.location.href='<?php echo $URLBASE ?>/src/views/usuario/login.php'" class="button-entrar">
                            <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg"><g>
                                <path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path>
                            </g></svg>
                            <span>Entrar</span>
                        </button>
                    <?php endif; ?>
                </div>
            </nav>
        </div>

        <div class="cbright" id="botao-entrar">
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <div class="perfil-logado" onclick="toggleMenu(event)">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/Icon perfil.png" alt="" class="icone-perfil">
                    <span class="nome-usuario">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></span>
                    <div class="menu-dropdown" id="menuPerfil">
                        <a href="<?php echo $URLBASE ?>/src/views/usuario/minha-conta-usuario.php"><img src="<?php echo $URLBASE ?>/public/assets/icons/Perfil2.png" alt="" class="perfil-header-inicial"> Meu Perfil</a>
                        <a href="<?php echo $URLBASE ?>/router/router.php?acao=logout"><img src="<?php echo $URLBASE ?>/public/assets/icons/sair.png" alt="">Sair</a>
                    </div>
                </div>
            <?php else: ?>
                <button onclick="window.location.href='<?php echo $URLBASE ?>/src/views/usuario/login.php'" class="button-entrar">
                    <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg"><g>
                        <path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path>
                    </g></svg>
                    <span>Entrar</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Resto do conteúdo (banner, pesquisa e listagem de livros) -->
    <div class="geralinfo">
        <div class="info">
            <img src="<?php echo $URLBASE ?>/public/assets/icons/fotoSenac 1.png" alt="Foto do Senac" class="senacFoto">
            <div class="letreiro">
                <div class="letras">
                    <h1 class="letras1">Bem-vindo a Biblioteca</h1>
                    <h1 class="letras2">SENAC HUB ACADEMY.</h1>
                </div>
                <p class="frase">"O ensino do futuro do mundo: pessoas inovando pela transformação do Brasil"</p>
            </div>
        </div>

        <form class="barrapesquisa" onsubmit="return false;">
            <input type="text" class="pesquisa" placeholder="Pesquise por um livro" id="campo-input" autocomplete="off">
            <button type="button" class="botaops" id="lupaId" onclick="focusInput()" tabindex="0"><img src="<?php echo $URLBASE ?>/public/assets/icons/lupa.svg" alt="Buscar"></button>
            <div class="listagem"><ul></ul></div>
        </form>
    </div>

    <!-- Gêneros e prateleiras (render com $livros) -->
    <div class="generos-livros">
        <h1 class="gen-title">Gêneros de Livros</h1>
        <div class="gen">
            <!-- exemplo de cards -->
            <div class="gencard">
                <div class="icon_livros"><img src="<?php echo $URLBASE ?>/public/assets/icons/tecnologia.svg" alt=""></div>
                <h2 class="gentitle">Tecnologia</h2>
            </div>
            <!-- ... outros gêneros ... -->
        </div>
    </div>

    <div class="container-estante">
        <div class="sup"><h1 class="title">Livros</h1></div>
        <div class="estante">
            <div class="livros">
                <div class="primeiraFileira">
                    <?php for ($i = 0; $i < min(4, count($livros)); $i++): $livro = $livros[$i]; ?>
                        <div class="livroEstante1">
                            <?php include __DIR__ . "/public/components/usuario/card/card2.php"; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="prateleira"></div>
                <div class="segundaFileira">
                    <?php for ($i = 4; $i < min(8, count($livros)); $i++): $livro = $livros[$i]; ?>
                        <div class="livroEstante1">
                            <?php include __DIR__ . "/public/components/usuario/card/card2.php"; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include(__DIR__ . '/public/components/usuario/footer/footer.php'); ?>
</div>

<script src="<?php echo $URLBASE ?>/public/js/usuario/tela-inicial.js"></script>
<script>
function toggleMenu(event) {
    event.stopPropagation();
    const menu = event.currentTarget.querySelector(".menu-dropdown");
    const isVisible = menu && menu.style.display === "block";
    document.querySelectorAll(".menu-dropdown").forEach(m => m.style.display = "none");
    if (menu) menu.style.display = isVisible ? "none" : "block";
}

document.addEventListener("click", () => {
    document.querySelectorAll(".menu-dropdown").forEach(m => m.style.display = "none");
});
</script>
</body>
</html>
