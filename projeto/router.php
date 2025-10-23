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

    case 'logout':
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email'], 
        $_SESSION['usuario_categoria'], $_SESSION['usuario_foto']);
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
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/AuxEntityController.php";
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
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/CadastrarLivroController.php";
        $cadastrarLivroController = new CadastrarLivroController();
        $cadastrarLivroController->cadastrar();
        exit;

    case 'atualizarEstoque':
        if (!isAdminLoggedIn()) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/AtualizarEstoqueController.php";
        $controller = new AtualizarEstoqueController();
        $resultado = $controller->atualizarEstoque();
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
        exit;

    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;

    // ADICIONE ESTES CASES NO SEU router.php, DEPOIS do case 'atualizarEstoque':

    case 'editarLivro':
        if (!isAdminLoggedIn()) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/EditarLivroController.php";
        $controller = new EditarLivroController();
        $controller->editar();
        exit;

    case 'buscarLivro':
        if (!isAdminLoggedIn()) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/EditarLivroController.php";
        $id_livro = (int) ($_GET['id_livro'] ?? 0);
        
        if ($id_livro <= 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
            exit;
        }
        
        $controller = new EditarLivroController();
        $resultado = $controller->buscarLivro($id_livro);
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
        exit;

    case 'deletarLivro':
        if (!isAdminLoggedIn()) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        require_once __DIR__ . "/src/controller/admin/DeletarLivroController.php";
        $controller = new DeletarLivroController();
        $controller->deletar();
        exit;

    // Adicione este case no switch do router.php, logo após o case 'atualizarEstoque':

    case 'atualizarNomeSocial':
        if (!isset($_SESSION['usuario_id'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido.');
            }

            $nomeSocial = trim($_POST['nome_social'] ?? '');

            // Nome social pode ser vazio (é opcional)
            if (strlen($nomeSocial) > 50) {
                throw new Exception('Nome social muito longo (máximo 50 caracteres).');
            }

            $id_usuario = $_SESSION['usuario_id'];
            $sucesso = $usuarioController->atualizarNomeSocial($id_usuario, $nomeSocial);

            if ($sucesso) {
                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => 'Nome social atualizado com sucesso!',
                    'nome_social' => $nomeSocial
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Erro ao atualizar nome social no banco de dados.');
            }

        } catch (Exception $e) {
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }