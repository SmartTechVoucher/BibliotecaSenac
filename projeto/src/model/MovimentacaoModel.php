<?php
// src/model/MovimentacaoModel.php

require_once __DIR__ . '/../../config/db/database.php';

class MovimentacaoModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
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

            // 2. Verifica se usuário já tem empréstimo PENDENTE ou ATIVO deste livro
            $sql_check = "SELECT COUNT(*) FROM movimentacoes 
                        WHERE id_usuario = :id_usuario 
                        AND id_livro = :id_livro 
                        AND status IN ('Pendente', 'Emprestado')";

            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_check->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt_check->execute();

            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Você já possui um empréstimo ativo deste livro.');
            }

            // 3. Cria o empréstimo com status PENDENTE
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

            // 4. Atualiza estoque (diminui disponível, aumenta emprestado)
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
     * NOVO: Confirma o empréstimo e inicia a contagem de 7 dias para devolução
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

            // 3. Calcula prazo de devolução (7 dias a partir de AGORA)
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
     * Busca estoque atual do livro
     */
    private function getEstoqueLivro($id_livro) {
        $sql = "SELECT * FROM exemplares WHERE id_livro = :id_livro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cancela empréstimos pendentes que passaram de 48h
     * (Deve ser executado por um cron job ou manualmente)
     */
    public function cancelarEmprestimosExpirados() {
        try {
            $this->conn->beginTransaction();

            // 1. Busca empréstimos pendentes expirados
            $sql_select = "SELECT id_movimentacao, id_livro 
                          FROM movimentacoes 
                          WHERE status = 'Pendente' 
                          AND data_limite_retirada < NOW()";
            
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->execute();
            $expirados = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            foreach ($expirados as $emp) {
                // 2. Cancela o empréstimo
                $sql_cancel = "UPDATE movimentacoes 
                              SET status = 'Cancelado' 
                              WHERE id_movimentacao = :id";
                $stmt_cancel = $this->conn->prepare($sql_cancel);
                $stmt_cancel->execute([':id' => $emp['id_movimentacao']]);

                // 3. Devolve ao estoque
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