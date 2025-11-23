<?php
// src/model/FilaReservaModel.php

require_once __DIR__ . '/../../config/db/database.php';

class FilaReservaModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Adiciona usuário na fila de reserva
     */
    public function entrarNaFila($id_livro, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            // 1. Verifica se usuário já está na fila deste livro
            $sql_check = "SELECT COUNT(*) FROM fila_reservas 
                         WHERE id_usuario = :id_usuario 
                         AND id_livro = :id_livro 
                         AND status = 'AGUARDANDO'";
            
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->execute([
                ':id_usuario' => $id_usuario,
                ':id_livro' => $id_livro
            ]);
            
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Você já está na fila de espera deste livro.');
            }

            // 2. Calcula a próxima posição na fila
            $posicao = $this->getProximaPosicao($id_livro);

            // 3. Insere na fila
            $sql_insert = "INSERT INTO fila_reservas 
                          (id_livro, id_usuario, posicao, status) 
                          VALUES (:id_livro, :id_usuario, :posicao, 'AGUARDANDO')";
            
            $stmt_insert = $this->conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario,
                ':posicao' => $posicao
            ]);

            // 4. Atualiza contador de reservas no estoque
            $sql_estoque = "UPDATE exemplares 
                           SET reservas = reservas + 1 
                           WHERE id_livro = :id_livro";
            
            $stmt_estoque = $this->conn->prepare($sql_estoque);
            $stmt_estoque->execute([':id_livro' => $id_livro]);

            $this->conn->commit();

            // 5. Calcula estimativa de tempo
            $estimativa = $this->calcularEstimativa($posicao);

            return [
                'sucesso' => true,
                'mensagem' => "Você entrou na fila de reserva!",
                'posicao' => $posicao,
                'estimativa' => $estimativa
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
     * Calcula a próxima posição na fila
     */
    private function getProximaPosicao($id_livro) {
        $sql = "SELECT COALESCE(MAX(posicao), 0) + 1 as proxima 
                FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND status = 'AGUARDANDO'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['proxima'];
    }

    /**
     * Calcula estimativa de tempo de espera
     */
    private function calcularEstimativa($posicao) {
        // Assume que cada empréstimo dura em média 14 dias
        $dias = $posicao * 14;
        
        if ($dias <= 30) {
            return "15-30 dias";
        } elseif ($dias <= 60) {
            return "30-60 dias";
        } else {
            return "mais de 60 dias";
        }
    }

    /**
     * Busca posição do usuário na fila de um livro
     */
    public function getPosicaoNaFila($id_livro, $id_usuario) {
        $sql = "SELECT posicao, data_entrada_fila 
                FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND id_usuario = :id_usuario 
                AND status = 'AGUARDANDO'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_livro' => $id_livro,
            ':id_usuario' => $id_usuario
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cancela reserva do usuário
     */
    public function cancelarReserva($id_livro, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            // 1. Cancela a reserva
            $sql_cancel = "UPDATE fila_reservas 
                          SET status = 'CANCELADO' 
                          WHERE id_livro = :id_livro 
                          AND id_usuario = :id_usuario 
                          AND status = 'AGUARDANDO'";
            
            $stmt_cancel = $this->conn->prepare($sql_cancel);
            $stmt_cancel->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario
            ]);

            if ($stmt_cancel->rowCount() == 0) {
                throw new Exception('Reserva não encontrada ou já cancelada.');
            }

            // 2. Reordena a fila (diminui posição dos que estão depois)
            $this->reordenarFila($id_livro);

            // 3. Atualiza contador de reservas
            $sql_estoque = "UPDATE exemplares 
                           SET reservas = reservas - 1 
                           WHERE id_livro = :id_livro AND reservas > 0";
            
            $stmt_estoque = $this->conn->prepare($sql_estoque);
            $stmt_estoque->execute([':id_livro' => $id_livro]);

            $this->conn->commit();

            return [
                'sucesso' => true,
                'mensagem' => 'Reserva cancelada com sucesso!'
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
     * Reordena a fila após um cancelamento
     */
    private function reordenarFila($id_livro) {
        $sql = "SELECT id_fila FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND status = 'AGUARDANDO' 
                ORDER BY posicao ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reordena
        $nova_posicao = 1;
        foreach ($filas as $fila) {
            $sql_update = "UPDATE fila_reservas 
                          SET posicao = :nova_posicao 
                          WHERE id_fila = :id_fila";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':nova_posicao' => $nova_posicao,
                ':id_fila' => $fila['id_fila']
            ]);
            
            $nova_posicao++;
        }
    }

    /**
     * Notifica o próximo da fila quando livro for devolvido
     */
    public function notificarProximoDaFila($id_livro) {
        try {
            // 1. Busca o primeiro da fila
            $sql = "SELECT id_fila, id_usuario 
                    FROM fila_reservas 
                    WHERE id_livro = :id_livro 
                    AND status = 'AGUARDANDO' 
                    ORDER BY posicao ASC 
                    LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_livro' => $id_livro]);
            $proximo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$proximo) {
                return ['sucesso' => false, 'mensagem' => 'Fila vazia'];
            }

            // 2. Atualiza status para NOTIFICADO e define prazo de 48h
            $data_limite = date('Y-m-d H:i:s', strtotime('+48 hours'));
            
            $sql_update = "UPDATE fila_reservas 
                          SET status = 'NOTIFICADO',
                              data_notificacao = NOW(),
                              data_limite_retirada = :data_limite
                          WHERE id_fila = :id_fila";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':data_limite' => $data_limite,
                ':id_fila' => $proximo['id_fila']
            ]);

            // TODO: Enviar email/notificação para o usuário

            return [
                'sucesso' => true,
                'id_usuario_notificado' => $proximo['id_usuario'],
                'data_limite' => $data_limite
            ];

        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    /**
     * Lista todas as reservas do usuário
     */
    public function getReservasUsuario($id_usuario) {
        $sql = "SELECT fr.*, l.titulo, l.foto, l.isbn
                FROM fila_reservas fr
                INNER JOIN livros l ON fr.id_livro = l.id_livro
                WHERE fr.id_usuario = :id_usuario
                AND fr.status IN ('AGUARDANDO', 'NOTIFICADO')
                ORDER BY fr.data_entrada_fila DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}