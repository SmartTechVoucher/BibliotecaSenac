<?php
require_once __DIR__ . "/../../../config/db/database.php";

/**
 * Modelo responsável pelas operações da tabela usuarios
 */
class UsuarioModel
{
    private $conn;

    public function __construct()
    {
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    /**
     * Valida login de usuário comum verificando na tabela usuarios
     */
    public function validarLogin($email, $numero_matricula)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email AND numero_matricula = :numero_matricula";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":numero_matricula", $numero_matricula);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Busca um usuário pelo ID
     */
    public function buscarUsuarioPorId($id_usuario)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Atualiza a senha de um usuário
     */
    public function atualizarSenha($id_usuario, $nova_senha)
    {
        try {
            $sql = "UPDATE usuarios SET senha = :senha WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":senha", $nova_senha);
            $stmt->bindParam(":id_usuario", $id_usuario);
            return $stmt->execute();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}