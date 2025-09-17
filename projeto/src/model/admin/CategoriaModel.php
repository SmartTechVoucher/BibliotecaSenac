<?php
/**
 * Model para cadastro de categorias (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção e listagem.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class CategoriaModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra uma nova categoria via modal.
     * @param string $nome Nome da categoria
     * @return int|false ID da categoria inserido ou false se erro
     */
    public function cadastrarCategoria($nome) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome da categoria é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_categoria FROM categorias WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Categoria já cadastrada.');
            }

            $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar categoria (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação categoria: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as categorias.
     * @return array Lista de categorias {id_categoria: id, nome: string}
     */
    public function getAllCategorias() {
        try {
            $sql = "SELECT id_categoria as id, nome FROM categorias ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar categorias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza uma categoria existente.
     * @param int $id ID da categoria
     * @param string $nome Novo nome
     * @return bool True se atualizado, false se erro
     */
    public function atualizarCategoria($id, $nome) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_categoria FROM categorias WHERE nome = :nome AND id_categoria != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de categoria já existe.');
            }

            $sql = "UPDATE categorias SET nome = :nome WHERE id_categoria = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar categoria (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar categoria: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui uma categoria.
     * @param int $id ID da categoria
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirCategoria($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_categoria = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Categoria está em uso em livros e não pode ser excluída.');
            }

            $sql = "DELETE FROM categorias WHERE id_categoria = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir categoria (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir categoria: " . $e->getMessage());
            return false;
        }
    }
}
?>