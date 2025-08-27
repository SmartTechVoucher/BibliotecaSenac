<?php

require_once __DIR__ . "/../../../config/db/database.php";

class UsuarioController{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function criarUsuario($nome, $senha, $nome_social, $email){
        try {
            $conn = $this->db->Connect();
            $sql = "INSERT INTO usuario (nome, senha, email) VALUES (:nome, :senha, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':senha', password_hash($senha, PASSWORD_BCRYPT));
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Log error or handle it as needed
            return false;
        }
    }
}
