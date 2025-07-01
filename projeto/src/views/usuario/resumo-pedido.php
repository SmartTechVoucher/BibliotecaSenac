<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido</title>
    <link rel="stylesheet" href="../../../public/css/usuario/livro_info.css">
    
</head>
<body>
    <!-- header  -->
    <?php   
        include "../../../public/components/usuario/header/header.php";
    ?>

    <main>
        <a href="caminho_home">Home / Reserva de Pedido</a>
        <h1 class="titulo">Resumo de pedido de reserva</h1>
        <div class="conteudo">
            <h2 class="aa">Informações do livro</h2>
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

            <div class="informacoes">
                <h2>Data de reserva</h2>

                <div class="form-group">
                    <label for="">Data de reserva: </label> 
                    <input type="date" name="" id="reserva">
                </div>

                <div class="form-group">
                    <label for="">Data de retirada: </label> 
                    <input type="date" name="" id="retirada">
                </div>

                <div class="form-group">
                    <label for="">Data de devolução: </label> 
                    <input type="date" name="" id="devolucao">
                </div>


                <h2>Informações do Usuário</h2>
                <div class="form-group-dois">
                    <label for="usuario" type="text">Nome: </label> 
                    <input type="text" name="" id="nome_usuario">
                </div>

                <div class="form-group-dois">
                    <label for="usuario" type="tel">Telefone: </label> 
                    <input type="tel" name="" id="telefone">
                </div>

                <div class="form-group-dois">
                    <label for="usuario" type="email">Email: </label> 
                    <input type="email" name="" id="email">
                </div>
                
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