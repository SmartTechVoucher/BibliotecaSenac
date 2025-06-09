<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de relatórios</title>
    <link rel="stylesheet" href="../../../public/css/admin/telaRelatorios.css">

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
            <img src="../../../public/assets/icons/Menu adm.png" alt="" class="hamburguer">
            <img src="../../../public/assets/img/LogoHub_academy.png" alt="Imagem do logo" class="logoSenacHub">
        </div>
            
        <div class="titulo"> 
            <h2>Biblioteca Senac</h2> 
            <h2 class="senac">Senac Mato Grosso do Sul</h2> 
        </div>

        <div class="icone"><img src="../../../public/assets/icons/Icon perfil.png" alt="Ícone de pessoa" id="iconeComandante"> </div>    

        <div class="minhaConta">
            <p>Minha conta</p>
            <p><a href="../usuario/login.php">Sair</a></p>
        </div>     
    </div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->   

     <div class="conteiner3">

        <div class="superior">
            <img src="../../../public/assets/icons/Superior esquerdo.png" alt="Superior esquerdo" class="esquerdo">
        </div>

        <div class="superior">
            <img src="../../../public/assets/icons/Superior direito.png" alt="Superior direito" class="direito">
        </div>

    </div>

    <div class="relatorio"><h1>Relatórios</h1></div>

    <div class="quadradoBranco">
        
        <form class="quadradoCinza">       

        <div class="input">        
            <div>
                <h3 class="sobre">Sobre:</h3>
                <select class="nome"> 
                    <option value="">Acervo</option>   
                    <option value="">Aquisição</option>   
                    <option value="">Empréstimos</option>   
                    <option value="">Usuários</option> 
                </select>  
            </div>    
                 
            <div>
                <h3 class="salvar">Exportar/salvar como:</h3>
                <select class="opcoes"> 
                    <option value="">PDF</option>   
                    <option value="">Png/Jpeg</option>   
                    <option value="">Texto</option>   
                    <option value="">Excel</option>   
                </select>  
            </div> 
        
        </div>
         
        <div class="unidade">
            <h4>Unidade:</h4>
            <select class="nome"> 
                <option value="">Todos</option>   
                <option value="">Dourados</option>   
                <option value="">Três Lagoas</option>   
                <option value="">Ponta Porã</option> 
                <option value="">Corumbá</option> 
                <option value="">Campo Grande-gastronomia</option> 
            </select>  

            <h4 class="ordenagem">Ordenagem:</h4>
            <select class="ordem"> 
                <option value="">Data crescente</option>   
                <option value="">Data decrescente</option>   
                <option value="">Crescente</option>   
                <option value="">Decrescente</option> 
            </select>  
             
        </div>    
       
        <div class="input" id="calendario">
           
            <div class="Calendario">
                <img src="../../../public/assets/icons/Calendar.png " alt="Calendário" class="imagemCalendario"> 
                
                <div>
                    <h4>Começando de:</h4>
                    <input type="date" class="calendar">

                    <h4>Até:</h4>
                    <input type="date" class="calendar2">
                </div>
           
            </div>
                        
        </div>

       <div class="botoes">
            <button type="reset" class="cancelar">Cancelar</button>
            <button type="submit" class="emitir">Emitir</button>
       </div>
        
     </form>
   
    </div>
    
    <div class="inferior">

        <div class="direito">
            <img src="../../../public/assets/icons/Inferior direito.png" alt="">
        </div>     

    </div>
            
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    <div class="rodape">

        <div class="logoFecomercio"><img src="../../../public/assets/icons/Fecomercio.png" alt="" class="Logo" ></div>
        
        <div><img src="../../../public/assets/icons/Livro.png" alt="" class="Libro">
            <div class="copyright">Senac MS Copyright © <br></div>
            <div class="todos"> 2024. Todos os Direitos Reservados</div>   
        </div>
        
    </div>

     <div id="menu" class="menu">

        <div><a href="./telaDeCadastroDeLivros.php">Cadastrar livros</a></div>
        <div><a href="./telaDeCadastroDeUsuários.php">Cadastrar usuários</a></div>
        <div><a href="./Histórico de empréstimos.php">Empréstimos</a></div>
        <div><a href="Por fazer">Usuários</a></div>
        <div><a href="./Tela dos livros cadastrados.php">Estoque de livros</a></div>
        <div><a href="./Tela inicial do adm.php">Tela inicial</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>

    <script src="../../../public/js/admin/telaDeRelatorios.js"></script>

</body>
</html>