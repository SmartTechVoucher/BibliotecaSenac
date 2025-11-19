<?php
/**
 * Model para cadastro de idiomas (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção e listagem.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class IdiomaModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra um novo idioma via modal.
     * @param string $nome Nome do idioma
     * @return int|false ID do idioma inserido ou false se erro
     */
    public function cadastrarIdioma($nome) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome do idioma é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_idioma FROM idiomas WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Idioma já cadastrado.');
            }

            $sql = "INSERT INTO idiomas (nome) VALUES (:nome)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar idioma (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação idioma: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os idiomas.
     * @return array Lista de idiomas {id_idioma: id, nome: string}
     */
    public function getAllIdiomas() {
        try {
            $sql = "SELECT id_idioma as id, nome FROM idiomas ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar idiomas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza um idioma existente.
     * @param int $id ID do idioma
     * @param string $nome Novo nome
     * @return bool True se atualizado, false se erro
     */
    public function atualizarIdioma($id, $nome) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_idioma FROM idiomas WHERE nome = :nome AND id_idioma != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de idioma já existe.');
            }

            $sql = "UPDATE idiomas SET nome = :nome WHERE id_idioma = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar idioma (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar idioma: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um idioma.
     * @param int $id ID do idioma
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirIdioma($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_idioma = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Idioma está em uso em livros e não pode ser excluído.');
            }

            $sql = "DELETE FROM idiomas WHERE id_idioma = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir idioma (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir idioma: " . $e->getMessage());
            return false;
        }
    }
}
?>