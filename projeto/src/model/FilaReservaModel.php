<?php
// src/model/FilaReservaModel.php

require_once __DIR__ . '/../../config/db/database.php';

class FilaReservaModel {
    private $conn;
    
    // Configurações do sistema
    const DIAS_MEDIA_EMPRESTIMO = 15; // Média de dias por empréstimo
    const DIAS_QUARENTENA = 2; // Dias de quarentena após devolução
    const HORAS_LIMITE_RETIRADA = 48; // Horas para retirar após notificação

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Adiciona usuário na fila de reserva com estimativa dinâmica
     */
    public function entrarNaFila($id_livro, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            // 1. Verifica se usuário já está na fila deste livro
            $sql_check = "SELECT COUNT(*) FROM fila_reservas 
                         WHERE id_usuario = :id_usuario 
                         AND id_livro = :id_livro 
                         AND status IN ('AGUARDANDO', 'NOTIFICADO')";
            
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->execute([
                ':id_usuario' => $id_usuario,
                ':id_livro' => $id_livro
            ]);
            
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception('Você já está na fila de espera deste livro.');
            }

            // 2. Verifica se usuário está bloqueado por atraso
            if ($this->usuarioBloqueado($id_usuario)) {
                throw new Exception('Você está temporariamente bloqueado por atraso na devolução de livros.');
            }

            // 3. Calcula a próxima posição na fila
            $posicao = $this->getProximaPosicao($id_livro);

            // 4. Calcula estimativa realista
            $estimativa = $this->calcularEstimativaRealista($id_livro, $posicao);

            // 5. Insere na fila
            $sql_insert = "INSERT INTO fila_reservas 
                          (id_livro, id_usuario, posicao, status, data_estimada_disponibilidade) 
                          VALUES (:id_livro, :id_usuario, :posicao, 'AGUARDANDO', :data_estimada)";
            
