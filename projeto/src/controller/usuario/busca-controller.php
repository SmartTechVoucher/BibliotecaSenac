<?php
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/auth-check.php';
require_once __DIR__ . '/../../model/usuario/LivroModel.php';

header('Content-Type: application/json');

try {
    $livroModel = new LivroModel();

    // Verificar se é requisição AJAX
    if (!isset($_GET['ajax']) && !isset($_POST['ajax'])) {
        throw new Exception('Acesso não autorizado');
    }

    $acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

    switch ($acao) {
        case 'buscar_livros':
            $termo = $_GET['termo'] ?? $_POST['termo'] ?? '';
            $categoria = $_GET['categoria'] ?? $_POST['categoria'] ?? '';
            $id_categoria = !empty($categoria) ? (int)$categoria : null;
            $limit = (int)($_GET['limit'] ?? $_POST['limit'] ?? 10);

            $livros = $livroModel->buscarLivrosAjax($termo, $id_categoria, $limit);

            echo json_encode([
                'sucesso' => true,
                'livros' => $livros,
                'total' => count($livros)
            ]);
            break;

        case 'get_categorias':
            $categorias = $livroModel->getCategoriasParaDropdown();

            echo json_encode([
                'sucesso' => true,
                'categorias' => $categorias
            ]);
            break;

        default:
            throw new Exception('Ação não reconhecida');
    }

} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}