<?php
$titulo = 'Relatórios';
$cssPagina = '/public/css/admin/relatorios.css';
$conteudo = __DIR__ . '/pages/relatorios-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . './layout-base.php';
