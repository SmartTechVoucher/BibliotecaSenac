<?php
// router.php

require_once __DIR__ . '/../config/constantes.php';
require_once __DIR__ . '/../config/db/database.php';
require_once __DIR__ . '/../src/controller/usuario/usuario-controller.php';

session_start();

// Verificar se a ação foi enviada
if (!isset($_GET['acao'])) {
    header('Location: ' . $URLBASE . '/index.php');
    exit;
}

$acao = $_GET['acao'];
$usuarioController = new UsuarioController();

switch ($acao) {
    case 'validarLogin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $senha = $_POST['senha'];
            $lembrar = isset($_POST['lembrar']);
            
            // Validações básicas
            if (empty($email) || empty($senha)) {
                $_SESSION['toast'] = [
                    'tipo' => 'erro',
                    'mensagem' => 'Email e senha são obrigatórios!'
                ];
                header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
                exit;
            }
            
            // Tenta validar o login
            $usuario = $usuarioController->validarLogin($email, $senha);
            
            if ($usuario) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                // Se marcou "lembrar senha", pode implementar cookies aqui
                if ($lembrar) {
                    // Implementar lógica de "lembrar senha" se necessário
                    // setcookie('lembrar_usuario', $usuario['id'], time() + (86400 * 30), '/');
                }
                
                $_SESSION['toast'] = [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Login realizado com sucesso! Bem-vindo, ' . $usuario['nome'] . '!'
                ];
                
                // Redireciona para a página principal
                header('Location: ' . $URLBASE . '/index.php');
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
        } else {
            // Método não permitido
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
        break;
        
    case 'logout':
        // Destrói a sessão
        session_destroy();
        $_SESSION = array();
        
        $_SESSION['toast'] = [
            'tipo' => 'info',
            'mensagem' => 'Logout realizado com sucesso!'
        ];
        
        header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
        exit;
        break;
        
    case 'criarUsuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Aqui você implementaria a lógica para criar usuário
            // Similar ao validarLogin, mas chamando criarUsuario()
            
            $nome = trim($_POST['nome']);
            $nome_social = trim($_POST['nome_social']);
            $cpf = trim($_POST['cpf']);
            $email = trim($_POST['email']);
            // ... outros campos
            
            $resultado = $usuarioController->criarUsuario(
                $nome, $nome_social, $cpf, $email, 
                // ... outros parâmetros
            );
            
            if ($resultado) {
                $_SESSION['toast'] = [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Usuário criado com sucesso!'
                ];
                header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            } else {
                $_SESSION['toast'] = [
                    'tipo' => 'erro',
                    'mensagem' => 'Erro ao criar usuário. Email ou CPF já existem.'
                ];
                header('Location: ' . $URLBASE . '/src/views/usuario/cadastro.php');
            }
            exit;
        }
        break;
        
    default:
        // Ação não reconhecida
        header('Location: ' . $URLBASE . '/index.php');
        exit;
}
?>