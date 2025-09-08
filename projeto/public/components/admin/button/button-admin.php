<?php
function botao($texto, $tipo = "", $cor = "#004A90", $tamanho = "16px", $onclick = "") {
    $buttonHTML = "<button type=\"$tipo\" style='
        background-color: {$cor};
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: {$tamanho};
        border-radius: 5px;
        cursor: pointer;'
        onclick=\"$onclick\"
    >{$texto}</button>";
    echo $buttonHTML;
}
 
?>
 
 