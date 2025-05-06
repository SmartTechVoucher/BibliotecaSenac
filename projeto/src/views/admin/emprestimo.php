<?php
    require "../../../config/constantes.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empréstimo</title>
    <link rel="stylesheet" href="../../../public/css/admin/emprestimo.css">
</head>

<body>
    <?php
        include "../../../public/components/header/header.php"
    ?>
    
    <main>
        <div class="quadradoBranco">

            <!-- pesquisas de usuario e livro -->
            <div class="search-container">
                <!-- pesquisar usuario -->
                <div class="searchUser">
                    <h3>Usuário:</h3>
                    <form class="searchInput">
                        <input type="text" name="" id="searchBar" placeholder="Insira nome, CPF ou nº de matrícula">
                        <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Usuario.png" alt="">
                    </form> 
                </div>
                
                <!-- dados do usuario -->
                <div class="usuarioCadastrado">
                    <img src="<?php echo $URLBASE?>/public/assets/img/NullUser.jpg" alt="">
                    <div class="userInfo">
                        <span id="nameUser"><b>Nome:</b> Carlos Terrel </span>
                        <div class="userInfo2">
                            <span><b>N° de matrícula:</b> 31182092025</span>
                            <span><b>Perfil de acesso:</b> Comum <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_PerfilAcesso.png" alt=""></span>
                        </div>
                        
                        <div class="userInfo3">
                            <span><b>Email:</b> iammusic@gmail.com</span>
                            <span><b>Telefone:</b> 6740028922</span>
                        </div>

                    </div>
                </div>
                
                <!-- pesquisar livro -->
                <div class="searchBook">
                    <h3>Código do livro:</h3>
                    <form class="searchInput">
                        <input type="text" name="" id="searchBar" placeholder="Insira código de exemplar">
                        <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_CodigoDoLivro.png" alt="">
                    </form>
                </div>

            </div>

            <!-- emprestimos já cadastrados no nome do usuario -->
            <div class="livrosEmprestados">
                <div class="tabela-header">
                    <div class="div1">Capa</div>
                    <div class="div2">Título</div>
                    <div class="div3">Exemplar</div>
                    <div class="div4">Data empres.</div>
                    <div class="div5">Data devo.</div>
                </div>

                <div class="tabela-footer"> 
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div>
                    </div>
                    <div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div>
                    </div><div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                            <button id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                            <button id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div>
                    </div><div class="book-grid">
                        <img class="grid1" src="<?php echo $URLBASE?>/public/assets/img/livroCapa.jpg"></img>
                        <div class="grid2"><a href="" url="">A Gaia Ciência</a></div>
                        <div class="grid3">618.92 T157e</div>
                        <div class="grid4">18/03/2025</div>
                        <div class="grid5">21/03/2025</div>
                        <div class="grid6">
                                <button type="submit" id="grid-button">Devolver <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Devolver.png" alt=""></button>
                                <button type="submit" id="grid-button">Renovar <img src="<?php echo $URLBASE?>/public/assets/icons/Icone_Renovar.png" alt=""></button>
                        </div>
                    </div>
                </div>
                
                
                
            </div>

        </div>
    </main>

    
    
</body>
</html>