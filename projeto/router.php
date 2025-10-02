<?php
// router.php centralizado

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

// Função helper para verificar auth admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']) && isset($_SESSION['admin']['id']);
}

if (!isset($_GET["acao"])) {
    header("Location: " . $URLBASE . "/src/views/usuario/index.php");
    exit;
}

$acao = $_GET["acao"];
$usuarioController = new UsuarioController();

switch ($acao) {
    // ====== USUÁRIO ======
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
        
            // CORREÇÃO: Adicionar a foto do usuário na sessão
            $_SESSION['usuario_foto'] = $usuario['foto_perfil'] ?? '';

            $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => 'Login realizado com sucesso!'];

            $redirecionarPara = $URLBASE . '/src/views/usuario/index.php';
            if (isset($_SESSION['redirect_after_login'])) {
                $redirecionarPara = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
            }

            header('Location: ' . $redirecionarPara);
            exit;
        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Email ou senha incorretos, ou conta inativa!'];
            header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
            exit;
        }
        break;

        // E no logout, adicione a limpeza da foto:
    case 'logout':
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email'], 
        $_SESSION['usuario_categoria'], $_SESSION['usuario_foto']); // Adicionar usuario_foto aqui
        $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Logout realizado com sucesso!'];
        session_regenerate_id(true);
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
        break;

    // ====== ADMIN ======

    case 'criarUsuario':
        if (!isAdminLoggedIn()) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Acesso negado!'];
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        require_once __DIR__ . '/src/controller/admin/CadastrarUsuarioController.php';
        
        $controller = new CadastrarUsuarioController();
        $resultado = $controller->cadastrar();

        $_SESSION['toast'] = [
            'tipo' => $resultado['success'] ? 'success' : 'erro',
            'mensagem' => $resultado['message']
        ];

        header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
        exit;
        break;

    case 'loginAdmin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        $adminController = new AdminController();
        $email = $_POST["nome"] ?? '';
        $senha = $_POST["senha"] ?? '';
        $resultado = $adminController->login($email, $senha);

        if ($resultado) {
            header("Location: " . $URLBASE . "/src/views/admin/telaInicialDoAdm.php");
            exit;
        } else {
            header("Location: " . $URLBASE . "/src/views/admin/login-adm.php");
            exit;
        }
        break;

    case 'auxEntity':
        if (!isAdminLoggedIn()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        require_once __DIR__ . "/../src/controller/admin/AuxEntityController.php";
        $tipo = $_GET['tipo'] ?? null;
        if (!$tipo) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Tipo não especificado.']);
            exit;
        }
        $auxController = new AuxEntityController($tipo);
        $auxController->handle();
        exit;

    case 'cadastrarLivro':
        if (!isAdminLoggedIn()) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        require_once __DIR__ . "/../src/controller/admin/CadastrarLivroController.php";
        $cadastrarLivroController = new CadastrarLivroController();
        $sucesso = $cadastrarLivroController->cadastrar();
        echo json_encode(['sucesso' => $sucesso]);
        exit;

    case 'atualizarEstoque':
        if (!isAdminLoggedIn()) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        require_once __DIR__ . "/../src/controller/admin/AtualizarEstoqueController.php";
        $controller = new AtualizarEstoqueController();
        $resultado = $controller->atualizarEstoque();
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
        exit;

    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
}
