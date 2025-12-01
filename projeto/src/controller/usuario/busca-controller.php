<?php
// /src/controller/usuario/busca-controller.php

// 1. INCLUSÕES ESSENCIAIS
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/auth-check.php';
// ATENÇÃO: Verifique o caminho. Se este controller está em /src/controller/usuario/, o caminho para o Model deve ser esse:
require_once __DIR__ . '/../../model/usuario/LivroModel.php'; 

// 2. GARANTIR SAÍDA LIMPA E CORRETA
// Se estiver usando o padrão Router/Front Controller, o ob_start() deve estar no arquivo principal.
// Aqui, garantimos a limpeza de buffers de saída que possam ter sido acidentalmente abertos.
if (ob_get_length() > 0) {
    ob_clean(); // Limpa qualquer coisa que já tenha sido enviada
}

header('Content-Type: application/json; charset=utf-8');

try {
    $livroModel = new LivroModel();

    // 3. VALIDAÇÃO DE ACESSO
    // Se você usa headers customizados (ex: X-Requested-With) pode ser mais seguro,
    // mas a verificação de 'acao' é suficiente para requisições controladas.
    $acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

    if (empty($acao)) {
        http_response_code(400);
        echo json_encode(['sucesso' => false, 'erro' => 'Ação não especificada.']);
        exit;
    }

    switch ($acao) {
        
        // 4. CASE: BUSCAR LIVROS
        case 'buscar_livros':
            $termo = $_GET['termo'] ?? $_POST['termo'] ?? '';
            // Pega a categoria, garantindo que seja um INT ou NULL (0 é null para o Model)
            $categoria = $_GET['categoria'] ?? $_POST['categoria'] ?? 0;
            $id_categoria = !empty($categoria) ? (int)$categoria : null;
            $limit = (int)($_GET['limit'] ?? $_POST['limit'] ?? 10);

            // A chamada AGORA FUNCIONARÁ, pois o método está no LivroModel.php
            $livros = $livroModel->buscarLivrosAjax($termo, $id_categoria, $limit);

            echo json_encode([
                'sucesso' => true,
                'livros' => $livros,
                'total' => count($livros)
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 5. CASE: BUSCAR CATEGORIAS
        case 'get_categorias':
            // A chamada AGORA FUNCIONARÁ
            $categorias = $livroModel->getCategoriasParaDropdown();

            echo json_encode([
                'sucesso' => true,
                'categorias' => $categorias
            ], JSON_UNESCAPED_UNICODE);
            break;

        // 6. DEFAULT
        default:
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erro' => 'Ação não reconhecida']);
            break;
    }

} catch (Exception $e) {
    // 7. TRATAMENTO DE ERROS GENÉRICOS
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        // O erro "<br />" quebrava o JSON. Agora, o PHP vai retornar SÓ O JSON.
        'erro' => 'Erro no servidor (verifique o log): ' . $e->getMessage() 
    ], JSON_UNESCAPED_UNICODE);
}
