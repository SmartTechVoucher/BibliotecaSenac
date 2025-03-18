<?php
require_once __DIR__ . "/../../config/db/database.php";

class LoginController{

    private $conn;

    public function __construct(){
        $banco = new Database();

        $this->conn = $banco->Connect();
    }

    public function ValidarLogin($nome,$senha){
        try {
            $sql = "SELECT * FROM usuario WHERE nome = :nome AND senha = :senha";
            $db = $this->conn->prepare($sql);
            $db->bindParam(":nome",$nome);
            $db->bindParam(":senha",$senha);
            $db->execute();
            $usuario = $db->fetchAll(PDO::FETCH_ASSOC);

            if($usuario){
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

