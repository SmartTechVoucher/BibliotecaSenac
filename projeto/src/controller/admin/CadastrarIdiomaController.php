<?php
/**
 * Controller para CRUD de idiomas via AJAX na tela de cadastro de livros.
 * Processa POST/GET para create, read, update, delete usando IdiomaModel.
 * Retorna JSON para JS.
 */

require_once __DIR__ . '/../../model/admin/IdiomaModel.php';

class CadastrarIdiomaController {
    private $idioma_model;

    public function __construct() {
        $this->idioma_model = new IdiomaModel();
    }

    /**
     * Processa ação baseada em $_GET['subacao'] (cadastrar, listar, atualizar, excluir).
     * @return void (echo JSON)
     */
    public function handle() {
        header('Content-Type: application/json');

        if (!isset($_GET['subacao'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Subação não especificada.']);
            return;
        }

        $subacao = $_GET['subacao'];

        try {
            switch ($subacao) {
                case 'cadastrar':
                    $this->cadastrar();
                    break;
                case 'listar':
                    $this->listar();
                    break;
                case 'atualizar':
                    $this->atualizar();
                    break;
                case 'excluir':
                    $this->excluir();
                    break;
                default:
                    echo json_encode(['sucesso' => false, 'mensagem' => 'Subação inválida.']);
            }
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    private function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método inválido. Use POST.');
        }

        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            throw new Exception('Nome do idioma é obrigatório.');
        }

        $id = $this->idioma_model->cadastrarIdioma($nome);

        if ($id) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Idioma cadastrado com sucesso!',
                'id' => $id,
                'nome' => $nome
            ]);
        } else {
            throw new Exception('Erro ao cadastrar idioma. Verifique logs.');
        }
    }

    private function listar() {
        $idiomas = $this->idioma_model->getAllIdiomas();
        echo json_encode([
            'sucesso' => true, 
            'idiomas' => $idiomas
        ]);
    }

    private function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método inválido. Use POST.');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');

        if ($id <= 0 || empty($nome)) {
            throw new Exception('ID e nome são obrigatórios.');
        }

        if ($this->idioma_model->atualizarIdioma($id, $nome)) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Idioma atualizado com sucesso!',
                'id' => $id,
                'nome' => $nome
            ]);
        } else {
            throw new Exception('Erro ao atualizar idioma. Verifique logs.');
        }
    }

    private function excluir() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método inválido. Use POST.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            throw new Exception('ID inválido.');
        }

        if ($this->idioma_model->excluirIdioma($id)) {
            echo json_encode([
                'sucesso' => true, 
                'mensagem' => 'Idioma excluído com sucesso!'
            ]);
        } else {
            throw new Exception('Erro ao excluir idioma. Pode estar em uso.');
        }
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new CadastrarIdiomaController();
    $controller->handle();
}
?>