<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Para requisições OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Ajuste o caminho conforme sua estrutura
require_once '../../controller/arquivo_de_conecti.php'; // AJUSTE O CAMINHO

$method = $_SERVER['REQUEST_METHOD'];

// ============= POST - CRIAR NOVO COMENTÁRIO =============
if ($method === 'POST') {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    // Log para debug
    error_log("Dados recebidos: " . print_r($data, true));

    if (!$data) {
        echo json_encode(['erro' => 'JSON inválido ou corpo vazio']);
        exit;
    }

    if (!isset($data['id_usuario'], $data['id_livro'], $data['comentario'], $data['avaliacao'])) {
        echo json_encode(['erro' => 'Campos obrigatórios ausentes (id_usuario, id_livro, comentario, avaliacao)']);
        exit;
    }

    $id_usuario = intval($data['id_usuario']);
    $id_livro = intval($data['id_livro']);
    $comentario = trim($data['comentario']);
    $avaliacao = intval($data['avaliacao']);

    // Validações
    if ($id_usuario <= 0) {
        echo json_encode(['erro' => 'ID do usuário inválido']);
        exit;
    }

    if ($id_livro <= 0) {
        echo json_encode(['erro' => 'ID do livro inválido']);
        exit;
    }

    if (empty($comentario)) {
        echo json_encode(['erro' => 'Comentário não pode estar vazio']);
        exit;
    }

    if ($avaliacao < 1 || $avaliacao > 4) {
        echo json_encode(['erro' => 'Avaliação deve estar entre 1 e 4']);
        exit;
    }

    $sql = "INSERT INTO comentarios (id_usuario, id_livro, comentario, avaliacao, data_comentario) 
            VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['erro' => 'Erro no prepare: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('iisi', $id_usuario, $id_livro, $comentario, $avaliacao);
    
    if ($stmt->execute()) {
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Comentário adicionado com sucesso',
            'id_comentario' => $stmt->insert_id
        ]);
    } else {
        echo json_encode(['erro' => 'Erro ao executar: ' . $stmt->error]);
    }
    
    $stmt->close();
    exit;
}

// ============= GET - BUSCAR COMENTÁRIOS DO LIVRO =============
if ($method === 'GET') {
    if (!isset($_GET['id_livro'])) {
        echo json_encode(['erro' => 'Parâmetro id_livro não enviado']);
        exit;
    }

    $id_livro = intval($_GET['id_livro']);

    if ($id_livro <= 0) {
        echo json_encode(['erro' => 'ID do livro inválido']);
        exit;
    }

    $sql = "SELECT 
                c.id_comentario, 
                c.id_usuario, 
                c.id_livro, 
                c.comentario, 
                c.avaliacao, 
                c.data_comentario,
                u.nome
            FROM comentarios c
            LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.id_livro = ?
            ORDER BY c.data_comentario DESC";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['erro' => 'Erro no prepare: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('i', $id_livro);
    
    if (!$stmt->execute()) {
        echo json_encode(['erro' => 'Erro ao executar: ' . $stmt->error]);
        exit;
    }

    $res = $stmt->get_result();
    $rows = [];
    
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
    
    echo json_encode($rows);
    $stmt->close();
    exit;
}

// Método não suportado
echo json_encode(['erro' => 'Método não suportado. Use GET ou POST']);
?>