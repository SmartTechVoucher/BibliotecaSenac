<?php
// auth-check.php (config/auth-check.php)

// Segurança de sessão
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/constantes.php';

/**
 * Verifica se o usuário está logado e se todas as chaves de sessão necessárias existem.
 * @return bool
 */
function usuarioEstaLogado() {
    return isset($_SESSION['usuario_id']) && 
           isset($_SESSION['usuario_nome']) && 
           isset($_SESSION['usuario_email']) &&
           isset($_SESSION['usuario_categoria']);
}

/**
 * Verifica se o usuário logado tem a categoria 'Bibliotecario'.
 * @return bool
 */
function usuarioEhAdmin() {
    return usuarioEstaLogado() && $_SESSION['usuario_categoria'] === 'Bibliotecario';
}

/**
 * Protege páginas que só usuários logados podem acessar.
 * Opcionalmente, pode restringir o acesso a categorias de usuários específicas.
 * Se não estiver logado ou não tiver a categoria permitida, redireciona para login.
 * * @param array|null $categoriasPermitidas Um array de strings com as categorias permitidas. Ex: ['Bibliotecario'].
 */
function protegerPagina(?array $categoriasPermitidas = null) {
    global $URLBASE;

    if (!usuarioEstaLogado()) {
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

    // Verificar se a categoria do usuário está na lista de categorias permitidas, se a lista foi fornecida.
    if ($categoriasPermitidas !== null && !in_array($_SESSION['usuario_categoria'], $categoriasPermitidas)) {
        // Acesso negado
        $_SESSION['toast'] = [
            'tipo' => 'erro',
            'mensagem' => 'Acesso negado. Você não tem permissão para acessar esta página.'
        ];

        // Redireciona para a página inicial ou outra página de erro
        header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
        exit;
    }
}

/**
 * Retorna dados do usuário logado.
 * @return array|null
 */
function obterUsuarioLogado() {
    if (usuarioEstaLogado()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'categoria' => $_SESSION['usuario_categoria']
        ];
    }
    return null;
}
?>