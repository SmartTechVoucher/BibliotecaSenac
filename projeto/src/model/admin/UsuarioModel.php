<?php
/**
 * Model para operações relacionadas a usuários
 * Gerencia todas as operações de banco de dados relacionadas aos usuários
 * 
 * @package Model
 * @author Sistema Biblioteca SENAC
 * @version 2.0
 */

require_once __DIR__ . '/../../../config/db/database.php';

class UsuarioModel {
    private $conn;
    private $table = 'usuarios';

    /**
     * Construtor - Inicializa conexão com banco de dados
     * @throws Exception Se falhar na conexão
     */
    public function __construct() {
        try {
            $banco = new Database();
            $this->conn = $banco->Connect();
            
            if (!$this->conn) {
                throw new Exception('Falha na conexão com o banco de dados.');
            }
        } catch (Exception $e) {
            error_log("Erro ao conectar ao banco: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lista usuários regulares (ativos) com paginação
     * 
     * @param int $pagina Número da página (default 1)
     * @param int $limite Itens por página (default 10)
     * @param string $busca Termo de busca opcional
     * @return array Array com usuários e informações de paginação
     */
    public function listarUsuariosRegulares($pagina = 1, $limite = 10, $busca = '') {
        try {
            $offset = ($pagina - 1) * $limite;

            $sql = "SELECT
                        u.id_usuario,
                        u.nome,
                        u.nome_social,
                        u.cpf,
                        u.email,
                        u.data_nascimento,
                        u.telefone,
                        u.endereco,
                        u.genero,
                        u.foto_perfil,
                        u.numero_matricula,
                        u.categoria,
                        u.unidade_senac,
                        u.curso,
                        u.turma,
                        u.data_fim_curso,
                        u.notas_usuario,
                        u.data_criacao,
                        u.data_atualizacao,
                        u.ativo,
                        c.nome as categoria_usuario_nome
                    FROM {$this->table} u
                    LEFT JOIN categorias_usuario c ON u.categoria = c.nome
                    WHERE u.ativo = 1";

            $params = [];
            
            if (!empty($busca)) {
                $sql .= " AND (u.nome LIKE :busca OR u.email LIKE :busca OR u.cpf LIKE :busca OR u.numero_matricula LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY u.nome ASC LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = $this->contarUsuarios(true, $busca);

            return [
                'usuarios' => $usuarios,
                'total' => $total,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total / $limite)
            ];

        } catch (PDOException $e) {
            error_log("Erro ao listar usuários regulares: " . $e->getMessage());
            return [
                'usuarios' => [], 
                'total' => 0, 
                'pagina_atual' => 1, 
                'total_paginas' => 0
            ];
        }
    }

    /**
     * Lista usuários bloqueados (inativos) com paginação
     * 
     * @param int $pagina Número da página (default 1)
     * @param int $limite Itens por página (default 10)
     * @param string $busca Termo de busca opcional
     * @return array Array com usuários e informações de paginação
     */
    public function listarUsuariosBloqueados($pagina = 1, $limite = 10, $busca = '') {
        try {
            $offset = ($pagina - 1) * $limite;

            $sql = "SELECT
                        u.id_usuario,
                        u.nome,
                        u.nome_social,
                        u.cpf,
                        u.email,
                        u.data_nascimento,
                        u.telefone,
                        u.endereco,
                        u.genero,
                        u.foto_perfil,
                        u.numero_matricula,
                        u.categoria,
                        u.unidade_senac,
                        u.curso,
                        u.turma,
                        u.data_fim_curso,
                        u.notas_usuario,
                        u.data_criacao,
                        u.data_atualizacao,
                        u.ativo,
                        c.nome as categoria_usuario_nome
                    FROM {$this->table} u
                    LEFT JOIN categorias_usuario c ON u.categoria = c.nome
                    WHERE u.ativo = 0";

            $params = [];
            
            if (!empty($busca)) {
                $sql .= " AND (u.nome LIKE :busca OR u.email LIKE :busca OR u.cpf LIKE :busca OR u.numero_matricula LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY u.nome ASC LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = $this->contarUsuarios(false, $busca);

            return [
                'usuarios' => $usuarios,
                'total' => $total,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total / $limite)
            ];

        } catch (PDOException $e) {
            error_log("Erro ao listar usuários bloqueados: " . $e->getMessage());
            return [
                'usuarios' => [], 
                'total' => 0, 
                'pagina_atual' => 1, 
                'total_paginas' => 0
            ];
        }
    }

    /**
     * Conta total de usuários (ativos ou bloqueados)
     * 
     * @param bool $ativos True para contar ativos, false para bloqueados
     * @param string $busca Termo de busca opcional
     * @return int Total de usuários
     */
    private function contarUsuarios($ativos = true, $busca = '') {
        try {
            $statusAtivo = $ativos ? 1 : 0;
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE ativo = :ativo";

            $params = [':ativo' => $statusAtivo];

            if (!empty($busca)) {
                $sql .= " AND (nome LIKE :busca OR email LIKE :busca OR cpf LIKE :busca OR numero_matricula LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                if ($key === ':ativo') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return (int) $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Erro ao contar usuários: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Busca um usuário específico por ID
     * 
     * @param int $id_usuario ID do usuário
     * @return array|false Dados do usuário ou false se não encontrado
     */
    public function buscarUsuarioPorId($id_usuario) {
        try {
            $sql = "SELECT
                        u.*,
                        c.nome as categoria_usuario_nome
                    FROM {$this->table} u
                    LEFT JOIN categorias_usuario c ON u.categoria = c.nome
                    WHERE u.id_usuario = :id_usuario";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário {$id_usuario}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um usuário por email
     * 
     * @param string $email Email do usuário
     * @return array|false Dados do usuário ou false se não encontrado
     */
    public function buscarUsuarioPorEmail($email) {
        try {
            $sql = "SELECT id_usuario, email, ativo FROM {$this->table} WHERE email = :email";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um usuário por CPF
     * 
     * @param string $cpf CPF do usuário
     * @return array|false Dados do usuário ou false se não encontrado
     */
    public function buscarUsuarioPorCPF($cpf) {
        try {
            // Remove formatação do CPF
            $cpf = preg_replace('/[^\d]/', '', $cpf);
            
            $sql = "SELECT id_usuario, cpf, ativo FROM {$this->table} WHERE cpf = :cpf";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por CPF: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza dados de um usuário
     * 
     * @param int $id_usuario ID do usuário
     * @param array $dados Dados a serem atualizados
     * @return bool True se atualizado com sucesso, false caso contrário
     */
    public function atualizarUsuario($id_usuario, $dados) {
        try {
            $campos = [];
            $params = [];

            // Campos que podem ser atualizados
            $camposPermitidos = [
                'nome' => PDO::PARAM_STR,
                'nome_social' => PDO::PARAM_STR,
                'email' => PDO::PARAM_STR,
                'data_nascimento' => PDO::PARAM_STR,
                'telefone' => PDO::PARAM_STR,
                'endereco' => PDO::PARAM_STR,
                'genero' => PDO::PARAM_STR,
                'numero_matricula' => PDO::PARAM_STR,
                'categoria' => PDO::PARAM_STR,
                'unidade_senac' => PDO::PARAM_STR,
                'curso' => PDO::PARAM_STR,
                'turma' => PDO::PARAM_STR,
                'data_fim_curso' => PDO::PARAM_STR,
                'notas_usuario' => PDO::PARAM_STR
            ];

            foreach ($dados as $campo => $valor) {
                if (isset($camposPermitidos[$campo]) && $valor !== '') {
                    $campos[] = "{$campo} = :{$campo}";
                    $params[":{$campo}"] = [
                        'valor' => $valor,
                        'tipo' => $camposPermitidos[$campo]
                    ];
                }
            }

            if (empty($campos)) {
                error_log("Nenhum campo válido para atualizar no usuário ID {$id_usuario}");
                return false;
            }

            // Adicionar timestamp de atualização
            $campos[] = "data_atualizacao = NOW()";
            $params[':id_usuario'] = [
                'valor' => $id_usuario,
                'tipo' => PDO::PARAM_INT
            ];

            $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id_usuario = :id_usuario";

            $stmt = $this->conn->prepare($sql);

            // Bind dos parâmetros
            foreach ($params as $key => $param) {
                $stmt->bindValue($key, $param['valor'], $param['tipo']);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                error_log("Usuário ID {$id_usuario} atualizado com sucesso");
                return true;
            }

            error_log("Nenhuma linha afetada ao atualizar usuário ID {$id_usuario}");
            return false;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário {$id_usuario}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bloqueia um usuário (define ativo = 0)
     * 
     * @param int $id_usuario ID do usuário
     * @return bool True se bloqueado com sucesso, false caso contrário
     */
    public function bloquearUsuario($id_usuario) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET ativo = 0, data_atualizacao = NOW() 
                    WHERE id_usuario = :id_usuario AND ativo = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                error_log("Usuário ID {$id_usuario} bloqueado com sucesso");
                return true;
            }

            return false;

        } catch (PDOException $e) {
            error_log("Erro ao bloquear usuário {$id_usuario}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Desbloqueia um usuário (define ativo = 1)
     * 
     * @param int $id_usuario ID do usuário
     * @return bool True se desbloqueado com sucesso, false caso contrário
     */
    public function desbloquearUsuario($id_usuario) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET ativo = 1, data_atualizacao = NOW() 
                    WHERE id_usuario = :id_usuario AND ativo = 0";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                error_log("Usuário ID {$id_usuario} desbloqueado com sucesso");
                return true;
            }

            return false;

        } catch (PDOException $e) {
            error_log("Erro ao desbloquear usuário {$id_usuario}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém estatísticas dos usuários
     * 
     * @return array Estatísticas (total_regulares, total_bloqueados, total_geral)
     */
    public function getEstatisticasUsuarios() {
        try {
            $sql = "SELECT
                        COUNT(CASE WHEN ativo = 1 THEN 1 END) as total_regulares,
                        COUNT(CASE WHEN ativo = 0 THEN 1 END) as total_bloqueados,
                        COUNT(*) as total_geral
                    FROM {$this->table}";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter estatísticas de usuários: " . $e->getMessage());
            return [
                'total_regulares' => 0, 
                'total_bloqueados' => 0, 
                'total_geral' => 0
            ];
        }
    }

    /**
     * Valida CPF
     * 
     * @param string $cpf CPF a ser validado
     * @return bool True se válido, false caso contrário
     */
    public function validarCPF($cpf) {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^\d]/', '', $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }

        // Calcula primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int)$cpf[$i] * (10 - $i);
        }

        $resto = ($soma * 10) % 11;
        if ($resto === 10 || $resto === 11) {
            $resto = 0;
        }
        
        if ($resto !== (int)$cpf[9]) {
            return false;
        }

        // Calcula segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int)$cpf[$i] * (11 - $i);
        }

        $resto = ($soma * 10) % 11;
        if ($resto === 10 || $resto === 11) {
            $resto = 0;
        }

        return $resto === (int)$cpf[10];
    }

    /**
     * Valida formato de email
     * 
     * @param string $email Email a ser validado
     * @return bool True se válido, false caso contrário
     */
    public function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
?>