<?php
/**
 * UsuarioModel.php (refatorado)
 * Model para operações relacionadas a usuários
 *
 * - Reinsere foto_perfil nas listagens
 * - Bind seguro de parâmetros
 * - Tratamento de erros com logs
 */

require_once __DIR__ . '/../../../config/db/database.php';

class UsuarioModel {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->Connect();

        // Segurança: certificar fetch assoc por padrão (opcional)
        if ($this->conn) {
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * Normaliza e valida parâmetros de paginação
     */
    private function normalizePageLimit($pagina, $limite) {
        $pagina = (int)$pagina;
        $limite = (int)$limite;
        if ($pagina < 1) $pagina = 1;
        if ($limite < 1) $limite = 10;
        $offset = ($pagina - 1) * $limite;
        return [$pagina, $limite, $offset];
    }

    /**
     * LISTAR USUÁRIOS REGULARES (ativo = 1)
     */
    public function listarUsuariosRegulares($pagina = 1, $limite = 10, $busca = "") {
        try {
            list($pagina, $limite, $offset) = $this->normalizePageLimit($pagina, $limite);

            $sql = "SELECT 
                        id_usuario,
                        nome,
                        nome_social,
                        email,
                        cpf,
                        categoria,
                        unidade_senac,
                        numero_matricula,
                        telefone,
                        foto_perfil,
                        ativo
                    FROM {$this->table}
                    WHERE ativo = 1";

            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (
                            nome LIKE :busca OR 
                            email LIKE :busca OR
                            cpf LIKE :busca OR
                            numero_matricula LIKE :busca
                        )";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY id_usuario DESC LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            // bind de parâmetros dinâmicos
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $usuarios = $stmt->fetchAll();

            $total = $this->contarUsuariosRegulares($busca);

            return [
                "usuarios" => $usuarios,
                "total" => (int)$total,
                "pagina_atual" => (int)$pagina,
                "total_paginas" => ($limite > 0) ? (int)ceil($total / $limite) : 1
            ];
        } catch (PDOException $e) {
            error_log("Erro listarUsuariosRegulares: " . $e->getMessage());
            return ["usuarios" => [], "total" => 0, "pagina_atual" => 1, "total_paginas" => 1];
        }
    }

    /**
     * CONTAR REGULARES
     */
    public function contarUsuariosRegulares($busca = "") {
        try {
            $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE ativo = 1";
            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (
                            nome LIKE :busca OR 
                            email LIKE :busca OR
                            cpf LIKE :busca OR
                            numero_matricula LIKE :busca
                        )";
                $params[':busca'] = '%' . $busca . '%';
            }

            $stmt = $this->conn->prepare($sql);
            if (isset($params[':busca'])) $stmt->bindValue(':busca', $params[':busca'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro contarUsuariosRegulares: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * LISTAR BLOQUEADOS (ativo = 0)
     */
    public function listarUsuariosBloqueados($pagina = 1, $limite = 10, $busca = "") {
        try {
            list($pagina, $limite, $offset) = $this->normalizePageLimit($pagina, $limite);

            $sql = "SELECT 
                        id_usuario,
                        nome,
                        nome_social,
                        email,
                        cpf,
                        categoria,
                        unidade_senac,
                        numero_matricula,
                        telefone,
                        foto_perfil,
                        ativo
                    FROM {$this->table}
                    WHERE ativo = 0";

            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (
                            nome LIKE :busca OR 
                            email LIKE :busca OR
                            cpf LIKE :busca OR
                            numero_matricula LIKE :busca
                        )";
                $params[':busca'] = '%' . $busca . '%';
            }

            $sql .= " ORDER BY id_usuario DESC LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);

            if (isset($params[':busca'])) $stmt->bindValue(':busca', $params[':busca'], PDO::PARAM_STR);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $usuarios = $stmt->fetchAll();

            $total = $this->contarUsuariosBloqueados($busca);

            return [
                "usuarios" => $usuarios,
                "total" => (int)$total,
                "pagina_atual" => (int)$pagina,
                "total_paginas" => ($limite > 0) ? (int)ceil($total / $limite) : 1
            ];
        } catch (PDOException $e) {
            error_log("Erro listarUsuariosBloqueados: " . $e->getMessage());
            return ["usuarios" => [], "total" => 0, "pagina_atual" => 1, "total_paginas" => 1];
        }
    }

    /**
     * CONTAR BLOQUEADOS
     */
    public function contarUsuariosBloqueados($busca = "") {
        try {
            $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE ativo = 0";
            $params = [];
            if (!empty($busca)) {
                $sql .= " AND (
                            nome LIKE :busca OR 
                            email LIKE :busca OR
                            cpf LIKE :busca OR
                            numero_matricula LIKE :busca
                        )";
                $params[':busca'] = '%' . $busca . '%';
            }

            $stmt = $this->conn->prepare($sql);
            if (isset($params[':busca'])) $stmt->bindValue(':busca', $params[':busca'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro contarUsuariosBloqueados: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * PEGAR USUÁRIO POR ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_usuario = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch();
            return $user ? $user : false;
        } catch (PDOException $e) {
            error_log("Erro buscarPorId ({$id}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * ALTERAR STATUS (bloquear/desbloquear)
     */
    public function alterarStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET ativo = :status, data_atualizacao = NOW() WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':status', (int)$status, PDO::PARAM_INT);
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro alterarStatus ({$id}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * ATUALIZAR USUÁRIO
     * Recebe array $dados com chaves correspondentes às colunas (nome, email, etc).
     * Observação: validações devem ser feitas no controller antes de chamar este método.
     */
    public function atualizarUsuario($id, $dados) {
        try {
            // construir SET dinamicamente (apenas colunas passadas)
            $setParts = [];
            $params = [];
            foreach ($dados as $col => $val) {
                // evita sobrescrever coluna id_usuario
                if ($col === 'id_usuario' || $col === 'id') continue;
                $setParts[] = "{$col} = :{$col}";
                $params[":{$col}"] = $val;
            }

            if (empty($setParts)) {
                error_log("atualizarUsuario: nenhum campo para atualizar (id {$id})");
                return false;
            }

            $sql = "UPDATE {$this->table} SET " . implode(", ", $setParts) . ", data_atualizacao = NOW() WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);

            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro atualizarUsuario ({$id}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * 📊 ESTATÍSTICAS DE USUÁRIOS
     */
    public function getEstatisticasUsuarios() {
        try {
            $sql = "
                SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN ativo = 1 THEN 1 ELSE 0 END) AS ativos,
                    SUM(CASE WHEN ativo = 0 THEN 1 ELSE 0 END) AS bloqueados
                FROM {$this->table}
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $d = $stmt->fetch();

            $total = (int)($d['total'] ?? 0);
            $ativos = (int)($d['ativos'] ?? 0);
            $bloqueados = (int)($d['bloqueados'] ?? 0);

            return [
                "total_geral" => $total,
                "total_regulares" => $ativos,
                "total_bloqueados" => $bloqueados,
                "percentual_ativos" => $total > 0 ? round(($ativos / $total) * 100, 2) : 0,
                "percentual_bloqueados" => $total > 0 ? round(($bloqueados / $total) * 100, 2) : 0
            ];
        } catch (PDOException $e) {
            error_log("Erro getEstatisticasUsuarios: " . $e->getMessage());
            return [
                "total_geral" => 0,
                "total_regulares" => 0,
                "total_bloqueados" => 0,
                "percentual_ativos" => 0,
                "percentual_bloqueados" => 0
            ];
        }
    }
}
?>
