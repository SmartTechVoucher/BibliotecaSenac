<?php
/**
 * Model para operações relacionadas a usuários regulares.
 * Gerencia listagem, bloqueio/desbloqueio de usuários.
 * Usa conexão PDO real com banco de dados MySQL.
 */

require_once __DIR__ . '/../../../config/db/database.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Lista usuários regulares com paginação
     * @param int $pagina Número da página (default 1)
     * @param int $limite Itens por página (default 10)
     * @param string $busca Termo de busca opcional
     * @return array
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
                    FROM usuarios u
                    LEFT JOIN categorias_usuario c ON u.categoria = c.nome
                    WHERE u.ativo = 1";

            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (u.nome LIKE :busca OR u.email LIKE :busca OR u.cpf LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY u.nome LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Contar total para paginação
            $total = $this->contarUsuariosRegulares($busca);

            return [
                'usuarios' => $usuarios,
                'total' => $total,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total / $limite)
            ];

        } catch (PDOException $e) {
            error_log("Erro ao listar usuários regulares: " . $e->getMessage());
            return ['usuarios' => [], 'total' => 0, 'pagina_atual' => 1, 'total_paginas' => 0];
        }
    }

    /**
     * Lista usuários bloqueados com paginação
     * @param int $pagina Número da página (default 1)
     * @param int $limite Itens por página (default 10)
     * @param string $busca Termo de busca opcional
     * @return array
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
                    FROM usuarios u
                    LEFT JOIN categorias_usuario c ON u.categoria = c.nome
                    WHERE u.ativo = 0";

            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (u.nome LIKE :busca OR u.email LIKE :busca OR u.cpf LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY u.nome LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }

            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Contar total para paginação
            $total = $this->contarUsuariosBloqueados($busca);

            return [
                'usuarios' => $usuarios,
                'total' => $total,
                'pagina_atual' => $pagina,
                'total_paginas' => ceil($total / $limite)
            ];

        } catch (PDOException $e) {
            error_log("Erro ao listar usuários bloqueados: " . $e->getMessage());
            return ['usuarios' => [], 'total' => 0, 'pagina_atual' => 1, 'total_paginas' => 0];
        }
    }

    /**
     * Conta total de usuários regulares
     * @param string $busca Termo de busca opcional
     * @return int
     */
    private function contarUsuariosRegulares($busca = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE ativo = 1";

            if (!empty($busca)) {
                $sql .= " AND (nome LIKE :busca OR email LIKE :busca OR cpf LIKE :busca)";
            }

            $stmt = $this->conn->prepare($sql);

            if (!empty($busca)) {
                $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
            }

            $stmt->execute();
            return (int) $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Erro ao contar usuários regulares: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Conta total de usuários bloqueados
     * @param string $busca Termo de busca opcional
     * @return int
     */
    private function contarUsuariosBloqueados($busca = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE ativo = 0";

            if (!empty($busca)) {
                $sql .= " AND (nome LIKE :busca OR email LIKE :busca OR cpf LIKE :busca)";
            }

            $stmt = $this->conn->prepare($sql);

            if (!empty($busca)) {
                $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
            }

            $stmt->execute();
            return (int) $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Erro ao contar usuários bloqueados: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Bloqueia um usuário (define ativo = 0)
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function bloquearUsuario($id_usuario) {
        try {
            $sql = "UPDATE usuarios SET ativo = 0, data_atualizacao = NOW() WHERE id_usuario = :id_usuario AND ativo = 1";
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
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function desbloquearUsuario($id_usuario) {
        try {
            $sql = "UPDATE usuarios SET ativo = 1, data_atualizacao = NOW() WHERE id_usuario = :id_usuario AND ativo = 0";
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
     * Busca um usuário específico por ID
     * @param int $id_usuario
     * @return array|false
     */
    public function buscarUsuarioPorId($id_usuario) {
        try {
            $sql = "SELECT
                        u.*,
                        c.nome as categoria_usuario_nome
                    FROM usuarios u
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
     * Obtém estatísticas dos usuários
     * @return array
     */
    public function getEstatisticasUsuarios() {
        try {
            $sql = "SELECT
                        COUNT(CASE WHEN ativo = 1 THEN 1 END) as total_regulares,
                        COUNT(CASE WHEN ativo = 0 THEN 1 END) as total_bloqueados,
                        COUNT(*) as total_geral
                    FROM usuarios";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao obter estatísticas de usuários: " . $e->getMessage());
            return ['total_regulares' => 0, 'total_bloqueados' => 0, 'total_geral' => 0];
        }
    }
}
?>