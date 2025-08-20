<?php
    require "../../../config/constantes.php"
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empréstimo</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="<?php echo $URLBASE?>/public/css/admin/emprestimo.css">
</head>

<body>
    
    <?php
        include "../../../public/components/admin/header/header-admin.php"
    ?>
    
        <div class="quadradoBranco">
                    
            <div class="h3">
                <h3>Usuário:</h3>
            </div>
                    
            <form class="searchInput">
                <input type="text" name="" class="searchBar" placeholder="Insira nome, CPF ou nº de matrícula">
                <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Usuario.png" alt="">
            </form> 
                
            <!-- dados do usuario -->
           <div class="usuarioCadastrado">
                    
            <img src="<?php echo $URLBASE?>/public/assets/img/NullUser.jpg" alt="" id="imagem">
                   
            <span class="nomeUsuario" id="nome"><b>Nome:</b> Carlos Terrel </span>
                   
                   
            <span class="nomeUsuario2" id="numero"><b>N° de matrícula:</b> 31182092025</span>
            <span class="nomeUsuario2" id="perfil"><b>Perfil de acesso:</b> Comum</span>
                    
            <span class="nomeUsuario" id="email"><b>Email:</b> iammusic@gmail.com</span>
            <span class="nomeUsuario" id= "telefone"><b>Telefone:</b> 6740028922</span>
                   
        </div>
                
        <!-- pesquisar livro -->
        <div class="searchBook">
            <h3>Código do livro:</h3>
            
            <form class="searchInput">
                <input type="text" name="" class="searchBar" placeholder="Insira código de exemplar">
                <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_CodigoDoLivro.png" alt="">
            </form>
        </div>

            <!-- emprestimos já cadastrados no nome do usuario -->
            <div class="livrosEmprestados">
                
                <div class="tabela-header">
                    <div class="div1">Capa</div>
                    <div class="div2">Título</div>
                    <!-- <div class="div3">Exemplar</div>
                    <div class="div4">Data empres.</div>
                    <div class="div5">Data devo.</div> -->
                </div>

                <div class="tabela-footer"> 
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <!-- <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div> -->
                        <!-- <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div> -->
                    </div>
                    
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <!-- <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div> -->
                    </div>
                    
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <!-- <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div> -->
                    </div>
                    
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <!-- <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                                <button type="submit" id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                                <button type="submit" id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div> -->
                    </div>
                </div>                
                
            </div>

        </div>
    <!-- </main> -->
    
    <?php
    include "../../../public/components/admin/footer/footer-admin.php";
    ?>
    
</body>
</html>