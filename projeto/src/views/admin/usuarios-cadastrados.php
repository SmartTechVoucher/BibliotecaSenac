<?php
$titulo = 'Ususarios Cadastrados';
$cssPagina = '/public/css/admin/usuarios-cadastrados.css';
$conteudo = __DIR__ . '/pages/usuarios-cadastrados-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . './layout-base.php';
