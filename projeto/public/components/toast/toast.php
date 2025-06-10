<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Toast</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">


<body>


    <?php
    function showToast($message, $type = 'success')
    {
        $color = $type === 'success' ? '#1F7E4D' : 'red';

        $imageTag = $type === 'success' ? "<img src='../../../public/assets/icons/toast-confirm.png'
    alt='Ícone de sucesso'
    style='width: 40px; height: auto; padding-right: 5px;'>
" : "";

        echo "
    
    <div class='toast toast-$type' style='
        background-color: $color;
        color: white;
        padding: 5px 15px 5px 5px;
        margin: 1px;
        border-radius: 30px;
        font-size: 20px;
        font-family: Poppins, sans-serif;
        text-wrap: nowrap;
        max-width: 350px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: left;'>
        $imageTag
        $message
    </div>
    ";
    }

    showToast("Livro Reservado com Sucesso! ", "success");
