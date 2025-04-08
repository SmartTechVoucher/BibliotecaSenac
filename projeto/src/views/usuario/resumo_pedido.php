<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido</title>
    <link rel="stylesheet" href="../../../public/css/livro_info.css">
    
</head>
<body>
    <!-- header  -->
    <?php   
        include "../../../public/components/header/header.php";
    ?>

    <main>
        <a href="caminho_home">Home / Reserva de Pedido</a>
        <h1>Resumo de pedido de reserva</h1>
        <div class="conteudo">
            <h2>Informações do livro</h2>
            <div class="livro"> 
                <img class="simposio_livro" src="../../../public/assets/img/Simposio.png" alt="" >
                <div class="conteudo_livro">
                    <h2>Simpósio do Barreado</h2>
                    <h2>Autor: Dante Mendonça</h2>
                    <p>Descrição: Nesse livro, ele aborda a pergunta-chave: 
                        “Afinal, o Barreado nasceu em Paranaguá, Antonina ou 
                        Morretes?”. O simpósio fictício realizado em Porto de 
                        Cima reuniu especialistas para debater essa questão e, 
                        naturalmente, terminou em confraternização em volta da 
                        mesa.O Barreado é um prato tradicional do Paraná, e o 
                        livro explora suas origens e receita, ilustrado com 
                        aquarelas do próprio autor .</p>
                </div>
            </div>

            <div class="data">
                <h2>Data de reserva</h2>
                <label for="">Data de reserva: </label> <input type="date" name="" id="reserva">
                <label for="">Data de retirada: </label> <input type="date" name="" id="retirada">
                <label for="">Data de devolução: </label> <input type="date" name="" id="devolucao">

                <h2>Informações do Usuário</h2>
                <label for="">Nome: </label> <input type="text" name="" id="nome_usuario">
                <label for="">Telefone: </label> <input type="text" name="" id="telefone">
                <label for="">Email: </label> <input type="text" name="" id="email">

            </div>

            <div class="botao">
                <button>Confirmar</button>
            </div>
        </div>
    </main>
    
    <!-- conteudo da pagina -->
   
    <?php   
        include "../../../public/components/footer/footer.php";
    ?>



</body>
</html>