<?php
// src/model/AvaliacaoModel.php

require_once __DIR__ . '/../../config/db/database.php';

class AvaliacaoModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Adiciona ou atualiza uma avaliação
     * Se o usuário já avaliou o livro, atualiza a avaliação existente
     */
    public function salvarAvaliacao($id_livro, $id_usuario, $estrelas, $comentario) {
        try {
            // Validações
            if ($estrelas < 1 || $estrelas > 5) {
                throw new Exception('Avaliação deve ter entre 1 e 5 estrelas.');
            }

            // Verifica se o usuário já avaliou este livro
            $sql_check = "SELECT id_avaliacao FROM avaliacoes 
                         WHERE id_livro = :id_livro AND id_usuario = :id_usuario";
            
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario
            ]);
            
            $existe = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                // Atualiza avaliação existente
                $sql = "UPDATE avaliacoes 
                       SET estrelas = :estrelas, 
                           comentario = :comentario,
                           data_atualizacao = NOW()
                       WHERE id_avaliacao = :id_avaliacao";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':estrelas' => $estrelas,
                    ':comentario' => $comentario,
                    ':id_avaliacao' => $existe['id_avaliacao']
                ]);

                return [
                    'success' => true,
                    'message' => 'Avaliação atualizada com sucesso!',
                    'acao' => 'atualizada'
                ];

            } else {
                // Insere nova avaliação
                $sql = "INSERT INTO avaliacoes 
                       (id_livro, id_usuario, estrelas, comentario) 
                       VALUES (:id_livro, :id_usuario, :estrelas, :comentario)";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':id_livro' => $id_livro,
                    ':id_usuario' => $id_usuario,
                    ':estrelas' => $estrelas,
                    ':comentario' => $comentario
                ]);

                return [
                    'success' => true,
                    'message' => 'Avaliação enviada com sucesso!',
                    'acao' => 'criada'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Busca todas as avaliações de um livro
     */
    public function getAvaliacoesLivro($id_livro, $limit = 50, $offset = 0) {
        try {
            $sql = "SELECT 
                        a.id_avaliacao,
                        a.estrelas,
                        a.comentario,
                        a.data_criacao,
                        u.nome as nome_usuario,
                        u.foto_perfil
                    FROM avaliacoes a
                    JOIN usuarios u ON a.id_usuario = u.id_usuario
                    WHERE a.id_livro = :id_livro
                    ORDER BY a.data_criacao DESC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Erro ao buscar avaliações: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca estatísticas de avaliações de um livro
     */
    public function getEstatisticasLivro($id_livro) {
        try {
            $sql = "SELECT * FROM vw_estatisticas_avaliacoes 
                   WHERE id_livro = :id_livro";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_livro' => $id_livro]);
            
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se não houver avaliações, retorna valores padrão
            if (!$stats || $stats['total_avaliacoes'] == 0) {
                return [
                    'total_avaliacoes' => 0,
                    'media_estrelas' => 0,
                    'media_arredondada' => 0,
                    'cinco_estrelas' => 0,
                    'quatro_estrelas' => 0,
                    'tres_estrelas' => 0,
                    'duas_estrelas' => 0,
                    'uma_estrela' => 0
                ];
            }
            
            return $stats;

        } catch (Exception $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            return [
                'total_avaliacoes' => 0,
                'media_estrelas' => 0
            ];
        }
    }

    /**
     * Verifica se o usuário já avaliou o livro
     */
    public function getAvaliacaoUsuario($id_livro, $id_usuario) {
        try {
            $sql = "SELECT * FROM avaliacoes 
                   WHERE id_livro = :id_livro AND id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_livro' => $id_livro,
                ':id_usuario' => $id_usuario
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Deleta uma avaliação
     */
    public function deletarAvaliacao($id_avaliacao, $id_usuario) {
        try {
            // Verifica se a avaliação pertence ao usuário
            $sql = "DELETE FROM avaliacoes 
                   WHERE id_avaliacao = :id_avaliacao 
                   AND id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id_avaliacao' => $id_avaliacao,
                ':id_usuario' => $id_usuario
            ]);

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Avaliação deletada com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Avaliação não encontrada ou você não tem permissão.'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Busca todas as avaliações de um usuário
     */
    public function getAvaliacoesUsuario($id_usuario, $limit = 20) {
        try {
            $sql = "SELECT 
                        a.*,
                        l.titulo,
                        l.foto,
                        l.isbn
                    FROM avaliacoes a
                    JOIN livros l ON a.id_livro = l.id_livro
                    WHERE a.id_usuario = :id_usuario
                    ORDER BY a.data_criacao DESC
                    LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Erro ao buscar avaliações do usuário: " . $e->getMessage());
            return [];
        }
    }
}