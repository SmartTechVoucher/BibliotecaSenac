<?php
$titulo = 'Minha Conta';
$cssPagina = '/public/css/admin/minha-conta.css';
$conteudo = __DIR__ . '/pages/minha-conta-content.php'; 
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];


include __DIR__ . '/layout-base.php';
