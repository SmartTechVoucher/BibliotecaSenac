<?php
require(__DIR__ . '/../../../config/constantes.php');
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/teste-login.css" />
</head>
<body>
  <div class="container">
    <section class="login">
      <!-- gradiente animado -->
      <div class="gradiente"></div>

      <!-- bolhas -->
      <div class="bolhas">
        <span></span><span></span><span></span><span></span><span></span>
      </div>

      <!-- form -->
      <form action="../../../router.php?acao=validarLogin" method="POST">
        <img src="../../../public/assets/img/LogoHub_academy.png" alt="logo-login" />
        <p id="subtitulo"></p>

        <div class="login-campos">
          <div class="campo-usuario">
            <label for="campo_login">Usuário</label>
            <input type="text" name="nome" id="campo_login" placeholder="Usuário" required />
          </div>
          <div class="campo-senha">
            <label for="campo_senha">Senha</label>
            <input type="password" name="senha" id="campo_senha" placeholder="Digite sua Senha" required />
            <span class="toggle-senha" onclick="mostrarSenha()">👁️</span>
          </div>
        </div>

        <div class="check-entrar">
          <div class="checkbox-container">
            <input type="checkbox" id="lembrar" />
            <label for="lembrar">Lembrar senha</label>
          </div>
          <a href="../../views/usuario/recuperarSenha.php">Recuperar Senha</a>
        </div>

        <button type="submit">ENTRAR</button>
      </form>
    </section>

    <section class="tela_animacao">
      <img src="<?php echo $URLBASE ?>/public/assets/img/gif_login.gif" alt="animação login" />
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
    const texto = ["Hub Academy", "Conectando você ao futuro..."];
    const el = document.getElementById("subtitulo");
    let linha = 0, i = 0;
    function digitar(){
      if(linha < texto.length){
        if(i < texto[linha].length){
          el.innerHTML += texto[linha][i];
          i++;
          setTimeout(digitar, 80);
        } else {
          linha++;
          i = 0;
          if(linha < texto.length){
            el.innerHTML += "<br>";
            setTimeout(digitar, 500);
          }
        }
      }
    }
    digitar();
  </script>
</body>
</html>
