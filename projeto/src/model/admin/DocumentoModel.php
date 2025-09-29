<?php
/**
 * Model para cadastro de tipos de documento (usado em modal da tela de cadastro de livros).
 * Usa PDO real para inserção e listagem.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class DocumentoModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastra um novo tipo de documento via modal.
     * @param string $nome Nome do tipo de documento
     * @return int|false ID do tipo de documento inserido ou false se erro
     */
    public function cadastrarDocumento($nome) {
        try {
            if (empty($nome)) {
                throw new Exception('Nome do tipo de documento é obrigatório.');
            }

            // Verifica se já existe
            $sql_check = "SELECT id_documento FROM documentos WHERE nome = :nome";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Tipo de documento já cadastrado.');
            }

            $sql = "INSERT INTO documentos (nome) VALUES (:nome)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar tipo de documento (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação tipo de documento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os tipos de documento.
     * @return array Lista de tipos de documento {id_documento: id, nome: string}
     */
    public function getAllDocumentos() {
        try {
            $sql = "SELECT id_documento as id, nome FROM documentos ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar tipos de documento: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza um tipo de documento existente.
     * @param int $id ID do tipo de documento
     * @param string $nome Novo nome
     * @return bool True se atualizado, false se erro
     */
    public function atualizarDocumento($id, $nome) {
        try {
            if (empty($nome) || $id <= 0) {
                throw new Exception('ID e nome são obrigatórios.');
            }

            // Verifica se nome já existe em outro registro
            $sql_check = "SELECT id_documento FROM documentos WHERE nome = :nome AND id_documento != :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':nome', $nome);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('Nome de tipo de documento já existe.');
            }

            $sql = "UPDATE documentos SET nome = :nome WHERE id_documento = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar tipo de documento (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação atualizar tipo de documento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um tipo de documento.
     * @param int $id ID do tipo de documento
     * @return bool True se excluído, false se erro ou não encontrado
     */
    public function excluirDocumento($id) {
        try {
            if ($id <= 0) {
                throw new Exception('ID inválido.');
            }

            // Verifica se está em uso
            $sql_check = "SELECT COUNT(*) FROM livros WHERE id_documento = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Tipo de documento está em uso em livros e não pode ser excluído.');
            }

            $sql = "DELETE FROM documentos WHERE id_documento = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao excluir tipo de documento (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao excluir tipo de documento: " . $e->getMessage());
            return false;
        }
    }
}
?>