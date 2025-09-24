<?php
/**
 * Controller para cadastro de autores via modal na tela de cadastro de livros.
 * Processa POST, insere usando AutorModel PDO, retorna JSON para AJAX (sucesso/ID ou erro).
 */

require_once __DIR__ . '/../../model/admin/AutorModel.php';

class CadastrarAutorController {
    private $autor_model;

    public function __construct() {
        $this->autor_model = new AutorModel();
    }

    /**
     * Processa cadastro de autor via POST do modal.
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
        $nacionalidade = trim($_POST['nacionalidade'] ?? '');

        if (empty($nome)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nome do autor é obrigatório.']);
            return;
        }

        $id_autor = $this->autor_model->cadastrarAutor($nome, $nacionalidade);

        if ($id_autor) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Autor cadastrado com sucesso!',
                'id' => $id_autor,
                'nome' => $nome
            ]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar autor. Verifique logs.']);
        }
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new CadastrarAutorController();
    $controller->cadastrar();
}
?>