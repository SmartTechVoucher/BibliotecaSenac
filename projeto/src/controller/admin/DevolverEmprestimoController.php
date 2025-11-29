<?php
/**
 * Controller para registrar a devolução de um livro.
 * ATUALIZADO: Notifica próximo da fila quando livro for devolvido
 */

require_once __DIR__ . '/../../../config/db/database.php';
require_once __DIR__ . '/../../model/FilaReservaModel.php';

header('Content-Type: application/json');
date_default_timezone_set('America/Campo_Grande');

$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erro: Falha na conexão com o banco de dados.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_movimentacao']) || !isset($_POST['id_livro'])) {
    echo json_encode(['success' => false, 'message' => 'Erro: Requisição inválida.']);
    exit;
}

$idMovimentacao = intval($_POST['id_movimentacao']);
$idLivro = intval($_POST['id_livro']);

try {
    // Inicia a transação
    $conn->beginTransaction();

    // 1. Atualizar a tabela de movimentações
    $sqlMov = "UPDATE movimentacoes 
               SET data_real_devolucao = NOW(), 
                   status = 'Devolvido' 
               WHERE id_movimentacao = :id_mov AND status = 'Emprestado'";
    
    $stmtMov = $conn->prepare($sqlMov);
    $stmtMov->bindValue(':id_mov', $idMovimentacao, PDO::PARAM_INT);
    $stmtMov->execute();

    if ($stmtMov->rowCount() == 0) {
        throw new Exception("Este livro já foi devolvido ou a movimentação não existe.");
    }

    // 2. Atualizar a tabela de exemplares
    $sqlEx = "UPDATE exemplares 
              SET disponiveis = disponiveis + 1, 
                  emprestados = emprestados - 1
              WHERE id_livro = :id_livro";
    
    $stmtEx = $conn->prepare($sqlEx);
    $stmtEx->bindValue(':id_livro', $idLivro, PDO::PARAM_INT);
    $stmtEx->execute();

    // 3. NOVO: Notifica o próximo da fila (se houver)
    $filaModel = new FilaReservaModel();
    $notificacao = $filaModel->notificarProximoDaFila($idLivro);

    $conn->commit();

    $mensagem = 'Livro devolvido com sucesso!';
    
    if ($notificacao['sucesso']) {
        $mensagem .= ' O próximo usuário da fila foi notificado.';
    }

    echo json_encode([
        'success' => true, 
        'message' => $mensagem
    ]);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}