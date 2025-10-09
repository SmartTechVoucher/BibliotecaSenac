<?php
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
  <title>Histórico de Empréstimos</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h2>Histórico de Empréstimos</h2>

  <!-- Filtros -->
  <form method="get" action="historico.php">
    <label><input type="radio" name="status" value="Todos" <?= $filtro=="Todos"?"checked":""; ?>> Todos</label>
    <label><input type="radio" name="status" value="Finalizado" <?= $filtro=="Finalizado"?"checked":""; ?>> Finalizado</label>
    <label><input type="radio" name="status" value="Atrasado" <?= $filtro=="Atrasado"?"checked":""; ?>> Atrasado</label>
    <label><input type="radio" name="status" value="Em andamento" <?= $filtro=="Em andamento"?"checked":""; ?>> Em andamento</label>
    <button type="submit">Filtrar</button>
  </form>

  <!-- Tabela -->
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>Status</th>
      <th>Exemplar</th>
      <th>Leitor</th>
      <th>Data</th>
      <th>Prazo</th>
      <th>Devolução</th>
    </tr>
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
        echo "<tr><td colspan='6'>Nenhum empréstimo encontrado</td></tr>";
    }
    ?>
  </table>
</body>
</html>
