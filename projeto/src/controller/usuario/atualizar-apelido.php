<?php
require_once __DIR__ . '/usuario-controller.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario_id'];
    $apelido = trim($_POST['apelido'] ?? '');

    if ($apelido === '') {
        echo json_encode(['success' => false, 'message' => 'O apelido não pode estar vazio']);
        exit;
    }

    $controller = new UsuarioController();
    $resultado = $controller->atualizarApelido($id_usuario, $apelido);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Apelido atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar apelido no banco']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
