<?php
$titulo = "Cadastro de Usuários";
$cssPagina = '/public/css/admin/cadastro-usuarios.css'; // CSS específico
$conteudo = __DIR__ . '/pages/cadastrar-usuarios-content.php';
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

require_once __DIR__ . '/layout-base.php';
