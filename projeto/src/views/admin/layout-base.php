<?php

require_once __DIR__ . '/../../../config/auth-check.php';
require __DIR__ . '/../../../config/constantes.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Biblioteca'; ?></title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/sidebar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/global.css">

    <!-- Font Awesome (CDN) -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-dQOfrh0xHgRewl6EExmCQdwbbq1ElM3xkAq6UjXoEoX/Zm8E0o6/v6uIhW7wz5ztk6V+dAsUpVWkm/4wFbdEYg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    <!-- CSS específico da página -->
    <?php if (!empty($cssPagina)): ?>
        <link rel="stylesheet" href="<?php echo $URLBASE . $cssPagina; ?>">
    <?php endif; ?>
</head>

<body>

    <!-- Sidebar -->
    <?php
    include __DIR__ . '/../../../public/components/admin/sidebar/sidebar.php';
    ?>

    <!-- Conteúdo principal -->
    <main class="main-content">
        <div class="container-main">
            <?php
    if (!empty($conteudo) && file_exists($conteudo)) {
        // Se for a tela de cadastro de usuários, inclui direto
        if (($titulo ?? '') === 'Cadastro de Usuários') {
            include $conteudo;
        } else {
            // Layout padrão com fieldset
            ?>
            <fieldset class="form-section">
                <legend><?= htmlspecialchars($titulo ?? 'Título não encontrado') ?></legend>
                <?php include $conteudo; ?>
            </fieldset>
            <?php
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Erro: conteúdo não encontrado.</p>";
    }
    ?>
        </div>
    </main>
    <!-- Modal de confirmação (se houver) -->
    <?php
    if (!empty($modalPagina)) {
        require_once __DIR__ . '/../../../public/components/usuario/modal/modal.php';
        echo renderModal($modalPagina['id'], $modalPagina['titulo'], $modalPagina['mensagem']);
    }
    ?>
</body>

</html>
