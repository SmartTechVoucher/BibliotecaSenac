<?php
// Inclua aqui seus outros controllers conforme necessário
require_once __DIR__ . '/src/controller/usuario/login-controller.php';

// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pega a ação da URL
$acao = $_GET['acao'] ?? '';

// Roteamento das ações
switch ($acao) {
    case 'validarLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            
            $loginController = new LoginController();
            
            // Valida o login e redireciona automaticamente se bem-sucedido
            $loginController->ValidarLogin($email, $senha);
            
            // Se chegou aqui, login falhou - redireciona de volta para login
            header("Location: /src/views/usuario/login.php");
            exit;
        } else {
            // Método não permitido
            header("Location: /src/views/usuario/login.php");
            exit;
        }
        break;
    
    case 'logout':
        $loginController = new LoginController();
        $loginController->Logout();
        break;
    
    case 'verificarLogin':
        // Endpoint para verificar se está logado (útil para AJAX)
        header('Content-Type: application/json');
        $loginController = new LoginController();
        
        $response = [
            'loggedIn' => $loginController->isLoggedIn(),
            'user' => $loginController->getUsuarioLogado()
        ];
        
        echo json_encode($response);
        exit;
        break;
    
    // Adicione outras ações aqui conforme necessário
    // case 'cadastrarUsuario':
    //     // Implementar quando tiver o sistema de cadastro
    //     break;
    
    default:
        // Ação não encontrada - redireciona para home ou erro 404
        header("HTTP/1.0 404 Not Found");
        header("Location: /index.php");
        exit;
}