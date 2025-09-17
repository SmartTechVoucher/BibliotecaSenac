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
    header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
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

            // usar chaves consistentes: id, nome, email, categoria
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_categoria'] = $usuario['categoria'];

            $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => 'Login realizado com sucesso! Bem-vindo, ' . $usuario['nome'] . '!'];
        
            // REDIRECIONAMENTO INTELIGENTE
            $redirecionarPara = $URLBASE . '/src/views/usuario/index.php'; // padrão
        
            // Se há uma página que o usuário tentou acessar antes do login
            if (isset($_SESSION['redirect_after_login'])) {
                $redirecionarPara = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']); // limpar
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
        // remover dados do usuário (mantém a sessão para poder setar toast)
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_email'], $_SESSION['usuario_categoria']);
 
        $_SESSION['toast'] = ['tipo' => 'info', 'mensagem' => 'Logout realizado com sucesso!'];
 
        // opcional: regenerar id para limpar associação antiga
        session_regenerate_id(true);
 
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
        break;
 
    case 'criarUsuario':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        // Capturar todos os campos do formulário
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
        if (empty($nome) || empty($cpf) || empty($email) || empty($data_nascimento) || 
            empty($categoria) || empty($unidade_senac) || empty($senha)) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'Preencha todos os campos obrigatórios!'];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        if ($senha !== $senha_confirm) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'As senhas não coincidem!'];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        if (strlen($senha) < 6) {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => 'A senha deve ter pelo menos 6 caracteres!'];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }

        // Upload de foto (se enviado)
        $foto_perfil = null;
        if (isset($_FILES['foto-usuario']) && $_FILES['foto-usuario']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/perfil/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['foto-usuario']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('perfil_') . '.' . $extension;
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['foto-usuario']['tmp_name'], $uploadPath)) {
                $foto_perfil = '/public/uploads/perfil/' . $filename;
            }
        }

        // Ajustar valores vazios para NULL
        $nome_social = empty($nome_social) ? null : $nome_social;
        $telefone = empty($telefone) ? null : $telefone;
        $endereco = empty($endereco) ? null : $endereco;
        $genero = empty($genero) ? null : $genero;
        $numero_matricula = empty($numero_matricula) ? null : $numero_matricula;
        $curso = empty($curso) ? null : $curso;
        $turma = empty($turma) ? null : $turma;
        $data_fim_curso = empty($data_fim_curso) ? null : $data_fim_curso;
        $notas_usuario = empty($notas_usuario) ? null : $notas_usuario;

        $resultado = $usuarioController->criarUsuario(
            $nome, $nome_social, $cpf, $email, $data_nascimento, $telefone, 
            $endereco, $genero, $foto_perfil, $numero_matricula, $categoria, 
            $unidade_senac, $curso, $turma, $data_fim_curso, $notas_usuario, $senha
        );

        if ($resultado['success']) {
            $_SESSION['toast'] = ['tipo' => 'success', 'mensagem' => $resultado['message']];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        } else {
            $_SESSION['toast'] = ['tipo' => 'erro', 'mensagem' => $resultado['message']];
            header('Location: ' . $URLBASE . '/src/views/admin/cadastro-usuarios.php');
            exit;
        }
        break;
 
    default:
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
}