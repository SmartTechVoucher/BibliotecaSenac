<?php
require_once __DIR__ . "/../../../config/db/database.php";

class LoginController{

    private $conn;

    public function __construct(){
        $banco = new Database();

        $this->conn = $banco->Connect();

    }

    public function ValidarLogin($nome,$senha){
   
    try {
        $sql = "SELECT * FROM usuario WHERE nome = :nome AND senha = :senha";
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
    session_start();

    // Verifica login de administrador
    if ($nome === "admin123" && $senha === "2020") {
        $_SESSION['usuario'] = [
            'id' => 0,
            'nome' => 'Administrador'
        ];
        $_SESSION['toast'] = [
            'mensagem' => "Login efetuado com sucesso!",
            'tipo' => "success"
        ];
        return true;
    }

    // Verifica login de usuário comum
    if ($nome === "12345678910" && $senha === "2020") {
        $_SESSION['usuario'] = [
            'id' => 1,
            'nome' => 'João da Silva'
        ];
        $_SESSION['toast'] = [
            'mensagem' => "Login efetuado com sucesso!",
            'tipo' => "success"
        ];
        return true;
    }

    // Login inválido
    $_SESSION['toast'] = [
        'mensagem' => "Usuário ou senha inválidos.",
        'tipo' => "error"
    ];
    return false;
}
    

}
