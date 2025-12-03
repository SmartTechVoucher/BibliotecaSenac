<?php
// $stats = [
//     ["title" => "Total de Livros", "value" => "1,234", "icon" => "📚", "color" => "#004A90"],
//     ["title" => "Usuários Cadastrados", "value" => "856", "icon" => "👥", "color" => "#28a745"],
//     ["title" => "Empréstimos Ativos", "value" => "142", "icon" => "📄", "color" => "#fd7e14"],
//     ["title" => "Taxa de Devolução", "value" => "94%", "icon" => "📈", "color" => "#6f42c1"],
// ];

// $activities = [
//     ["action" => "Novo livro cadastrado", "book" => "Dom Casmurro", "time" => "há 2 horas"],
//     ["action" => "Empréstimo realizado", "book" => "1984", "time" => "há 3 horas"],
//     ["action" => "Livro devolvido", "book" => "O Cortiço", "time" => "há 5 horas"],
//     ["action" => "Novo usuário cadastrado", "book" => "Maria Silva", "time" => "há 1 dia"],
// ];

// $topBooks = [
//     ["title" => "1984", "author" => "George Orwell", "count" => 45],
//     ["title" => "O Senhor dos Anéis", "author" => "J.R.R. Tolkien", "count" => 38],
//     ["title" => "Dom Casmurro", "author" => "Machado de Assis", "count" => 32],
//     ["title" => "Harry Potter", "author" => "J.K. Rowling", "count" => 28],
// ];
?>


<div class="dashboard-actions">
    <button class="btn btn-primary" onclick="window.location.href='<?php echo $URLBASE ?>/src/views/admin/emprestimo.php'">
        <span class="icon">＋</span>
        Novo Empréstimo
    </button>

    <button class="btn btn-primary" onclick="window.location.href='<?php echo $URLBASE ?>/src/views/admin/cadastro-livros.php'">
        <span class="icon"><img src="<?php echo $URLBASE ?>/public/assets/icons/livros-add.png" alt="livro" class="icon-dashboard"></span>
        Cadastrar Livro
    </button>

    <button class="btn btn-primary" onclick="window.location.href='<?php echo $URLBASE ?>/src/views/admin/cadastro-usuarios.php'">
        <span class="icon"><img src="<?php echo $URLBASE ?>/public/assets/icons/usuario-add.png" alt="Adicionar Usuário" class="icon-dashboard"></span>
        Cadastrar Usuário
    </button>
</div>
<!-- Cabeçalho -->
<div style="margin-bottom: 30px;">
    
    <p style="color: #6c757d; font-family: var(--fontes); margin-top: 5px;">Visão geral do sistema de biblioteca</p>
</div>

<!-- Cards de Estatísticas -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <?php foreach ($stats as $stat): ?>
        <div style="background: var(--branco); border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div>
                <div style="font-weight: 500; font-size: 0.95rem;"><?= $stat['title'] ?></div>
                <div style="font-size: 1.5rem; font-weight: 700; margin-top: 5px;"><?= $stat['value'] ?></div>
            </div>
            <div style="font-size: 1.5rem; color: <?= $stat['color'] ?>;border-radius: 10px; background:#00264d; width: 100%;
                    max-width: 40px;
                    height: 100%;
                    max-height: 40px; display:flex; justify-content:center; align-items:center"><?= $stat['icon'] ?></div>
        </div>
    <?php endforeach; ?>
</div>
<!-- Atividades Recentes -->
<!-- <div style="background: var(--branco); border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h3 style="font-family: var(--fontes); font-weight: 600; font-size: 1.2rem; margin-bottom: 15px;">Atividades Recentes</h3>
    <?php foreach ($activities as $activity): ?>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ced4da; padding: 10px 0;">
            <div>
                <div style="font-weight: 500;"><?= $activity['action'] ?? '' ?></div>
                <div style="color: #6c757d; font-size: 0.9rem;"><?= $activity['book'] ?? '' ?></div>
            </div>
            <div style="color: #6c757d; font-size: 0.8rem;"><?= $activity['time'] ?? '' ?></div>
        </div>
    <?php endforeach; ?>
</div> -->

<!-- Livros Mais Emprestados -->
<div style="background: var(--branco); border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);margin-top: 5%;">
    <h3 style="font-family: var(--fontes); font-weight: 600; font-size: 1.2rem; margin-bottom: 15px;">Livros Mais Emprestados</h3>
    <?php foreach ($topBooks as $book): ?>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ced4da; padding: 10px 0;">
            <div>
                <div style="font-weight: 500;"><?= $book['title'] ?? '' ?></div>
                <div style="color: #6c757d; font-size: 0.9rem;"><?= $book['author'] ?? '' ?></div>
            </div>
            <div style="font-weight: 600; font-size: 0.9rem;"><?= $book['count'] ?? 0 ?> vezes</div>
        </div>
    <?php endforeach; ?>
</div>
</div>