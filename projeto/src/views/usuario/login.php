<?php
require(__DIR__ . '/../../../config/constantes.php');
require(__DIR__ . '/../../controller/usuario/login-controller.php');

session_start();

// Se já estiver logado, redireciona baseado no tipo de usuário
if (isset($_SESSION['usuario'])) {
    $loginController = new LoginController();
    $loginController->redirecionarUsuario();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/login.css" />
</head>

<body>
  <div class="container">
    <section class="login">
      <!-- gradiente animado -->
      <div class="gradiente"></div>

      <!-- bolhas -->
      <div class="quadrados">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
      </div>

      <!-- form -->
      <form action="<?php echo $URLBASE ?>/router/router.php?acao=validarLogin" method="POST">
        <img src="<?php echo $URLBASE ?>/public/assets/img/LogoHub_academy.png" alt="logo-login" class="logo-hub" />
        <p id="subtitulo" class="titulo-login"></p>

        <div class="login-campos">
          <div class="campo-usuario">
            <label for="campo_login"><img src="<?php echo $URLBASE ?>/public/assets/icons/perfil.png" alt=""> Email</label>
            <input type="email" name="email" id="campo_login" placeholder="Digite seu email" required />
          </div>
          <div class="campo-senha">
            <label for="campo_senha"><img src="<?php echo $URLBASE ?>/public/assets/icons/cadeado-senha.png" alt="" class="cadeado-senha"> Senha</label>
            <input type="password" name="senha" id="campo_senha" placeholder="Digite sua senha" required />
            <span class="toggle-senha" onclick="mostrarSenha()"><img src="<?php echo $URLBASE ?>/public/assets/icons/ocultar-2.png" alt="" class="ocultar-senha"></span>
          </div>
        </div>

        <div class="check-entrar">
          <div class="checkbox-container">
            <input type="checkbox" id="lembrar" name="lembrar" />
            <label for="lembrar">Lembrar senha</label>
          </div>
          <a href="<?php echo $URLBASE ?>/src/views/usuario/recuperar-senha.php">Recuperar Senha</a>
        </div>

        <button type="submit">ENTRAR</button>
      </form>
    </section>

    <!-- <section class="tela_animacao">
      <img src="<?php echo $URLBASE ?>/public/assets/img/gif_login.gif" alt="animação login" />
    </section> -->
  </div>

  <?php
  if (isset($_SESSION['toast'])) {
    include_once __DIR__ . '/../../../../public/components/toast/toast.php';
    unset($_SESSION['toast']);
  }
  ?>

  <script>
    function mostrarSenha() {
      const campo = document.getElementById("campo_senha");
      campo.type = campo.type === "password" ? "text" : "password";
    }

    // texto digitando
    const texto = ["Hub Academy", "Conectando você ao futuro"];
    const el = document.getElementById("subtitulo");
    let linha = 0,
      i = 0;

    function digitar() {
      if (linha < texto.length) {
        if (i < texto[linha].length) {
          el.innerHTML += texto[linha][i];
          i++;
          setTimeout(digitar, 80);
        } else {
          linha++;
          i = 0;
          if (linha < texto.length) {
            el.innerHTML += "<br>";
            setTimeout(digitar, 500);
          } else {
            // Depois de terminar de digitar, inicia animação das reticências
            animarReticencias();
          }
        }
      }
    }

    let reticenciasCount = 0;
    const maxReticencias = 3;

    function animarReticencias() {
      reticenciasCount = (reticenciasCount + 1) % (maxReticencias + 1);
      el.innerHTML = texto[0] + "<br>" + texto[1] + ".".repeat(reticenciasCount);
      setTimeout(animarReticencias, 500);
    }

    digitar();
  </script>
</body>

</html>