<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../../public/css/login.css"> 
</head>
<body>
    <div class="container">
        <section class="login">
            <form action="../../../router.php?acao=validarLogin" method="POST">
                <label for="campo_login">Login</label>
                <input type="text" name="nome" id="campo_login" placeholder="Digite seu login">
                <label for="campo_senha">Senha</label>
                <input type="password" name="senha" id="campo_senha" placeholder="Digite sua senha">
                <button type="submit">Entrar</button>
                <a href="../../views/usuario/recuperarSenha.php">Recuperar Senha</a>
            </form>

            <img src="../../../public/assets/img/Logo Senac Hub.png" alt="" class="logo">
            <img src="../../../public/assets/img/Logo Senac Hub.png" alt="" class="logo2">
            
        </section>
        <section class="tela_animacao">
            <img src="../../../public/assets/img/gif_login.gif" alt="">
        </section>
    </div>
    <script src="/projeto/public/js/login.js"></script>
</body>
</html>