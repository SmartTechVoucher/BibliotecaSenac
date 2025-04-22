<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo á Biblioteca SENAC HUB ACADEMY!</title>
    <link rel="stylesheet" href="../projeto/public/css/tela_inicial.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="conteiner">
        <div class="cabecalho">
            <div class="cbleft">
                <img class="icsenac" src="../projeto/public/assets/icons/SenacIcon 1.png" alt="Icone Senac">
            </div>
            <div class="cbright">
                <!-- <img src="../projeto/public/assets/icons/Icon perfil.png" alt="Foto perfil" class="icperfil"> -->
                <button>
                <svg class="icone-perfil" xmlns="http://www.w3.org/2000/svg" ><defs><style></style></defs><g ><path fill="white" d="M10.15,18.29c1.26,1.42,2.95,2.3,4.82,2.3s3.7-.95,4.97-2.47c3.28,.84,6.01,2.56,7.7,4.79,1.45-2.3,2.29-5.02,2.29-7.94C29.93,6.7,23.23,0,14.97,0S0,6.7,0,14.97c0,3.17,.99,6.1,2.67,8.52,1.53-2.35,4.2-4.22,7.48-5.19ZM14.97,5.41c3.16,0,5.72,3.05,5.72,6.82s-2.56,6.82-5.72,6.82-5.72-3.05-5.72-6.82,2.56-6.82,5.72-6.82Z"></path></g></svg>
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
                <img src="../projeto/public/assets/icons/bolha 1.png" alt="bolha" class="bolha1">
            </div>
            <div class="barrapesquisa">
                <input type="text" class="pesquisa"> <button class="botaops">Buscar</button>
            </div>
        </div>
        <h1 class="gentitle">Gêneros de Livros</h1>
        <div class="gen">
            <div class="gencard">
                <img src="../projeto/public/assets/icons/tecnologiaicon 1.png" alt="" class="genicon">
                <h2 class="gentitle">Tecnologia</h2>
            </div>
            <div class="gencard">
                <img src="../projeto/public/assets/icons/saudeicon 1.png" alt="" class="genicon">
                <h2 class="gentitle">Saúde</h2>
            </div>
            <div class="gencard">
                <img src="../projeto/public/assets/icons/gestao 1.png" alt="" class="genicon">
                <h2 class="gentitle">Gestão</h2>
            </div>
            <div class="gencard">
                <img src="../projeto/public/assets/icons/gestao 1.png" alt="" class="genicon">
                <h2 class="gentitle">Design</h2>
            </div>
            <div class="gencard">
                <img src="../projeto/public/assets/icons/educacaoicon 1.png" alt="" class="genicon">
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
                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 1">
                        </div>
       
                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 2">
                        </div>
       
                        <div class="livroEstante">
                            <img src="../projeto/public/assets/img/card prat.png" alt="Livro 3">
                        </div>
                       
                    </div>

                    <div class="quatroUltimos">

                        <div class="livroEstante">
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
                        </div>

                    </div>
                 
   
                </div>

 
            </div>
        </div>

        <?php include '../BibliotecaSenac/projeto/public/components/footer/footer.php' ?>
 
        <!-- <div class="rodape">
            <footer>
                <div class="logo-senac">
                    <img src="./imagens/logo-senac 1.png" alt="Logo Senac" id="logo-senac">
                </div>
               
                <div class="livro">
                    <img src="./imagens/7d5902e0e5af7bd9178f51ad7301137e_high.webp_image 1 (1) 1.png" alt="Livro icon">
                    <div class="Copyright">
                        <p>Senac MS Copyright © <br>
                            2024. Todos os Direitos Reservados
                        </p>
                    </div>
 
                </div>
   
                <div class="textos">
                    <div class="contato">
                        <p>
                            Fale conosco
                            <br>
                            Central de
                            Atendimento
                            <br>
                            (67) 3312-6260
                        </p>
                    </div>
                    <div class="email">
                        <p>
                            Email:
                            <br>
                            atendimento@ms.senac.br
                            <br>
                            Sugestões, dúvidas
                            elogios ou críticas
                           
                        </p>
                    </div>
                    <div class="redes">
                        <p>
                            Siga-nos
                        </p>
                        <div class="social-icons">
                            <a href="#"><img src="./imagens/Facebook 1.png" alt="Facebook"></a>
                            <a href="#"><img src="./imagens/Instagram 1.png" alt="Instagram"></a>
                            <a href="#"><img src="./imagens/LinkedIn 1.png" alt="LinkedIn"></a>
                            <a href="#"><img src="./imagens/Twitter 1.png" alt="Twitter"></a>
                            <a href="#"><img src="./imagens/WhatsApp 1.png" alt="WhatsApp"></a>
                            <a href="#"><img src="./imagens/YouTube 1.png" alt="YouTube"></a>
                          </div>
                    </div>
                </div>
            </footer>
        </div> -->
 
    </div>
 
   
</body>
</html>