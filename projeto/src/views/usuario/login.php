<?php
// login.php
require_once __DIR__ . '/../../../config/constantes.php';
require_once __DIR__ . '/../../../config/auth-check.php';

// Capturar dados do toast ANTES de redirecionar
$toastData = null;
if (isset($_SESSION['toast'])) {
    $toastData = [
        'mensagem' => $_SESSION['toast']['mensagem'],
        'tipo' => $_SESSION['toast']['tipo']
    ];
    unset($_SESSION['toast']);
}

// Se já está logado, redireciona para a página principal
if (usuarioEstaLogado()) {
    header('Location: ' . $URLBASE . '/src/views/usuario/index.php');
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">

</head>
<body>
    <div class="container">
        <section class="login">
            <div class="gradiente"></div>
            <div class="quadrados">
                <span></span><span></span><span></span><span></span><span></span>
            </div>

            <form action="<?php echo $URLBASE ?>/router.php?acao=validarLogin" method="POST">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/logo-hub-academy.png" alt="logo-login" class="logo-hub" />
                <p id="subtitulo" class="titulo-login"></p>

                <div class="login-campos">
                    <div class="campo-usuario">
                        <label for="campo_login"><img src="<?php echo $URLBASE ?>/public/assets/icons/perfil.png" alt=""> Email</label>
                        <input type="email" name="email" id="campo_login" placeholder="Digite seu email" />
                    </div>
                    <div class="campo-senha">
                        <label for="campo_senha"><img src="<?php echo $URLBASE ?>/public/assets/icons/cadeado-senha.png" alt=""> Senha</label>
                        <input type="password" name="senha" id="campo_senha" placeholder="Digite sua senha" />
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

    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>

    <?php if ($toastData) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                mostrarToast("<?php echo addslashes($toastData['mensagem']); ?>", "<?php echo $toastData['tipo']; ?>");
            });
        </script>
    <?php endif; ?>

    <script>
        function mostrarSenha() {
      const campo = document.getElementById("campo_senha");
      const ocultar = document.getElementsByClassName("ocultar-senha")[0];
      campo.type = campo.type === "password" ? "text" : "password";
      if (campo.type === "password") {
        ocultar.src = "../../../public/assets/icons/ocultar-fechado.png"
      } else {
        ocultar.src = "../../../public/assets/icons/ocultar.png"
      }
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