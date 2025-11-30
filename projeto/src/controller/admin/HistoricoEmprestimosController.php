<?php
// src/controller/admin/HistoricoEmprestimosController.php

require_once __DIR__ . '/../../../config/db/database.php';

class HistoricoEmprestimosController {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Lista histórico de empréstimos com filtros e paginação
     */
    public function listar() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Parâmetros de entrada
            $status_filtro = isset($_GET['status']) ? $_GET['status'] : 'Todos';
            $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
            $itens_por_pagina = isset($_GET['limite']) ? intval($_GET['limite']) : 5;
            
            if ($pagina < 1) $pagina = 1;
            if ($itens_por_pagina < 1) $itens_por_pagina = 5;
            
            $offset = ($pagina - 1) * $itens_por_pagina;

            // Monta query base
            $where_clauses = [];
            $params = [];

            // Filtro por status
            if ($status_filtro !== 'Todos') {
                // Mapeia status frontend para backend
                switch ($status_filtro) {
                    case 'Em andamento':
                        $where_clauses[] = "(m.status = 'Emprestado' OR m.status = 'Pendente')";
                        break;
                    case 'Atrasado':
                        $where_clauses[] = "m.status = 'Atrasado'";
                        break;
                    case 'Finalizado':
                        $where_clauses[] = "m.status = 'Devolvido'";
                        break;
                }
            }

            $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

            // Query para contar total
            $sql_count = "SELECT COUNT(*) as total 
                         FROM movimentacoes m 
                         $where_sql";
            
            $stmt_count = $this->conn->prepare($sql_count);
            $stmt_count->execute($params);
            $total_registros = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

            // Query para buscar dados
            $sql = "SELECT 
                        m.id_movimentacao,
                        m.id_usuario,
                        m.id_livro,
                        m.data_movimentacao,
                        m.data_limite_retirada,
                        m.data_prevista_devolucao,
                        m.data_real_devolucao,
                        m.status as status_bd,
                        u.nome as leitor_nome,
                        l.isbn,
                        l.titulo
                    FROM movimentacoes m
                    INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                    INNER JOIN livros l ON m.id_livro = l.id_livro
                    $where_sql
                    ORDER BY m.data_movimentacao DESC
                    LIMIT :limite OFFSET :offset";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limite', $itens_por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $emprestimos_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Processa cada empréstimo para calcular status correto
            $emprestimos = array_map(function($emp) {
                return $this->processarEmprestimo($emp);
            }, $emprestimos_raw);

            // Calcula total de páginas
            $total_paginas = ceil($total_registros / $itens_por_pagina);

            echo json_encode([
                'success' => true,
                'emprestimos' => $emprestimos,
                'paginacao' => [
                    'pagina_atual' => $pagina,
                    'itens_por_pagina' => $itens_por_pagina,
                    'total_registros' => $total_registros,
                    'total_paginas' => $total_paginas
                ]
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao listar histórico: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar histórico de empréstimos.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Processa um empréstimo e define o status correto
     */
    private function processarEmprestimo($emp) {
        $agora = new DateTime();
        $status_exibicao = '';
        $classe_css = '';

        // Define status baseado na lógica de negócio
        if ($emp['status_bd'] === 'Devolvido') {
            // FINALIZADO
            $status_exibicao = 'Finalizado';
            $classe_css = 'status-finalizado';
            
        } elseif ($emp['status_bd'] === 'Pendente') {
            // Ainda não retirou (48h para retirar)
            $limite_retirada = new DateTime($emp['data_limite_retirada']);
            
            if ($agora > $limite_retirada) {
                // Expirou o prazo de retirada
                $status_exibicao = 'Cancelado';
                $classe_css = 'status-cancelado';
            } else {
                // Ainda dentro do prazo de retirada
                $status_exibicao = 'Em andamento';
                $classe_css = 'status-andamento';
            }
            
        } elseif ($emp['status_bd'] === 'Emprestado') {
            // Já retirou, verificar se está no prazo
            $prazo_devolucao = new DateTime($emp['data_prevista_devolucao']);
            
            if ($agora > $prazo_devolucao) {
                // ATRASADO
                $status_exibicao = 'Atrasado';
                $classe_css = 'status-atrasado';
            } else {
                // EM ANDAMENTO (dentro do prazo)
                $status_exibicao = 'Em andamento';
                $classe_css = 'status-andamento';
            }
            
        } elseif ($emp['status_bd'] === 'Atrasado') {
            // ATRASADO (marcado pelo sistema)
            $status_exibicao = 'Atrasado';
            $classe_css = 'status-atrasado';
            
        } elseif ($emp['status_bd'] === 'Cancelado') {
            $status_exibicao = 'Cancelado';
            $classe_css = 'status-cancelado';
        } else {
            $status_exibicao = $emp['status_bd'];
            $classe_css = 'status-outro';
        }

        // Formata datas para exibição
        $data_emprestimo = $emp['data_movimentacao'] 
            ? date('d/m/Y', strtotime($emp['data_movimentacao'])) 
            : '---';
        
        $prazo_devolucao = $emp['data_prevista_devolucao']
            ? date('d/m/Y', strtotime($emp['data_prevista_devolucao']))
            : ($emp['data_limite_retirada'] 
                ? date('d/m/Y', strtotime($emp['data_limite_retirada']))
                : '---');
        
        $data_devolucao = $emp['data_real_devolucao']
            ? date('d/m/Y', strtotime($emp['data_real_devolucao']))
            : 'Em andamento';

        return [
            'id_movimentacao' => $emp['id_movimentacao'],
            'status' => $status_exibicao,
            'classe_css' => $classe_css,
            'exemplar' => $emp['isbn'] ?? '---',
            'leitor' => $emp['leitor_nome'] ?? 'Desconhecido',
            'titulo' => $emp['titulo'] ?? '',
            'data' => $data_emprestimo,
            'prazo' => $prazo_devolucao,
            'devolucao' => $data_devolucao,
            'status_original' => $emp['status_bd']
        ];
    }

    /**
     * Busca estatísticas gerais do histórico
     */
    public function estatisticas() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'Devolvido' THEN 1 ELSE 0 END) as finalizados,
                        SUM(CASE WHEN status = 'Atrasado' THEN 1 ELSE 0 END) as atrasados,
                        SUM(CASE WHEN status IN ('Emprestado', 'Pendente') THEN 1 ELSE 0 END) as em_andamento,
                        SUM(CASE WHEN status = 'Cancelado' THEN 1 ELSE 0 END) as cancelados
                    FROM movimentacoes";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'estatisticas' => $stats
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar estatísticas.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}

// Executa se for chamado diretamente
if (basename($_SERVER['PHP_SELF']) === 'HistoricoEmprestimosController.php') {
    $controller = new HistoricoEmprestimosController();
    
    $acao = $_GET['acao'] ?? 'listar';
    
    switch ($acao) {
        case 'listar':
            $controller->listar();
            break;
        case 'estatisticas':
            $controller->estatisticas();
            break;
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Ação inválida.'
            ], JSON_UNESCAPED_UNICODE);
    }
}