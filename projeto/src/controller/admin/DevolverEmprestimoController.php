<?php
/**
 * Controller para registrar a devolução de um livro.
 * Recebe 'id_movimentacao' e 'id_livro' via POST.
 * Usa uma transação para atualizar 'movimentacoes' e 'exemplares'.
 * Retorna JSON.
 */

require_once __DIR__ . '/../../../config/db/database.php';
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
    // define a data real de devolução e muda o status, apenas se o status atual for 'Emprestado'
 
    $sqlMov = "UPDATE movimentacoes 
               SET data_real_devolucao = NOW(), 
                   status = 'Devolvido' 
               WHERE id_movimentacao = :id_mov AND status = 'Emprestado'";
    
    $stmtMov = $conn->prepare($sqlMov);
    $stmtMov->bindValue(':id_mov', $idMovimentacao, PDO::PARAM_INT);
    $stmtMov->execute();

    // verifica se a atualização da movimentação realmente aconteceu
    if ($stmtMov->rowCount() == 0) {
        // se rowCount é 0, o livro provavelmente já foi devolvido
        // damos rollback e avisamos o usuário
        throw new Exception("Este livro já foi devolvido ou a movimentação não existe.");
    }

    // 2. Atualizar a tabela de exemplares
    // devolve o livro ao estoque de 'disponiveis' e remove de 'emprestados'
    $sqlEx = "UPDATE exemplares 
              SET disponiveis = disponiveis + 1, 
                  emprestados = emprestados - 1
              WHERE id_livro = :id_livro";
    
    $stmtEx = $conn->prepare($sqlEx);
    $stmtEx->bindValue(':id_livro', $idLivro, PDO::PARAM_INT);
    $stmtEx->execute();

    // se o bglh rodou sem erro, confirma a transação
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Livro devolvido com sucesso!']);

} catch (Exception $e) {
    // se algo deu errado, desfaz tudo
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}