<?php
/**
 * Model para operações relacionadas a administradores.
 * Usa conexão PDO real com banco de dados MySQL.
 * Todos os nomes e comentários em português.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class AdminModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    /**
     * Valida login de administrador com query real na tabela adminstrador.
     * Verifica email e senha (assumindo senha hashed com password_hash).
     * @param string $email Email do admin
     * @param string $senha Senha informada
     * @return array|false Dados do admin se válido, false caso contrário
     */
    public function validarLoginAdmin($email, $senha) {
        try {
            $sql = "SELECT * FROM adminstrador WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin_data && password_verify($senha, $admin_data['senha'])) {
                // Remove senha do retorno por segurança
                unset($admin_data['senha']);
                return $admin_data;
            }
        } catch (PDOException $e) {
            error_log("Erro ao validar login admin: " . $e->getMessage());
            return false;
        }

        return false;
    }

    /**
     * Obtém todos os administradores (para futuras listagens).
     * @return array Lista de admins
     */
    public function getTodosAdmins() {
        try {
            $sql = "SELECT id_administrador, nome, email, telefone FROM adminstrador";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar admins: " . $e->getMessage());
            return [];
        }
    }
}
?>