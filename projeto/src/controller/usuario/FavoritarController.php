<?php
session_start();
// Inclua a classe Database para conexão
require_once __DIR__ . '/../../../config/db/database.php';

header('Content-Type: application/json');

// O ID do usuário deve ser obtido da sessão
$id_usuario = $_SESSION['user_id'] ?? null;

// 1. Verifica autenticação
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// 2. Obtém o ID do livro da requisição JSON
$data = json_decode(file_get_contents('php://input'), true);
$id_livro = $data['id_livro'] ?? null;

if (!$id_livro || !is_numeric($id_livro)) {
    echo json_encode(['success' => false, 'message' => 'ID do livro inválido.']);
    exit;
}

$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados.']);
    exit;
}

try {
    // 3. Verifica se o livro já está favoritado
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favoritos WHERE id_usuario = :id_usuario AND id_livro = :id_livro");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
    $stmt->execute();
    $is_favorited = $stmt->fetchColumn() > 0;

    $action_performed = '';
    
    if ($is_favorited) {
        // 4. Se estiver favoritado -> REMOVE
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = :id_usuario AND id_livro = :id_livro");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
        $stmt->execute();
        $action_performed = 'removed';
    } else {
        // 5. Se não estiver favoritado -> ADICIONA
        $stmt = $conn->prepare("INSERT INTO favoritos (id_usuario, id_livro) VALUES (:id_usuario, :id_livro)");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
        $stmt->execute();
        $action_performed = 'added';
    }

    echo json_encode([
        'success' => true, 
        'action' => $action_performed, 
        'message' => 'Favorito atualizado com sucesso.'
    ]);

} catch (PDOException $e) {
    error_log("Erro de PDO ao alternar favorito: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}

?>