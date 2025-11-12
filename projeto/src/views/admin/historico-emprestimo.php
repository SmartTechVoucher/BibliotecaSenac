<?php
$titulo = 'Emprestimos Realizados';
$cssPagina = '/public/css/admin/historico-emprestimo.css';
$conteudo = __DIR__ . '/pages/historico-emprestimo-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . '/layout-base.php';

include __DIR__ . '/conexao.php'; 


$filtro = isset($_GET['status']) ? $_GET['status'] : 'Todos';


$sql = "SELECT * FROM emprestimos";


if ($filtro != 'Todos') {
    $sql .= " WHERE status = '$filtro'";
}

$result = $conn->query($sql);

if(!$result){
    die("Erro na consulta: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de empréstimos</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
        <?php
            require_once "../../../config/constantes.php";
        ?>
        <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/footer-admin.css">
        <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
        <link rel="stylesheet" href="/BibliotecaSenac/projeto/public/css/admin/historico-emprestimo.css">
    </head>

 <body>
    <?php   
        include "../../../public/components/admin/header/header-admin.php";
    ?>

    <div class="main-container">
        <h2>Histórico de Empréstimos</h2>   
        <div class="filter-options">
            <span>Filtrar por Status:</span>
            <label>
                <input type="radio" name="statusFilter" value="Todos" checked onchange="aplicarFiltroEPaginacao()"> Todos
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Finalizado" onchange="aplicarFiltroEPaginacao()"> Finalizado
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Atrasado" onchange="aplicarFiltroEPaginacao()"> Atrasado
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Em andamento" onchange="aplicarFiltroEPaginacao()"> Em andamento
            </label>
        </div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Exemplar</th>
                        <th>Leitor</th>
                        <th>Data</th>
                        <th>Prazo</th>
                        <th>Devolução</th>
                    </tr>
                </thead>
                <tbody id="userTable"></tbody>
                
            </table>

    <div class="pagination-controls">
        <button id="prevBtn" onclick="paginaAnterior()">Anterior</button>
        <span id="pageInfo" class="pagination-info"></span>
        <button id="nextBtn" onclick="proximaPagina()">Próximo</button>
    </div>

    </div>     

    <tbody>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['status']}</td>
                <td>{$row['exemplar']}</td>
                <td>{$row['leitor']}</td>
                <td>" . date('d/m/Y', strtotime($row['data'])) . "</td>
                <td>" . date('d/m/Y', strtotime($row['prazo'])) . "</td>
                <td>{$row['devolucao']}</td>
              </tr>";
    }
} else {
    echo "<tr class='linha-vazia'>
            <td colspan='6'>Nenhum empréstimo encontrado</td>
          </tr>";
}
?>
</tbody>
 <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    <script src="../../../public/js/admin/historico-emprestimo.js"></script>

 </body>
</html>
$titulo = 'Emprestimos Realizados';
$cssPagina = '/public/css/admin/historico-emprestimo.css';
$conteudo = __DIR__ . '/pages/historico-emprestimo-content.php'; // caminho do conteúdo
$modalPagina = [
    'id' => 'confirmModal',
    'titulo' => 'Confirmação',
    'mensagem' => 'Você tem certeza que deseja sair?'
];

// Inclui o layout-base (que já inclui sidebar, main-content e modal)
include __DIR__ . '/layout-base.php';
