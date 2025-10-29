<?php
/**
 * Controller para renovar um empréstimo (adicionar 5 dias).
 * Recebe 'id_movimentacao' via POST.
 * Retorna JSON.
 */

require_once __DIR__ . '/../../../config/db/database.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erro: Falha na conexão com o banco de dados.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_movimentacao'])) {
    echo json_encode(['success' => false, 'message' => 'Erro: Requisição inválida.']);
    exit;
}

$idMovimentacao = intval($_POST['id_movimentacao']);

try {
    // Adiciona 5 dias à data_prevista_devolucao
    // Apenas se o status ainda for 'Emprestado'
    $sql = "UPDATE movimentacoes 
            SET data_prevista_devolucao = DATE_ADD(data_prevista_devolucao, INTERVAL 5 DAY) 
            WHERE id_movimentacao = :id AND status = 'Emprestado'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $idMovimentacao, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Empréstimo renovado com sucesso!']);
    } else {
        throw new Exception("Não foi possível renovar (empréstimo já devolvido ou não encontrado).");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}