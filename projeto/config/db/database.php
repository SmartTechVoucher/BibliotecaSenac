<?php
class Database
{
    private $server = "localhost";
    private $dbname = "bibliotecasenac";
    private $user = "root";
    private $pass = "";

    public function Connect(){
        try {
            $conn = new PDO(
                "mysql:host=" . $this->server . ";dbname=" . $this->dbname,
                $this->user,$this->pass
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            echo "Deu certo porra";
            return $conn;

        } catch (\PDOException $th) {
            
            echo "Erro: ".$th->getMessage();
        }
        
    }

}

$db = new Database();
$db->Connect();

