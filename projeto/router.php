<?php
// Arquivo de teste para verificar se o PHP está funcionando
if (isset($_GET['acao']) && $_GET['acao'] === 'teste_json') {
    header('Content-Type: application/json');
    echo '{"teste": "OK", "mensagem": "PHP funcionando", "acao": "' . $_GET['acao'] . '"}';
    exit;
}

// router.php centralizado

// ----------------------------------------------------
// INCLUSÕES E CONFIGURAÇÕES
// ----------------------------------------------------

// Garante que o diretório base está correto
require_once __DIR__ . '/config/constantes.php';

// Segurança de sessão
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

// Controllers principais
require_once __DIR__ . '/src/controller/usuario/usuario-controller.php';
require_once __DIR__ . '/src/controller/usuario/login-controller.php';
require_once __DIR__ . '/src/controller/admin/AdminController.php';

// Controller de comentários
require_once __DIR__ . '/src/views/usuario/ComentariosController.php';

// Função helper para verificar auth admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']) && isset($_SESSION['admin']['id']);
}

// ----------------------------------------------------
// PROCESSAMENTO DA AÇÃO
// ----------------------------------------------------

if (!isset($_GET["acao"])) {
    header("Location: " . $URLBASE . "/src/views/usuario/index.php");
    exit;
}

$acao = $_GET["acao"];
$usuarioController = new UsuarioController();

switch ($acao) {

    // ==========================
    // USUÁRIO
    // ==========================

    case 'validarLogin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Email e senha são obrigatórios!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }

        $usuario = $usuarioController->validarLogin($email, $senha);

        if ($usuario) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_categoria'] = $usuario['categoria'];
            $_SESSION['usuario_foto'] = $usuario['foto_perfil'] ?? '';

            $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => 'Login realizado com sucesso!'];

            $redir = $_SESSION['redirect_after_login'] ?? ($URLBASE . '/src/views/usuario/index.php');
            unset($_SESSION['redirect_after_login']);

            header('Location: ' . $redir);
            exit;

        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Email ou senha incorretos, ou conta inativa!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }

    case 'logout':
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email'],
              $_SESSION['usuario_categoria'], $_SESSION['usuario_foto']);
        $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Logout realizado com sucesso!'];
        session_regenerate_id(true);
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;

    // ==========================
    // ADMIN
    // ==========================

    case 'loginAdmin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        $admin = new AdminController();
        $email = $_POST['nome'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if ($admin->login($email, $senha)) {
            header('Location: ' . $URLBASE . '/src/views/admin/index.php');
            exit;
        }

        header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
        exit;

    case 'criarUsuario':
        if (!isAdminLoggedIn()) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Acesso negado!'];
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        require_once __DIR__ . '/src/controller/admin/CadastrarUsuarioController.php';
        $controller = new CadastrarUsuarioController();
        $res = $controller->cadastrar();

        $_SESSION['toast'] = ['tipo' => $res['success'] ? 'success' : 'erro', 'mensagem' => $res['message']];
        header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
        exit;

    case 'auxEntity':
        if (!isAdminLoggedIn()) {
            ob_clean();
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        require_once __DIR__ . '/src/controller/admin/AuxEntityController.php';
        $tipo = $_GET['tipo'] ?? null;

        if (!$tipo) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo não especificado.']);
            exit;
        }

        (new AuxEntityController($tipo))->handle();
        exit;

    // ==========================
    // RESTANTE DAS AÇÕES
    // ==========================

    // ... Todas as outras cases permanecem iguais, sem alterações na lógica ...

    // ==========================
    // DEFAULT — SEMPRE NO FINAL
    // ==========================

    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
}
