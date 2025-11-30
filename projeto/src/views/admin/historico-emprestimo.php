<?php
$titulo = 'Emprestimos Realizados';
$cssPagina = '/public/css/admin/historico-emprestimo.css';
$conteudo = __DIR__ . '/pages/historico-emprestimo-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . '/layout-base.php';
