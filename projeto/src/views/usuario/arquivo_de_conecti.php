<?php
/**
 * Configuração de Conexão com Banco de Dados
 * Localização: config/conexao.php
 */

// Configurações do banco
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bibliotecasenac');
define('DB_CHARSET', 'utf8mb4');

// Criar conexão
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    error_log("Erro de conexão: " . $conn->connect_error);
    
    // Se for requisição AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Erro de conexão com o banco de dados'
        ]);
        exit;
    }
    
    die("Erro de conexão com o banco de dados");
}

// Configurar charset
if (!$conn->set_charset(DB_CHARSET)) {
    error_log("Erro ao definir charset: " . $conn->error);
}

// Configurar timezone (Horário de Brasília)
$conn->query("SET time_zone = '-03:00'");

// Configurações de erro
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Função para executar queries preparadas
 */
function executarQuery($conn, $sql, $tipos = "", $params = []) {
    $stmt = $conn->prepare($sql);
    
    if ($tipos && !empty($params)) {
        $stmt->bind_param($tipos, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}

/**
 * Função para sanitizar entrada
 */
function sanitizar($conn, $valor) {
    return $conn->real_escape_string(trim($valor));
}

/**
 * Função para fechar conexão
 */
function fecharConexao($conn) {
    if ($conn) {
        $conn->close();
    }
}

// Registrar shutdown
register_shutdown_function(function() use ($conn) {
    fecharConexao($conn);
});
?>