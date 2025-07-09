<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro de usuários</title>
    <?php
    require_once "../../../config/constantes.php";
  ?>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
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
 <?php   
    include "../../../public/components/admin/header/header-admin.php";
  ?>

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

   
            
   <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

    
   <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/telaDeCadastroDeUsuarios.js"></script>

</body>
</html>