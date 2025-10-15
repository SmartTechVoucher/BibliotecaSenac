<?php
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json');

// Verificar ação
$acao = $_POST['acao'] ?? '';
$emprestimo_id = isset($_POST['emprestimo_id']) ? intval($_POST['emprestimo_id']) : 0;

if (!$acao || !$emprestimo_id) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
    exit;
}

switch ($acao) {
    case 'renovar':
        renovarEmprestimo($emprestimo_id, $conn);
        break;
    
    case 'devolver':
        devolverLivro($emprestimo_id, $conn);
        break;
    
    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida']);
        break;
}

function renovarEmprestimo($id, $conn) {
   
    $sql = "SELECT * FROM emprestimos WHERE id = ? AND status = 'ativo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Empréstimo não encontrado ou já devolvido']);
        return;
    }
    
    $emprestimo = $result->fetch_assoc();
    
    if ($emprestimo['renovacoes'] >= 2) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Limite de renovações atingido (máximo 2)']);
        return;
    }
    
    // Adicionar 14 dias ao prazo de devolução
    $novo_prazo = date('Y-m-d', strtotime($emprestimo['prazo_devolucao'] . ' +14 days'));
    
    $sql_update = "UPDATE emprestimos 
                   SET prazo_devolucao = ?, 
                       renovacoes = renovacoes + 1,
                       status = 'renovado'
                   WHERE id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $novo_prazo, $id);
    
    if ($stmt_update->execute()) {
        echo json_encode([
            'sucesso' => true, 
            'mensagem' => 'Empréstimo renovado com sucesso!',
            'novo_prazo' => date('d/m/Y', strtotime($novo_prazo)),
            'renovacoes' => $emprestimo['renovacoes'] + 1
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao renovar empréstimo']);
    }
    
    $stmt_update->close();
    $stmt->close();
}

function devolverLivro($id, $conn) {
    // Verificar se o empréstimo existe e está ativo
    $sql = "SELECT * FROM emprestimos WHERE id = ? AND status IN ('ativo', 'atrasado', 'renovado')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Empréstimo não encontrado ou já devolvido']);
        return;
    }
    
    $emprestimo = $result->fetch_assoc();
    $data_devolucao = date('Y-m-d');
    
    // Verificar se houve atraso
    $dias_atraso = 0;
    if (strtotime($data_devolucao) > strtotime($emprestimo['prazo_devolucao'])) {
        $dias_atraso = floor((strtotime($data_devolucao) - strtotime($emprestimo['prazo_devolucao'])) / 86400);
    }
    
    // Atualizar empréstimo
    $sql_update = "UPDATE emprestimos 
                   SET data_devolucao = ?,
                       status = 'devolvido'
                   WHERE id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $data_devolucao, $id);
    
    if ($stmt_update->execute()) {
        // Atualizar quantidade disponível do livro
        $sql_livro = "UPDATE livros 
                      SET quantidade_disponivel = quantidade_disponivel + 1,
                          status = 'disponivel'
                      WHERE id = ?";
        
        $stmt_livro = $conn->prepare($sql_livro);
        $stmt_livro->bind_param("i", $emprestimo['livro_id']);
        $stmt_livro->execute();
        $stmt_livro->close();
        
        $mensagem = 'Livro devolvido com sucesso!';
        if ($dias_atraso > 0) {
            $mensagem .= " (Atraso de $dias_atraso dias)";
        }
        
        echo json_encode([
            'sucesso' => true, 
            'mensagem' => $mensagem,
            'data_devolucao' => date('d/m/Y', strtotime($data_devolucao)),
            'dias_atraso' => $dias_atraso
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao devolver livro']);
    }
    
    $stmt_update->close();
    $stmt->close();
}

$conn->close();
?>