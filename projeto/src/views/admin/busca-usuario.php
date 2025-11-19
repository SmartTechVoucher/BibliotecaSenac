<?php
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$query = $conn->real_escape_string($_GET['q']);

$sql = "SELECT id, nome, email, matricula, telefone, acesso 
        FROM usuarios 
        WHERE nome LIKE ? OR matricula LIKE ? OR email LIKE ?
        LIMIT 10";

$stmt = $conn->prepare($sql);
$searchTerm = "%{$query}%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);

$stmt->close();
$conn->close();
?>