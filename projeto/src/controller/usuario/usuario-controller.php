<?php
// UsuarioController.php
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/db/database.php';

class UsuarioController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function criarUsuario($nome, $nome_social, $cpf, $email, $data_nascimento, $telefone, 
                                $endereco, $genero, $foto_perfil, $numero_matricula, $categoria, 
                                $unidade_senac, $curso, $turma, $data_fim_curso, $notas_usuario, $senha) {
        try {
            $conn = $this->db->Connect();

            if ($this->emailJaExiste($email)) {
                return ['success' => false, 'message' => 'Este email já está cadastrado!'];
            }

            if ($this->cpfJaExiste($cpf)) {
                return ['success' => false, 'message' => 'Este CPF já está cadastrado!'];
            }

            if (!$this->validarCategoria($categoria)) {
                return ['success' => false, 'message' => 'Categoria inválida!'];
            }

            if (!$this->validarUnidade($unidade_senac)) {
                return ['success' => false, 'message' => 'Unidade SENAC inválida!'];
            }

            if ($genero && !$this->validarGenero($genero)) {
                return ['success' => false, 'message' => 'Gênero inválido!'];
            }

            $sql = "INSERT INTO usuarios (nome, nome_social, cpf, email, data_nascimento, telefone, endereco,
                    genero, foto_perfil, numero_matricula, categoria, unidade_senac, curso, turma, 
                    data_fim_curso, notas_usuario, senha)
                    VALUES (:nome, :nome_social, :cpf, :email, :data_nascimento, :telefone, :endereco,
                    :genero, :foto_perfil, :numero_matricula, :categoria, :unidade_senac, :curso, :turma,
                    :data_fim_curso, :notas_usuario, :senha)";

            $stmt = $conn->prepare($sql);
            $hash = password_hash($senha, PASSWORD_BCRYPT);

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':nome_social', $nome_social);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':data_nascimento', $data_nascimento);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':foto_perfil', $foto_perfil);
            $stmt->bindParam(':numero_matricula', $numero_matricula);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':unidade_senac', $unidade_senac);
            $stmt->bindParam(':curso', $curso);
            $stmt->bindParam(':turma', $turma);
            $stmt->bindParam(':data_fim_curso', $data_fim_curso);
            $stmt->bindParam(':notas_usuario', $notas_usuario);
            $stmt->bindParam(':senha', $hash);

            $result = $stmt->execute();
            
            if ($result) {
                return ['success' => true, 'message' => 'Usuário criado com sucesso!'];
            } else {
                return ['success' => false, 'message' => 'Erro ao executar query de inserção'];
            }
            
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno do servidor'];
        }
    }

    public function validarLogin($email, $senha) {
        try {
            $conn = $this->db->Connect();

            $sql = "SELECT id_usuario AS id, nome, email, senha, categoria, foto_perfil, ativo 
                    FROM usuarios 
                    WHERE email = :email AND ativo = 1 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && isset($usuario['senha']) && password_verify($senha, $usuario['senha'])) {
                unset($usuario['senha']);
                return $usuario;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Erro ao validar login: " . $e->getMessage());
            return false;
        }
    }

    public function obterUsuarioPorId($id) {
        try {
            $conn = $this->db->Connect();
            
            // Usa os nomes reais das colunas conforme o phpMyAdmin
            $sql = "SELECT 
                        id_usuario,
                        nome,
                        nome_social,
                        cpf,
                        email,
                        data_nascimento,
                        telefone,
                        endereco,
                        genero,
                        foto_perfil,
                        numero_matricula,
                        categoria,
                        unidade_senac,
                        curso,
                        turma,
                        data_fim_curso,
                        notas_usuario,
                        ativo,
                        data_criacao,
                        data_atualizacao
                    FROM usuarios 
                    WHERE id_usuario = :id AND ativo = 1 
                    LIMIT 1";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                // Remove senha por segurança (só pra garantir)
                unset($usuario['senha']);
            }
            
            return $usuario;
            
        } catch (PDOException $e) {
            error_log("Erro ao obter usuário: " . $e->getMessage());
            return false;
        }
}


    /**
     * =====================================================
     * MÉTODOS PARA PERFIL DO USUÁRIO
     * =====================================================
     */

    /**
     * Atualiza o apelido do usuário
     * @param int $id_usuario
     * @param string $apelido
     * @return bool
     */
    public function atualizarApelido($id_usuario, $apelido) {
        try {
            $conn = $this->db->Connect();
            
            $sql = "UPDATE usuarios 
                    SET apelido = :apelido, 
                        updated_at = NOW() 
                    WHERE id_usuario = :id AND ativo = 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':apelido', $apelido, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar apelido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza o nome social do usuário
     * @param int $id_usuario
     * @param string $nome_social
     * @return bool
     */
    public function atualizarNomeSocial($id_usuario, $nome_social) {
        try {
            $conn = $this->db->Connect();
            
            $sql = "UPDATE usuarios 
                    SET nome_social = :nome_social, 
                        updated_at = NOW() 
                    WHERE id_usuario = :id AND ativo = 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome_social', $nome_social, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar nome social: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza a foto de perfil do usuário
     * @param int $id_usuario
     * @param string $caminho_foto
     * @return bool
     */
    public function atualizarFotoPerfil($id_usuario, $caminho_foto) {
        try {
            $conn = $this->db->Connect();
            
            $sql = "UPDATE usuarios 
                    SET foto_perfil = :foto, 
                        updated_at = NOW() 
                    WHERE id_usuario = :id AND ativo = 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':foto', $caminho_foto, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar foto de perfil: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza dados pessoais do usuário (telefone, endereço)
     * @param int $id_usuario
     * @param array $dados
     * @return bool
     */
    public function atualizarDadosPessoais($id_usuario, $dados) {
        try {
            $conn = $this->db->Connect();
            
            $campos_permitidos = ['telefone', 'endereco'];
            $set_clausulas = [];
            $parametros = [':id' => $id_usuario];

            foreach ($campos_permitidos as $campo) {
                if (isset($dados[$campo])) {
                    $set_clausulas[] = "$campo = :$campo";
                    $parametros[":$campo"] = $dados[$campo];
                }
            }

            if (empty($set_clausulas)) {
                return false;
            }

            $sql = "UPDATE usuarios 
                    SET " . implode(', ', $set_clausulas) . ", 
                        updated_at = NOW() 
                    WHERE id_usuario = :id AND ativo = 1";

            $stmt = $conn->prepare($sql);
            
            foreach ($parametros as $param => $valor) {
                $stmt->bindValue($param, $valor);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar dados pessoais: " . $e->getMessage());
            return false;
        }
    }

    /**
     * =====================================================
     * MÉTODOS PRIVADOS DE VALIDAÇÃO
     * =====================================================
     */

    private function emailJaExiste($email) {
        try {
            $conn = $this->db->Connect();
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email AND ativo = 1";
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
            $sql = "SELECT COUNT(*) FROM usuarios WHERE cpf = :cpf AND ativo = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro cpfJaExiste: " . $e->getMessage());
            return false;
        }
    }

    private function validarCategoria($categoria) {
        $categoriasValidas = ['Aluno', 'Docente', 'Bibliotecario'];
        return in_array($categoria, $categoriasValidas);
    }

    private function validarUnidade($unidade) {
        $unidadesValidas = ['Senac Hub Academy', 'Senac Dourados', 'Senac Três Lagoas'];
        return in_array($unidade, $unidadesValidas);
    }

    private function validarGenero($genero) {
        $generosValidos = ['Masculino', 'Feminino', 'Não binario', 'Outros', 'Não informar'];
        return in_array($genero, $generosValidos);
    }
}