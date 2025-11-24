<?php
// src/controller/admin/ConfirmarEmprestimoController.php

require_once __DIR__ . '/../../model/MovimentacaoModel.php';
require_once __DIR__ . '/../../model/FilaReservaModel.php';

class ConfirmarEmprestimoController {
    private $movimentacaoModel;
    private $filaModel;

    public function __construct() {
        $this->movimentacaoModel = new MovimentacaoModel();
        $this->filaModel = new FilaReservaModel();
    }

    /**
     * Confirma o empréstimo e inicia a contagem de 48 horas
     */
    public function confirmar() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Valida se é admin
            if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Acesso negado. Apenas administradores podem confirmar empréstimos.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Valida entrada
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Método inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_movimentacao = isset($_POST['id_movimentacao']) ? intval($_POST['id_movimentacao']) : 0;

            if ($id_movimentacao <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de movimentação inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Confirma o empréstimo
            $resultado = $this->movimentacaoModel->confirmarEmprestimo($id_movimentacao);

            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao confirmar empréstimo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao processar confirmação: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}

// Executa o controller se for chamado diretamente
if (basename($_SERVER['PHP_SELF']) === 'ConfirmarEmprestimoController.php') {
    $controller = new ConfirmarEmprestimoController();
    $controller->confirmar();
}