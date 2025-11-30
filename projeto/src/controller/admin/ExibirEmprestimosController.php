<?php
/**
 * Controller responsável por buscar e exibir a tabela de empréstimos 
 * de um usuário de forma PAGINADA.
 * 
 * Recebe:
 * - $_GET['id'] (ID do usuário)
 * - $_GET['page'] (Número da página, opcional, default=1)
 * 
 * Retorna:
 * - JSON com { success: true, tabela_html: "...", paginacao_html: "..." }
 */

require_once __DIR__ . '/../../../config/db/database.php';
// Define que a resposta será JSON
header('Content-Type: application/json');

// --- Configuração da Paginação ---
$itens_por_pagina = 5; // Quantos empréstimos por página

// --- Conexão ao DB ---
date_default_timezone_set('America/Campo_Grande');
$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erro: Falha na conexão com o banco de dados.']);
    exit;
}

// --- Validação de Entrada ---
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido.']);
    exit;
}

$idUsuario = intval($_GET['id']);
$pagina_atual = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($pagina_atual < 1) $pagina_atual = 1;

// --- Cálculo de Offset ---
$offset = ($pagina_atual - 1) * $itens_por_pagina;

try {
    // --- Query 1: Contar o TOTAL de itens ---
    $sqlTotal = "SELECT COUNT(*) as total FROM movimentacoes WHERE id_usuario = :id";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmtTotal->execute();
    $total_itens = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
    
    if ($total_itens == 0) {
        echo json_encode([
            'success' => true,
            'tabela_html' => '<h4>Histórico de Emprtimos</h4><p>Este usuário não possui empréstimos ativos ou histórico.</p>',
            'paginacao_html' => ''
        ]);
        exit;
    }
    
    $total_paginas = ceil($total_itens / $itens_por_pagina);

    // --- Query 2: Buscar os itens da PÁGINA ATUAL ---
    $sql = "SELECT 
                m.id_movimentacao, m.id_livro, l.titulo, l.isbn, 
                m.data_movimentacao, m.data_limite_retirada,
                m.data_prevista_devolucao, m.data_real_devolucao, m.status
            FROM movimentacoes m
            JOIN livros l ON m.id_livro = l.id_livro
            WHERE m.id_usuario = :id
            ORDER BY 
                CASE 
                    WHEN m.status = 'Pendente' THEN 1
                    WHEN m.status = 'Emprestado' THEN 2
                    ELSE 3
                END,
                m.data_movimentacao DESC
            LIMIT :limit OFFSET :offset";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- Geração do HTML da Tabela ---
    $html_tabela = "<h4>Histórico de Empréstimos (Página $pagina_atual de $total_paginas)</h4>";
    $html_tabela .= "<table class='loan-table'>";
    $html_tabela .= "<thead>
            <tr>
                <th>Título</th>
                <th>ISBN</th>
                <th>Data Empréstimo</th>
                <th>Prazo Devolução</th>
                <th>Data Devolvido</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
          </thead>";
    $html_tabela .= "<tbody>";

    foreach ($emprestimos as $emp) {
        $dataEmp = new DateTime($emp['data_movimentacao']);
        
        // Define o prazo de devolução baseado no status
        $dataPrazo = '---';
        if ($emp['status'] == 'Pendente' && $emp['data_limite_retirada']) {
            // Para pendentes, mostra o prazo de retirada (48h)
            $dataPrazo = (new DateTime($emp['data_limite_retirada']))->format('d/m/Y H:i');
        } elseif ($emp['data_prevista_devolucao']) {
            // Para emprestados, mostra o prazo de devolução
            $dataPrazo = (new DateTime($emp['data_prevista_devolucao']))->format('d/m/Y');
        }
        
        $dataDev = $emp['data_real_devolucao'] ? (new DateTime($emp['data_real_devolucao']))->format('d/m/Y') : '---';

        $html_tabela .= "<tr>
                <td>" . htmlspecialchars($emp['titulo']) . "</td>
                <td>" . htmlspecialchars($emp['isbn']) . "</td>
                <td>" . $dataEmp->format('d/m/Y H:i') . "</td>
                <td>" . $dataPrazo . "</td>
                <td>" . $dataDev . "</td>
                <td>" . htmlspecialchars($emp['status']) . "</td>
                <td>";
        
        // Ações baseadas no status
        $id_mov = $emp['id_movimentacao'];
        $id_livro = $emp['id_livro'];
        
        if ($emp['status'] == 'Pendente') {
            // Botão para CONFIRMAR o empréstimo (inicia as 48h de devolução)
            $html_tabela .= "<button class='btn-acao btn-confirmar' style='background-color: #28a745;' onclick='confirmarEmprestimo($id_mov)'>Confirmar</button>";
            
        } elseif ($emp['status'] == 'Emprestado') {
            // Botões para RENOVAR e DEVOLVER
            $html_tabela .= "<button class='btn-acao btn-renovar' onclick='renovarEmprestimo($id_mov)'>Renovar</button>";
            $html_tabela .= "<button class='btn-acao btn-devolver' onclick='devolverEmprestimo($id_mov, $id_livro)'>Devolver</button>";
            
        } else {
            // Para status 'Devolvido' ou 'Cancelado'
            $html_tabela .= "---";
        }
        
        $html_tabela .= "</td></tr>";
    }
    $html_tabela .= "</tbody></table>";

    // --- Geração do HTML da Paginação ---
    $html_paginacao = "";
    if ($total_paginas > 1) {
        $html_paginacao = "<nav class='pagination'>";
        
        // Botão "Anterior"
        if ($pagina_atual > 1) {
            $prev_page = $pagina_atual - 1;
            $html_paginacao .= "<a href='#' onclick='event.preventDefault(); carregarTabelaEmprestimos(usuarioSelecionadoId, $prev_page)'>&laquo; Anterior</a>";
        }

        // Links das páginas
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_atual) {
                $html_paginacao .= "<a href='#' class='active'>$i</a>";
            } else {
                $html_paginacao .= "<a href='#' onclick='event.preventDefault(); carregarTabelaEmprestimos(usuarioSelecionadoId, $i)'>$i</a>";
            }
        }

        // Botão "Próximo"
        if ($pagina_atual < $total_paginas) {
            $next_page = $pagina_atual + 1;
            $html_paginacao .= "<a href='#' onclick='event.preventDefault(); carregarTabelaEmprestimos(usuarioSelecionadoId, $next_page)'>Próximo &raquo;</a>";
        }
        $html_paginacao .= "</nav>";
    }

    // --- Resposta JSON Final ---
    echo json_encode([
        'success' => true,
        'tabela_html' => $html_tabela,
        'paginacao_html' => $html_paginacao
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro de Query: ' . $e->getMessage()]);
}
?>