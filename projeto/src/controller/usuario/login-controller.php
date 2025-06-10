<?php
require_once __DIR__ . "/../../../config/db/database.php";

class LoginController{

    private $conn;

    public function __construct(){
        $banco = new Database();

        $this->conn = $banco->Connect();
    }

    // public function ValidarLogin($nome,$senha){
    //     if (!preg_match('/^\d{11}$/', $nome)) {
    //         $_SESSION['toast'] = "Usuário deve conter exatamente 11 números.";
    //         return false;
    //     }
    
    //     if (!preg_match('/^\d{4}$/', $senha)) {
    //         $_SESSION['toast'] = "A senha deve conter exatamente 4 números.";
    //         return false;
    //     }
    
    //     try {
    //         $sql = "SELECT * FROM usuario WHERE nome = :nome AND senha = :senha";
    //         $db = $this->conn->prepare($sql);
    //         $db->bindParam(":nome",$nome);
    //         $db->bindParam(":senha",$senha);
    //         $db->execute();
    //         $usuario = $db->fetchAll(PDO::FETCH_ASSOC);

    //         if ($usuario) {
    //             // Salva os dados na sessão
    //             $_SESSION['usuario'] = [
    //                 'id' => $usuario['id'],
    //                 'nome' => $usuario['nome']
    //             ];
    //             $_SESSION['toast'] = "Login efetuado com sucesso!";
    //             return true;
    //         } else {
    //             $_SESSION['toast'] = "Usuário ou senha inválidos.";
    //             return false;
                
    //     } catch (\Throwable $th) {
    //         $_SESSION['toast'] = "Erro interno no servidor.";
    //         return false;
    //         //throw $th;
    //     }
    // }
    public function ValidarLogin($nome, $senha) {
        // Simulação de login
        if ($nome === "12345678901" && $senha === "Mar152007#") {
            $_SESSION['usuario'] = [
                'id' => 1,
                'nome' => 'João da Silva'
            ];
            $_SESSION['toast'] = "Login efetuado com sucesso!";
            return true;
        } else {
            $_SESSION['toast'] = "Usuário ou senha inválidos.";
            return false;
        }
    }
    

}
