<!-- SIDEBAR FIXA -->
<aside class="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="<?php echo $URLBASE ?>/public/assets/img/LogoHub_academy.png" alt="Logo HUB Academy">
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
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/livros.png" alt="Livros Cadastrados" class="icon"> Livros Cadastrados
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/cadastro-usuarios.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/usuario-add.png" alt="Cadastrar Usuários" class="icon"> Cadastrar Usuários
                </a>
            </li>
            <li>
                <a href="<?php echo $URLBASE ?>/src/views/admin/usuarios-cadastrados.php">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/usuarios.png" alt="Usuários Cadastrados" class="icon"> Usuários Cadastrados
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
        <p>Bem-vindo, <strong>Luciano</strong></p>
        <a href="<?php echo $URLBASE ?>/src/views/admin/minha-conta.php">⚙️ Perfil</a>
        <a href="#" onclick="confirmarSaida(event)" class="logout">🚪 Sair</a>
    </div>
</aside>

<!-- Modal de confirmação -->
<?php
require_once __DIR__ . '/../../../components/usuario/modal/modal.php';
echo renderModal('confirmModal', 'Confirmação', 'Você tem certeza que deseja sair?');
?>
