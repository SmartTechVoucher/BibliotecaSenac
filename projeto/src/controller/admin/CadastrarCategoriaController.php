
<?php
/**
 * Controller para cadastro de categorias via modal na tela de cadastro de livros.
 * Processa POST, insere usando CategoriaModel PDO, retorna JSON para AJAX (sucesso/ID ou erro).
 */

require_once __DIR__ . '/../../model/admin/CategoriaModel.php';

class CadastrarCategoriaController {
    private $categoria_model;

    public function __construct() {
        $this->categoria_model = new CategoriaModel();
    }

    /**
     * Processa cadastro de categoria via POST do modal.
     * Retorna JSON para JS (sucesso com ID, erro com mensagem).
     * @return void (echo JSON)
     */
    public function cadastrar() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método inválido. Use POST.']);
            return;
        }

        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nome da categoria é obrigatório.']);
            return;
        }

        $id_categoria = $this->categoria_model->cadastrarCategoria($nome);

        if ($id_categoria) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Categoria cadastrada com sucesso!',
                'id' => $id_categoria,
                'nome' => $nome
            ]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar categoria. Verifique logs.']);
        }
    }
}

// Se chamado diretamente (para testes), mas normalmente chamado via AJAX do modal
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new CadastrarCategoriaController();
    $controller->cadastrar();
}
?>