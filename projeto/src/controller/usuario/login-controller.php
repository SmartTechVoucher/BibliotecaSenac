<?php
require_once __DIR__ . "/../../../config/db/database.php";

class LoginController{

    private $conn;

    public function __construct(){
        $banco = new Database();

        $this->conn = $banco->Connect();

    }

    public function ValidarLogin($nome, $senha) {
        session_start();

        try {
            $sql = "SELECT * FROM usuarios WHERE numero_matricula = :nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) { 
                $_SESSION['usuario'] = [
                    'id' => $usuario['id_usuario'],
                    'nome' => $usuario['nome']
                ];
                $_SESSION['toast'] = [
                    'mensagem' => "Login efetuado com sucesso!",
                    'tipo' => "success"
                ];
                return true;
            } else {
                $_SESSION['toast'] = [
                    'mensagem' => "Usuário ou senha inválidos.",
                    'tipo' => "error"
                ];
                return false;
            }
        } catch (PDOException $th) {
            error_log("Erro ao validar login usuário: " . $th->getMessage());
            $_SESSION['toast'] = [
                'mensagem' => "Erro interno no servidor.",
                'tipo' => "error"
            ];
            return false;
        }
    }

}
