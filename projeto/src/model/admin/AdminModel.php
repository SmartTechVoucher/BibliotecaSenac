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

    /**
     * Busca administrador por email para recuperação de senha.
     * @param string $email Email do admin
     * @return array|false Dados do admin se encontrado, false caso contrário
     */
    public function buscarAdminPorEmail($email) {
        try {
            $sql = "SELECT * FROM adminstrador WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar admin por email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza token de reset de senha para administrador.
     * @param int $id_admin ID do administrador
     * @param string $token Token gerado
     * @param string $expiry Data de expiração
     * @return bool True se atualizado, false caso contrário
     */
    public function atualizarTokenReset($id_admin, $token, $expiry) {
        try {
            $sql = "UPDATE adminstrador SET reset_token = :token, reset_expiry = :expiry WHERE id_administrador = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expiry', $expiry);
            $stmt->bindParam(':id', $id_admin);
            $result = $stmt->execute();

            if (!$result) {
                error_log("SQL Execute failed. SQL: {$sql}, Params: token={$token}, expiry={$expiry}, id={$id_admin}");
                error_log("PDO Error Info: " . print_r($stmt->errorInfo(), true));
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar token reset: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Verifica se token de reset é válido.
     * @param string $token Token a verificar
     * @return array|false Dados do admin se token válido, false caso contrário
     */
    public function verificarTokenReset($token) {
        try {
            $sql = "SELECT * FROM adminstrador WHERE reset_token = :token AND reset_expiry > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao verificar token reset: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza senha do administrador e remove token de reset.
     * @param int $id_admin ID do administrador
     * @param string $nova_senha Senha nova hashed
     * @return bool True se atualizado, false caso contrário
     */
    public function atualizarSenhaERemoverToken($id_admin, $nova_senha) {
        try {
            $sql = "UPDATE adminstrador SET senha = :senha, reset_token = NULL, reset_expiry = NULL WHERE id_administrador = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':senha', $nova_senha);
            $stmt->bindParam(':id', $id_admin);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar senha: " . $e->getMessage());
            return false;
        }
    }
}
?>