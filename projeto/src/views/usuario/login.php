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
                <label for="campo_login">Usuário</label>
                <input type="text" name="nome" id="campo_login" placeholder="Digite seu usuário">
                <label for="campo_senha">Senha</label>
                <input type="password" name="senha" id="campo_senha" placeholder="Digite sua senha">
                <?php
    
    if (isset($_GET['error'])) {
        echo "<p style='color:red;padding: 5px;'>Usuário ou senha incorretos!</p>";
        unset($_SESSION['error']);
    }
    if(empty($login) || ($senha)){
        $erro = true;
    }

    if($erro) {
        echo "<p style='color:red;padding:5px;'> Usuário ou senha podem estar vazios!</p>";
    } else {
        $erro = false;
        echo "<p style='color:green:padding:5px;'> Todos os campos estão preenchidos!</p>";
    }
    
    ?>
     
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