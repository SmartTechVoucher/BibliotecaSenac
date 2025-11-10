<?php

$titulo = 'Listagem de Livros';
$conteudo = __DIR__ . '/pages/livros-cadastrados-content.php';
$cssPagina = '/public/css/admin/livros-cadastrados.css';
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

include __DIR__ . '/layout-base.php';

