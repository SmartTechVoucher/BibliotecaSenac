<?php
$titulo = 'Relatórios';
$cssPagina = '/public/css/admin/relatorios.css';
$conteudo = __DIR__ . '/pages/livros-cadastrados-teste.php'; 
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];


include __DIR__ . '/layout-base.php';
