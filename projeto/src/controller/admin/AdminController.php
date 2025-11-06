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

    /**
     * Processa recuperação de senha do administrador.
     * @param string $email Email do admin
     * @return array Resultado com sucesso/mensagem
     */
    public function recuperarSenha($email) {
        try {
            if (empty($email)) {
                error_log("Tentativa de recuperação de senha sem email");
                return ['success' => false, 'message' => 'Email é obrigatório.'];
            }

            error_log("Iniciando recuperação de senha para email: {$email}");

            $admin = $this->admin_model->buscarAdminPorEmail($email);
            if (!$admin) {
                error_log("Email não encontrado no sistema: {$email}");
                return ['success' => false, 'message' => 'Email não encontrado no sistema.'];
            }

            error_log("Admin encontrado: {$admin['nome']} (ID: {$admin['id_administrador']})");

            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira em 1 hora

            error_log("Token gerado, expira em: {$expiry}");

            if (!$this->admin_model->atualizarTokenReset($admin['id_administrador'], $token, $expiry)) {
                error_log("Falha ao atualizar token no banco para admin ID: {$admin['id_administrador']}");
                return ['success' => false, 'message' => 'Erro ao gerar token de recuperação.'];
            }

            error_log("Token atualizado no banco com sucesso");

            // Envia email real
            if ($this->enviarEmailRecuperacao($admin, $token)) {
                error_log("Email enviado com sucesso para: {$admin['email']}");
                return ['success' => true, 'message' => 'Instruções enviadas para seu email. Verifique sua caixa de entrada (inclusive spam).'];
            } else {
                error_log("Falha no envio de email para: {$admin['email']}");
                return ['success' => false, 'message' => 'Erro ao enviar email. Verifique a configuração do servidor e tente novamente.'];
            }
        } catch (Exception $e) {
            error_log("Erro inesperado na recuperação de senha: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno do servidor. Tente novamente mais tarde.'];
        }
    }

    /**
     * Envia email de recuperação de senha.
     * @param array $admin Dados do admin
     * @param string $token Token de reset
     * @return bool True se enviado, false caso contrário
     */
    private function enviarEmailRecuperacao($admin, $token) {
        try {
            $reset_link = $GLOBALS['URLBASE'] . "/src/views/admin/resetar-senha.php?token=" . $token;

            // Tenta usar PHPMailer se disponível
            if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../../vendor/autoload.php';
                require_once __DIR__ . '/../../../config/email-config.php';

                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USERNAME;
                    $mail->Password = SMTP_PASSWORD;
                    $mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = SMTP_PORT;

                    // Configurações adicionais
                    $mail->CharSet = EMAIL_CHARSET;
                    $mail->Encoding = 'base64';

                    if (SMTP_DEBUG) {
                        $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
                    }

                    $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
                    $mail->addReplyTo(EMAIL_FROM, EMAIL_FROM_NAME);
                    $mail->addAddress($admin['email'], $admin['nome']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Recuperação de Senha - Biblioteca SENAC';
                    $mail->Body = $this->getEmailTemplate($admin, $reset_link);
                    $mail->AltBody = strip_tags($this->getEmailTemplate($admin, $reset_link));

                    $mail->send();
                    error_log("Email PHPMailer enviado com sucesso para {$admin['email']}");
                    return true;
                } catch (\PHPMailer\PHPMailer\Exception $e) {
                    error_log("Erro PHPMailer: {$mail->ErrorInfo}");
                    // Fallback para mail() se PHPMailer falhar
                }
            }

            // Fallback: usar mail() do PHP
            error_log("Usando mail() como fallback para {$admin['email']}");
            $assunto = 'Recuperação de Senha - Biblioteca SENAC';
            $mensagem = $this->getEmailTemplate($admin, $reset_link);

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Biblioteca SENAC <noreply@biblioteca-senac.com>\r\n";
            $headers .= "Reply-To: noreply@biblioteca-senac.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

            $resultado = mail($admin['email'], $assunto, $mensagem, $headers);

            if (!$resultado) {
                error_log("Falha no envio de email para {$admin['email']}: função mail() retornou false");
                return false;
            }

            error_log("Email mail() enviado com sucesso para {$admin['email']}");
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar email de recuperação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna o template HTML do email.
     * @param array $admin Dados do admin
     * @param string $reset_link Link de reset
     * @return string Template HTML
     */
    private function getEmailTemplate($admin, $reset_link) {
        return "
        <html>
        <head>
            <title>Recuperação de Senha</title>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f8f9fa; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .button { background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; margin: 20px 0; font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); }
                .button:hover { background: #218838; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
                .warning { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔐 Biblioteca SENAC</h1>
                    <h2>Recuperação de Senha</h2>
                </div>
                <div class='content'>
                    <h3>Olá {$admin['nome']},</h3>
                    <p>Recebemos uma solicitação para redefinir sua senha de administrador do <strong>Sistema Biblioteca SENAC Hub Academy</strong>.</p>

                    <div class='warning'>
                        <strong>⚠️ Segurança:</strong> Este link é válido por <strong>1 hora</strong>. Após este período, você precisará solicitar uma nova recuperação de senha.
                    </div>

                    <p>Para criar uma nova senha segura, clique no botão abaixo:</p>

                    <div style='text-align: center;'>
                        <a href='{$reset_link}' class='button'>🔑 Redefinir Minha Senha</a>
                    </div>

                    <p><strong>Link direto (caso o botão não funcione):</strong><br>
                    <a href='{$reset_link}'>{$reset_link}</a></p>

                    <div class='warning'>
                        <strong>📧 Importante:</strong> Se você não solicitou esta recuperação, ignore este email. Sua senha permanecerá segura.
                    </div>

                    <p>Para sua segurança, recomendamos:</p>
                    <ul>
                        <li>Usar uma senha com pelo menos 8 caracteres</li>
                        <li>Incluir letras maiúsculas, minúsculas, números e símbolos</li>
                        <li>Não compartilhar sua senha com terceiros</li>
                    </ul>

                    <p>Em caso de dúvidas, entre em contato com o suporte técnico.</p>
                </div>
                <div class='footer'>
                    <p><strong>Equipe Biblioteca SENAC Hub Academy</strong></p>
                    <p>Este é um email automático, por favor não responda.</p>
                    <p>&copy; 2025 Biblioteca SENAC. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Processa reset de senha usando token.
     * @param string $token Token de reset
     * @param string $nova_senha Nova senha
     * @param string $confirmar_senha Confirmação da senha
     * @return array Resultado com sucesso/mensagem
     */
    public function resetarSenha($token, $nova_senha, $confirmar_senha) {
        if (empty($token)) {
            return ['success' => false, 'message' => 'Token inválido.'];
        }

        if (empty($nova_senha) || empty($confirmar_senha)) {
            return ['success' => false, 'message' => 'Todas as senhas são obrigatórias.'];
        }

        if ($nova_senha !== $confirmar_senha) {
            return ['success' => false, 'message' => 'As senhas não coincidem.'];
        }

        if (strlen($nova_senha) < 6) {
            return ['success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres.'];
        }

        // Verifica token
        $admin = $this->admin_model->verificarTokenReset($token);
        if (!$admin) {
            return ['success' => false, 'message' => 'Token inválido ou expirado.'];
        }

        // Hash da nova senha
        $senha_hashed = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza senha e remove token
        if ($this->admin_model->atualizarSenhaERemoverToken($admin['id_administrador'], $senha_hashed)) {
            return ['success' => true, 'message' => 'Senha redefinida com sucesso!'];
        } else {
            return ['success' => false, 'message' => 'Erro ao redefinir senha.'];
        }
    }
}
?>