            $stmt_insert = $this->conn->prepare($sql_insert);
            $stmt_insert->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario,
                ':posicao' => $posicao,
                ':data_estimada' => $estimativa['data_estimada']
            ]);

            // 6. Atualiza contador de reservas no estoque
            $sql_estoque = "UPDATE exemplares 
                           SET reservas = reservas + 1 
                           WHERE id_livro = :id_livro";
            
            $stmt_estoque = $this->conn->prepare($sql_estoque);
            $stmt_estoque->execute([':id_livro' => $id_livro]);

            $this->conn->commit();

            // 7. Registra notificação para envio futuro
            $this->registrarNotificacaoPendente($id_usuario, $id_livro, 
                "Você entrou na fila de reserva. Posição: $posicao. Estimativa: {$estimativa['texto']}");

            return [
                'sucesso' => true,
                'mensagem' => "Você entrou na fila de reserva!",
                'posicao' => $posicao,
                'estimativa' => $estimativa['texto'],
                'data_estimada' => $estimativa['data_estimada']
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
     * Calcula estimativa REALISTA baseada em empréstimos ativos
     */
    private function calcularEstimativaRealista($id_livro, $posicao) {
        // 1. Conta quantos empréstimos ativos existem deste livro
        $sql_ativos = "SELECT COUNT(*) as total 
                      FROM movimentacoes 
                      WHERE id_livro = :id_livro 
                      AND status = 'Emprestado'";
        
        $stmt_ativos = $this->conn->prepare($sql_ativos);
        $stmt_ativos->execute([':id_livro' => $id_livro]);
        $emprestimos_ativos = $stmt_ativos->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Calcula dias até disponibilidade
        // Fórmula: (empréstimos ativos + posição na fila - 1) * média de dias + quarentena
        $total_aguardando = $emprestimos_ativos + $posicao - 1;
        $dias_estimados = ($total_aguardando * self::DIAS_MEDIA_EMPRESTIMO) + self::DIAS_QUARENTENA;
        
        // 3. Calcula data estimada
        $data_estimada = date('Y-m-d', strtotime("+{$dias_estimados} days"));
        
        // 4. Gera texto amigável
        if ($dias_estimados <= 7) {
            $texto = "até 1 semana";
        } elseif ($dias_estimados <= 15) {
            $texto = "1-2 semanas";
        } elseif ($dias_estimados <= 30) {
            $texto = "2-4 semanas";
        } elseif ($dias_estimados <= 60) {
            $texto = "1-2 meses";
        } else {
            $texto = "mais de 2 meses";
        }

        return [
            'dias' => $dias_estimados,
            'texto' => $texto,
            'data_estimada' => $data_estimada
        ];
    }

    /**
     * Verifica se usuário está bloqueado por atraso
     */
    private function usuarioBloqueado($id_usuario) {
        $sql = "SELECT COUNT(*) FROM movimentacoes 
                WHERE id_usuario = :id_usuario 
                AND status = 'Atrasado'
                AND data_real_devolucao IS NULL";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Calcula a próxima posição na fila
     */
    private function getProximaPosicao($id_livro) {
        $sql = "SELECT COALESCE(MAX(posicao), 0) + 1 as proxima 
                FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND status IN ('AGUARDANDO', 'NOTIFICADO')";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['proxima'];
    }

    /**
     * Busca posição do usuário na fila de um livro
     */
    public function getPosicaoNaFila($id_livro, $id_usuario) {
        $sql = "SELECT posicao, data_entrada_fila, data_estimada_disponibilidade, status
                FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND id_usuario = :id_usuario 
                AND status IN ('AGUARDANDO', 'NOTIFICADO')";
        
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
                          AND status IN ('AGUARDANDO', 'NOTIFICADO')";
            
            $stmt_cancel = $this->conn->prepare($sql_cancel);
            $stmt_cancel->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario
            ]);

            if ($stmt_cancel->rowCount() == 0) {
                throw new Exception('Reserva não encontrada ou já cancelada.');
            }

            // 2. Reordena a fila e atualiza estimativas
            $this->reordenarFilaComEstimativas($id_livro);

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
     * Reordena a fila E recalcula estimativas para todos
     */
    private function reordenarFilaComEstimativas($id_livro) {
        $sql = "SELECT id_fila FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND status = 'AGUARDANDO' 
                ORDER BY posicao ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $nova_posicao = 1;
        foreach ($filas as $fila) {
            // Recalcula estimativa para nova posição
            $estimativa = $this->calcularEstimativaRealista($id_livro, $nova_posicao);
            
            $sql_update = "UPDATE fila_reservas 
                          SET posicao = :nova_posicao,
                              data_estimada_disponibilidade = :data_estimada
                          WHERE id_fila = :id_fila";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':nova_posicao' => $nova_posicao,
                ':data_estimada' => $estimativa['data_estimada'],
                ':id_fila' => $fila['id_fila']
            ]);
            
            $nova_posicao++;
        }
    }

    /**
     * Notifica o próximo da fila quando livro for devolvido
     * COM PERÍODO DE QUARENTENA DE 2 DIAS
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

            // 2. Calcula datas com quarentena
            $data_disponivel = date('Y-m-d H:i:s', strtotime('+' . self::DIAS_QUARENTENA . ' days'));
            $data_limite = date('Y-m-d H:i:s', strtotime($data_disponivel . ' +' . self::HORAS_LIMITE_RETIRADA . ' hours'));
            
            // 3. Atualiza status para NOTIFICADO
            $sql_update = "UPDATE fila_reservas 
                          SET status = 'NOTIFICADO',
                              data_notificacao = NOW(),
                              data_disponivel_retirada = :data_disponivel,
                              data_limite_retirada = :data_limite
                          WHERE id_fila = :id_fila";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':data_disponivel' => $data_disponivel,
                ':data_limite' => $data_limite,
                ':id_fila' => $proximo['id_fila']
            ]);

            // 4. Registra notificação
            $this->registrarNotificacaoPendente(
                $proximo['id_usuario'], 
                $id_livro,
                "Seu livro estará disponível em " . self::DIAS_QUARENTENA . " dias! Você terá " . self::HORAS_LIMITE_RETIRADA . " horas para retirá-lo.",
                'LIVRO_DISPONIVEL'
            );

            // 5. Atualiza estimativas de todos da fila
            $this->atualizarEstimativasTodosFila($id_livro);

            return [
                'sucesso' => true,
                'id_usuario_notificado' => $proximo['id_usuario'],
                'data_disponivel' => $data_disponivel,
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
     * Atualiza estimativas de todos na fila após mudança
     */
    private function atualizarEstimativasTodosFila($id_livro) {
        $sql = "SELECT id_fila, id_usuario, posicao 
                FROM fila_reservas 
                WHERE id_livro = :id_livro 
                AND status = 'AGUARDANDO'
                ORDER BY posicao";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_livro' => $id_livro]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($usuarios as $usuario) {
            $estimativa = $this->calcularEstimativaRealista($id_livro, $usuario['posicao']);
            
            // Atualiza data estimada
            $sql_update = "UPDATE fila_reservas 
                          SET data_estimada_disponibilidade = :data_estimada 
                          WHERE id_fila = :id_fila";
            
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->execute([
                ':data_estimada' => $estimativa['data_estimada'],
                ':id_fila' => $usuario['id_fila']
            ]);

            // Notifica usuário sobre nova estimativa (se mudou significativamente)
            $this->registrarNotificacaoPendente(
                $usuario['id_usuario'],
                $id_livro,
                "Atualização: Sua posição é {$usuario['posicao']}. Nova estimativa: {$estimativa['texto']}",
                'ATUALIZACAO_FILA'
            );
        }
    }

    /**
     * Registra notificação para envio futuro (email/push)
     * PREPARADO PARA IMPLEMENTAÇÃO FUTURA
     */
    private function registrarNotificacaoPendente($id_usuario, $id_livro, $mensagem, $tipo = 'INFO') {
        try {
            $sql = "INSERT INTO notificacoes 
                   (id_usuario, id_livro, mensagem, tipo, status, data_criacao) 
                   VALUES (:id_usuario, :id_livro, :mensagem, :tipo, 'PENDENTE', NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_livro' => $id_livro,
                ':mensagem' => $mensagem,
                ':tipo' => $tipo
            ]);
            
            return true;
        } catch (PDOException $e) {
            // Se tabela não existe ainda, apenas loga (implementação futura)
            error_log("Notificação não registrada (tabela pode não existir): " . $e->getMessage());
            return false;
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

    /**
     * CRON JOB: Expira reservas notificadas que não foram retiradas
     */
    public function expirarReservasNaoRetiradas() {
        try {
            $this->conn->beginTransaction();

            // 1. Busca reservas expiradas
            $sql_select = "SELECT id_fila, id_livro, id_usuario 
                          FROM fila_reservas 
                          WHERE status = 'NOTIFICADO' 
                          AND data_limite_retirada < NOW()";
            
            $stmt_select = $this->conn->prepare($sql_select);
            $stmt_select->execute();
            $expiradas = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

            foreach ($expiradas as $reserva) {
                // 2. Marca como expirada
                $sql_expirar = "UPDATE fila_reservas 
                               SET status = 'EXPIRADO' 
                               WHERE id_fila = :id_fila";
                
                $stmt_expirar = $this->conn->prepare($sql_expirar);
                $stmt_expirar->execute([':id_fila' => $reserva['id_fila']]);

                // 3. Notifica próximo da fila
                $this->notificarProximoDaFila($reserva['id_livro']);

                // 4. Notifica usuário que perdeu a vez
                $this->registrarNotificacaoPendente(
                    $reserva['id_usuario'],
                    $reserva['id_livro'],
                    "Você perdeu sua vez na fila por não retirar o livro no prazo.",
                    'RESERVA_EXPIRADA'
                );
            }

            $this->conn->commit();
            
            return [
                'sucesso' => true,
                'total_expiradas' => count($expiradas)
            ];

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['sucesso' => false, 'mensagem' => $e->getMessage()];
        }
    }
}