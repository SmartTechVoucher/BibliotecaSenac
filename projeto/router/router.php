<?php
// router.php
require_once __DIR__ . '/../config/constantes.php';
require_once __DIR__ . '/../src/controller/usuario/usuario-controller.php';
 
// Segurança antes do start
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();
 
if (!isset($_GET['acao'])) {
    header('Location: ' . $URLBASE . '/index.php');
    exit;
}
 
$acao = $_GET['acao'];
$usuarioController = new UsuarioController();
 
switch ($acao) {
    case 'validarLogin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
 
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $lembrar = isset($_POST['lembrar']);
 
        if (empty($email) || empty($senha)) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Email e senha são obrigatórios!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
 
        $usuario = $usuarioController->validarLogin($email, $senha);
 
        if ($usuario) {
            // proteção contra fixation
            session_regenerate_id(true);
 
            // usar chaves consistentes: id, nome, email
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
 
            $_SESSION['toast'] = ['tipo' => 'sucesso', 'mensagem' => 'Login realizado com sucesso! Bem-vindo, ' . $usuario['nome'] . '!'];
            header('Location: ' . $URLBASE . '/index.php');
            exit;
        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Email ou senha incorretos!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
        break;
 
    case 'logout':
        // remover dados do usuário (mantém a sessão para poder setar toast)
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email']);
 
        $_SESSION['toast'] = ['tipo' => 'info', 'mensagem' => 'Logout realizado com sucesso!'];
 
        // opcional: regenerar id para limpar associação antiga
        session_regenerate_id(true);
 
        header('Location: ' . $URLBASE . '/index.php');
        exit;
        break;
 
    case 'criarUsuario':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/usuario/cadastro.php');
            exit;
        }
 
        // exemplo mínimo, adapte aos seus campos
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
 
        $resultado = $usuarioController->criarUsuario($nome, '', $cpf, $email, null, null, null, null, null, null, null, null, null, null, $senha);
 
        if ($resultado) {
            $_SESSION['toast'] = ['tipo' => 'sucesso', 'mensagem' => 'Usuário criado com sucesso!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Erro ao criar usuário. Verifique os dados.'];
            header('Location: ' . $URLBASE . '/src/views/usuario/cadastro.php');
            exit;
        }
        break;
 
    default:
        header('Location: ' . $URLBASE . '/index.php');
        exit;
}
 