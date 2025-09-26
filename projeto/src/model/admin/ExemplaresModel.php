<?php
require_once __DIR__ . '/../../../config/db/database.php';

class ExemplaresModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    public function getEstoqueByLivro($id_livro) {
        try {
            $sql = "SELECT * FROM exemplares WHERE id_livro = :id_livro";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return $this->criarEstoqueInicial($id_livro);
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Erro ao obter estoque do livro $id_livro: " . $e->getMessage());
            return [];
        }
    }

    private function criarEstoqueInicial($id_livro) {
        try {
            $sql = "INSERT INTO exemplares (id_livro, total_exemplares, disponiveis, emprestados, reservas)
                    VALUES (:id_livro, 1, 1, 0, 0)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'id_livro' => $id_livro,
                'total_exemplares' => 1,
                'disponiveis' => 1,
                'emprestados' => 0,
                'reservas' => 0,
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];
        } catch (PDOException $e) {
            error_log("Erro ao criar estoque inicial para livro $id_livro: " . $e->getMessage());
            return [];
        }
    }

    public function atualizarEstoque($id_livro, $total_exemplares, $disponiveis, $emprestados, $reservas) {
        try {
            $sql_select = "SELECT COUNT(*) FROM exemplares WHERE id_livro = :id_livro";
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt_select->execute();
            $existe = $stmt_select->fetchColumn() > 0;

            if ($existe) {
                $sql = "UPDATE exemplares SET
                        total_exemplares = :total_exemplares,
                        disponiveis = :disponiveis,
                        emprestados = :emprestados,
                        reservas = :reservas,
                        data_atualizacao = NOW()
                        WHERE id_livro = :id_livro";
            } else {
                $sql = "INSERT INTO exemplares (id_livro, total_exemplares, disponiveis, emprestados, reservas, data_atualizacao)
                        VALUES (:id_livro, :total_exemplares, :disponiveis, :emprestados, :reservas, NOW())";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->bindParam(':total_exemplares', $total_exemplares, PDO::PARAM_INT);
            $stmt->bindParam(':disponiveis', $disponiveis, PDO::PARAM_INT);
            $stmt->bindParam(':emprestados', $emprestados, PDO::PARAM_INT);
            $stmt->bindParam(':reservas', $reservas, PDO::PARAM_INT);

            $sucesso = $stmt->execute();

            if ($sucesso) {
                error_log("Estoque atualizado com sucesso - Livro ID: $id_livro | Total: $total_exemplares | Disp: $disponiveis | Emp: $emprestados | Res: $reservas");
            }

            return $sucesso;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar estoque do livro $id_livro: " . $e->getMessage());
            return false;
        }
    }

    public function getEstatisticasEstoque() {
        try {
            $sql = "SELECT
                    SUM(total_exemplares) as total_exemplares,
                    SUM(disponiveis) as total_disponiveis,
                    SUM(emprestados) as total_emprestados,
                    SUM(reservas) as total_reservas,
                    COUNT(*) as total_livros_com_estoque
                    FROM exemplares";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return [
                    'total_exemplares' => 0,
                    'total_disponiveis' => 0,
                    'total_emprestados' => 0,
                    'total_reservas' => 0,
                    'total_livros_com_estoque' => 0
                ];
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Erro ao obter estatísticas de estoque: " . $e->getMessage());
            return [
                'total_exemplares' => 0,
                'total_disponiveis' => 0,
                'total_emprestados' => 0,
                'total_reservas' => 0,
                'total_livros_com_estoque' => 0
            ];
        }
    }

    public function getLivrosBaixoEstoque() {
        try {
            $sql = "SELECT e.*, l.titulo, l.isbn
                    FROM exemplares e
                    JOIN livros l ON e.id_livro = l.id_livro
                    WHERE e.disponiveis < 2
                    ORDER BY e.disponiveis ASC, l.titulo ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao obter livros com baixo estoque: " . $e->getMessage());
            return [];
        }
    }
}
?>