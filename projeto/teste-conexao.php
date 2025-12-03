<?php
require_once "config/db/database.php";

try {
    $db = new Database();
    $conn = $db->Connect();

    if ($conn) {
        echo "Conexão estabelecida com sucesso!\n";

        // Testar consulta na tabela usuarios
        $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total de usuários na tabela: " . $result['total'] . "\n";

        // Mostrar estrutura da tabela usuarios
        echo "\nEstrutura da tabela usuarios:\n";
        $stmt = $conn->query("DESCRIBE usuarios");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            echo "- {$column['Field']}: {$column['Type']} " .
                 ($column['Null'] == 'NO' ? 'NOT NULL' : 'NULL') .
                 ($column['Key'] ? " ({$column['Key']})" : '') .
                 ($column['Default'] !== null ? " DEFAULT {$column['Default']}" : '') . "\n";
        }

        // Testar consulta de um usuário específico
        echo "\nPrimeiro usuário da tabela:\n";
        $stmt = $conn->query("SELECT * FROM usuarios LIMIT 1");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "ID: {$user['id_usuario']}\n";
            echo "Nome: {$user['nome']}\n";
            echo "Email: {$user['email']}\n";
            echo "CPF: {$user['cpf']}\n";
            echo "Categoria: {$user['categoria']}\n";
            echo "Unidade: {$user['unidade_senac']}\n";
            echo "Ativo: {$user['ativo']}\n";
        } else {
            echo "Nenhum usuário encontrado na tabela.\n";
        }

    } else {
        echo "Falha na conexão com o banco de dados.\n";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>