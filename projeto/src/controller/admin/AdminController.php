<?php
/**
 * Controller para autenticação de administradores.
 * Usa AdminModel com conexão PDO.
 */

require_once __DIR__ . '/../../model/admin/AdminModel.php';

class AdminController {
    private $admin_model;

    public function __construct() {
        $this->admin_model = new AdminModel();
    }

    /**
     * Processa login de administrador com validação real.
     * @param string $email Email do admin (mapeado de 'nome' no form)
     * @param string $senha Senha informada
     * @return bool True se login bem-sucedido, false caso contrário
     */
    public function login($email, $senha) {
        session_start();  // Garante sessão iniciada

        // Valida credenciais usando model (DB real)
        $admin_data = $this->admin_model->validarLoginAdmin($email, $senha);

        if ($admin_data) {
            // Salva dados na sessão para admin
            $_SESSION['admin'] = [
                'id' => $admin_data['id_administrador'],
                'nome' => $admin_data['nome'],
                'email' => $admin_data['email']
            ];
            // Toast de sucesso em português
            $_SESSION['toast'] = [
                'mensagem' => 'Login de administrador efetuado com sucesso!',
                'tipo' => 'success'
            ];
            return true;
        } else {
            // Toast de erro em português
            $_SESSION['toast'] = [
                'mensagem' => 'Email ou senha inválidos para administrador. Verifique suas credenciais.',
                'tipo' => 'error'
            ];
            return false;
        }
    }
}
?>