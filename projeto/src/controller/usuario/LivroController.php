<?php
// src/controller/usuario/LivroController.php

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
    public function buscarDetalhes($id) {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id_livro = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($id_livro <= 0) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'ID do livro inválido'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $livro = $this->model->getLivroDetalhes($id_livro);

            if (!$livro) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Livro não encontrado'
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Formata os dados para o frontend
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
                'exemplares' => $livro['exemplares'],
                'disponibilidade' => [
                    'geral' => $livro['disponibilidade_geral'],
                    'total_exemplares' => $livro['total_exemplares'],
                    'total_disponivel' => $livro['total_disponivel'],
                    'total_emprestados' => $livro['total_emprestados'],
                    'total_reservados' => $livro['total_reservados']
                ],
                'avaliacoes' => $this->model->getAvaliacoesLivro($id_livro)
            ];

            echo json_encode($response, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Erro no LivroController::buscarDetalhes: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar detalhes do livro'
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Busca livros para autocomplete (pesquisa)
     */
    public function buscarParaPesquisa() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';
            
            if (strlen($termo) < 2) {
                echo json_encode([
                    'sucesso' => false,
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
            error_log("Erro ao buscar livros: " . $e->getMessage());
            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar livros'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}