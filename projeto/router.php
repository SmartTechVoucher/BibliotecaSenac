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
        $_SESSION['toast'] = ['tipo' => 'info', 'mensagem' => 'Logout realizado com sucesso!'];
        session_regenerate_id(true);
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
        break;

    // ====== ADMIN ======

    case 'criarUsuario':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        // Coleta dos dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $nome_social = trim($_POST['nome_social'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $telefone = trim($_POST['telefone'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $genero = $_POST['genero'] ?? '';
        $numero_matricula = trim($_POST['matricula'] ?? '');
        $categoria = $_POST['categoria'] ?? '';
        $unidade_senac = $_POST['unidade_senac'] ?? '';
        $curso = trim($_POST['curso'] ?? '');
        $turma = trim($_POST['turma'] ?? '');
        $data_fim_curso = $_POST['data_fim_curso'] ?? '';
        $notas_usuario = trim($_POST['notas_usuario'] ?? '');
        $senha = $_POST['senha_usuario'] ?? '';
        $senha_confirm = $_POST['senha_usuario_confirm'] ?? '';

        // Validações básicas
        if (empty($nome) || empty($cpf) || empty($email) || empty($data_nascimento) || empty($categoria) || empty($unidade_senac) || empty($senha)) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Campos obrigatórios não preenchidos!'];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        if ($senha !== $senha_confirm) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'As senhas não coincidem!'];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        // Processamento da foto de perfil
        $foto_perfil = '';
        if (isset($_FILES['foto-usuario']) && $_FILES['foto-usuario']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../uploads/perfil/';
            
            // Criar diretório se não existir
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $arquivo = $_FILES['foto-usuario'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $extensoes_permitidas)) {
                $nome_arquivo = 'perfil_' . uniqid() . '.' . $extensao;
                $caminho_completo = $upload_dir . $nome_arquivo;

                if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
                    $foto_perfil = $nome_arquivo;
                } else {
                    $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Erro ao fazer upload da foto!'];
                    header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
                    exit;
                }
            } else {
                $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Formato de imagem não suportado!'];
                header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
                exit;
            }
        }

        // Ajustar valores para o banco
        $genero = match($genero) {
            'masculino' => 'Masculino',
            'feminino' => 'Feminino',
            'nao_binario' => 'Não binario',
            'outros' => 'Outros',
            'nao_informar' => 'Não informar',
            default => null
        };

        $categoria = match($categoria) {
            'graduacao' => 'Aluno',
            'pos' => 'Docente', 
            'extensao' => 'Bibliotecario',
            default => $categoria
        };

        $unidade_senac = match($unidade_senac) {
            'senac_hub' => 'Senac Hub Academy',
            'senac_dou' => 'Senac Dourados',
            'senac_tres' => 'Senac Três Lagoas',
            default => $unidade_senac
        };

        // Criar usuário
        $resultado = $usuarioController->criarUsuario(
            $nome, $nome_social, $cpf, $email, $data_nascimento, $telefone,
            $endereco, $genero, $foto_perfil, $numero_matricula, $categoria,
            $unidade_senac, $curso, $turma, $data_fim_curso, $notas_usuario, $senha
        );

        if ($resultado['success']) {
            $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => $resultado['message']];
        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => $resultado['message']];
        }

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
