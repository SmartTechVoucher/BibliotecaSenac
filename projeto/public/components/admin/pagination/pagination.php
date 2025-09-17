<?php
function renderPagination($totalItems, $page = 1, $pageSize = 10) {
    $totalPages = max(1, ceil($totalItems / $pageSize));
    $page = max(1, min($page, $totalPages)); // garante que não passe do limite

    ob_start();
    ?>
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }
        .page-btn {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            background: white;
            transition: background 0.2s ease;
        }
        .page-btn:hover {
            background: #f0f0f0;
        }
        .page-btn.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .page-info {
            font-size: 14px;
            color: #555;
        }
    </style>

    <div class="pagination">
        <!-- Botão Anterior -->
        <a class="page-btn <?= $page == 1 ? 'disabled' : '' ?>" 
           href="<?= $page > 1 ? '?page='.($page-1) : '#' ?>">
            ◀ Anterior
        </a>

        <!-- Info da página -->
        <span class="page-info">Página <?= $page ?> de <?= $totalPages ?></span>

        <!-- Botão Próximo -->
        <a class="page-btn <?= $page == $totalPages ? 'disabled' : '' ?>" 
           href="<?= $page < $totalPages ? '?page='.($page+1) : '#' ?>">
            Próximo ▶
        </a>
    </div>
    <?php
    return ob_get_clean();
}
