<?php


// debug rápido: mostra qual arquivo/linha já enviou saída
if (headers_sent($file, $line)) {
    die("Headers já enviados em $file na linha $line\n");
}

// auth-check.php (config/auth-check.php)

// Segurança de sessão
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/constantes.php';
require_once(__DIR__ . '/../src/controller/usuario/usuario-controller.php');

/**
 * Verifica se o usuário está logado.
 * @return bool
 */
function usuarioEstaLogado(): bool {
    return isset($_SESSION['usuario_id']);
}

/**
 * Verifica se o usuário logado tem a categoria 'Bibliotecario'.
 * @return bool
 */
function usuarioEhAdmin(): bool {
    return usuarioEstaLogado() && ($_SESSION['usuario_categoria'] ?? '') === 'Bibliotecario';
}

/**
 * Protege páginas que só usuários logados podem acessar.
 * - Se não estiver logado → redireciona para login.
 * - Se categorias permitidas forem passadas → verifica se o usuário pertence a elas.
 * 
 * @param array|null $categoriasPermitidas Exemplo: ['Bibliotecario']
 */
function protegerPagina(?array $categoriasPermitidas = null): void {
    global $URLBASE;

    // Se não estiver logado → redireciona para login
    if (!usuarioEstaLogado()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? null;
        $_SESSION['toast'] = [
            'tipo' => 'info',
            'mensagem' => 'Você precisa fazer login para acessar esta página.'
        ];
        header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
        exit;
    }

    // Se categorias foram passadas e usuário não está em nenhuma delas
    if ($categoriasPermitidas !== null && !in_array($_SESSION['usuario_categoria'], $categoriasPermitidas)) {
        $_SESSION['toast'] = [
            'tipo' => 'erro',
            'mensagem' => 'Acesso negado. Você não tem permissão para acessar esta página.'
        ];
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
    }
}

/**
 * Retorna dados do usuário logado.
 * @return array|null
 */
function obterUsuarioLogado(): ?array {
    if (!usuarioEstaLogado()) {
        return null;
    }

    // Recupera o ID salvo na sessão
    $usuarioId = $_SESSION['usuario_id'];

    // Busca os dados completos no banco
    $usuarioController = new UsuarioController();
    $usuario = $usuarioController->obterUsuarioPorId($usuarioId);

    // Se por algum motivo não achar no banco, retorna apenas o básico
    if (!$usuario) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'categoria' => $_SESSION['usuario_categoria'] ?? null
        ];
    }

    return $usuario;
}
