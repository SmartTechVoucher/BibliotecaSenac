<?php
require_once __DIR__ . '/config/constantes.php';

ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/src/controller/usuario/usuario-controller.php';
require_once __DIR__ . '/src/controller/usuario/login-controller.php';
require_once __DIR__ . '/src/controller/admin/AdminController.php';

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

            } catch (Exception $e) {
                error_log('Router cadastrarLivro error: ' . $e->getMessage());
                if ($isAjax) {
                    echo json_encode([
                        'sucesso' => false,
                        'mensagem' => 'Erro interno no servidor: ' . $e->getMessage()
                    ]);
                    exit;
                } else {
                    session_start();
                    $_SESSION['toast'] = [
                        'mensagem' => 'Erro interno no servidor.',
                        'tipo' => 'error'
                    ];
                    header("Location: ./src/views/admin/telaDeCadastroDeLivros.php");
                    exit;
                }
            }
            break;

        case 'atualizarEstoque':
            if (!isAdminLoggedIn()) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Acesso negado. Faça login como administrador.'
                ]);
                exit;
            }

            header('Content-Type: application/json; charset=utf-8');

            try {
                require_once __DIR__ . "/src/controller/admin/AtualizarEstoqueController.php";
                $controller = new AtualizarEstoqueController();
                $resultado = $controller->atualizarEstoque();

                echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
                exit;

    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
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