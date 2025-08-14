<?php
require_once __DIR__ . "/../../../config/db/database.php";

class LoginController{

    private $conn;

    public function __construct(){
        $banco = new Database();

        $this->conn = $banco->Connect();

    }

    public function ValidarLogin($nome,$email,$senha){
        
        if(empty($nome) || empty($email) || empty($senha)){ 
            echo "ERRO: nome, email e senha sao obrigatorios";
            return;
        }

        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if($stmt->rowCount() > 0){
            echo "Erro: Email já cadastrado!";
            return;
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $nome_social = '';
        $cpf = 'CPF'.rand(100000, 999999);
        $data_nascimento = '2000-01-01';
        $telefone = '0000000000';
        $rua = 'Rua Fictícia';
        $bairro = 'Bairro Teste';
        $numero_matricula = rand(1000, 9999);
        $data_inicio = '2025-01-01';
        $data_fim = '2025-12-31';         
        $id_categoria_usuario = 1;

        $sql = "INSERT INTO usuarios (nome, nome_social, cpf, email, data_nascimento, telefone, rua, bairro, numero_matricula, data_inicio, data_fim, senha, id_categoria_usuario)
        VALUES
        (:nome, :nome_social, :cpf, :email, :data_nascimento, :telefone, :rua, :bairro, :numero_matricula, :data_inicio, :data_fim, :senha, :id_categoria_usuario)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':nome_social' => $nome_social,
            ':cpf' => $cpf,
            ':email' => $email,
            ':data_nascimento' => $data_nascimento,
            ':telefone' => $telefone,
            ':rua' => $rua,
            ':bairro' => $bairro,
            ':numero_matricula' => $numero_matricula,
            ':data_inicio' => $data_inicio,    
            ':data_fim' => $data_fim,
            ':senha' => $hash,
            ':id_categoria_usuario' => $id_categoria_usuario

        ]);
        
        echo "Usuario criado com sucesso";

    //try {
        //$sql = "SELECT * FROM usuarios WHERE nome = :nome AND nome_social = :nome_social AND cpf = :cpf AND email = :email AND data_nascimento = :data_nascimento AND telefone = :telefone
        //AND rua = :rua AND bairro = :bairro AND numero_matricula = :numero_matricula AND data_inicio = :data_inicio AND data_fim = :data_fim";
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
//     public function ValidarLogin($nome, $senha) {
//     session_start();

//     // Verifica login de administrador
//     if ($nome === "admin123" && $senha === "2020") {
//         $_SESSION['usuario'] = [
//             'id' => 0,
//             'nome' => 'Administrador'
//         ];
//         $_SESSION['toast'] = [
//             'mensagem' => "Login efetuado com sucesso!",
//             'tipo' => "success"
//         ];
//         return true;
//     }

//     // Verifica login de usuário comum
//     if ($nome === "12345678910" && $senha === "2020") {
//         $_SESSION['usuario'] = [
//             'id' => 1,
//             'nome' => 'João da Silva'
//         ];
//         $_SESSION['toast'] = [
//             'mensagem' => "Login efetuado com sucesso!",
//             'tipo' => "success"
//         ];
//         return true;
//     }

//     // Login inválido
//     $_SESSION['toast'] = [
//         'mensagem' => "Usuário ou senha inválidos.",
//         'tipo' => "error"
//     ];
//     return false;
    }

}

$teste = new LoginController();
$teste->ValidarLogin("Gabriel","jogoperdi3@gmail.com", "1234");

