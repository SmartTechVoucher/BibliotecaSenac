<?php
require_once __DIR__ . "/../../model/admin/AdminModel.php";

class AdminController {

    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    public function login($nome, $senha) {
        session_start();

        try {
            $admin = $this->adminModel->validarLogin($nome, $senha);

            if ($admin) {
                // Salva os dados na sessão
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
            } else {
                $_SESSION['toast'] = [
                    'mensagem' => "Usuário ou senha inválidos.",
                    'tipo' => "error"
                ];
                return false;
            }
        } catch (\Throwable $th) {
            $_SESSION['toast'] = [
                'mensagem' => "Erro interno no servidor.",
                'tipo' => "error"
            ];
            return false;
        }
    }
}