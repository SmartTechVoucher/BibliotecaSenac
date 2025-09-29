<?php
/**
 * Model para cadastro de unidades/editoras (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção e listagem.
 * Todos nomes e comentários em português.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class UnidadeModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra uma nova unidade/editora via modal.
     * @param string $nome Nome da unidade/editora
     * @return int|false ID da unidade inserido ou false se erro
     */
    public function cadastrarUnidade($nome) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome da unidade/editora é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_unidade FROM unidades WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Unidade/editora já cadastrada.');
            }

            $sql = "INSERT INTO unidades (nome) VALUES (:nome)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar unidade (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação unidade: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as unidades/editoras.
     * @return array Lista de unidades {id_unidade: id, nome: string}
     */
    public function getAllUnidades() {
        try {
            $sql = "SELECT id_unidade as id, nome FROM unidades ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar unidades: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza uma unidade existente.
     * @param int $id ID da unidade
     * @param string $nome Novo nome
     * @return bool True se atualizado, false se erro
     */
    public function atualizarUnidade($id, $nome) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_unidade FROM unidades WHERE nome = :nome AND id_unidade != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de unidade já existe.');
            }

            $sql = "UPDATE unidades SET nome = :nome WHERE id_unidade = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar unidade (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar unidade: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui uma unidade.
     * @param int $id ID da unidade
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirUnidade($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_unidade = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Unidade está em uso em livros e não pode ser excluída.');
            }

            $sql = "DELETE FROM unidades WHERE id_unidade = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir unidade (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir unidade: " . $e->getMessage());
            return false;
        }
    }
}
?>