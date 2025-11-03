<?php
header('Content-Type: application/json; charset=utf-8');
// Se front e backend estiverem em domínios diferentes, ative CORS:
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexao.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data) {
        echo json_encode(['erro' => 'JSON inválido ou corpo vazio']);
        exit;
    }

    if (!isset($data['id_usuario'], $data['id_livro'], $data['comentario'], $data['avaliacao'])) {
        echo json_encode(['erro' => 'Campos obrigatórios ausentes']);
        exit;
    }

    $id_usuario = intval($data['id_usuario']);
    $id_livro = intval($data['id_livro']);
    $comentario = $data['comentario'];
    $avaliacao = intval($data['avaliacao']);

    $sql = "INSERT INTO comentarios (id_usuario, id_livro, comentario, avaliacao) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['erro' => 'Erro no prepare: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('iisi', $id_usuario, $id_livro, $comentario, $avaliacao);
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['erro' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

if ($method === 'GET') {
    if (!isset($_GET['id_livro'])) {
        echo json_encode(['erro' => 'id_livro não enviado']);
        exit;
    }
    $id_livro = intval($_GET['id_livro']);

    $sql = "SELECT c.id_comentario, c.id_usuario, c.id_livro, c.comentario, c.avaliacao, c.data_comentario, u.nome
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
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
    echo json_encode($rows);
    $stmt->close();
    exit;
}

echo json_encode(['erro' => 'Método não suportado']);
?>