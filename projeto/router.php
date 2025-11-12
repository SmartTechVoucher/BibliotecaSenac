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
require_once __DIR__ . '/src/controller/usuario/usuario-controller.php'; // UsuarioController
require_once __DIR__ . '/src/controller/usuario/login-controller.php';   // LoginController
require_once __DIR__ . '/src/controller/admin/AdminController.php';

// 🚨 INCLUSÃO DO CONTROLLER DE COMENTÁRIOS (CAMINHO BASEADO NA SUA ESTRUTURA)
// Note que a classe será instanciada como ComentariosController (no plural)
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
// Instancia o controller principal de usuário para ações gerais
$usuarioController = new UsuarioController(); 

switch ($acao) {
    // ====== USUÁRIO E AUTENTICAÇÃO ======
    
    case 'validarLogin':
        // ... (seu código de validarLogin)
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
        // ... (seu código de logout)
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email'], 
        $_SESSION['usuario_categoria'], $_SESSION['usuario_foto']);
        $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Logout realizado com sucesso!'];
        session_regenerate_id(true);
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
        break;

    case 'atualizarNomeSocial':
        if (!isset($_SESSION['usuario_id'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método inválido.');
            }
            
            // 🚨 CORREÇÃO: Lendo dados JSON do body (como o JavaScript envia)
            $input = json_decode(file_get_contents('php://input'), true);
            $nomeSocial = trim($input['nome_social'] ?? '');

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
        break;

    // ----------------------------------------------------
    // ⭐️ AÇÃO DE COMENTÁRIOS E AVALIAÇÕES (RESOLVE O PROBLEMA PRINCIPAL) ⭐️
    // ----------------------------------------------------
    case 'comentarios':
        // Prepara a resposta para JSON
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        // 🚨 Instancia o Controller (certifique-se que o nome da classe é ComentariosController)
        $controller = new ComentariosController();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // 1. CARREGAR COMENTÁRIOS (GET)
            $id_livro = (int) ($_GET['id_livro'] ?? 0);
            
            if ($id_livro <= 0) {
                echo json_encode(['sucesso' => false, 'erro' => 'ID do livro inválido.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Chama o método do Controller. Espera um array: ['comentarios' => [...], 'estatisticas' => {...}]
            $resultado = $controller->getComentariosPorLivro($id_livro);
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
            
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 2. ENVIAR COMENTÁRIO (POST)
            
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['sucesso' => false, 'erro' => 'Você precisa estar logado para comentar.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Lê o JSON enviado pelo JavaScript
            $dados = json_decode(file_get_contents('php://input'), true);

            // Adiciona o ID do usuário da sessão para segurança
            $dados['id_usuario'] = $_SESSION['usuario_id'];

            if (empty($dados) || !isset($dados['id_livro'], $dados['comentario'], $dados['avaliacao'])) {
                echo json_encode(['sucesso' => false, 'erro' => 'Dados de comentário incompletos.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Chama o método do Controller. Espera um array: ['sucesso' => true/false, 'mensagem' => '...']
            $resultado = $controller->adicionarComentario($dados);
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } else {
            // Método não permitido
            echo json_encode(['sucesso' => false, 'erro' => 'Método não permitido para esta ação.'], JSON_UNESCAPED_UNICODE);
        }
        exit;
        break;

    // ====== ADMIN ======
    
    case 'criarUsuario':
        // ... (seu código existente)
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
        // ... (seu código existente)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        $adminController = new AdminController();
        $email = $_POST["nome"] ?? '';
        $senha = $_POST["senha"] ?? '';
        $resultado = $adminController->login($email, $senha);

        if ($resultado) {
            header("Location: " . $URLBASE . "/src/views/admin/index.php");
            exit;
        } else {
            header("Location: " . $URLBASE . "/src/views/admin/login-adm.php");
            exit;
        }
        break;

    case 'recuperarSenha':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
            exit;
        }

        $adminController = new AdminController();
        $email = $_POST["email"] ?? '';
        $resultado = $adminController->recuperarSenha($email);

        $_SESSION['toast'] = [
            'tipo' => $resultado['success'] ? 'success' : 'erro',
            'mensagem' => $resultado['message']
        ];

        header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
        exit;
        break;

    case 'resetarSenha':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/resetar-senha.php?token=' . ($_GET['token'] ?? ''));
            exit;
        }

        $adminController = new AdminController();
        $token = $_POST["token"] ?? '';
        $nova_senha = $_POST["nova_senha"] ?? '';
        $confirmar_senha = $_POST["confirmar_senha"] ?? '';
        $resultado = $adminController->resetarSenha($token, $nova_senha, $confirmar_senha);

        $_SESSION['toast'] = [
            'tipo' => $resultado['success'] ? 'success' : 'erro',
            'mensagem' => $resultado['message']
        ];

        if ($resultado['success']) {
            header('Location: ' . $URLBASE . '/src/views/admin/login-adm.php');
        } else {
            header('Location: ' . $URLBASE . '/src/views/admin/resetar-senha.php?token=' . $token);
        }
        exit;
        break;

    
    case 'auxEntity':
    // ... (seu código existente)
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
        break;

        
    case 'cadastrarLivro':
    // ... (seu código existente)
        if (!isAdminLoggedIn()) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
            exit;
        }
        
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
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
            } catch (Exception $e) {
                error_log('Router atualizarEstoque error: ' . $e->getMessage());
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Erro interno no servidor: ' . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            break;



   default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        require_once __DIR__ . "/src/controller/admin/CadastrarLivroController.php";
        $cadastrarLivroController = new CadastrarLivroController();
        $cadastrarLivroController->cadastrar();
        exit;

    case 'atualizarEstoque':
    // ... (seu código existente)
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

    case 'editarLivro':
    // ... (seu código existente)
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
    // ... (seu código existente)
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
    // ... (seu código existente)
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

    
    // ----------------------------------------------------
    // ⚠️ DEFAULT (SÓ REDIRECIONA QUANDO NENHUMA ROTA É ENCONTRADA)
    // ----------------------------------------------------
    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
        break;
    }
}