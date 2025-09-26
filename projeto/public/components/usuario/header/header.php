<?php
// header.php

// incluir constantes
require_once __DIR__ . '/../../../../config/constantes.php';

// garantir sessão (se ainda não foi iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Resolve a URL da imagem do usuário:
 * - se $foto é vazio -> retorna empty string
 * - se começa com "http" ou "/" -> retorna tal qual (URL absoluta/absoluta do servidor)
 * - caso contrário -> assume que é um filename e monta $URLBASE/public/uploads/usuarios/{filename}
 */
function resolverUrlFotoUsuario(?string $foto): string {
    global $URLBASE;

    if (empty($foto)) return '';

    $foto = trim($foto);

    // URL absoluta (http/https) ou caminho absoluto no server (/alguma/coisa)
    if (preg_match('#^https?://#i', $foto) || strpos($foto, '/') === 0) {
        return $foto;
    }

    // Caso padrão: filename salvo no banco -> pasta de uploads
    return rtrim($URLBASE, '/') . '/public/uploads/usuarios/' . rawurlencode($foto);
}

// Compatibilidade: se a página já setou essas variáveis, usa; senão busca na sessão
$usuarioLogado = isset($usuarioLogado) ? (bool)$usuarioLogado : (isset($_SESSION['usuario_id']));
$nomeUsuario   = $nomeUsuario ?? ($_SESSION['usuario_nome'] ?? '');
$fotoUsuarioRaw = $_SESSION['usuario_foto'] ?? $_SESSION['foto_perfil'] ?? $_SESSION['usuario_imagem'] ?? '';
$fotoUsuarioUrl = resolverUrlFotoUsuario($fotoUsuarioRaw);

// caminho ícone padrão
$iconePadrao = rtrim($URLBASE, '/') . '/public/assets/icons/Icon perfil.png';
?>

<button class="cbmenu-icon" id="menu-toggle">
    <i class="fas fa-bars"></i>
</button>

<div class="cabecalho">
    <div class="cbleft">
        <img class="icsenac" src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/SenacIcon 1.png" alt="Icone Hub academy">
    </div>

    <div id="menu-links">
        <nav>
            <ul class="navbar-desktop-top">
                <li class="menu-li">
                    <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/home.png" alt="">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/index.php">Início</a>
                </li>
                <li class="menu-li">
                    <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/livro-menu.png" alt="">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/filtro-livros.php">Livros</a>
                </li>
                <li class="menu-li">
                    <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/livro-menu.png" alt="">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/filtro-livros.php">Livros</a>
                </li>
            </ul>

            <div class="entrar-mobile">
                <?php if ($usuarioLogado): ?>
                    <div class="perfil-logado" onclick="toggleMenu(event)">
                        <?php if (!empty($fotoUsuarioUrl)): ?>
                            <img src="<?php echo htmlspecialchars($fotoUsuarioUrl); ?>" alt="Foto de <?php echo htmlspecialchars($nomeUsuario); ?>" class="icone-perfil">
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars($iconePadrao); ?>" alt="Ícone padrão" class="icone-perfil">
                        <?php endif; ?>

                        <span class="nome-usuario">Bem-vindo, <?php echo htmlspecialchars($nomeUsuario); ?></span>

                        <div class="menu-dropdown" id="menuPerfil">
                            <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/minha-conta-usuario.php">
                                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/Perfil2.png" alt=""> Meu Perfil
                            </a>
                            <a href="<?php echo htmlspecialchars($URLBASE); ?>/router.php?acao=logout">
                                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/sair.png" alt=""> Sair
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <button onclick="redirectToPage()" class="button-entrar">
                        <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 30" aria-hidden="true">
                            <g><path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path></g>
                        </svg>
                        <span>Entrar</span>
                    </button>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <div class="cbright" id="botao-entrar">
        <?php if ($usuarioLogado): ?>
            <div class="perfil-logado" onclick="toggleMenu(event)">
                <?php if (!empty($fotoUsuarioUrl)): ?>
                    <img src="<?php echo htmlspecialchars($fotoUsuarioUrl); ?>" alt="Foto de <?php echo htmlspecialchars($nomeUsuario); ?>" class="icone-perfil">
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars($iconePadrao); ?>" alt="Ícone padrão" class="icone-perfil">
                <?php endif; ?>

                </span class="nome-usuario">Olá, <?php echo htmlspecialchars($nomeUsuario); ?></span>

                <div class="menu-dropdown" id="menuPerfil">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/minha-conta-usuario.php">
                        <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/Perfil2.png" alt="" class="perfil-header-inicial"> Meu Perfil
                    </a>
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/router.php?acao=logout">
                        <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/sair.png" alt=""> Sair
                    </a>
                </div>
            </div>
        <?php else: ?>
            <button onclick="redirectToPage()" class="button-entrar">
                <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 30 30" aria-hidden="true">
                    <g><path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path></g>
                </svg>
                <span>Entrar</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<div id="menu-lateral" class="menu-lateral">
    <div class="perfil-lateral">
        <h2 class="hub">HUB ACADEMY</h2>
        <h2 class="biblioteca">Biblioteca</h2>
    </div>

    <div class="menu-sanduiche">
        <ul class="navbar-desktop">
            <li class="menu-li">
                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/home.png" alt="">
                <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/index.php">Início</a>
            </li>

            <?php if ($usuarioLogado): ?>
                <li class="menu-li">
                    <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/perfil.png" alt="">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/minha-conta-usuario.php">Meu Perfil</a>
                </li>
                <li class="menu-li">
                    <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/atividades.png" alt="">
                    <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/minha-atividade.php">Minha Atividade</a>
                </li>
            <?php endif; ?>

            <li class="menu-li">
                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/PesquisaIcon.png" alt="">
                <a href="<?php echo htmlspecialchars($URLBASE); ?>/src/views/usuario/filtro-livros.php">Pesquisar Livros</a>
            </li>
            <li class="menu-li">
                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/ContatoIcon.png" alt="">
                <a href="https://ww3.ms.senac.br/">Contato</a>
            </li>
        </ul>
    </div>

    <div class="perfil-lateral-2">
        <?php if ($usuarioLogado): ?>
            <a href="<?php echo htmlspecialchars($URLBASE); ?>/router.php?acao=logout" class="logout-link">
                <img src="<?php echo htmlspecialchars($URLBASE); ?>/public/assets/icons/sair.png" alt="Sair">
                <p>Sair</p>
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="overlay" class="overlay"></div>
