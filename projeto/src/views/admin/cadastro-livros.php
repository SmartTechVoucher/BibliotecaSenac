<?php
$titulo = 'cadastro de Livros';
$cssPagina = '/public/css/admin/cadastro-livros.css';
$conteudo = __DIR__ . '/pages/cadastro-livros-content.php'; // caminho do conteúdo
$Titulo = "Cadastro de livro";
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . '/layout-base.php';
