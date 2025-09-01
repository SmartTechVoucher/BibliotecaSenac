<?php
// router.php

session_start();
require_once __DIR__ . '/../config/constantes.php';
require_once __DIR__ . '/../config/db/database.php';

// Verificar se a ação foi enviada
if (!isset($_GET['acao'])) {
    header('Location: ' . $URLBASE);
    exit;
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'validarLogin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE);
            exit;
        }
        
        require_once __DIR__ . '/../src/controller/usuario/usuario-controller.php';
        
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        
        // Validações básicas
        if (empty($email) || empty($senha)) {
            $_SESSION['toast'] = [
                'tipo' => 'erro',
                'mensagem' => 'Email e senha são obrigatórios!'
            ];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
        
        try {
            $usuarioController = new UsuarioController();
            $usuario = $usuarioController->validarLogin($email, $senha);
            
            if ($usuario) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                $_SESSION['toast'] = [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Bem-vindo(a), ' . $usuario['nome'] . '!'
                ];
                
                // Redirecionar para dashboard (ajuste o caminho)
                header('Location: ' . $URLBASE . 'index.php');
                exit;
                
            } else {
                // Login falhou
                $_SESSION['toast'] = [
                    'tipo' => 'erro',
                    'mensagem' => 'Email ou senha incorretos!'
                ];
                header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
                exit;
            }
            
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'tipo' => 'erro',
                'mensagem' => 'Erro interno do servidor. Tente novamente.'
            ];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
        break;

    case 'validarAdminLogin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE);
            exit;
        }

        require_once __DIR__ . '/../src/controller/admin/AdminController.php';

        $nome = trim($_POST['nome'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // Validações básicas
        if (empty($nome) || empty($senha)) {
            $_SESSION['toast'] = [
                'tipo' => 'erro',
                'mensagem' => 'Nome e senha são obrigatórios!'
            ];
            header('Location: ' . $URLBASE . '/public/adm/login.php');
            exit;
        }

        try {
            $adminController = new AdminController();
            $resultado = $adminController->login($nome, $senha);

            if ($resultado) {
                // Login bem-sucedido - redirecionar para tela inicial do admin
                header('Location: ' . $URLBASE . '/src/views/admin/telaInicialDoAdm.php');
                exit;

            } else {
                // Login falhou
                $_SESSION['toast'] = [
                    'tipo' => 'erro',
                    'mensagem' => 'Nome ou senha incorretos!'
                ];
                header('Location: ' . $URLBASE . '/public/adm/login.php');
                exit;
            }

        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'tipo' => 'erro',
                'mensagem' => 'Erro interno do servidor. Tente novamente.'
            ];
            header('Location: ' . $URLBASE . '/public/adm/login.php');
            exit;
        }
        break;

    case 'logout':
        // Destruir sessão
        session_destroy();
        
        $_SESSION['toast'] = [
            'tipo' => 'sucesso',
            'mensagem' => 'Logout realizado com sucesso!'
        ];
        
        header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
        exit;
        break;
        
    default:
        header('Location: ' . $URLBASE);
        exit;
}
?>