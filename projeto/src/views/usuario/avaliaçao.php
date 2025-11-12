<?php
/**
 * API de Avaliações e Comentários
 * Gerencia todas as operações relacionadas a avaliações de livros
 */

require_once "../../../config/constantes.php";
require_once "../../../config/conexao.php";

// Configuração de cabeçalhos
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está autenticado
 */
function verificarAutenticacao() {
    if (!isset($_SESSION['id_usuario'])) {
        http_response_code(401);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Usuário não autenticado',
            'codigo' => 'AUTH_REQUIRED'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    return $_SESSION['id_usuario'];
}

/**
 * Valida e sanitiza dados de entrada
 */
function validarDadosAvaliacao($dados) {
    $erros = [];
    
    if (!isset($dados['id_livro']) || !is_numeric($dados['id_livro'])) {
        $erros[] = 'ID do livro inválido';
    }
    
    if (!isset($dados['nota']) || !is_numeric($dados['nota'])) {
        $erros[] = 'Nota inválida';
    } elseif ($dados['nota'] < 1 || $dados['nota'] > 5) {
        $erros[] = 'Nota deve estar entre 1 e 5';
    }
    
    if (!isset($dados['comentario']) || empty(trim($dados['comentario']))) {
        $erros[] = 'Comentário é obrigatório';
    } elseif (strlen(trim($dados['comentario'])) < 10) {
        $erros[] = 'Comentário deve ter no mínimo 10 caracteres';
    } elseif (strlen(trim($dados['comentario'])) > 5000) {
        $erros[] = 'Comentário deve ter no máximo 5000 caracteres';
    }
    
    return $erros;
}

/**
 * Formata data para exibição
 */
function formatarDataBR($data) {
    $timestamp = strtotime($data);
    return date('d/m/Y H:i', $timestamp);
}

// ============================================
// GET: Buscar avaliações de um livro
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if (!isset($_GET['id_livro'])) {
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'ID do livro não informado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $id_livro = intval($_GET['id_livro']);
    $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 50;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    try {
        // Usar stored procedure para buscar avaliações
        $sql = "CALL sp_buscar_avaliacoes_livro(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $id_livro, $limite, $offset);
        $stmt->execute();
        
        // Primeiro resultado: avaliações
        $resultAvaliacoes = $stmt->get_result();
        $avaliacoes = [];
        
        $id_usuario_logado = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
        
        while ($row = $resultAvaliacoes->fetch_assoc()) {
            $avaliacoes[] = [
                'id_avaliacao' => intval($row['id_avaliacao']),
                'id_usuario' => intval($row['id_usuario']),
                'nome_usuario' => $row['nome_exibicao'],
                'foto_perfil' => $row['foto_perfil'],
                'nota' => intval($row['nota']),
                'comentario' => $row['comentario'],
                'data_criacao' => $row['data_criacao'],
                'data_formatada' => formatarDataBR($row['data_criacao']),
                'pode_editar' => ($id_usuario_logado === intval($row['id_usuario'])),
                'foi_editado' => ($row['data_criacao'] !== $row['data_atualizacao'])
            ];
        }
        
        // Próximo resultado: estatísticas
        $stmt->next_result();
        $resultStats = $stmt->get_result();
        $stats = $resultStats->fetch_assoc();
        
        $stmt->close();
        
        // Verificar se usuário já avaliou este livro
        $usuarioJaAvaliou = false;
        $avaliacaoUsuario = null;
        
        if ($id_usuario_logado) {
            $sqlUsuario = "SELECT id_avaliacao, nota, comentario 
                          FROM avaliacoes 
                          WHERE id_livro = ? AND id_usuario = ? AND ativo = TRUE";
            $stmtUsuario = $conn->prepare($sqlUsuario);
            $stmtUsuario->bind_param("ii", $id_livro, $id_usuario_logado);
            $stmtUsuario->execute();
            $resultUsuario = $stmtUsuario->get_result();
            
            if ($resultUsuario->num_rows > 0) {
                $usuarioJaAvaliou = true;
                $avaliacaoUsuario = $resultUsuario->fetch_assoc();
            }
            $stmtUsuario->close();
        }

        echo json_encode([
            'sucesso' => true,
            'estatisticas' => [
                'total' => intval($stats['total_avaliacoes']),
                'media' => round(floatval($stats['media_notas']), 1),
                'media_arredondada' => intval($stats['media_arredondada']),
                'distribuicao' => [
                    5 => intval($stats['estrelas_5']),
                    4 => intval($stats['estrelas_4']),
                    3 => intval($stats['estrelas_3']),
                    2 => intval($stats['estrelas_2']),
                    1 => intval($stats['estrelas_1'])
                ]
            ],
            'usuario_ja_avaliou' => $usuarioJaAvaliou,
            'avaliacao_usuario' => $avaliacaoUsuario,
            'avaliacoes' => $avaliacoes,
            'paginacao' => [
                'limite' => $limite,
                'offset' => $offset,
                'total' => count($avaliacoes)
            ]
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Erro ao buscar avaliações',
            'detalhes' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ============================================
// POST: Criar ou atualizar avaliação
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_usuario = verificarAutenticacao();
    
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);

    if ($dados === null) {
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Dados JSON inválidos'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validar dados
    $erros = validarDadosAvaliacao($dados);
    if (!empty($erros)) {
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Dados inválidos',
            'detalhes' => $erros
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $id_livro = intval($dados['id_livro']);
    $nota = intval($dados['nota']);
    $comentario = trim($dados['comentario']);

    try {
        // Usar stored procedure
        $sql = "CALL sp_adicionar_avaliacao(?, ?, ?, ?, @id_avaliacao, @mensagem)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $id_livro, $id_usuario, $nota, $comentario);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Buscar resultados da procedure
            $result = $conn->query("SELECT @id_avaliacao as id, @mensagem as msg");
            $output = $result->fetch_assoc();
            
            if (intval($output['id']) > 0) {
                // Buscar a avaliação criada/atualizada
                $sqlBuscar = "SELECT a.*, u.nome 
                             FROM avaliacoes a 
                             JOIN usuarios u ON a.id_usuario = u.id_usuario 
                             WHERE a.id_avaliacao = ?";
                $stmtBuscar = $conn->prepare($sqlBuscar);
                $stmtBuscar->bind_param("i", $output['id']);
                $stmtBuscar->execute();
                $avaliacao = $stmtBuscar->get_result()->fetch_assoc();
                $stmtBuscar->close();
                
                http_response_code(201);
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => $output['msg'],
                    'id_avaliacao' => intval($output['id']),
                    'avaliacao' => [
                        'id_avaliacao' => intval($avaliacao['id_avaliacao']),
                        'nota' => intval($avaliacao['nota']),
                        'comentario' => $avaliacao['comentario'],
                        'data_criacao' => $avaliacao['data_criacao'],
                        'data_formatada' => formatarDataBR($avaliacao['data_criacao'])
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception($output['msg']);
            }
        } else {
            throw new Exception($stmt->error);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Erro ao salvar avaliação',
            'detalhes' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// ============================================
// DELETE: Remover avaliação
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    $id_usuario = verificarAutenticacao();
    
    $input = file_get_contents('php://input');
    $dados = json_decode($input, true);
    
    if (!isset($dados['id_avaliacao'])) {
        http_response_code(400);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'ID da avaliação não informado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $id_avaliacao = intval($dados['id_avaliacao']);

    try {
        // Usar stored procedure
        $sql = "CALL sp_remover_avaliacao(?, ?, @sucesso, @mensagem)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_avaliacao, $id_usuario);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Buscar resultados
            $result = $conn->query("SELECT @sucesso as sucesso, @mensagem as msg");
            $output = $result->fetch_assoc();
            
            if ($output['sucesso']) {
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => $output['msg']
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(403);
                echo json_encode([
                    'sucesso' => false,
                    'erro' => $output['msg']
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            throw new Exception($stmt->error);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Erro ao remover avaliação',
            'detalhes' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

http_response_code(405);
echo json_encode([
    'sucesso' => false,
    'erro' => 'Método não permitido'
], JSON_UNESCAPED_UNICODE);
?>