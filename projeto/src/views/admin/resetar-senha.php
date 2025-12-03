<?php
require(__DIR__ . '/../../../config/constantes.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/admin/login-adm.css">
</head>

<body>
    <div class="container">
        <section class="login">
            <div class="container-title">
                <div class="container-title2">
                    <div class="container-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-library h-6 w-6 text-library-orange" data-lov-id="src/components/LoginForm.tsx:34:16" data-lov-name="Library" data-component-path="src/components/LoginForm.tsx" data-component-line="34" data-component-file="LoginForm.tsx" data-component-name="Library" data-component-content="%7B%22className%22%3A%22h-6%20w-6%20text-library-orange%22%7D">
                            <path d="m16 6 4 14"></path>
                            <path d="M12 6v14"></path>
                            <path d="M8 8v12"></path>
                            <path d="M4 4v16"></path>
                        </svg>
                    </div>

                    <div>
                        <h1 class="labels">
                            Biblioteca SENAC
                        </h1>
                        <p class="label-hub">
                            Hub Academy
                        </p>
                    </div>
                </div>
            </div>

            <div class="container-form">
                <form action="../../../router.php?acao=resetarSenha" method="POST" class="card-login">
                    <div class="titles-form">
                        <h2>Redefinir Senha</h2>
                        <p>Crie uma nova senha para sua conta</p>
                    </div>

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

                    <div class="input-1">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" name="nova_senha" id="nova_senha" placeholder="Digite sua nova senha" required>
                    </div>

                    <div class="input-2">
                        <label for="confirmar_senha">Confirmar Senha</label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirme sua nova senha" required>
                    </div>

                    <button type="submit">Redefinir Senha</button>
                    <div class="text-center">
                        <a href="login-adm.php">Voltar ao login</a>
                    </div>
                </form>
            </div>

            <?php if (isset($_SESSION['toast'])): ?>
            <div id="toast" class="toast <?= $_SESSION['toast']['tipo'] ?> hidden">
                <?= $_SESSION['toast']['mensagem'] ?>
            </div>
            <?php unset($_SESSION['toast']); ?>
            <?php endif; ?>

            <?php if (isset($_GET['token']) && !empty($_GET['token'])): ?>
            <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #bee5eb;">
                <strong>Instruções:</strong> Digite sua nova senha abaixo. Certifique-se de escolher uma senha forte com pelo menos 6 caracteres.
            </div>
            <?php endif; ?>
        </section>
        <section class="tela_animacao">
            <img src="<?php echo $URLBASE ?>/public/assets/img/gif_login.gif" alt="">
        </section>
    </div>
    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>
    <script>
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('hidden');
                setTimeout(() => toast.remove(), 5000);
            }, 100);
        }
    </script>
</body>

</html>