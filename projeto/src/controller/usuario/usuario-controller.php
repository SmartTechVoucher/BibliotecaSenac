<?php
// UsuarioController.php
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/db/database.php';
 
class UsuarioController {
    private $db;
 
    public function __construct() {
        $this->db = new Database();
    }
 
    public function criarUsuario($nome, $nome_social, $cpf, $email, $data_nascimento, $telefone, $rua, $bairro,
                                 $numero_matricula, $id_categoria_curso, $id_curso, $data_inicio, $data_fim, $genero, $senha) {
        try {
            $conn = $this->db->Connect();
 
            if ($this->emailJaExiste($email)) {
                return false;
            }
 
            if ($this->cpfJaExiste($cpf)) {
                return false;
            }
 
            $sql = "INSERT INTO usuarios (nome, nome_social, cpf, email, data_nascimento, telefone, rua, bairro,
                    numero_matricula, id_categoria_curso, id_curso, data_inicio, data_fim, genero, senha)
                    VALUES (:nome, :nome_social, :cpf, :email, :data_nascimento, :telefone, :rua, :bairro,
                    :numero_matricula, :id_categoria_curso, :id_curso, :data_inicio, :data_fim, :genero, :senha)";
 
            $stmt = $conn->prepare($sql);
 
            $hash = password_hash($senha, PASSWORD_BCRYPT);
 
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
            $stmt->bindParam(':senha', $hash);
 
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
 
    public function validarLogin($email, $senha) {
        try {
            $conn = $this->db->Connect();
 
            // SELECT aliased para retornar 'id' consistente
            $sql = "SELECT id_usuario AS id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
 
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if ($usuario && isset($usuario['senha']) && password_verify($senha, $usuario['senha'])) {
                // remove senha do array retornado
                unset($usuario['senha']);
                return $usuario; // contém id, nome, email
            }
 
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao validar login: " . $e->getMessage());
            return false;
        }
    }
 
    private function emailJaExiste($email) {
        try {
            $conn = $this->db->Connect();
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro emailJaExiste: " . $e->getMessage());
            return false;
        }
    }
 
    private function cpfJaExiste($cpf) {
        try {
            $conn = $this->db->Connect();
            $sql = "SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro cpfJaExiste: " . $e->getMessage());
            return false;
        }
    }
}