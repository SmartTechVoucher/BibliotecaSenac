<!-- SIDEBAR FIXA -->
<aside class="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="<?php echo $URLBASE ?>/public/assets/icons/logo-hub-academy.png" alt="Logo HUB Academy">
        <h3>HUB ACADEMY</h3>
        <span class="subtitle">Biblioteca</span>
    </div>

    <!-- Navegação -->
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/inicial.php" class="active">
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

    <!-- Perfil -->
    <div class="sidebar-profile">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round-icon lucide-circle-user-round"><path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/></svg>
        <p>Bem-vindo, <strong>Luciano</strong></p>
        <!-- <a href="<?php echo $URLBASE ?>/src/views/admin/minha-conta.php">⚙️ Perfil</a>
        <a href="#" onclick="confirmarSaida(event)" class="logout">🚪 Sair</a> -->
    </div>
</aside>

<!-- Modal de confirmação -->
<?php
require_once __DIR__ . '/../../../components/usuario/modal/modal.php';
echo renderModal('confirmModal', 'Confirmação', 'Você tem certeza que deseja sair?');
?>
