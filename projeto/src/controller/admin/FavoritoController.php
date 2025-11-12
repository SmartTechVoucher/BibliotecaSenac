<?php

require_once __DIR__ . '/../../../config/db/database.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_livro = $_POST['id_livro'] ?? null;

if (!$id_livro) {
    echo json_encode(['success' => false, 'message' => 'Livro não informado.']);
    exit;
}

try {
    // Verifica se já está favoritado
    $stmt = $conn->prepare("SELECT * FROM favoritos WHERE id_usuario = ? AND id_livro = ?");
    $stmt->execute([$id_usuario, $id_livro]);
    $favoritado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($favoritado) {
        // Remove dos favoritos
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_livro = ?");
        $stmt->execute([$id_usuario, $id_livro]);
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Livro removido dos favoritos.']);
    } else {
        // Adiciona aos favoritos
        $stmt = $conn->prepare("INSERT INTO favoritos (id_usuario, id_livro) VALUES (?, ?)");
        $stmt->execute([$id_usuario, $id_livro]);
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Livro adicionado aos favoritos.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
