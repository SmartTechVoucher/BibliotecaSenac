<?php
include 'conexao.php';

$id = $_GET['id']; 
$sql = "SELECT * FROM emprestimos WHERE id = $id";
$result = $conn->query($sql);
$dado = $result->fetch_assoc();
?>

<h2>Detalhes do Empréstimo</h2>
<p><strong>Exemplar:</strong> <?= $dado['exemplar'] ?></p>
<p><strong>Leitor:</strong> <?= $dado['leitor'] ?></p>
<p><strong>Status:</strong> <?= $dado['status'] ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($dado['data'])) ?></p>
<p><strong>Prazo:</strong> <?= date('d/m/Y', strtotime($dado['prazo'])) ?></p>
