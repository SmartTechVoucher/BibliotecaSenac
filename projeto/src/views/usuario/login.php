<?php
require(__DIR__ . '/../../../config/constantes.php');
session_start();
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
      <form action="../../../router.php" method="POST">
        <img src="../../../public/assets/img/LogoHub_academy.png" alt="logo-login" class="logo-hub" />
        <p id="subtitulo" class="titulo-login"></p>

        <div class="login-campos">
          <div class="campo-usuario">
            <!-- MUDANÇA: campo nome → email -->
            <label for="campo_login"><img src="../../../public/assets/icons/perfil.png" alt=""> Email</label>
            <input type="email" name="email" id="campo_login" placeholder="Seu email" required />
          </div>
          <div class="campo-senha">
            <label for="campo_senha"><img src="../../../public/assets/icons/cadeado-senha.png" alt="" class="cadeado-senha"> Senha</label>
            <input type="password" name="senha" id="campo_senha" placeholder="Senha" required />
            <span class="toggle-senha" onclick="mostrarSenha()"><img src="../../../public/assets/icons/ocultar-2.png" alt="" class="ocultar-senha"></span>
          </div>
        </div>

        <div class="check-entrar">
          <div class="checkbox-container">
            <input type="checkbox" id="lembrar" />
            <label for="lembrar">Lembrar senha</label>
          </div>
          <a href="../../views/usuario/recuperar-senha.php">Recuperar Senha</a>
        </div>

        <button type="submit">ENTRAR</button>
      </form>

      <!-- ADIÇÃO: Usuários demo para teste durante desenvolvimento -->
      <?php if (defined('DESENVOLVIMENTO') && DESENVOLVIMENTO === true): ?>
      <div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.1); border-radius: 10px; color: white; font-size: 12px;">
        <strong>👤 Usuários de Teste:</strong><br>
        <small>
          📧 jogoperdi3@gmail.com - Senha: password (Estudante)<br>
          📧 maria.santos@email.com - Senha: password (Professor)<br>
          📧 admin@sistema.com - Senha: password (Admin)
        </small>
      </div>
      <?php endif; ?>
    </section>
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