<?php
require(__DIR__ . '/../../../config/constantes.php');


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/recuperar-senha.css">
</head>

<body>
    <div class="container">
        
        <section class="login">
            <div class="gradiente"></div>

            <!-- bolhas -->
            <div class="quadrados">
                <span></span><span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span><span></span>
            </div>
            <form action="">
                <label for="campo_email"><img src="../../../public/assets/icons/email.png" alt="">E-mail</label>
                <input type="text" name="email" id="campo_email" placeholder="Digite seu E-mail">

                <button type="submit">Enviar</button>

                <a href="../usuario/teste-login.php">Voltar</a>
            </form>

        </section>

    </div>
</body>

</html>