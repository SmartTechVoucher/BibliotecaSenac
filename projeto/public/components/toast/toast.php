<?php
include_once __DIR__ . '/../../../config/constantes.php'; // Caminho absoluto correto

function showToast($mensagemToast, $type = 'success')
{
    global $URLBASE; // Torna a variável visível dentro da função

    $color = $type === 'success' ? '#1F7E4D' : 'red';

    $imageTag = $type === 'success'
        ? "<img src='" . $URLBASE . "/public/assets/icons/toast-confirm.png' alt='Ícone de sucesso'
            style='width: 40px; height: auto; padding-right: 5px;'>"
        : "";

    echo "
    <div class='toast toast-$type' style='
        background-color: $color;
        color: white;
        padding: 5px 15px 5px 5px;
        margin: 10px;
        border-radius: 30px;
        font-size: 20px;
        font-family: Poppins, sans-serif;
        max-width: 350px;
        display: flex;
        align-items: center;
        justify-content: left;'>
        $imageTag
        $mensagemToast
    </div>
    ";
}
