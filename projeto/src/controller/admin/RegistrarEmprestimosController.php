<?php
/**
 * Controller responsável por registrar um novo empréstimo.
 * Recebe id_usuario e isbn via POST.
 * Retorna JSON com {success: true/false, message: "..."}
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_usuario']) || !isset($_POST['isbn'])) {
    echo json_encode(['success' => false, 'message' => 'Erro: Requisição inválida.']);
    exit;
}

$idUsuario = intval($_POST['id_usuario']);
$isbn = trim($_POST['isbn']);
$prazoDevolucaoDias = 6; // esse aq é o prazo padrão de devolução em dias

try {
    $conn->beginTransaction();

    // 1. Encontrar o id_livro e verificar disponibilidade (usando o JOIN com exemplares)
    $sqlLivro = "SELECT l.id_livro, e.disponiveis 
                 FROM livros l
                 JOIN exemplares e ON l.id_livro = e.id_livro
                 WHERE l.isbn = :isbn
                 LIMIT 1";
    $stmtLivro = $conn->prepare($sqlLivro);
    $stmtLivro->bindValue(':isbn', $isbn, PDO::PARAM_STR);
    $stmtLivro->execute();
    $livro = $stmtLivro->fetch(PDO::FETCH_ASSOC);

    if (!$livro) {
        throw new Exception("Livro com este ISBN não encontrado.");
    }

    if ($livro['disponiveis'] <= 0) {
        throw new Exception("Livro indisponível. Não há exemplares para empréstimo.");
    }
    
    $idLivro = $livro['id_livro'];

    // Inserir na tabela de movimentações
    $dataMovimentacao = date('Y-m-d H:i:s');
    $dataPrevistaDevolucao = date('Y-m-d', strtotime("+$prazoDevolucaoDias days"));

    $sqlInsert = "INSERT INTO movimentacoes 
                    (id_usuario, id_livro, data_movimentacao, data_prevista_devolucao, status)
                  VALUES 
                    (:id_usuario, :id_livro, :data_mov, :data_prazo, 'Emprestado')";
    
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->execute([
        ':id_usuario' => $idUsuario,
        ':id_livro' => $idLivro,
        ':data_mov' => $dataMovimentacao,
        ':data_prazo' => $dataPrevistaDevolucao
    ]);

    // Atualizar a tabela de exemplares
    $sqlUpdate = "UPDATE exemplares 
                  SET disponiveis = disponiveis - 1, 
                      emprestados = emprestados + 1
                  WHERE id_livro = :id_livro";
    
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bindValue(':id_livro', $idLivro, PDO::PARAM_INT);
    $stmtUpdate->execute();

    // Se tudo deu certo, confirma a transação
    $conn->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Empréstimo registrado com sucesso!'
    ]);

} catch (Exception $e) {
    // Se algo deu errado, desfaz a transação
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    // Retorna a mensagem de erro específica
    echo json_encode([
        'success' => false, 
        'message' => 'Erro: ' . $e->getMessage()
    ]);
}