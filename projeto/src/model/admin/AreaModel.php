<?php
/**
 * Model para cadastro de áreas (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção e listagem.
 * Todos nomes e comentários em português.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class AreaModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra uma nova área via modal.
     * @param string $nome Nome da área
     * @return int|false ID da área inserido ou false se erro
     */
    public function cadastrarArea($nome) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome da área é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_area FROM areas WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Área já cadastrada.');
            }

            $sql = "INSERT INTO areas (nome) VALUES (:nome)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar área (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação área: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as áreas.
     * @return array Lista de áreas {id_area: id, nome: string}
     */
    public function getAllAreas() {
        try {
            $sql = "SELECT id_area as id, nome FROM areas ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar áreas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza uma área existente.
     * @param int $id ID da área
     * @param string $nome Novo nome
     * @return bool True se atualizado, false se erro
     */
    public function atualizarArea($id, $nome) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_area FROM areas WHERE nome = :nome AND id_area != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de área já existe.');
            }

            $sql = "UPDATE areas SET nome = :nome WHERE id_area = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar área (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar área: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui uma área.
     * @param int $id ID da área
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirArea($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_area = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Área está em uso em livros e não pode ser excluída.');
            }

            $sql = "DELETE FROM areas WHERE id_area = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir área (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir área: " . $e->getMessage());
            return false;
        }
    }
}
?>