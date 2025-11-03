<?php
function botao($texto, $tipo = "button", $classe = "btn-primary", $onclick = "") {
    // $classe pode ser "btn-primary", "cancel-button" ou qualquer outra que você definir no CSS
    $buttonHTML = "<button type=\"$tipo\" class=\"$classe\" onclick=\"$onclick\">$texto</button>";
    echo $buttonHTML;
}
?>
