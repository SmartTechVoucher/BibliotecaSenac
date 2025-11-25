<?php
// src/model/MovimentacaoModel.php

require_once __DIR__ . '/../../config/db/database.php';
require_once __DIR__ . '/FilaReservaModel.php';

class MovimentacaoModel {
    private $conn;
    private $filaModel;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
        $this->filaModel = new FilaReservaModel();
    }

    /**
     * Cria um empréstimo PENDENTE
     * Usuário tem 48h para retirar na biblioteca
     */
    public function criarEmprestimo($id_livro, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            // 1. Verifica se o livro está disponível
            $estoque = $this->getEstoqueLivro($id_livro);
            if ($estoque['disponiveis'] <= 0) {
                throw new Exception('Livro indisponível no momento.');
            }

            // 2. Verifica se usuário está bloqueado
            if ($this->usuarioBloqueadoPorAtraso($id_usuario)) {
                throw new Exception('Você está temporariamente bloqueado por atraso na devolução.');
            }

            // 3. Verifica se usuário já tem empréstimo PENDENTE ou ATIVO deste livro
            $sql_check = "SELECT COUNT(*) FROM movimentacoes 
                        WHERE id_usuario = :id_usuario 
                        AND id_livro = :id_livro 
                        AND status IN ('Pendente', 'Emprestado')";

            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->execute([
                ':id_usuario' => $id_usuario,
                ':id_livro' => $id_livro
            ]);

            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Você já possui um empréstimo ativo deste livro.');
            }

            // 4. Cria o empréstimo com status PENDENTE
            $data_limite_retirada = date('Y-m-d H:i:s', strtotime('+48 hours'));
            
            $sql_insert = "INSERT INTO movimentacoes 
                          (id_usuario, id_livro, data_movimentacao, data_limite_retirada, status) 
                          VALUES (:id_usuario, :id_livro, NOW(), :data_limite, 'Pendente')";
            
            $stmt_insert = $this->conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':id_usuario' => $id_usuario,
                ':id_livro' => $id_livro,
                ':data_limite' => $data_limite_retirada
            ]);

            $id_movimentacao = $this->conn->lastInsertId();

            // 5. Atualiza estoque
            $sql_estoque = "UPDATE exemplares 
                           SET disponiveis = disponiveis - 1,
                               emprestados = emprestados + 1
                           WHERE id_livro = :id_livro";
            
            $stmt_estoque = $this->conn->prepare($sql_estoque);
            $stmt_estoque->execute([':id_livro' => $id_livro]);

            $this->conn->commit();

            return [
                'sucesso' => true,
                'mensagem' => 'Empréstimo solicitado! Você tem 48 horas para retirar o livro na biblioteca.',
                'id_movimentacao' => $id_movimentacao,
                'data_limite_retirada' => $data_limite_retirada
            ];

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    /**
     * Confirma o empréstimo e inicia a contagem de 7 dias para devolução
     */
    public function confirmarEmprestimo($id_movimentacao) {
        try {
            $this->conn->beginTransaction();

            // 1. Busca o empréstimo
            $sql_select = "SELECT * FROM movimentacoes WHERE id_movimentacao = :id";
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->execute([':id' => $id_movimentacao]);
            $emprestimo = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if (!$emprestimo) {
                throw new Exception('Empréstimo não encontrado.');
            }

            // 2. Verifica se está pendente
            if ($emprestimo['status'] !== 'Pendente') {
                throw new Exception('Este empréstimo já foi confirmado ou não está pendente.');
            }

            // 3. Calcula prazo de devolução (7 dias)
            $data_prevista_devolucao = date('Y-m-d', strtotime('+7 days'));

            // 4. Atualiza o empréstimo
            $sql_update = "UPDATE movimentacoes 
                          SET status = 'Emprestado',
                              data_prevista_devolucao = :data_prevista,
                              data_limite_retirada = NULL
                          WHERE id_movimentacao = :id";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':data_prevista' => $data_prevista_devolucao,
                ':id' => $id_movimentacao
            ]);

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Empréstimo confirmado! Prazo de devolução: 7 dias.',
                'data_prevista_devolucao' => $data_prevista_devolucao
            ];

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Processa devolução COM período de quarentena
     */
    public function devolverLivro($id_movimentacao, $id_livro) {
        try {
            $this->conn->beginTransaction();

            // 1. Busca o empréstimo
            $sql_select = "SELECT * FROM movimentacoes WHERE id_movimentacao = :id";
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->execute([':id' => $id_movimentacao]);
            $emprestimo = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if (!$emprestimo || $emprestimo['status'] !== 'Emprestado') {
                throw new Exception('Empréstimo não encontrado ou já devolvido.');
            }

            // 2. Registra devolução
            $sql_devolver = "UPDATE movimentacoes 
                            SET status = 'Devolvido',
                                data_real_devolucao = NOW()
                            WHERE id_movimentacao = :id";
            
            $stmt_devolver = $this->conn->prepare($sql_devolver);
            $stmt_devolver->execute([':id' => $id_movimentacao]);

            // 3. Verifica se há fila de espera
            $sql_fila = "SELECT COUNT(*) as total FROM fila_reservas 
                        WHERE id_livro = :id_livro 
                        AND status = 'AGUARDANDO'";
            
            $stmt_fila = $this->conn->prepare($sql_fila);
            $stmt_fila->execute([':id_livro' => $id_livro]);
            $tem_fila = $stmt_fila->fetch(PDO::FETCH_ASSOC)['total'] > 0;

            if ($tem_fila) {
                // TEM FILA: Livro entra em quarentena (2 dias)
                // Não volta para estoque ainda
                $sql_quarentena = "UPDATE exemplares 
                                  SET emprestados = emprestados - 1,
                                      em_quarentena = em_quarentena + 1
                                  WHERE id_livro = :id_livro";
                
                $stmt_quarentena = $this->conn->prepare($sql_quarentena);
                $stmt_quarentena->execute([':id_livro' => $id_livro]);

                // Notifica próximo da fila (com 2 dias de espera)
                $resultado_fila = $this->filaModel->notificarProximoDaFila($id_livro);
                
                $mensagem = "Livro devolvido! O próximo da fila foi notificado e poderá retirá-lo em 2 dias.";

            } else {
                // SEM FILA: Livro volta direto para disponível
                $sql_estoque = "UPDATE exemplares 
                               SET disponiveis = disponiveis + 1,
                                   emprestados = emprestados - 1
                               WHERE id_livro = :id_livro";
                
                $stmt_estoque = $this->conn->prepare($sql_estoque);
                $stmt_estoque->execute([':id_livro' => $id_livro]);

                $mensagem = "Livro devolvido com sucesso!";
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => $mensagem
            ];

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Libera livro da quarentena para o primeiro da fila
     */
    public function liberarLivroQuarentena($id_livro) {
        try {
            $sql = "UPDATE exemplares 
                   SET em_quarentena = em_quarentena - 1,
                       disponiveis = disponiveis + 1
                   WHERE id_livro = :id_livro 
                   AND em_quarentena > 0";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_livro' => $id_livro]);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verifica se usuário está bloqueado por atraso
     */
    private function usuarioBloqueadoPorAtraso($id_usuario) {
        $sql = "SELECT COUNT(*) FROM movimentacoes 
                WHERE id_usuario = :id_usuario 
                AND status IN ('Atrasado', 'Emprestado')
                AND data_prevista_devolucao < CURDATE()
                AND data_real_devolucao IS NULL";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * CRON JOB: Marca empréstimos como atrasados
     */
    public function marcarEmprestimosAtrasados() {
        try {
            $sql = "UPDATE movimentacoes 
                   SET status = 'Atrasado' 
                   WHERE status = 'Emprestado' 
                   AND data_prevista_devolucao < CURDATE()";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $total = $stmt->rowCount();

            // TODO: Enviar notificações aos usuários atrasados
            
            return [
                'sucesso' => true,
                'total_atrasados' => $total
            ];

        } catch (Exception $e) {
            return ['sucesso' => false, 'mensagem' => $e->getMessage()];
        }
    }

    /**
     * CRON JOB: Cancela empréstimos pendentes expirados
     */
    public function cancelarEmprestimosExpirados() {
        try {
            $this->conn->beginTransaction();

            $sql_select = "SELECT id_movimentacao, id_livro 
                          FROM movimentacoes 
                          WHERE status = 'Pendente' 
                          AND data_limite_retirada < NOW()";
            
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->execute();
            $expirados = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            foreach ($expirados as $emp) {
                // Cancela
                $sql_cancel = "UPDATE movimentacoes 
                              SET status = 'Cancelado' 
                              WHERE id_movimentacao = :id";
                $stmt_cancel = $this->conn->prepare($sql_cancel);
                $stmt_cancel->execute([':id' => $emp['id_movimentacao']]);

                // Devolve ao estoque
                $sql_estoque = "UPDATE exemplares 
                               SET disponiveis = disponiveis + 1,
                                   emprestados = emprestados - 1
                               WHERE id_livro = :id_livro";
                $stmt_estoque = $this->conn->prepare($sql_estoque);
                $stmt_estoque->execute([':id_livro' => $emp['id_livro']]);
            }

            $this->conn->commit();
            
            return [
                'sucesso' => true,
                'total_cancelados' => count($expirados)
            ];

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['sucesso' => false, 'mensagem' => $e->getMessage()];
        }
    }

    /**
     * Busca estoque atual do livro
     */
    private function getEstoqueLivro($id_livro) {
        $sql = "SELECT * FROM exemplares WHERE id_livro = :id_livro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lista empréstimos do usuário
     */
    public function getEmprestimosUsuario($id_usuario) {
        $sql = "SELECT m.*, l.titulo, l.foto, l.isbn
                FROM movimentacoes m
                INNER JOIN livros l ON m.id_livro = l.id_livro
                WHERE m.id_usuario = :id_usuario
                ORDER BY m.data_movimentacao DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}