<?php
require(__DIR__ . '/../../../config/constantes.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                
                <form action="../../../router.php?acao=loginAdmin" method="POST" class="card-login" id="card-login">
                    <div class="titles-form">
                        <h2>Acesso Administrativo</h2>
                        <p>Entre com suas credenciais</p>
                    </div>

                    <div class="input-1">
                        <label for="campo_login">Usuário</label>
                        <input type="text" name="nome" id="campo_login" placeholder="Digite seu usuário">
                    </div>

                    <div class="input-2">
                        <label for="campo_senha">Senha</label>
                        <input type="password" name="senha" id="campo_senha" placeholder="Digite sua senha">
                    </div>

                    <button type="submit">Entrar no Sistema</button>
                    <div class="text-center">
                        <a href="#" id="link-recuperar">Esqueceu sua Senha ?</a>
                    </div>
                </form>

                <form action="../../../router.php?acao=recuperarSenha" method="POST" class="card-recuperar-senha hidden" id="card-recuperar" novalidate>
                    <div class="titles-form">
                        <h2>Recuperar Senha</h2>
                        <p>Digite seu email para receber as instruções</p>
                    </div>

                    <div class="input-1">
                        <label for="campo_email">Email</label>
                        <input type="email" name="email" id="campo_email" placeholder="Digite seu email">
                    </div>

                    <button type="submit" id="btn-enviar">Enviar instruções</button>
                    <div class="text-center">
                        <a href="#" id="link-voltar">
                            <img src="<?php echo $URLBASE ?>/public/assets/icons/voltar-admin.png" alt="" class="icon-voltar">
                            Voltar ao login
                        </a>
                    </div>
                </form>
            </div>


            <?php if (isset($_SESSION['toast'])): ?>
            <div id="toast" class="toast <?= $_SESSION['toast']['tipo'] ?>">
                <?= $_SESSION['toast']['mensagem'] ?>
            </div>
            <?php unset($_SESSION['toast']); ?>
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
                toast.style.display = 'block';
                setTimeout(() => toast.remove(), 5000);
            }, 100);
        }
    </script>

    <script src="../../../public/js/admin/login-adm.js"></script>

</body>

</html>