<?php
// src/controller/usuario/EmprestimoController.php

require_once __DIR__ . '/../../model/MovimentacaoModel.php';
require_once __DIR__ . '/../../model/FilaReservaModel.php';

class EmprestimoController {
    private $movimentacaoModel;
    private $filaModel;

    public function __construct() {
        $this->movimentacaoModel = new MovimentacaoModel();
        $this->filaModel = new FilaReservaModel();
    }

    /**
     * Solicita um empréstimo (livro disponível)
     */
    public function solicitarEmprestimo() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Verifica se usuário está logado
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Você precisa estar logado para solicitar empréstimo.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Valida entrada
            $id_livro = isset($_POST['id_livro']) ? intval($_POST['id_livro']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_livro <= 0) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'ID do livro inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Cria o empréstimo
            $resultado = $this->movimentacaoModel->criarEmprestimo($id_livro, $id_usuario);

            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao solicitar empréstimo: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao processar solicitação.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Entra na fila de reserva (livro indisponível)
     */
    public function entrarNaFila() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Verifica se usuário está logado
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Você precisa estar logado para entrar na fila.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Valida entrada
            $id_livro = isset($_POST['id_livro']) ? intval($_POST['id_livro']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_livro <= 0) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'ID do livro inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Adiciona na fila
            $resultado = $this->filaModel->entrarNaFila($id_livro, $id_usuario);

            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao entrar na fila: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao processar solicitação.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Cancela uma reserva
     */
    public function cancelarReserva() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Não autenticado.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_livro = isset($_POST['id_livro']) ? intval($_POST['id_livro']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_livro <= 0) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'ID do livro inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = $this->filaModel->cancelarReserva($id_livro, $id_usuario);

            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao cancelar reserva: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao processar solicitação.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Lista empréstimos do usuário
     */
    public function meusEmprestimos() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Não autenticado.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_usuario = intval($_SESSION['usuario_id']);
            
            $emprestimos = $this->movimentacaoModel->getEmprestimosUsuario($id_usuario);
            $reservas = $this->filaModel->getReservasUsuario($id_usuario);

            echo json_encode([
                'sucesso' => true,
                'emprestimos' => $emprestimos,
                'reservas' => $reservas
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao buscar empréstimos: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar dados.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Verifica posição na fila
     */
    public function verificarPosicaoFila() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'sucesso' => false,
                    'na_fila' => false
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_livro <= 0) {
                echo json_encode([
                    'sucesso' => false,
                    'na_fila' => false
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $posicao = $this->filaModel->getPosicaoNaFila($id_livro, $id_usuario);

            if ($posicao) {
                echo json_encode([
                    'sucesso' => true,
                    'na_fila' => true,
                    'posicao' => $posicao['posicao'],
                    'data_entrada' => $posicao['data_entrada_fila']
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'sucesso' => true,
                    'na_fila' => false
                ], JSON_UNESCAPED_UNICODE);
            }

        } catch (Exception $e) {
            error_log("Erro ao verificar fila: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'na_fila' => false
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}