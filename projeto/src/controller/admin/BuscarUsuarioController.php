<?php
/**
 * Controller responsável por buscar usuários no banco de dados via AJAX.
 * Recebe o parâmetro `q` (query de busca) via GET e retorna JSON com até 8 resultados.
 * Compatível com a classe Database e o padrão MVC utilizado no projeto.
 */

require_once __DIR__ . '/../../../config/db/database.php';



$db = new Database();
$conn = $db->Connect();

if (!$conn) {
    echo "<div class='user-item'>Erro: não foi possível conectar ao banco de dados.</div>";
    exit;
}

if (!isset($_GET['q'])) exit;

$search = trim($_GET['q']);
if ($search === '') exit;

try {
    $sql = "SELECT id_usuario, nome, email, cpf 
            FROM usuarios 
            WHERE nome LIKE :term 
               OR email LIKE :term 
               OR cpf LIKE :term
            ORDER BY nome ASC 
            LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':term', "%$search%", PDO::PARAM_STR);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) === 0) {
        echo "<div class='user-item'>Nenhum usuário encontrado.</div>";
        exit;
    }

    foreach ($users as $user) {
        echo "<div class='user-item' onclick=\"mostrarUsuario({$user['id_usuario']})\">";
        echo "<strong>{$user['nome']}</strong><br>";
        echo"<div class='user-subinfo'>";
        echo "<small>Email: {$user['email']}</small><br>";
        echo "<small>CPF: {$user['cpf']}</small>";
        echo "</div>";
        echo "</div>";
    }

} catch (PDOException $e) {
    echo "<div class='user-item'>Erro: " . htmlspecialchars($e->getMessage()) . "</div>";
}