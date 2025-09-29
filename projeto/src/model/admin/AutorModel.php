<?php
/**
 * Model para CRUD de autores (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção, listagem, update, delete.
 * Todos nomes e comentários em português.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class AutorModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra um novo autor via modal.
     * @param string $nome Nome do autor
     * @param string $nacionalidade Nacionalidade
     * @return int|false ID do autor inserido ou false se erro
     */
    public function cadastrarAutor($nome, $nacionalidade) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome do autor é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_autor FROM autores WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Autor já cadastrado.');
            }

            $sql = "INSERT INTO autores (nome, nacionalidade) VALUES (:nome, :nacionalidade)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':nacionalidade', $nacionalidade);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar autor (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação autor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os autores.
     * @return array Lista de autores {id_autor: id, nome: string}
     */
    public function getAllAutores() {
        try {
            $sql = "SELECT id_autor as id, nome FROM autores ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar autores: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza um autor existente.
     * @param int $id ID do autor
     * @param string $nome Novo nome
     * @param string $nacionalidade Nova nacionalidade (opcional)
     * @return bool True se atualizado, false se erro
     */
    public function atualizarAutor($id, $nome, $nacionalidade = null) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_autor FROM autores WHERE nome = :nome AND id_autor != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de autor já existe.');
            }

            $sql = "UPDATE autores SET nome = :nome";
            $params = [':nome' => $nome, ':id' => $id];
            if ($nacionalidade !== null) {
                $sql .= ", nacionalidade = :nacionalidade";
                $params[':nacionalidade'] = $nacionalidade;
            }
            $sql .= " WHERE id_autor = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar autor (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar autor: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um autor.
     * @param int $id ID do autor
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirAutor($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_autor = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Autor está em uso em livros e não pode ser excluído.');
            }

            $sql = "DELETE FROM autores WHERE id_autor = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir autor (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir autor: " . $e->getMessage());
            return false;
        }
    }
}
?>