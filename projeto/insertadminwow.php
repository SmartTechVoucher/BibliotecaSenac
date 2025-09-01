<?php
require_once "config/db/database.php";

try {
    $database = new Database();
    $conn = $database->Connect();

    $nome = 'AdminTestPFWxFkYQkVzMqEaDnGpHtFsLrJNzBvXcDlMwRpSgKjUhTlWiYaMbNcOdPeQfRgShTjVlXnYmZoApBqCrDsEtFuGvHwIxJy';
    $cpf = '12345678901';
    $email = 'admintestAbCdEfGhIjKlMnOpQrStUvWxYzAbCdEfGhIjKlMnOpQrStUvWxYzAbCdEfGhIjKlMnOpQrSt@test.com';
    $data_nascimento = '1990-01-01';
    $telefone = '1123456789';
    $rua = 'Rua Exemplo 123';
    $bairro = 'Centro';
    $genero = 'M';
    $senha = 'SenhaTestPFWxFkYQkVzMqEaDnGpHtFsLrJNzBvXcDlMwRpSgKjUhTlWiYaMbNcOdPeQfRgShTjVlXnYmZoApBqCrDsEtFuGvHwIxJy';

    $sql = "INSERT INTO adminstrador (nome, cpf, email, data_nascimento, telefone, rua, bairro, genero, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $cpf, $email, $data_nascimento, $telefone, $rua, $bairro, $genero, $senha]);

    echo "<h2>test</h2>";
    echo "<h3>credenciais:</h3>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
    echo "<p><strong>Senha:</strong> " . htmlspecialchars($senha) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>CPF:</strong> " . htmlspecialchars($cpf) . "</p>";

    echo "<div style='margin-top: 20px; padding: 10px; background-color: #e8f5e8; border: 1px solid #4caf50;'>";
    echo "<p>Wow funciono</p>";
    echo "<p>testa o login ai: <strong>/public/adm/login.php</strong></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error inserting admin:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>