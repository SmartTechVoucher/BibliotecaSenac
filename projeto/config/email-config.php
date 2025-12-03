<?php
/**
 * Configurações de email para o sistema Biblioteca SENAC
 * Configure suas credenciais SMTP aqui
 */

// Configurações SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'seu-email@gmail.com'); // ALTERE AQUI
define('SMTP_PASSWORD', 'sua-senha-app'); // ALTERE AQUI - use senha de app do Gmail
define('SMTP_PORT', 587);
define('SMTP_ENCRYPTION', 'tls'); // tls ou ssl

// Configurações do remetente
define('EMAIL_FROM', 'noreply@biblioteca-senac.com');
define('EMAIL_FROM_NAME', 'Biblioteca SENAC Hub Academy');

// Configurações adicionais
define('SMTP_DEBUG', false); // true para debug, false para produção
define('EMAIL_CHARSET', 'UTF-8');

// Configurações para diferentes provedores SMTP
/*
Para Gmail:
- SMTP_HOST: smtp.gmail.com
- SMTP_PORT: 587
- SMTP_ENCRYPTION: tls
- SMTP_USERNAME: seu-email@gmail.com
- SMTP_PASSWORD: senha de app (gere em conta Google > Segurança > Senhas de app)

Para Outlook/Hotmail:
- SMTP_HOST: smtp-mail.outlook.com
- SMTP_PORT: 587
- SMTP_ENCRYPTION: tls

Para Yahoo:
- SMTP_HOST: smtp.mail.yahoo.com
- SMTP_PORT: 587
- SMTP_ENCRYPTION: tls

Para servidor próprio (exemplo):
- SMTP_HOST: mail.seudominio.com
- SMTP_PORT: 587 ou 465
- SMTP_ENCRYPTION: tls ou ssl
*/
?>