<?php

require_once __DIR__ . "/../../../config/db/database.php";

class UsuarioController{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function criarUsuario($nome, $nome_social, $cpf, $email, $data_nascimento, $telefone, $rua, $bairro, 
    $numero_matricula, $id_categoria_curso, $id_curso, $data_inicio, $data_fim, $genero, $senha){
        try {
            $conn = $this->db->Connect();

            if (empty($nome)|| empty($nome_social) || empty($cpf) || empty($email) || empty($data_nascimento) 
                || empty($telefone) || empty($rua) || empty($bairro) || empty($numero_matricula) || empty($id_categoria_curso) 
                || empty($id_curso) || empty($data_inicio) || empty($data_fim) || empty($genero) || empty($senha)) {
                throw new InvalidArgumentException("Todos os campos são obrigatórios.");
            }

            if ($this->emailJaExiste($Email)){
                throw new InvalidArgumentException("Email já cadastro.");
            }

            if($this->cpfJaExiste($cpf)){
                throw new InvalidArgumentException("CPF já cadastrado.");
            }

            $sql = "INSERT INTO usuarios (nome, nome_social, cpf, email, data_nascimento, telefone, rua, bairro, 
            numero_matricula, id_categoria_curso, id_curso, data_inicio, data_fim, genero, senha) VALUES (:nome, :nome_social, :cpf, :email, :data_nascimento, :telefone, :rua, :bairro, 
            :numero_matricula, :id_categoria_curso, :id_curso, :data_inicio, :data_fim, :genero, :senha)";

            $db = $conn->prepare($sql);
            
            $db->bindParam(':nome', $nome);
            $db->bindParam(':nome_social', $nome_social);
            $db->bindParam(':cpf', $cpf);
            $db->bindParam(':data_nascimento', $data_nascimento);
            $db->bindParam(':telefone', $telefone);
            $db->bindParam(':rua', $rua);
            $db->bindParam(':bairro', $bairro);
            $db->bindParam(':numero_matricula', $numero_matricula);
            $db->bindParam(':id_categoria_curso', $id_categoria_curso);
            $db->bindParam(':id_curso', $id_curso);
            $db->bindParam(':data_inicio', $data_inicio);
            $db->bindParam(':data_fim', $data_fim);
            $db->bindParam(':genero', $genero);
            $db->bindParam(':senha', password_hash($senha, PASSWORD_BCRYPT));
            $db->bindParam(':email', $email);

            if($db->execute()){
                return True;
            }else{
                return False;
            }
        } catch (PDOException $e) {
            // Log error or handle it as needed
            return false;
        }
    }
}
