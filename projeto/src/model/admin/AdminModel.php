<?php
require_once __DIR__ . "/../../../config/db/database.php";

class AdminModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    public function validarLogin($nome, $senha) {
        try {
            $sql = "SELECT * FROM adminstrador WHERE nome = :nome AND senha = :senha";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":senha", $senha);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            return $admin;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}