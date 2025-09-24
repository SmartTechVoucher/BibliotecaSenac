<?php
require(__DIR__ . '/../../../config/constantes.php');


?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTech - Dashboard</title>
    <link rel="stylesheet" href="../../../public/css/usuario/minha-atividade-teste.css">
    <link rel="stylesheet" href="../../../public/css/components/usuario/header.css">
    <link rel="stylesheet" href="../../../public/css/components/usuario/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Header -->
    <?php
    include "../../../public/components/usuario/header/header.php";
    ?>
    <!-- <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">📚</div>
                    <span class="logo-text">BiblioTech</span>
                </div>
                <nav class="nav">
                    <a href="#" class="nav-link active">Início</a>
                    <a href="#" class="nav-link">Livros</a>
                    <a href="#" class="nav-link">Favoritos</a>
                    <a href="#" class="nav-link">Empréstimos</a>
                </nav>
                <div class="user-menu">
                    <div class="user-avatar">👤</div>
                </div>
            </div>
        </div>
    </header> -->

    <!-- Main Content -->
    <main class="main">
        <div class="container">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <div class="welcome-content">
                    <h1 class="welcome-title">Bem-vindo de volta, Marlon!</h1>
                    <p class="welcome-description">Explore nossa vasta coleção de livros digitais e físicos.</p>
                    <span class="welcome-status">Conta Premium</span>
                </div>
                <div class="welcome-icon">📖</div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <div class="dashboard-card" data-modal="notifications">
                    <div class="card-header">
                        <div class="card-icon">🔔</div>
                        <div class="card-count">3</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Notificações</h3>
                        <p class="card-description">Atualizações sobre empréstimos, reservas e novidades</p>
                    </div>
                    <button class="card-action">Ver todas</button>
                </div>

                <div class="dashboard-card" data-modal="favorites">
                    <div class="card-header">
                        <div class="card-icon">❤️</div>
                        <div class="card-count">12</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Favoritos</h3>
                        <p class="card-description">Seus livros marcados como favoritos</p>
                    </div>
                    <button class="card-action">Explorar</button>
                </div>

                <div class="dashboard-card accent" data-modal="recommendations">
                    <div class="card-header">
                        <div class="card-icon">📚</div>
                        <div class="card-count">8</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Recomendações</h3>
                        <p class="card-description">Livros selecionados especialmente para você</p>
                    </div>
                    <button class="card-action">Descobrir</button>
                </div>

                <div class="dashboard-card" data-modal="loans">
                    <div class="card-header">
                        <div class="card-icon">⏰</div>
                        <div class="card-count">2</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Empréstimos Ativos</h3>
                        <p class="card-description">Livros que você possui no momento</p>
                    </div>
                    <button class="card-action">Gerenciar</button>
                </div>
            </div>

            <!-- Quick Stats -->
            <!-- <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">127</div>
                    <div class="stat-label">Livros Lidos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">15h</div>
                    <div class="stat-label">Tempo de Leitura</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Gêneros</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number accent">95%</div>
                    <div class="stat-label">Meta Mensal</div>
                </div>
            </div> -->
        </div>
    </main>

    <!-- Modals (simplified for brevity) -->
    <div class="modal" id="notifications-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>🔔 Notificações</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="notification-item">
                    <div class="notification-content">
                        <h4>Prazo de devolução próximo</h4>
                        <p>O livro '1984' deve ser devolvido em 2 dias</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="favorites-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content large">
            <div class="modal-header">
                <h2>❤️ Meus Favoritos</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="favorites-grid">
                    <div class="favorite-book">
                        <h3>O Senhor dos Anéis</h3>
                        <p>J.R.R. Tolkien</p>
                        <button class="btn primary">Emprestar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="recommendations-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content extra-large">
            <div class="modal-header">
                <h2>📚 Recomendações</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="recommendations-grid">
                    <div class="recommendation-book">
                        <h3>Dune</h3>
                        <p>Frank Herbert</p>
                        <button class="btn primary">Emprestar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="loans-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content large">
            <div class="modal-header">
                <h2>⏰ Empréstimos Ativos</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td>1984</td>
                        <td>George Orwell</td>
                        <td><button class="btn primary">Devolver</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>

    <script src="../../../public/js/usuario/teste-minha-atividade.js"></script>
    <script src="<?php echo $URLBASE ?>/public/js/components/header.js" defer></script>
</body>

</html>