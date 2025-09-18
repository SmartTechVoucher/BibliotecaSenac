<?php
/**
 * Controller genérico para CRUD de entidades auxiliares (idioma, categoria, area, documento, unidade, autor) via AJAX.
 * Usa reflexão para carregar models dinamicamente baseado em 'tipo'.
 * Processa ações: cadastrar, listar, atualizar, excluir.
 * Retorna JSON para JS.
 */

class AuxEntityController {
    private $model_map = [
        'autor' => 'AutorModel',
        'idioma' => 'IdiomaModel',
        'categoria' => 'CategoriaModel',
        'area' => 'AreaModel',
        'documento' => 'DocumentoModel',
        'unidade' => 'UnidadeModel'
    ];

    private $model_instance;
    private $tipo;
    private $table_id_map = [
        'autor' => 'id_autor',
        'idioma' => 'id_idioma',
        'categoria' => 'id_categoria',
        'area' => 'id_area',
        'documento' => 'id_documento',
        'unidade' => 'id_unidade'
    ];

    public function __construct($tipo) {
        if (!array_key_exists($tipo, $this->model_map)) {
            throw new Exception('Tipo de entidade inválido: ' . $tipo);
        }

        $this->tipo = $tipo;
        $model_class = $this->model_map[$tipo];
        $model_file = __DIR__ . '/../../model/admin/' . $model_class . '.php';
        if (!file_exists($model_file)) {
            throw new Exception('Arquivo do model não encontrado: ' . $model_file);
        }
        require_once $model_file;
        $this->model_instance = new $model_class();
    }

    /**
     * Processa ação baseada em $_GET['subacao'].
     * @return void (echo JSON)
     */
    public function handle() {
        header('Content-Type: application/json; charset=utf-8');

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
                case 'buscar':
                    $this->buscar();
                    break;
                case 'atualizar':
                    $this->atualizar();
                    break;
                case 'excluir':
                    $this->excluir();
                    break;
                default:
                    throw new Exception('Subação inválida: ' . $subacao);
            }
        } catch (Exception $e) {
            error_log('AuxEntity handle error [' . $this->tipo . ']: ' . $e->getMessage() . ' | Subacao: ' . $subacao);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    private function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método inválido. Use POST.');
        }

        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            throw new Exception(ucfirst($this->tipo) . ' nome é obrigatório.');
        }

        // Chama método cadastrar dinamicamente
        $method = 'cadastrar' . ucfirst($this->tipo);
        if (!method_exists($this->model_instance, $method)) {
            throw new Exception('Método de cadastro não encontrado para ' . $this->tipo . '. Método: ' . $method);
        }

        $id = false;
        if ($this->tipo === 'autor') {
            $nacionalidade = trim($_POST['nacionalidade'] ?? null);
            $id = $this->model_instance->$method($nome, $nacionalidade);
        } else {
            $id = $this->model_instance->$method($nome);
        }

        if ($id !== false && $id > 0) {
            echo json_encode([
                'sucesso' => true,
                'mensagem' => ucfirst($this->tipo) . ' cadastrado com sucesso!',
                'id' => $id,
                'nome' => $nome
            ], JSON_UNESCAPED_UNICODE);
        } else {
            error_log('AuxEntity cadastrar failed for ' . $this->tipo . ': nome=' . $nome);
            throw new Exception('Erro ao cadastrar ' . $this->tipo . '. Nome já existe ou erro no banco.');
        }
    }

    private function listar() {
        $method = 'getAll' . ucfirst($this->tipo) . 's';
        // Special case for 'autor' - Portuguese plural
        if ($this->tipo === 'autor') {
            $method = 'getAllAutores';
        }
        if (!method_exists($this->model_instance, $method)) {
            throw new Exception('Método de listagem não encontrado: ' . $method);
        }

        $items = $this->model_instance->$method();
        if (!is_array($items)) {
            $items = [];
        }
        echo json_encode([
            'sucesso' => true,
            $this->tipo . 's' => $items
        ], JSON_UNESCAPED_UNICODE);
    }

    private function buscar() {
        $query = trim($_GET['query'] ?? '');
        error_log('T1: Busca ' . $this->tipo . ' query: ' . $query);

        $method = 'getAll' . ucfirst($this->tipo) . 's';
        // Special case for 'autor'
        if ($this->tipo === 'autor') {
            $method = 'getAllAutores';
        }
        if (!method_exists($this->model_instance, $method)) {
            error_log('T2: Método getAll não encontrado: ' . $method . ' for ' . $this->tipo);
            echo json_encode(['sucesso' => false, 'mensagem' => 'Método de listagem não encontrado para busca']);
            return;
        }

        $allItems = $this->model_instance->$method();
        if (!is_array($allItems)) {
            $allItems = [];
        }
        $filteredItems = array_filter($allItems, function($item) use ($query) {
            return stripos($item['nome'], $query) !== false;
        });

        $filteredItems = array_values($filteredItems); // Reindex array

        error_log('T1: Busca completa - ' . count($filteredItems) . ' resultados for ' . $this->tipo);
        echo json_encode([
            'sucesso' => true,
            $this->tipo . 's' => $filteredItems
        ], JSON_UNESCAPED_UNICODE);
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

        $method = 'atualizar' . ucfirst($this->tipo);
        if (!method_exists($this->model_instance, $method)) {
            throw new Exception('Método de atualização não encontrado para ' . $this->tipo);
        }

        $nacionalidade = null;
        if ($this->tipo === 'autor') {
            $nacionalidade = trim($_POST['nacionalidade'] ?? null);
        }

        $success = false;
        if ($this->tipo === 'autor') {
            $success = $this->model_instance->$method($id, $nome, $nacionalidade);
        } else {
            $success = $this->model_instance->$method($id, $nome);
        }

        if ($success !== false) {
            error_log('T1: ' . ucfirst($this->tipo) . ' atualizado com sucesso ID: ' . $id);
            echo json_encode([
                'sucesso' => true,
                'mensagem' => ucfirst($this->tipo) . ' atualizado com sucesso!',
                'id' => $id,
                'nome' => $nome
            ], JSON_UNESCAPED_UNICODE);
        } else {
            error_log('T2: Falha ao atualizar ' . $this->tipo . ' - ID: ' . $id . ', nome: ' . $nome . ', nacionalidade: ' . ($nacionalidade ?? 'null'));
            throw new Exception('Erro ao atualizar ' . $this->tipo . '. Nome já existe ou erro no banco.');
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

        $method = 'excluir' . ucfirst($this->tipo);
        if (!method_exists($this->model_instance, $method)) {
            throw new Exception('Método de exclusão não encontrado para ' . $this->tipo);
        }

        if ($this->model_instance->$method($id)) {
            error_log('T1: ' . ucfirst($this->tipo) . ' excluído com sucesso ID: ' . $id);
            echo json_encode([
                'sucesso' => true,
                'mensagem' => ucfirst($this->tipo) . ' excluído com sucesso!'
            ]);
        } else {
            error_log('T2: Falha ao excluir ' . $this->tipo . ' - ID: ' . $id . ' (pode estar em uso)');
            throw new Exception('Erro ao excluir ' . $this->tipo . '. Pode estar em uso.');
        }
    }
}

// Executa se chamado diretamente via router
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    if (!isset($_GET['tipo'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo de entidade não especificado.']);
        exit;
    }
    $tipo = $_GET['tipo'];
    $controller = new AuxEntityController($tipo);
    $controller->handle();
}
?>