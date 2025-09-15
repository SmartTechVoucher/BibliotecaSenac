<?php
// auth-check.php (criar em: config/auth-check.php)

// Segurança de sessão
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/constantes.php';

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function usuarioEstaLogado() {
    return isset($_SESSION['usuario_id']) && 
           isset($_SESSION['usuario_nome']) && 
           isset($_SESSION['usuario_email']);
}

/**
 * Protege páginas que só usuários logados podem acessar
 * Se não estiver logado, redireciona para login
 */
function protegerPagina() {
    if (!usuarioEstaLogado()) {
        global $URLBASE;
        
        // Salvar a página que o usuário tentou acessar para redirecionar depois do login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Definir mensagem de toast
        $_SESSION['toast'] = [
            'tipo' => 'info', 
            'mensagem' => 'Você precisa fazer login para acessar esta página.'
        ];
        
        // Redirecionar para login
        header('Location: ' . $URLBASE . '/src/views/usuario/login.php');
        exit;
    }
}

/**
 * Retorna dados do usuário logado
 * @return array|null
 */
function obterUsuarioLogado() {
    if (usuarioEstaLogado()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email']
        ];
    }
    return null;
}
?>