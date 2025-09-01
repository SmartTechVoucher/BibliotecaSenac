<?php
require_once __DIR__ . "/../../../config/db/database.php";
require_once __DIR__ . "/../../model/admin/AdminModel.php";
require_once __DIR__ . "/../../model/usuario/UsuarioModel.php";

class LoginController
{
    private $conn;
    private $adminModel;
    private $usuarioModel;

    public function __construct()
    {
        $banco = new Database();
        $this->conn = $banco->Connect();
        $this->adminModel = new AdminModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function validarLogin($nome, $senha) {
        session_start();

        try {
            // Primeiro tenta login como administrador
            $adminModel = new AdminModel();
            $admin = $adminModel->validarLogin($nome, $senha);

            if ($admin) {
                $_SESSION['usuario'] = [
                    'id' => $admin['id_administrador'],
                    'nome' => $admin['nome'],
                    'tipo' => 'admin'
                ];
                $_SESSION['toast'] = [
                    'mensagem' => "Login efetuado com sucesso!",
                    'tipo' => "success"
                ];
                return true;
            }

            // Se não encontrou admin, tenta como usuário comum
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->validarLogin($nome, $senha);

            if ($usuario) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id_usuario'],
                    'nome' => $usuario['nome'],
                    'tipo' => 'usuario'
                ];
                $_SESSION['toast'] = [
                    'mensagem' => "Login efetuado com sucesso!",
                    'tipo' => "success"
                ];
                return true;
            }

            // Login inválido tanto para admin quanto usuário
            $_SESSION['toast'] = [
                'mensagem' => "Usuário ou senha inválidos.",
                'tipo' => "error"
            ];
            return false;

        } catch (\Throwable $th) {
            $_SESSION['toast'] = [
                'mensagem' => "Erro interno no servidor.",
                'tipo' => "error"
            ];
            return false;
        }
    }

    /**
     * Redireciona usuário baseado no tipo (admin ou usuário comum)
     */
    public function redirecionarUsuario()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . $this->getUrlBase() . '/src/views/usuario/login.php');
            exit;
        }

        $tipo = $_SESSION['usuario']['tipo'];

        if ($tipo === 'admin') {
            header('Location: ' . $this->getUrlBase() . '/src/views/admin/telaInicialDoAdm.php');
        } else {
            header('Location: ' . $this->getUrlBase() . '/index.php');
        }
        exit;
    }

    /**
     * Verifica se usuário está logado
     */
    public function verificarSessao()
    {
        session_start();
        return isset($_SESSION['usuario']);
    }

    /**
     * Faz logout do usuário
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: ' . $this->getUrlBase() . '/src/views/usuario/login.php');
        exit;
    }

    private function getUrlBase()
    {
        if (defined('URLBASE')) {
            return URLBASE;
        }
        return '/BibliotecaSenac/projeto';
    }
}
