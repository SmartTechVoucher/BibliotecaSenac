<?php
require_once __DIR__ . '/../../model/admin/ExemplaresModel.php';
require_once __DIR__ . '/../../model/usuario/LivroModel.php';
require_once __DIR__ . '/../../../config/db/database.php';

class AtualizarEstoqueController {
    private $exemplares_model;
    private $livro_model;
    private $conn;

    public function __construct() {
        $this->exemplares_model = new ExemplaresModel();
        $this->livro_model = new LivroModel();
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    public function atualizarEstoque() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido. Use POST.');
            }

            $id_livro = (int) ($_POST['id_livro'] ?? 0);
            $total_exemplares = (int) ($_POST['total_exemplares'] ?? 0);
            $disponiveis = (int) ($_POST['disponiveis'] ?? 0);
            $emprestados = (int) ($_POST['emprestados'] ?? 0);
            $reservas = (int) ($_POST['reservas'] ?? 0);

            if ($id_livro <= 0) {
                throw new Exception('ID do livro inválido.');
            }

            if ($total_exemplares < 1) {
                throw new Exception('Total de exemplares deve ser pelo menos 1.');
            }

            if ($disponiveis < 0 || $emprestados < 0 || $reservas < 0) {
                throw new Exception('Os valores não podem ser negativos.');
            }

            $soma_calculada = $disponiveis + $emprestados + $reservas;
            if ($soma_calculada !== $total_exemplares) {
                throw new Exception("Inconsistência nos valores: Total ($total_exemplares) deve ser igual à soma de Disponíveis + Emprestados + Reservas ($soma_calculada).");
            }

            $livros = $this->livro_model->getTodosLivros();
            $livro_existe = false;
            foreach ($livros as $livro) {
                if ($livro['id_livro'] === $id_livro) {
                    $livro_existe = true;
                    break;
                }
            }

            if (!$livro_existe) {
                throw new Exception('Livro não encontrado.');
            }

            $emprestimos_ativos = $this->verificarEmprestimosAtivos($id_livro);
            if ($emprestimos_ativos > $total_exemplares) {
                throw new Exception("Não é possível reduzir o total de exemplares para $total_exemplares pois há $emprestimos_ativos empréstimos ativos.");
            }

            $atualizado = $this->exemplares_model->atualizarEstoque($id_livro, $total_exemplares, $disponiveis, $emprestados, $reservas);

            if (!$atualizado) {
                throw new Exception('Erro ao atualizar estoque no banco de dados.');
            }

            $this->logAtualizacaoEstoque($id_livro, $total_exemplares, $disponiveis, $emprestados, $reservas);

            return [
                'sucesso' => true,
                'mensagem' => "Estoque do livro atualizado com sucesso! Total: $total_exemplares, Disponíveis: $disponiveis, Emprestados: $emprestados, Reservas: $reservas"
            ];

        } catch (Exception $e) {
            error_log('AtualizarEstoqueController error: ' . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    private function verificarEmprestimosAtivos($id_livro) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM movimentacoes m
                    WHERE m.id_livro = :id_livro
                    AND m.status = 'emprestado'
                    AND m.data_real_devolucao IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao verificar empréstimos ativos do livro $id_livro: " . $e->getMessage());
            return 0;
        }
    }

    private function logAtualizacaoEstoque($id_livro, $total_exemplares, $disponiveis, $emprestados, $reservas) {
        $log_message = sprintf(
            "[%s] Estoque atualizado - Livro ID: %d | Total: %d | Disponíveis: %d | Emprestados: %d | Reservas: %d | IP: %s",
            date('Y-m-d H:i:s'),
            $id_livro,
            $total_exemplares,
            $disponiveis,
            $emprestados,
            $reservas,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        );

        error_log('ESTOQUE_UPDATE: ' . $log_message);
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    session_start();

    if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Acesso negado. Faça login como administrador.'
        ]);
        exit;
    }

    $controller = new AtualizarEstoqueController();
    $resultado = $controller->atualizarEstoque();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
}
?>