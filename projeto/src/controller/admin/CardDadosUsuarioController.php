<?php
require_once __DIR__ . '/../../../config/db/database.php';

$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo "<p>Erro: não foi possível conectar ao banco de dados.</p>";
    exit;
}

if (!isset($_GET['id'])) exit;

$id = intval($_GET['id']);

try {
    $sql = "SELECT nome, email, cpf, telefone, categoria, unidade_senac
            FROM usuarios 
            WHERE id_usuario = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<p>Usuário não encontrado.</p>";
        exit;
    }

    echo "<h3>" . htmlspecialchars($user['nome']) . "</h3>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
    echo "<p><strong>CPF:</strong> " . htmlspecialchars($user['cpf']) . "</p>";
    if (!empty($user['telefone'])) echo "<p><strong>Telefone:</strong> " . htmlspecialchars($user['telefone']) . "</p>";
    echo "<p><strong>Categoria:</strong> " . htmlspecialchars($user['categoria']) . "</p>";
    echo "<p><strong>Unidade:</strong> " . htmlspecialchars($user['unidade_senac']) . "</p>";

} catch (PDOException $e) {
    echo "<p>Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
}
