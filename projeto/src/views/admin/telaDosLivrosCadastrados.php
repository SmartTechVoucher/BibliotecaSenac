<?php
   require "../../../config/constantes.php";
?>     

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros cadastrados</title>
    
    <link rel="stylesheet" href="../../../public/css/admin/telaDosLivrosCadastrados.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>
    
 <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->    
 
   <div class="conteiner">      
            
        <div class="menuAdm">
            <img src="<?php echo $URLBASE?>/public/assets/icons/Menu adm.png" alt="" class="hamburguer">
            <img src="<?php echo $URLBASE?>/public/assets/img/LogoHub_academy.png" alt="Imagem do logo" class="logoSenacHub">
        </div>

        <div class="titulo"> 
            <h2>Biblioteca Senac</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <div class="icone"><img src="<?php echo $URLBASE?>/public/assets/icons/Icon perfil.png" alt="Ícone de pessoa" id="iconeComandante"> </div>    

        <div class="minhaConta">
            <p><a href="./minha-conta.php">Minha conta</a></p>
            <a href="../usuario/login.php">Sair</a>
        </div>

    </div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->  
 
 <div class="conteiner3">      
    <img src="<?php echo $URLBASE?>/public/assets/icons/Superior esquerdo.png" alt="" class="esquerdo">
    <img src="<?php echo $URLBASE?>/public/assets/icons/Superior direito.png" alt="" class="direito">
 </div>

    <h2 id="livro-titulomaster">Listagem de livros cadastrados</h1>
    <form>

        <div class="controle">
            <input type="text" name="" class="busca" placeholder="Pesquise por título ou ibsm do livro">

            <button type="submit" class="botao">
                <img src="../../../public/assets/icons/Buscar.png" alt="Imagem de lupa">
            </button>

            <div class="controle2">
                <label class="unidade">Unidade:</label>
                <select name="" id="">
                    <option value="">Selecione</option>
                    <option value="">Dourados</option>
                    <option value="">Três Lagoas</option>
                    <option value="">Ponta Porã</option>
                    <option value="">Corumbá</option>
                    <option value="">Campo Grande</option>
                </select>
            </div>    

        </div>

    </form>

    <p id="livros-por-aparecer">Livros por aparecer:</p>
    
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>
    <div class="livro-container">
        <img id="livro-img" src="<?php echo $URLBASE?>/public/assets/img/Simposio.png" alt="">
        <div class="livro-informacoes">
            <span id="livro-titulo">O pequeno Principe</span>
            <span>IBSM: <a href="">8583769238590205TY</a></span> 
            <div id="livro-exemplares">
                <span>Total de exemplares</span>
                <span>Disponíveis: <b>5</b></span>
                <span>Emprestados: <b>2</b></span>
                <span>Reserva: <b>0</b></span>
            </div>
        </div>
        <img id="livro-pontos" src="<?php echo $URLBASE?>/public/assets/icons/pontinhos.png" alt="">
        
    </div>

    <div id="menu" class="menu">

        <div><a href="./telaDeCadastroDeUsuarios.php">Cadastrar usuários</a></div>
        <div><a href="./telaDeCadastroDeLivros.php">Cadastrar livros</a></div>
        <div><a href="./usuarios-cadastrados.php">Usuários cadastrados</a></div>
        <div><a href="./telaDeRelatorios.php">Relatórios</a></div>
        <div><a href="./telaInicialDoAdm.php">Tela inicial</a></div>
        <div><a href="./historico-emprestimo.php">Estoque de livros</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>

    
    
            
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

   <?php   
    include "../../../public/components/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/telaDosLivrosCadastrados.js"></script>

</body>
</html>