<?php
require __DIR__ . '/../../../config/constantes.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Biblioteca'; ?></title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/global.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/admin/sidebar.css">

    <!-- CSS específico da página -->
    <?php if(!empty($cssPagina)): ?>
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
        <fieldset class="form-section">
            <legend><?= htmlspecialchars($fieldsetTitulo ?? 'Titulo não encontrado') ?></legend>
            <?php 
            if (!empty($conteudo) && file_exists($conteudo)) {
                include $conteudo;
            } else {
                echo "<p style='color:red; text-align:center;'>Erro: conteúdo não encontrado.</p>";
            }
            ?>
        </fieldset>
    </div>
</main>
    <!-- Modal de confirmação (se houver) -->
    <?php
    if(!empty($modalPagina)) {
        require_once __DIR__ . '/../../../public/components/usuario/modal/modal.php';
        echo renderModal($modalPagina['id'], $modalPagina['titulo'], $modalPagina['mensagem']);
    }
    ?>
</body>
</html>
