<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo á Biblioteca SENAC HUB ACADEMY!</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/usuario/tela_inicial.css">
</head>

<body>
    <div class="conteiner">
        <div class="cabecalho">
            <div class="cbleft">
                <img class="icsenac" src="../projeto/public/assets/icons/SenacIcon 1.png" alt="Icone Senac">
            </div>
            <div class="cbright">
                <button onclick="redirectToPage()" class="button-entrar">
                    <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style></style>
                        </defs>
                        <g>
                            <path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path>
                        </g>
                    </svg>
                    <span>Entrar</span>
                </button>
            </div>
        </div>
        <div class="topPage">
            <div class="geralinfo">
                <img src="../projeto/public/assets/icons/fotoSenac 1.png" alt="Foto do Senac" class="senacFoto">
                <div class="letreiro">
                    <h1 class="letras">Bem-vindo a Biblioteca</h1>
                    <h1 class="letras2">SENAC HUB ACADEMY.</h1>
                    <p class="frase">"O ensino do futuro do mundo: pessoas inovando pela <br>transformação do Brasil"</p>
                </div>

                <div class="partecima">

                </div>
            </div>
            <div class="partebaixo">
                <img src="../projeto/public/assets/icons/bolha.png" alt="bolha" class="bolha">
            </div>
            <form class="barrapesquisa">
                <input type="text" class="pesquisa" placeholder="Pesquise por um livro"> <button type="button" class="botaops" id="lupaId" onclick="focusInput()"><img src="../projeto/public/assets/icons/lupa.svg" alt="Buscar"></button>
                <div class="listagem">
                    <ul>
                        <li class="listagem-item">1 item</li>
                        <li class="listagem-item">2 item</li>
                        <li class="listagem-item">3 item</li>
                        <li class="listagem-item">4 item</li>
                        <li class="listagem-item">5 item</li>
                        <li class="listagem-item">6 item</li>
                        <li class="listagem-item">7 item</li>
                        <li class="listagem-item">8 item</li>
                        <li class="listagem-item">9 item</li>
                        <li class="listagem-item">10 item</li>
                    </ul>
                </div>
            </form>
            <!-- <form class="barrapesquisa" onsubmit="event.preventDefault();">
                <div class="input-expandido" id="inputExpandido" tabindex="0">
                    <input type="text" placeholder="Pesquisar..." class="pesquisa" onfocus="expandir()" onblur="recolher()">
                    <div class="listagem">
                        <p>Item 1</p>
                        <p>Item 2</p>
                        <p>Item 3</p>
                    </div>
                </div>
                <button class="botaops" id="lupaId" onclick="focusInput()">
                    <img src="../projeto/public/assets/icons/lupa.svg" alt="">
                </button>
            </form> -->
        </div>
        <h1 class="gentitle">Gêneros de Livros</h1>
        <div class="gen">
            <div class="gencard">
                <div class="icon_livros">
                    <img src="./public/assets/icons/tecnologia.svg" alt="" class="genicon1">
                </div>
                <h2 class="gentitle">Saúde</h2>
            </div>
            <div class="gencard">
                <div class="icon_livros">
                    <img src="../projeto/public/assets/icons/saude.svg" alt="" class="genicon2">
                </div>
                <h2 class="gentitle">Saúde</h2>
            </div>
            <div class="gencard">
                <div class="icon_livros">
                    <img src="../projeto/public/assets/icons/gestao.svg" alt="" class="genicon3">
                </div>
                <h2 class="gentitle">Gestão</h2>
            </div>
            <div class="gencard">
                <div class="icon_livros">
                    <img src="../projeto/public/assets/icons/Designer-teste.png" alt="" class="genicon4">
                </div>
                <h2 class="gentitle">Design</h2>
            </div>
            <div class="gencard">
                <div class="icon_livros">
                    <img src="../projeto/public/assets/icons/Livros-teste.png" alt="" class="genicon5">
                </div>
                <h2 class="gentitle">Educação</h2>
            </div>
        </div>

        <div class="sup">
            <h1 class="title">Livros</h1>
        </div>

        <div class="estante">

            <img class="estanteimg" src="../projeto/public/assets/icons/estante 1.png" alt="">

            <div class="livros">

                <div class="primeiraFileira">

                    <div class="tresPrimeiros">
                        <!-- <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 1">
                        </div>

                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 2">
                        </div>

                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 3">
                        </div> -->

                    </div>

                    <div class="quatroUltimos">

                        <!-- <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 4">
                        </div>

                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 5">
                        </div>


                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 6">
                        </div>


                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 7">
                        </div> -->

                    </div>


                </div>


            </div>
        </div>

        <?php include '../projeto/public/components/footer/footer.php' ?>

    </div>


    <script src="./public/js/usuario/telaInicial.js"></script>
</body>

</html>