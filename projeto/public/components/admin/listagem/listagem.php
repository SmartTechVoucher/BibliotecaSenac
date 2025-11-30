<?php

/**
 * Component: TableList
 *
* @param array $columns       → ["titulo" => "Título", "autor" => "Autor", ...]
 * @param array $data          → array de registros vindos do banco
 * @param bool  $actions       → se exibe editar/deletar
 * @param int   $perPage       → itens por página
 * @param array|null $searchInput → array de configuração do InputAdmin (opcional)
 */
function Listagem(array $columns, array $data, bool $actions = true, int $perPage = 10, ?array $searchInput = null)
{
    global $URLBASE;

    // Renderiza input dinamicamente se existir
    if ($searchInput !== null) {
        ?>
        <div class="listagem-search" style="margin-bottom: 16px;">
            <?php
            InputAdmin(
                largura: $searchInput['largura'] ?? 100,
                name: $searchInput['name'] ?? '',
                icone: $searchInput['icone'] ?? '',
                id: $searchInput['id'] ?? '',
                required: $searchInput['required'] ?? false,
                placeholder: $searchInput['placeholder'] ?? ''
            );
            ?>
        </div>
        <?php
    }

    // Página atual (via GET)
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

    // Total de páginas
    $totalPages = max(1, ceil(count($data) / $perPage));

    // Slice dos dados da página atual
    $start = ($page - 1) * $perPage;
    $pageData = array_slice($data, $start, $perPage);
    ?>
    <div class="table-listagem-wrapper">
        <table class="table-listagem">
        <thead>
            <tr>
                <?php foreach ($columns as $key => $label): ?>
                    <th><?= htmlspecialchars($label) ?></th>
                <?php endforeach; ?>

                <?php if ($actions): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($pageData as $row): ?>
                <tr>
                    <?php foreach ($columns as $key => $label): ?>
                        <td><?= htmlspecialchars($row[$key] ?? "") ?></td>
                    <?php endforeach; ?>

                    <?php if ($actions): ?>
                        <td class="actions">
                            <span class="edit-icon" onclick="editar(<?= $row['id'] ?>)">✏️</span>
                            <span class="delete-icon" onclick="deletar(<?= $row['id'] ?>)">🗑️</span>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </div>
    

    <!-- PAGINAÇÃO -->
    <div class="paginacao">
        <a href="?page=<?= max(1, $page - 1) ?>" class="btn <?= $page == 1 ? 'disabled' : '' ?>">Anterior</a>

        <span>Página <?= $page ?> de <?= $totalPages ?></span>

        <a href="?page=<?= min($totalPages, $page + 1) ?>" class="btn <?= $page == $totalPages ? 'disabled' : '' ?>">Próxima</a>
    </div>

    <style>
.table-listagem-wrapper {
    width: 100%;
    overflow-x: auto;
}

/* Tabela principal */
.table-listagem {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    min-width: 600px; /* opcional, garante legibilidade em telas grandes */
}

/* Células */
.table-listagem th,
.table-listagem td {
    padding: 14px;
    text-align: left;
}

/* Hover sutil */
.table-listagem tbody tr:hover {
    background-color: rgba(30, 64, 175, 0.09);
    transition: background-color 0.2s ease;
}

/* Responsividade */
@media (max-width: 1024px) {
    .table-listagem th, .table-listagem td {
        padding: 10px;
    }
}

@media (max-width: 768px) {
    .table-listagem th, .table-listagem td {
        padding: 8px;
        white-space: normal; /* permite quebra de linha */
    }
    .actions {
        flex-wrap: wrap; /* ícones de ação se ajustam */
        gap: 6px;
    }
}

@media (max-width: 480px) {
    .table-listagem th, .table-listagem td {
        font-size: 14px; /* reduz o tamanho da fonte */
    }
    .paginacao {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}

.actions {
    display: flex;
    gap: 12px;
}
.edit-icon { color: #1d4ed8; cursor: pointer; }
.delete-icon { color: #ef4444; cursor: pointer; }
.actions span:hover { opacity: 0.7; }

/* PAGINAÇÃO */
.paginacao {
    margin-top: 15px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.paginacao .btn {
    padding: 8px 14px;
    background: #e5e7eb;
    border-radius: 6px;
    color: #333;
    text-decoration: none;
}
.paginacao .btn.disabled {
    opacity: 0.4;
    pointer-events: none;
}

/* Hover sutil */
.table-listagem tbody tr:hover {
    background-color: rgba(30, 64, 175, 0.09);
    transition: background-color 0.2s ease;
}
</style>

    <?php
}
