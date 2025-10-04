<?php
$titulo = 'Dashboard';
$cssPagina = '/public/css/admin/inicial.css';
$conteudo = __DIR__ . '/pages/inicial-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . './layout-base.php';
