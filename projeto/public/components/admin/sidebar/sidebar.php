<!-- SIDEBAR FIXA -->
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <!-- botão sanduíche (chama toggleSidebar) -->
        <button class="menu-toggle" type="button" onclick="toggleSidebar()" aria-label="Abrir menu">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        <div class="perfil-lateral">
            <h2 class="hub">HUB ACADEMY</h2>
            <h2 class="biblioteca">Biblioteca</h2>
        </div>
    </div>

    <!-- Navegação -->
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/inicial.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/home.png" alt="Início" class="icon"> Início
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/cadastro-livros.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/livros-add.png" alt="Cadastrar Livros" class="icon"> Cadastrar Livros
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/livros-cadastrados.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/livros.png" alt="Livros Cadastrados" class="icon"> Livros
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/cadastro-usuarios.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/usuario-add.png" alt="Cadastrar Usuários" class="icon"> Cadastrar Usuários
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/usuarios-cadastrados.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/usuarios.png" alt="Usuários Cadastrados" class="icon"> Usuários
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/relatorios.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/relatorio-white.png" alt="Relatórios" class="icon"> Relatórios
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/emprestimo.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/livro-aberto.png" alt="Empréstimos" class="icon"> Empréstimos
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/historico-emprestimo.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/historico.png" alt="Histórico" class="icon"> Histórico
                </a>
            </li>
        </ul>
    </nav>

    <!-- Perfil / Botão entrar -->
    <?php if(!isset($_SESSION['usuario'])): ?>
        <div class="sidebar-login">
            <button class="btn-entrar" onclick="window.location.href='<?php echo $URLBASE ?>/src/views/admin/login-adm.php'" type="submit">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/icon-perfil-adm.png" alt="">
                Entrar
            </button>
        </div>

    <?php else: ?>
        <div class="sidebar-profile">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-circle-user-round-icon lucide-circle-user-round">
                <path d="M18 20a6 6 0 0 0-12 0" />
                <circle cx="12" cy="10" r="4" />
                <circle cx="12" cy="12" r="10" />
            </svg>
            <p>Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Usuário') ?></strong></p>
        </div>
    <?php endif; ?>
</aside>

<!-- JS permanece igual -->
<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  const isOpen = sidebar.classList.toggle('open');
  document.body.classList.toggle('menu-open', isOpen);
}

document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('.sidebar-nav ul li a');
  const currentPage = window.location.pathname.split('/').pop();

  links.forEach(link => {
    const linkPage = link.getAttribute('href').split('/').pop();
    if (linkPage === currentPage) link.classList.add('active');

    link.addEventListener('mouseenter', () => link.classList.add('hovering'));
    link.addEventListener('mouseleave', () => link.classList.remove('hovering'));
  });
})
</script>
