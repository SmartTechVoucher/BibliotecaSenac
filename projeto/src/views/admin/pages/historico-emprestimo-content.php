<?php
require_once "../../../config/constantes.php";

// Verifica se é admin

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Empréstimos - Biblioteca SENAC</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/historico-emprestimo.css">
    
    <script>
        const URLBASE = "<?php echo $URLBASE; ?>";
    </script>
</head>
<body>
    <!-- Header/Sidebar (se você tiver) -->
    
    <div class="main-container">
        <!-- Cabeçalho -->
        <div class="header-section">
            <h1>Histórico de Empréstimos</h1>
            <p class="subtitle">Visualize e acompanhe todos os empréstimos realizados</p>
        </div>

        <!-- Cards de Estatísticas -->
        <div id="estatisticas-container" class="stats-grid">
            <!-- Será preenchido pelo JavaScript -->
        </div>

        <!-- Filtros -->
        <div class="filter-section">
            <div class="filter-header">
                <span class="filter-label">🔍 Filtrar por Status:</span>
            </div>
            
            <div class="filter-options">
                <label class="filter-radio">
                    <input type="radio" name="statusFilter" value="Todos" checked>
                    <span class="radio-custom"></span>
                    <span class="radio-label">Todos</span>
                </label>
                
                <label class="filter-radio">
                    <input type="radio" name="statusFilter" value="Em andamento">
                    <span class="radio-custom"></span>
                    <span class="radio-label">Em andamento</span>
                </label>
                
                <label class="filter-radio">
                    <input type="radio" name="statusFilter" value="Finalizado">
                    <span class="radio-custom"></span>
                    <span class="radio-label">Finalizado</span>
                </label>
                
                <label class="filter-radio">
                    <input type="radio" name="statusFilter" value="Atrasado">
                    <span class="radio-custom"></span>
                    <span class="radio-label">Atrasado</span>
                </label>
            </div>
        </div>

        <!-- Tabela -->
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>ISBN</th>
                        <th>Leitor</th>
                        <th>Data Empréstimo</th>
                        <th>Prazo</th>
                        <th>Devolução</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <!-- Skeleton loader -->
                    <tr>
                        <td colspan="6" class="loading-row">
                            <div class="skeleton-loader">
                                <div class="skeleton-line"></div>
                                <div class="skeleton-line"></div>
                                <div class="skeleton-line"></div>
                            </div>
                            <p style="margin-top: 15px; color: #666;">Carregando empréstimos...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="pagination-controls">
            <button id="prevBtn" class="pagination-btn" disabled>
                <span>←</span> Anterior
            </button>
            
            <span id="pageInfo" class="pagination-info">Carregando...</span>
            
            <button id="nextBtn" class="pagination-btn" disabled>
                Próximo <span>→</span>
            </button>
        </div>
    </div>

    <script src="<?php echo $URLBASE ?>/public/js/admin/historico-emprestimo.js"></script>
</body>
</html>