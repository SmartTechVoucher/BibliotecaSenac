<?php
// src/controller/usuario/AvaliacaoController.php

require_once __DIR__ . '/../../model/AvaliacaoModel.php';

class AvaliacaoController {
    private $model;

    public function __construct() {
        $this->model = new AvaliacaoModel();
    }

    /**
     * Salva ou atualiza uma avaliação
     */
    public function salvar() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Verifica se usuário está logado
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Você precisa estar logado para avaliar.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Valida método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Método inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Valida dados
            $id_livro = isset($_POST['id_livro']) ? intval($_POST['id_livro']) : 0;
            $estrelas = isset($_POST['estrelas']) ? intval($_POST['estrelas']) : 0;
            $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_livro <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Livro inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if ($estrelas < 1 || $estrelas > 5) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Selecione uma avaliação de 1 a 5 estrelas.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Salva avaliação
            $resultado = $this->model->salvarAvaliacao($id_livro, $id_usuario, $estrelas, $comentario);
            
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao salvar avaliação: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao processar avaliação.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Lista avaliações de um livro
     */
    public function listar() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0;
            
            if ($id_livro <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Livro inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Busca avaliações
            $avaliacoes = $this->model->getAvaliacoesLivro($id_livro);
            
            // Busca estatísticas
            $estatisticas = $this->model->getEstatisticasLivro($id_livro);

            echo json_encode([
                'success' => true,
                'avaliacoes' => $avaliacoes,
                'estatisticas' => $estatisticas
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao listar avaliações: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar avaliações.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Busca avaliação do usuário logado para um livro
     */
    public function minhaAvaliacao() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'success' => false,
                    'avaliou' => false
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_livro = isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);
            
            if ($id_livro <= 0) {
                echo json_encode([
                    'success' => false,
                    'avaliou' => false
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $avaliacao = $this->model->getAvaliacaoUsuario($id_livro, $id_usuario);

            if ($avaliacao) {
                echo json_encode([
                    'success' => true,
                    'avaliou' => true,
                    'avaliacao' => $avaliacao
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => true,
                    'avaliou' => false
                ], JSON_UNESCAPED_UNICODE);
            }

        } catch (Exception $e) {
            error_log("Erro ao buscar avaliação do usuário: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'avaliou' => false
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Deleta uma avaliação
     */
    public function deletar() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Não autenticado.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Método inválido.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $id_avaliacao = isset($_POST['id_avaliacao']) ? intval($_POST['id_avaliacao']) : 0;
            $id_usuario = intval($_SESSION['usuario_id']);

            if ($id_avaliacao <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Avaliação inválida.'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $resultado = $this->model->deletarAvaliacao($id_avaliacao, $id_usuario);
            
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro ao deletar avaliação: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao deletar avaliação.'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}

// Executa o controller se for chamado diretamente
if (basename($_SERVER['PHP_SELF']) === 'AvaliacaoController.php') {
    $controller = new AvaliacaoController();
    
    $acao = $_GET['acao'] ?? '';
    
    switch ($acao) {
        case 'salvar':
            $controller->salvar();
            break;
        case 'listar':
            $controller->listar();
            break;
        case 'minha':
            $controller->minhaAvaliacao();
            break;
        case 'deletar':
            $controller->deletar();
            break;
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Ação inválida.'
            ], JSON_UNESCAPED_UNICODE);
    }
}