<?php
session_start();
if (isset($_SESSION['toast'])) {
    $mensagem = $_SESSION['toast'];
    unset($_SESSION['toast']);
    include_once __DIR__ . '../../../../public/components/toast/toast.php';

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../../public/css/bundles/style-login.css"> 
</head>
<body>
    <div class="container">
        <section class="login">
                <form action="../../../router.php?acao=validarLogin" method="POST">
                    <img src="../../../public/assets/img/LogoHub_academy.png" alt="logo-login">
                    <div class="login-campos">
                        <div class="campo-usuario">
                            <label for="campo_login">Usuário</label>
                            <input type="text" name="nome" id="campo_login" placeholder="Usuário">
                        </div> 
                        <div class="campo-senha">
                            <label for="campo_senha">Senha</label>
                            <input type="password" name="senha" id="campo_senha" placeholder="Digite sua Senha">
                            <!-- <span class="toggle-senha" onclick="mostrarSenha()">👁️</span> -->
                        </div>
                    </div>
                    <!-- <?php
    
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
    
    ?> -->
            <div class="check-entrar">
                <div class="checkbox-container">
                    <input type="checkbox"  id="lembrar">
                    <label for="lembrar">Lembrar senha</label>
                </div>
                <div>
                    <a href="../../views/usuario/recuperarSenha.php" class="link-recuperar-senha">Recuperar Senha</a>
                </div>
                
            </div>

            <button type="submit" id="btnEntrar">ENTRAR</button>
                
            </form>
            

            <!-- <img src="../../../public/assets/img/QuadradinhosHub_Login.png" alt="" class="logo">
            <img src="../../../public/assets/img/QuadradinhosHub_Login.png" alt="" class="logo2"> -->
            <!-- <div class="decor-top-right">
                <div class="square"></div>
                <div class="square"></div>
                <div class="square"></div>
            </div> -->

            <!-- <div class="decor-bottom-left">
            <div class="square"></div>
            <div class="square"></div>
            <div class="square"></div>
            </div> -->

        </section>
        <section class="tela_animacao">
            <img src="../../../public/assets/img/gif_login.gif" alt="">
        </section>
    </div>
    <script src="../../../public/js/usuario/login.js"></script>
</body>
</html>