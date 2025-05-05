<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro de livros</title>
    <link rel="stylesheet" href="../../../public/css/Tela de cadastro de livros.css">

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
            <a href="../usuario/login.php">Sair</a>
        </div>

    </div>

 <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo--> <!--Corpo-->   

    <div class="conteiner3">      
        <img src="../../../public/assets/icons/Superior esquerdo.png" alt="" class="esquerdo">
        <img src="../../../public/assets/icons/Superior direito.png" alt="" class="direito">
    </div>

    <form class="formulario">

        <div class="tituloObrigatorio"> 
            <label for="Titulo2">Título</label>
            <p class="obrigatorio" id="obrigatorio1">obrigatório*</p>          
        </div>
        
        <input type="text" class="titulo2" required oninput="mostrarFalta()">
        
        <!--O input acima se refere a TítuloObrigatório-->

        <div class="autorObrigatorio">
            <label for="Autor">Autor </label>
            <p class="obrigatorio" id="obrigatorio2">obrigatório*</p>
        </div>
       
        <input type="text" class="autor" required oninput="mostrarFalta2()">
        
        <!--O input acima se refere a autorObrigatório-->

        <div class="codigoObrigatorio">
            <label for="Código do livro">Código do livro</label>
            <p class="obrigatorio" id="obrigatorio3">obrigatório*</p>
        </div>
        
        <input type="text" class="codigo" required oninput="mostrarFalta3()">
       
        <!--O input acima se refere a codigoObrigatório-->

        <label for="Idioma">Idioma</label>
        <input type="text" class="idioma">

        <label for="Área">Área</label>
        <select name="" id="" class="area">
            <option>Selecione</option>
            <option>Beleza</option>
            <option>Comércio</option>
            <option>Comunicação</option>
            <option>Design</option>
            <option>Gestão</option>
            <option>Moda</option> 
            <option>Saúde</option>
            <option>Segurança</option>              
            <option>Tecnologia da informação - TI</option>   
        </select>

        <label for="Unidade">Unidade</label>
        <select name="" id="" class="unidade">
            <option>Selecione</option>
            <option>Dourados</option>
            <option>Três Lagoas</option>
            <option>Ponta Porã</option>
            <option>Corumbá</option>
            <option>Campo Grande - Gastronomia</option>
        </select>       

        <label for="Editora">Editora</label>
        <input type="text" class="editora">
        
        <label for="Ano">Ano</label>
        <input type="text" class="ano"> 

        <label>Foto de capa</label>

        <div id="mensagemPrimeiroForm"></div>

        <label for="foto2" class="foto2">
            <img src="../../../public/assets/icons/Group 36.png" alt="" class="fundoBranco">
            <img src="../../../public/assets/icons/Group 34.png" alt="" class="upload">   
        </label>
        <input style="display: none;" type="file" id="foto2" onchange="texto2()">

        <label for="Resumo do livro">Resumo do livro</label>
        <textarea cols="30" rows="10" class="resumo"></textarea>

        <label for="Notas">Notas</label>
        <textarea cols="30" rows="10" class="notas"></textarea>

        <label for="Tipo de documento">Tipo de documento</label>
        <select name="" id="" class="tipoDocumento">
            <option>Selecione</option>
            <option>Artigo</option>
            <option>Folheto</option>
            <option>Livro</option>
            <option>Periódico</option>
        </select>

        <label for="Local publicado">Local publicado</label>
        <input type="text" class="localPublicado">

        <label for="Categoria/tags">Categoria/tags</label>
        <select name="" id="" class="categoria">
            <option>Selecione</option>
                <option>Beleza</option>
                <option>Comércio</option>
                <option>Comunicação</option>
                <option>Design</option>
                <option>Gestão</option>
                <option>Moda</option>  
                <option>Saúde</option>
                <option>Segurança</option>   
                <option>Tecnologia da informação - TI</option>
        </select>

        <button type="submit" class="botao">Enviar <img src="../../../public/assets/icons/Novo Projeto (5) 1.png" alt="Imagem de seta" class="seta"></button>       

    </form>

    <div class="paraTestar">

        <form class="formulario2" id="formulario2">
        
            <div class="tituloObrigatorio2">
                <label for="Titulo2">Título</label>
                <p class="obrigatorio" id="obrigatorio1">obrigatório*</p>
            </div>
        
            <input type="text" class="titulo2" id="titulo2" required oninput="mostrarFaltaSegundoForm()">
            
            <div class="autorObrigatorio2">
                <label for="Autor">Autor </label>
                <p class="obrigatorio" id="obrigatorio2">obrigatório*</p>
            </div>
        
            <input type="text" class="autor" required oninput="mostrarFalta2SegundoForm()">
        
            <div class="codigoObrigatorio2">
                <label for="Código do livro">Código do livro</label>
                <p class="obrigatorio" id="obrigatorio3">obrigatório*</p>
            </div>
            
            <input type="text" class="codigo" required oninput="mostrarFalta3SegundoForm()">
        
            <label for="Idioma">Idioma</label>
            <input type="text" class="idioma">

            <label for="Área">Área</label>
            <select name="" id="" class="area">
                <option>Selecione</option>
                <option>Beleza</option>
                <option>Comércio</option>
                <option>Comunicação</option>
                <option>Design</option>
                <option>Gestão</option>
                <option>Moda</option> 
                <option>Saúde</option>
                <option>Segurança</option>              
                <option>Tecnologia da informação - TI</option>                 
            </select>

            <label for="Unidade">Unidade</label>
            <select name="" id="" class="unidade">
                <option>Selecione</option>
                <option>Dourados</option>
                <option>Três Lagoas</option>
                <option>Ponta Porã</option>
                <option>Corumbá</option>
                <option>Campo Grande - Gastronomia</option>

            </select>
            
            <label for="Editora">Editora</label>
            <input type="text" class="editora">
            
            <label for="Ano">Ano</label>
            <input type="text" class="ano"> 
            
            <label>Foto de capa</label>
           
            <label for="foto" class="foto2">
                <img src="../../../public/assets/icons/Component 62.png" alt="" class="fundoBranco">
            </label>
            <input style="display: none;" type="file" id="foto" onchange="texto()">
            
            <div id="mensagem"></div>

            <div class="Botao"><button class="botao" type="submit">Enviar <img src="../../../public/assets/icons/Novo Projeto (5) 1.png" alt="Imagem de seta" class="seta"></button></div>
        </form>

        <form class="formulario2_segundaMetade" id="formulario2_segundaMetade">

            <label for="Resumo do livro">Resumo do livro</label>
            <textarea cols="30" rows="10" class="resumo"></textarea>
    
            <label for="Notas">Notas</label>
            <textarea cols="30" rows="10" class="notas"></textarea>
    
            <label for="Tipo de documento">Tipo de documento</label>
            <select name="" id="" class="tipoDocumento">
                <option>Selecione</option>
                <option>Artigo</option>
                <option>Folheto</option>
                <option>Livro</option>
                <option>Periódico</option>         
            </select>
    
            <label for="Local publicado">Local publicado</label>
            <input type="text" class="localPublicado">
    
            <label for="Categoria/tags">Categoria/tags</label>
            <select name="" id="" class="categoria">
                <option>Selecione</option>
                <option>Beleza</option>
                <option>Comércio</option>
                <option>Comunicação</option>
                <option>Design</option>
                <option>Gestão</option>
                <option>Moda</option>  
                <option>Saúde</option>
                <option>Segurança</option>   
                <option>Tecnologia da informação - TI</option>            
            </select>          
    
        </form>

 </div>  
    
    <div class="inferiorDireito">

        <img src="../../../public/assets/icons/Inferior direito.png" alt="" class="inferior">

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

        <div><a href="./Tela de cadastro de usuários.php">Cadastrar usuários</a></div>
        <div><a href="./Tela de relatórios.php">Relatórios</a></div>
        <div><a href="./Histórico de empréstimos.php">Empréstimos</a></div>
        <div><a href="Por fazer">Usuários</a></div>
        <div><a href="Tela dos livros cadastrados.php">Estoque de livros</a></div>
        <div><a href="Por fazer">Renovações</a></div>
        <div><a href="./Tela inicial do adm.php">Tela inicial do adm</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>

    <script src="../../../public/js/Tela de cadastro de livros.js"></script>

</body>
</html>