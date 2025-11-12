<?php
$titulo = 'Minha Conta';
$cssPagina = '/public/css/admin/minha-conta.css';
$conteudo = __DIR__ . '/pages/minha-conta-content.php'; 
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . '/layout-base.php';
