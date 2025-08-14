<?php
function botao($texto, $cor = "#2563eb", $tamanho = "16px", $onclick = "") {
    return "<button style='
        background-color: {$cor};
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: {$tamanho};
        border-radius: 8px;
        cursor: pointer;'
        onclick=\"$onclick\"
    >{$texto}</button>";
}

echo botao("Clique-me", "#004A90", "20px", "console.log('Hello')");
?>
