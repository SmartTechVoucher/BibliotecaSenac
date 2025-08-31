<?php

require(__DIR__ . '/../../../config/constantes.php');
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
            
            // Verificar se email já existe
            if ($this->emailJaExiste($email)) {
                return false;
            }
            
            // Verificar se CPF já existe
            if ($this->cpfJaExiste($cpf)) {
                return false;
            }
            
            $sql = "INSERT INTO usuarios (nome, nome_social, cpf, email, data_nascimento, telefone, rua, bairro, 
                    numero_matricula, id_categoria_curso, id_curso, data_inicio, data_fim, genero, senha) 
                    VALUES (:nome, :nome_social, :cpf, :email, :data_nascimento, :telefone, :rua, :bairro, 
                    :numero_matricula, :id_categoria_curso, :id_curso, :data_inicio, :data_fim, :genero, :senha)";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':nome_social', $nome_social);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':data_nascimento', $data_nascimento);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':rua', $rua);
            $stmt->bindParam(':bairro', $bairro);
            $stmt->bindParam(':numero_matricula', $numero_matricula);
            $stmt->bindParam(':id_categoria_curso', $id_categoria_curso);
            $stmt->bindParam(':id_curso', $id_curso);
            $stmt->bindParam(':data_inicio', $data_inicio);
            $stmt->bindParam(':data_fim', $data_fim);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':senha', password_hash($senha, PASSWORD_BCRYPT));

            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    // Função para validar login
    public function validarLogin($email, $senha) {
        try {
            $conn = $this->db->Connect();
            
            $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Remove a senha do retorno por segurança
                unset($usuario['senha']);
                return $usuario;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Erro ao validar login: " . $e->getMessage());
            return false;
        }
    }
    
    // Verificar se email já existe
    private function emailJaExiste($email) {
        try {
            $conn = $this->db->Connect();
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Verificar se CPF já existe
    private function cpfJaExiste($cpf) {
        try {
            $conn = $this->db->Connect();
            $sql = "SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}