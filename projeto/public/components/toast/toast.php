<?php
include_once __DIR__ . '/../../../config/constantes.php';

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
    position: fixed;
    top: 6%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background-color: $color;
    color: white;
    padding: 5px 10px;
    border-radius: 30px;
    font-size: 20px;
    font-family: Poppins, sans-serif;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    '>
        $imageTag
        $mensagemToast
    </div>
    <script src='$URLBASE/public/js/components/toast.js'></script>
    ";
}
