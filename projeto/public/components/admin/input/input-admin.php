<body>
<?php
function InputAdmin(
    $largura,
    $altura ="auto",
    $padding = "12px",
    $placeholder = "",
    $name="",
    $id="input-admin",
    $readonly= false,
    $tipo ="text",
    $required= false,
    $valor = "" ,
    $accept = "",
    $icone = "" // ícone opcional
) {
    static $css_adicionado = false;

    if (!$css_adicionado) {
        $css = '
<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.input-admin-wrapper {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
}

.input-admin-icon {
    position: absolute;
    left: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.input-admin-icon img {
    width: 18px;
    height: 18px;
    object-fit: contain;
}

.input-admin {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #ffffff;
    color: #495057;
    font-size: 1em;
    box-sizing: border-box;
    transition: all 0.1s ease-in-out;
    width: 100%;
}

.input-admin:read-only {
    background-color: #e6e6e6;
}

.input-admin:focus {
    border-color: dodgerblue; 
    box-shadow: 0 0 5px dodgerblue; 
}

.input-com-icone {
    padding-left: 38px; /* espaço pro ícone */
}
</style>';
        echo $css;
        $css_adicionado = true;
    }

    echo '<div class="input-admin-wrapper" style="width:' . htmlspecialchars($largura) . '%; height:' . htmlspecialchars($altura) . ';">';

    // Se tiver ícone, exibe
    if (!empty($icone)) {
        echo '<span class="input-admin-icon">' . $icone . '</span>';
    }

    // Define classe extra se tiver ícone
    $classeInput = 'input-admin';
    if (!empty($icone)) {
        $classeInput .= ' input-com-icone';
    }

    // Ajusta padding inline somente se não houver ícone
    $estiloInline = 'height:' . htmlspecialchars($altura) . ';';
    if (empty($icone)) {
        $estiloInline .= ' padding:' . htmlspecialchars($padding) . ';';
    }

    $html = '<input class="' . $classeInput . '" ';
    $html .= 'type="' . htmlspecialchars($tipo) . '" ';
    $html .= 'id="' . htmlspecialchars($id) . '" ';
    $html .= 'name="' . htmlspecialchars($name) . '" ';
    $html .= 'placeholder="' . htmlspecialchars($placeholder) . '" ';
    $html .= 'value="' . htmlspecialchars($valor) . '" ';
    $html .= 'accept="' . htmlspecialchars($accept) . '" ';
    $html .= 'style="' . $estiloInline . '" ';

    if ($readonly) {
        $html .= 'readonly ';
    }
    if($required){
        $html .= 'required ';
    }

    $html .= '>';

    echo $html;
    echo '</div>';
}
?>
</body>
</html>
