<?php
require_once __DIR__ . "/../../../config/db/database.php";

class LoginController {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    /**
     * Valida o login do usuário usando email e senha
     * @param string $email
     * @param string $senha
     * @param bool $redirect Se deve redirecionar após login bem-sucedido
     * @return bool
     */
    public function ValidarLogin($email, $senha, $redirect = true) {
        session_start();

        try {
            // Validação básica dos campos
            if (empty($email) || empty($senha)) {
                $_SESSION['toast'] = [
                    'mensagem' => "Email e senha são obrigatórios.",
                    'tipo' => "error"
                ];
                return false;
            }

            // Busca o usuário pelo email
            $sql = "SELECT u.*, cu.nome as categoria_nome, c.nome as curso_nome 
                    FROM usuarios u 
                    LEFT JOIN categorias_usuario cu ON u.id_categoria_usuario = cu.id_categoria_usuario
                    LEFT JOIN cursos c ON u.id_curso = c.id_curso
                    WHERE u.email = :email";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido - salva os dados na sessão
                $_SESSION['usuario'] = [
                    'id' => $usuario['id_usuario'],
                    'nome' => $usuario['nome'],
                    'nome_social' => $usuario['nome_social'],
                    'email' => $usuario['email'],
                    'numero_matricula' => $usuario['numero_matricula'],
                    'categoria' => $usuario['categoria_nome'],
                    'categoria_id' => $usuario['id_categoria_usuario'],
                    'curso' => $usuario['curso_nome'],
                    'data_inicio' => $usuario['data_inicio'],
                    'data_fim' => $usuario['data_fim']
                ];

                $_SESSION['toast'] = [
                    'mensagem' => "Login efetuado com sucesso! Bem-vindo(a), " . $usuario['nome'] . "!",
                    'tipo' => "success"
                ];

                // Se deve redirecionar, faz o redirect baseado na categoria
                if ($redirect) {
                    $redirectUrl = $this->getRedirectUrl($usuario['id_categoria_usuario']);
                    header("Location: $redirectUrl");
                    exit;
                }

                return true;

            } else {
                // Credenciais inválidas
                $_SESSION['toast'] = [
                    'mensagem' => "Email ou senha inválidos.",
                    'tipo' => "error"
                ];
                return false;
            }

        } catch (Exception $e) {
            // Erro interno
            $_SESSION['toast'] = [
                'mensagem' => "Erro interno no servidor. Tente novamente.",
                'tipo' => "error"
            ];
            error_log("Erro no login: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Define a URL de redirecionamento baseada na categoria do usuário
     * @param int $categoriaId
     * @return string
     */
    private function getRedirectUrl($categoriaId) {
        // Você pode ajustar essas URLs conforme sua estrutura
        switch ($categoriaId) {
            case 1: // Estudante
                return '/index.php'; // ou '/dashboard/estudante.php'
            case 2: // Professor  
                return '/index.php'; // ou '/dashboard/professor.php'
            case 3: // Funcionário/Admin
                return '/admin/index.php'; // ou '/admin/dashboard.php'
            default:
                return '/index.php';
        }
    }

    /**
     * Realiza logout do usuário
     * @param string $redirectTo URL para redirecionar após logout
     * @return void
     */
    public function Logout($redirectTo = '/src/views/usuario/login.php') {
        session_start();
        
        // Remove todas as variáveis da sessão
        $_SESSION = array();
        
        // Destrói o cookie da sessão se existir
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destrói a sessão
        session_destroy();
        
        // Inicia nova sessão para o toast
        session_start();
        $_SESSION['toast'] = [
            'mensagem' => "Logout realizado com sucesso!",
            'tipo' => "success"
        ];

        // Redireciona para a página de login
        header("Location: $redirectTo");
        exit;
    }

    /**
     * Verifica se o usuário está logado
     * @return bool
     */
    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']['id']);
    }

    /**
     * Obtém os dados do usuário logado
     * @return array|null
     */
    public function getUsuarioLogado() {
        session_start();
        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
    }

    /**
     * Verifica se o usuário tem uma determinada categoria
     * @param string $categoria Nome da categoria
     * @return bool
     */
    public function hasCategoria($categoria) {
        $usuario = $this->getUsuarioLogado();
        return $usuario && $usuario['categoria'] === $categoria;
    }

    /**
     * Middleware para proteger páginas - redireciona se não logado
     * @param string $redirectTo URL para redirecionar se não logado
     * @return void
     */
    public function requireLogin($redirectTo = '/src/views/usuario/login.php') {
        if (!$this->isLoggedIn()) {
            session_start();
            $_SESSION['toast'] = [
                'mensagem' => "Você precisa fazer login para acessar esta página.",
                'tipo' => "error"
            ];
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Middleware para proteger páginas por categoria
     * @param array $allowedCategories IDs das categorias permitidas
     * @param string $redirectTo URL para redirecionar se não autorizado
     * @return void
     */
    public function requireCategory($allowedCategories, $redirectTo = '/acesso-negado.php') {
        $this->requireLogin(); // Primeiro verifica se está logado
        
        $usuario = $this->getUsuarioLogado();
        $userCategoryId = $usuario['categoria_id'] ?? 0;
        
        if (!in_array($userCategoryId, $allowedCategories)) {
            session_start();
            $_SESSION['toast'] = [
                'mensagem' => "Você não tem permissão para acessar esta página.",
                'tipo' => "error"
            ];
            header("Location: $redirectTo");
            exit;
        }
    }
}