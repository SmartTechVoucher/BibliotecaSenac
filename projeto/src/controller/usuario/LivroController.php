<?php
// src/controller/usuario/LivroController.php

require_once __DIR__ . '/../../../config/constantes.php'; // Incluir constantes para garantir URLBASE
require_once __DIR__ . '/../../model/usuario/LivroModel.php';

class LivroController {
    private $model;

    public function __construct() {
        $this->model = new LivroModel();
    }

    /**
     * Retorna detalhes completos de um livro em JSON
     * Usado pela página livro-info.php
     */
    public function buscarDetalhes() {
        // *** LIMPEZA CRÍTICA: Previne o erro "<br /> is not valid JSON" ***
        if (ob_get_length() > 0) {
            ob_clean(); 
        }
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Aceita tanto 'id' quanto 'id_livro'
            $id_livro = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['id_livro']) ? intval($_GET['id_livro']) : 0);
            
            if ($id_livro <= 0) {
                http_response_code(400);
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'ID do livro inválido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $livro = $this->model->getLivroDetalhes($id_livro);

            if (!$livro) {
                http_response_code(404);
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Livro não encontrado'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Busca as avaliações (Novo método no Model)
            $avaliacoes = $this->model->getAvaliacoesLivro($id_livro);

            // Formata os dados para o frontend, removendo as chaves inexistentes
            $response = [
                'sucesso' => true,
                'livro' => [
                    'id' => $livro['id_livro'],
                    'titulo' => $livro['titulo'],
                    'autor' => $livro['autor_nome'] ?? 'Autor desconhecido',
                    'editora' => $livro['editora_nome'] ?? 'Editora não informada',
                    'categoria' => $livro['categoria_nome'] ?? 'Sem categoria',
                    'isbn' => $livro['isbn'] ?? 'Não informado',
                    'data_publicacao' => $livro['data_publicacao'] ?? '',
                    'numero_paginas' => $livro['numero_paginas'] ?? 0,
                    'descricao' => $livro['descricao'] ?? $livro['resumo_livro'] ?? 'Sem descrição disponível',
                    'resumo' => $livro['resumo_livro'] ?? '',
                    'foto' => $livro['foto_url'] ?? '',
                    'notas' => $livro['notas'] ?? '',
                    'idioma' => $livro['idioma_nome'] ?? '',
                    'area' => $livro['area_nome'] ?? '',
                    'tipo_documento' => $livro['documento_nome'] ?? ''
                ],
                'disponibilidade' => [
                    // A chave 'geral' é deduzida pelo frontend se total_disponivel > 0
                    'total_exemplares' => $livro['total_exemplares'] ?? 0,
                    'total_disponivel' => $livro['total_disponivel'] ?? 0,
                    'total_emprestados' => $livro['total_emprestados'] ?? 0,
                    'total_reservados' => $livro['total_reservados'] ?? 0 
                ],
                'avaliacoes' => $avaliacoes
            ];

            echo json_encode($response, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro no LivroController::buscarDetalhes: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro interno ao buscar detalhes do livro'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Busca livros para autocomplete (pesquisa)
     */
    public function buscarParaPesquisa() {
        // *** LIMPEZA CRÍTICA: Previne o erro "<br /> is not valid JSON" ***
        if (ob_get_length() > 0) {
            ob_clean(); 
        }
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';
            
            if (strlen($termo) < 2) {
                echo json_encode([
                    'sucesso' => true, // Retorna sucesso, mas com lista vazia se for muito curto
                    'livros' => [],
                    'mensagem' => 'Digite pelo menos 2 caracteres'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $livros = $this->model->buscarLivros($termo, 10);

            echo json_encode([
                'sucesso' => true,
                'livros' => $livros
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro no LivroController::buscarParaPesquisa: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar livros para pesquisa'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
