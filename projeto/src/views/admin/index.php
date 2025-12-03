<?php
// Ativa buffer de saída para evitar erros de header
ob_start();

// Configura exibição de erros (apenas desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o controller
require_once __DIR__ . '/../../controller/admin/DashboardController.php';

// Cria controller e pega dados
$controller = new DashboardController();
$data = $controller->index();

// Extrai dados
$stats = $data['stats'] ?? [];
$activities = $data['activities'] ?? [];
$topBooks = $data['topBooks'] ?? [];

// Dados do layout
$titulo = 'Dashboard';
$cssPagina = '/public/css/admin/inicial.css';
$conteudo = __DIR__ . '/pages/inicial-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui layout-base
include __DIR__ . '/layout-base.php';

// Envia buffer
ob_end_flush();
