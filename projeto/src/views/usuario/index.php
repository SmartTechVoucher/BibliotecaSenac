<?php
// index.php
require __DIR__ . '/../../../config/constantes.php';
require __DIR__ . '/../../../config/auth-check.php';
include_once __DIR__ . '/../../../src/model/usuario/livro-model.php';

// Usar a função de verificação de login do auth-check.php
$usuario = obterUsuarioLogado();

// Definir as variáveis que o header.php e o restante da página precisam
$usuarioLogado = ($usuario !== null);
$nomeUsuario = $usuario['nome'] ?? '';
$emailUsuario = $usuario['email'] ?? '';

$model = new LivroModel();
$livros = $model->getLivrosMock();

// Capturar dados do toast
$toastData = null;
if (isset($_SESSION['toast'])) {
    $toastData = [
        'mensagem' => $_SESSION['toast']['mensagem'],
        'tipo' => $_SESSION['toast']['tipo']
    ];
    unset($_SESSION['toast']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Biblioteca SENAC HUB ACADEMY!</title>

    <!-- Preconnect para melhorar carregamento das fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fontes com display=swap para evitar FOUT -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/usuario/tela-inicial.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/card2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
</head>

<body>
    <div id="overlay" class="overlay"></div>

    <?php include "../../../public/components/usuario/header/header.php" ?>
    
    <div class="conteiner">
        <div class="geralinfo">
            <div class="info">
                <img src="<?php echo $URLBASE ?>/public/assets/icons/fotoSenac 1.png" alt="Foto do Senac" class="senacFoto">
                <div class="letreiro">
                    <div class="letras">
                        <?php if ($usuarioLogado): 
                            $primeiroNome = explode(' ', trim($nomeUsuario))[0] ?? trim($nomeUsuario);
                            ?>
                            <h1 class="letras1">Bem-vindo de volta, <?php echo htmlspecialchars($primeiroNome); ?>!</h1>
                            <h1 class="letras2">SENAC HUB ACADEMY.</h1>
                        <?php else: ?>
                            <h1 class="letras1">Bem-vindo à Biblioteca</h1>
                            <h1 class="letras2">SENAC HUB ACADEMY.</h1>
                        <?php endif; ?>
                    </div>
                    <p class="frase">"O ensino do futuro do mundo: pessoas inovando pela <br>transformação do Brasil"</p>
                </div>
            </div>

            <form class="barrapesquisa">
                <input type="text" class="pesquisa" placeholder="Pesquise por um livro" id="campo-input" autocomplete="off"> 
                <button type="button" class="botaops" id="lupaId" onclick="focusInput()" tabindex="0">
                    <img src="<?php echo $URLBASE ?>/public/assets/icons/lupa.svg" alt="Buscar">
                </button>
                <div class="listagem">
                    <ul></ul>
                </div>
            </form>
        </div>

        <div class="generos-livros">
            <h1 class="gen-title">Gêneros de Livros</h1>
            <div class="gen">
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/tecnologia.svg" alt="" class="genicon1">
                    </div>
                    <h2 class="gentitle">Tecnologia</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/saude.svg" alt="" class="genicon2">
                    </div>
                    <h2 class="gentitle">Saúde</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/gestao.svg" alt="" class="genicon3">
                    </div>
                    <h2 class="gentitle">Gestão</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/Designer-teste.png" alt="" class="genicon4">
                    </div>
                    <h2 class="gentitle">Design</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/Livros-teste.png" alt="" class="genicon5">
                    </div>
                    <h2 class="gentitle">Educação</h2>
                </div>
                <div class="gencard">
                    <div class="icon_livros">
                        <img src="<?php echo $URLBASE ?>/public/assets/icons/Geography.png" alt="" class="genicon5" id="comunicacao">
                    </div>
                    <h2 class="gentitle">Comunicação</h2>
                </div>
            </div>
        </div>

        <div class="container-estante">
            <div class="sup">
                <h1 class="title">Livros</h1>
            </div>
            <div class="estante">
                <div class="livros">
                    <div class="primeiraFileira">
                        <?php
                        for ($i = 3; $i < 7 && $i < count($livros); $i++):
                            $livro = $livros[$i];
                        ?>
                            <div class="livroEstante1">
                                <?php include "../../../public/components/usuario/card/card2.php"; ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="prateleira"></div>

                    <div class="segundaFileira">
                        <?php
                        for ($i = 3; $i < 8 && $i < count($livros); $i++):
                            $livro = $livros[$i];
                        ?>
                            <div class="livroEstante1">
                                <?php include "../../../public/components/usuario/card/card2.php"; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include "../../../public/components/usuario/footer/footer.php"; ?>
    </div>

    <script src="<?php echo $URLBASE ?>/public/js/components/toast.js"></script>
    <script src="<?php echo $URLBASE ?>/public/js/usuario/tela-inicial.js" defer></script>
    <script src="<?php echo $URLBASE ?>/public/js/components/header.js" defer></script>
    
    <?php if ($toastData): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            mostrarToast("<?php echo addslashes($toastData['mensagem']); ?>", "<?php echo $toastData['tipo']; ?>");
        });
    </script>
    <?php endif; ?>
    
</body>
</html>