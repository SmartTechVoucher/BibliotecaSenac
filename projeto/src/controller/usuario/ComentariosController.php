<?php
// src/controller/usuario/ComentariosController.php

require_once __DIR__ . '/../../../config/database.php';

class ComentariosController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->Connect();
    }

    public function handle() {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->criarComentario();
        } elseif ($method === 'GET') {
            $this->listarComentarios();
        } else {
            $this->responderErro('Método não suportado');
        }
    }

    private function criarComentario() {
        try {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true);

            error_log("Dados recebidos para criar comentário: " . print_r($data, true));

            if (!$data) {
                $this->responderErro('JSON inválido ou corpo vazio');
                return;
            }

            // Validar campos obrigatórios
            if (!isset($data['id_usuario'], $data['id_livro'], $data['comentario'], $data['avaliacao'])) {
                $this->responderErro('Campos obrigatórios ausentes (id_usuario, id_livro, comentario, avaliacao)');
                return;
            }

            $id_usuario = intval($data['id_usuario']);
            $id_livro = intval($data['id_livro']);
            $comentario = trim($data['comentario']);
            $avaliacao = intval($data['avaliacao']);

            // Validações
            if ($id_usuario <= 0) {
                $this->responderErro('ID do usuário inválido');
                return;
            }

            if ($id_livro <= 0) {
                $this->responderErro('ID do livro inválido');
                return;
            }

            if (empty($comentario)) {
                $this->responderErro('Comentário não pode estar vazio');
                return;
            }

            if ($avaliacao < 1 || $avaliacao > 4) {
                $this->responderErro('Avaliação deve estar entre 1 e 4');
                return;
            }

            // Verificar se usuário existe
            $checkUser = $this->conn->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
            $checkUser->execute([$id_usuario]);
            if ($checkUser->rowCount() === 0) {
                $this->responderErro('Usuário não encontrado');
                return;
            }

            // Verificar se livro existe
            $checkBook = $this->conn->prepare("SELECT id_livro FROM livros WHERE id_livro = ?");
            $checkBook->execute([$id_livro]);
            if ($checkBook->rowCount() === 0) {
                $this->responderErro('Livro não encontrado');
                return;
            }

            // Inserir no banco
            $sql = "INSERT INTO comentarios (id_usuario, id_livro, comentario, avaliacao, data_comentario) 
                    VALUES (?, ?, ?, ?, NOW())";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                error_log('Erro no prepare: ' . print_r($this->conn->errorInfo(), true));
                $this->responderErro('Erro ao preparar consulta');
                return;
            }

            if ($stmt->execute([$id_usuario, $id_livro, $comentario, $avaliacao])) {
                $id_comentario = $this->conn->lastInsertId();
                
                // Buscar nova média após inserir
                $mediaQuery = $this->conn->prepare(
                    "SELECT 
                        COUNT(*) as total_avaliacoes,
                        ROUND(AVG(avaliacao)) as media_estrelas
                    FROM comentarios 
                    WHERE id_livro = ?"
                );
                $mediaQuery->execute([$id_livro]);
                $mediaResult = $mediaQuery->fetch();

                $this->responderSucesso([
                    'sucesso' => true,
                    'mensagem' => 'Comentário adicionado com sucesso',
                    'id_comentario' => $id_comentario,
                    'media_estrelas' => intval($mediaResult['media_estrelas']),
                    'total_avaliacoes' => intval($mediaResult['total_avaliacoes'])
                ]);
            } else {
                error_log('Erro ao executar: ' . print_r($stmt->errorInfo(), true));
                $this->responderErro('Erro ao salvar comentário');
            }

        } catch (Exception $e) {
            error_log('Exceção em criarComentario: ' . $e->getMessage());
            $this->responderErro('Erro interno: ' . $e->getMessage());
        }
    }

    private function listarComentarios() {
        try {
            if (!isset($_GET['id_livro'])) {
                $this->responderErro('Parâmetro id_livro não enviado');
                return;
            }

            $id_livro = intval($_GET['id_livro']);

            if ($id_livro <= 0) {
                $this->responderErro('ID do livro inválido');
                return;
            }

            // Buscar comentários com informações do usuário
            $sql = "SELECT 
                        c.id_comentario, 
                        c.id_usuario, 
                        c.id_livro, 
                        c.comentario, 
                        c.avaliacao, 
                        c.data_comentario,
                        DATE_FORMAT(c.data_comentario, '%d/%m/%Y') as data_formatada,
                        u.nome as nome_usuario
                    FROM comentarios c
                    LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
                    WHERE c.id_livro = ?
                    ORDER BY c.data_comentario DESC";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                error_log('Erro no prepare: ' . print_r($this->conn->errorInfo(), true));
                $this->responderErro('Erro ao preparar consulta');
                return;
            }

            if (!$stmt->execute([$id_livro])) {
                error_log('Erro ao executar: ' . print_r($stmt->errorInfo(), true));
                $this->responderErro('Erro ao buscar comentários');
                return;
            }

            $comentarios = $stmt->fetchAll();

            // Calcular estatísticas de avaliação
            $statsQuery = $this->conn->prepare(
                "SELECT 
                    COUNT(*) as total_avaliacoes,
                    ROUND(AVG(avaliacao)) as media_estrelas,
                    ROUND(AVG(avaliacao), 2) as media_exata,
                    SUM(CASE WHEN avaliacao = 1 THEN 1 ELSE 0 END) as total_1_estrela,
                    SUM(CASE WHEN avaliacao = 2 THEN 1 ELSE 0 END) as total_2_estrelas,
                    SUM(CASE WHEN avaliacao = 3 THEN 1 ELSE 0 END) as total_3_estrelas,
                    SUM(CASE WHEN avaliacao = 4 THEN 1 ELSE 0 END) as total_4_estrelas
                FROM comentarios 
                WHERE id_livro = ?"
            );
            $statsQuery->execute([$id_livro]);
            $stats = $statsQuery->fetch();

            // Retornar comentários + estatísticas
            $this->responderSucesso([
                'comentarios' => $comentarios,
                'estatisticas' => [
                    'total_avaliacoes' => intval($stats['total_avaliacoes']) ?: 0,
                    'media_estrelas' => intval($stats['media_estrelas']) ?: 0,
                    'media_exata' => floatval($stats['media_exata']) ?: 0,
                    'distribuicao' => [
                        1 => intval($stats['total_1_estrela']),
                        2 => intval($stats['total_2_estrelas']),
                        3 => intval($stats['total_3_estrelas']),
                        4 => intval($stats['total_4_estrelas'])
                    ]
                ]
            ]);

        } catch (Exception $e) {
            error_log('Exceção em listarComentarios: ' . $e->getMessage());
            $this->responderErro('Erro interno: ' . $e->getMessage());
        }
    }

    private function responderSucesso($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function responderErro($mensagem) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => $mensagem], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>