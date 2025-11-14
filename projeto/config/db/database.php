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
                "mysql:host=" . $this->server . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $conn;

        } catch (PDOException $e) {
            error_log("Erro de conexão DB: " . $e->getMessage());
            // Lança a exceção em vez de retornar null
            throw new Exception("Falha na conexão com o banco de dados: " . $e->getMessage());
        }
    }
}
?>
