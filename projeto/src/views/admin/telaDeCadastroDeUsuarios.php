<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro de usuários</title>
    <link rel="stylesheet" href="../../../public/css/admin/telaDeCadastroDeUsuarios.css"> 

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
            <p><a href="./minha-conta.php">Minha conta</a></p>
            <a href="../usuario/login.php">Sair</a>
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

    <form class="quadradoCinza"> 

        <div class="input">
            <img src="../../../public/assets/icons/Identidade.png" alt="">
            <input type="text" class="nome" placeholder="Nome completo">    
        </div>
       
        <div class="input" id="nomeSocial1">
            <input type="text" class="nomeSocial" placeholder="Nome social">
        </div>

        <div class="opcional"><p>Opcional</p></div>

        <div class="input" id="nascimento">
            <input type="text" class="nascimento" placeholder="Nascimento">
        </div>
       
        <div class="input" id="sexo">
            <label for="">Sexo:</label>
    
            <select name="" id="">
                <option value="">Masculino</option>
                <option value="">Feminino</option>
            </select>

            <input type="radio" name="tipoPessoa" id=""> Pessoa Física
            <input type="radio" name="tipoPessoa" id=""> Pessoa Jurídica
        </div>
        
        <div class="input">
            <img src="../../../public/assets/icons/cpf.png" alt="">
            <input type="number" class="cpf" placeholder="CPF*" required min="11" max="11">
        </div>

        <div id="foto-container">
            <label for="foto" id="labelFoto">Carregar foto:</label>
            <input type="text" id="placeholder" placeholder="Nenhuma foto selecionada" readonly onchange="trocar()">
            
            <input type="file" id="foto" accept="image/*" style="display: none;">
            
            <button id="btn-procurar" style="position: relative; right: 0%;">Procurar</button>
            <button id="btn-excluir">Excluir</button>
        </div>

        <div class="rg"> 
            <h3>Dados de RG:</h3>

            <label>Número:</label>
            <input type="text" class="inputRg">

            <label>Órgão emissor:</label>
            <input type="text" class="inputRg">

            <label>UF:</label>
            <input type="text" class="inputRg">
            
            <br>
            <br>

            <label>País</label>
            <input type="text" class="inputRg">

            <label>Data:</label>
            <input type="text" class="inputRg">           

            <button class="inputRg">Procurar</button>
        </div>
        
        <div class="filiacao"> 
            <h3>Filiação:</h3>

            <label>Nome do pai:</label>
            <input type="text" class="inputFiliacao">

            <label>Nome da mãe:</label>
            <input type="text" class="inputFiliacao">

            <label>Responsável:</label>
            <input type="text" class="inputFiliacao">
            
        </div>
        
        <div class="contato"> 
            <h3>Contato:</h3>

            <label>Telefone Residencial:</label>
            <input type="text" class="inputTelefone">
            
            <label class="telefone">Telefone Comercial:
                <input type="text" class="inputTelefone">
            </label>

            <br>
            <br>

            <label>Celular:</label>
            <input type="text" class="inputTelefone">

            <label class="telefone">Outro telefone:
                <input type="text" class="">
            </label>

            <br>
            <br>

            <label>Email:</label>
            <input type="text" class="inputTelefone">

            <label>Homepage:</label>
            <input type="text" class="inputTelefone">         
            
            <input type="checkbox">
            <label>Cancelar o recebimento de emails.</label>
           
        </div>

        <div class="profissao"> 
            <h3>Dados Profissionais:</h3>

            <label>Profissão:</label>
            <input type="text" class="inputProfissao">

            <label>Cargo:</label>
            <input type="text" class="inputProfissao">
            
        </div>
        
        <div class="endereco"> 
            <p>Endereço residencial:</p>
            <textarea cols="30" rows="5" class="textArea"></textarea>
            
            <p class="enderecoComercial">End. Comercial / End. Malote:</p>
            <textarea cols="30" rows="5" class="textArea2"></textarea>
            
        </div>

        <div class="botoes">
            <button type="submit">Salvar</button>
            <button type="reset">Cancelar</button>
        </div>
   
    </form>

    <div class="fotoApresentacao">
        <input readonly placeholder="Sem foto" class="fotoPorAparecer">
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
        <div><a href="./telaDeRelatorios.php">Relatórios</a></div>
        <div><a href="./historico-emprestimo.php">Empréstimos</a></div>
        <div><a href="usuarios-cadastrados.php">Usuários</a></div>
        <div><a href="./telaDosLivrosCadastrados.php">Estoque de livros</a></div>
        <div><a href="./telaInicialDoAdm.php">Tela inicial</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>

    <script src="../../../public/js/admin/telaDeCadastroDeUsuarios.js"></script>

</body>
</html>