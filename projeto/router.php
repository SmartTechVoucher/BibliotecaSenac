<?php
// ==========================
// router.php REFATORADO COMPLETO
// ==========================

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

// Helper: Verifica se admin está logado
function isAdminLoggedIn() {
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']) && isset($_SESSION['admin']['id']);
}

// Se não houver ação, manda para home
if (!isset($_GET['acao'])) {
    header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
    exit;
}

$acao = $_GET['acao'];
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
// LIVROS (ADM)
// ==========================

case 'cadastrarLivro':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/CadastrarLivroController.php';
    (new CadastrarLivroController())->cadastrar();
    exit;

case 'atualizarEstoque':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/AtualizarEstoqueController.php';
    echo json_encode((new AtualizarEstoqueController())->atualizarEstoque(), JSON_UNESCAPED_UNICODE);
    exit;

case 'editarLivro':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/EditarLivroController.php';
    (new EditarLivroController())->editar();
    exit;

case 'buscarLivro':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/EditarLivroController.php';
    $id_livro = (int) ($_GET['id_livro'] ?? 0);

    if ($id_livro <= 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID inválido.']);
        exit;
    }

    echo json_encode((new EditarLivroController())->buscarLivro($id_livro), JSON_UNESCAPED_UNICODE);
    exit;

case 'deletarLivro':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/DeletarLivroController.php';
    (new DeletarLivroController())->deletar();
    exit;

// ==========================
// LIVROS (USUÁRIO) — ROTA CORRIGIDA
// ==========================

case 'buscarLivroDetalhes':
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/LivroController.php';
    $livroController = new LivroController();
    $livroController->buscarDetalhes();  // ✅ SEM parâmetro
    exit;

// ==========================
// NOME SOCIAL
// ==========================

case 'atualizarNomeSocial':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Método inválido.');

        $nomeSocial = trim($_POST['nome_social'] ?? '');
        if (strlen($nomeSocial) > 50) throw new Exception('Nome social muito longo.');

        $ok = $usuarioController->atualizarNomeSocial($_SESSION['usuario_id'], $nomeSocial);

        echo json_encode([
            'sucesso' => $ok,
            'mensagem' => $ok ? 'Nome social atualizado!' : 'Falha ao atualizar.'
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
    }

    exit;

// ==========================
// EMPRÉSTIMOS E RESERVAS
// ==========================

case 'solicitarEmprestimo':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/EmprestimoController.php';  // ✅ USUARIO, não admin!
    $emprestimoController = new EmprestimoController();
    $emprestimoController->solicitarEmprestimo();
    exit;

case 'entrarNaFila':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/EmprestimoController.php';
    $emprestimoController = new EmprestimoController();
    $emprestimoController->entrarNaFila();
    exit;

case 'cancelarReserva':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/EmprestimoController.php';
    $emprestimoController = new EmprestimoController();
    $emprestimoController->cancelarReserva();
    exit;

case 'meusEmprestimos':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/EmprestimoController.php';
    $emprestimoController = new EmprestimoController();
    $emprestimoController->meusEmprestimos();
    exit;

case 'verificarPosicaoFila':
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/EmprestimoController.php';
    $emprestimoController = new EmprestimoController();
    $emprestimoController->verificarPosicaoFila();
    exit;

case 'confirmarEmprestimo':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/ConfirmarEmprestimoController.php';
    $confirmarController = new ConfirmarEmprestimoController();
    $confirmarController->confirmar();
    exit;

// ==========================
// AVALIAÇÕES/COMENTÁRIOS
// Adicione ANTES do 'default' no router.php
// ==========================

case 'salvarAvaliacao':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/AvaliacaoController.php';
    $avaliacaoController = new AvaliacaoController();
    $avaliacaoController->salvar();
    exit;

case 'listarAvaliacoes':
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/AvaliacaoController.php';
    $avaliacaoController = new AvaliacaoController();
    $avaliacaoController->listar();
    exit;

case 'minhaAvaliacao':
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/AvaliacaoController.php';
    $avaliacaoController = new AvaliacaoController();
    $avaliacaoController->minhaAvaliacao();
    exit;

case 'deletarAvaliacao':
    if (!isset($_SESSION['usuario_id'])) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/usuario/AvaliacaoController.php';
    $avaliacaoController = new AvaliacaoController();
    $avaliacaoController->deletar();
    exit;

case 'historicoEmprestimos':
    if (!isAdminLoggedIn()) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Acesso negado.']);
        exit;
    }

    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/src/controller/admin/HistoricoEmprestimosController.php';
    $historicoController = new HistoricoEmprestimosController();
    
    $acao = $_GET['acao'] ?? 'listar';
    if ($acao === 'listar') {
        $historicoController->listar();
    } elseif ($acao === 'estatisticas') {
        $historicoController->estatisticas();
    }
    exit;
    

// ==========================
// DEFAULT — SEMPRE NO FINAL
// ==========================

default:
    header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
    exit;

}
