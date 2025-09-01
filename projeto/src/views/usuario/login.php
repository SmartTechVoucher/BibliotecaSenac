<?php
// login.php
require_once __DIR__ . '/../../../config/constantes.php';

// Segurança de sessão
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (session_status() === PHP_SESSION_NONE) session_start();

// Se já está logado, redireciona para index
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ' . $URLBASE . '/index.php');
    exit;
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
      <div class="gradiente"></div>
      <div class="quadrados">
        <span></span><span></span><span></span><span></span><span></span>
      </div>

      <form action="<?php echo $URLBASE ?>/router/router.php?acao=validarLogin" method="POST">
        <img src="<?php echo $URLBASE ?>/public/assets/img/LogoHub_academy.png" alt="logo-login" class="logo-hub" />
        <p id="subtitulo" class="titulo-login"></p>

        <div class="login-campos">
          <div class="campo-usuario">
            <label for="campo_login"><img src="<?php echo $URLBASE ?>/public/assets/icons/perfil.png" alt=""> Email</label>
            <input type="email" name="email" id="campo_login" placeholder="Digite seu email" required />
          </div>
          <div class="campo-senha">
            <label for="campo_senha"><img src="<?php echo $URLBASE ?>/public/assets/icons/cadeado-senha.png" alt=""> Senha</label>
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
  </div>

  <?php if (!empty($_SESSION['toast'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        mostrarToast("<?php echo addslashes($_SESSION['toast']['mensagem']); ?>", "<?php echo $_SESSION['toast']['tipo']; ?>");
      });
    </script>
    <?php unset($_SESSION['toast']); ?>
  <?php endif; ?>

  <script>
    function mostrarSenha() {
      const campo = document.getElementById("campo_senha");
      campo.type = campo.type === "password" ? "text" : "password";
    }

    const texto = ["Hub Academy", "Conectando você ao futuro"];
    const el = document.getElementById("subtitulo");
    let linha = 0, i = 0;
    function digitar() {
      if (linha < texto.length) {
        if (i < texto[linha].length) {
          el.innerHTML += texto[linha][i++];
          setTimeout(digitar, 80);
        } else {
          linha++; i = 0;
          if (linha < texto.length) { el.innerHTML += "<br>"; setTimeout(digitar, 500); }
        }
      }
    }
    digitar();
  </script>
</body>
</html>
