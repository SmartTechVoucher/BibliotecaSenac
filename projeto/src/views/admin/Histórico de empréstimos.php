<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de empréstimos</title>
        <link rel="stylesheet" href="../../../public/css/admin/Histórico de empréstimos.css">
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

    <h1>histórico de empréstimos</h1>   
        

     <div class="tabela">     
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Exemplar</th>
                    <th>Leitor</th>
                    <th>Data</th>
                    <th>Prazo</th>
                    <th>Devolução</th>
                    <th>Ações</th>
                </tr>
            </thead>
            
            <tbody id="tabela-corpo">
                <tr>
                    <td>devolvido</td>
                    <td>402864</td>
                    <td>victor hugo</td>
                    <td>20/02/25</td>
                    <td>30/02/25</td>
                    <td>19/02/2025</td>
                    
                    <td>
                        <button type img="s" class="edit" onclick="editar(this)">Editar</button>
                        <button class="delete" onclick="excluir(this)">Excluir</button>
                    </td>
                
                </tr>
            </tbody>
        </table>
        
        <tbody id="tabela-corpo">
            
         <table>
               <tr>
                    <td>Emprestado</td>
                    <td>785930</td>
                    <td>lulu oliveira</td>
                    <td>20/02/2025</td>
                    <td>03/03/2025</td>
                    <td>30/02/2025</td>
                    
                    <td>
                        <button class="edit" onclick="editar(this)">Editar</button>
                        <button class="delete" onclick="excluir(this)">Excluir</button>
                    </td>
               </tr>
          </table>
        
        </tbody>
        
        <tbody id="tabela-corpo">
            
          <table>
                <tr>
                    <td>Emprestado</td>
                    <td>80909099</td>
                    <td>João</td>
                    <td>20/02/2025</td>
                    <td>10 dias</td>
                    <td>30/02/2025</td>
                    
                    <td>
                        <button class="edit" onclick="editar(this)">Editar</button>
                        <button class="delete" onclick="excluir(this)">Excluir</button>
                    </td>
                </tr>
          </table>
        </tbody>
       
      
        <tbody id="tabela-corpo">
            <table>
                <tr>
                    <td>Emprestado</td>
                    <td>944512</td>
                    <td>João</td>
                    <td>20/02/2025</td>
                    <td>04 dias</td>
                    <td>30/02/2025</td>
                    
                    <td>
                        <button class="edit" onclick="editar(this)">Editar</button>
                        <button class="delete" onclick="excluir(this)">Excluir</button>
                    </td>
                </tr>

           </table>    
        </tbody>
     </div> 
        
        <div class="inferiorDireito">

            <img src="../../../public/assets/icons/Inferior direito.png" alt="" class="inferior">

        </div>

        <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->  <!--Rodapé-->

     <div class="rodape">

        <div class="logoFecomercio"><img src="../../../public/assets/icons/Fecomercio.png" alt="" class="Logo" ></div>

        <div>
            <img src="../../../public/assets/icons/Livro.png" alt="" class="Libro">
            <div class="copyright">Senac MS Copyright © <br></div>
            <div class="todos"> 2024. Todos os Direitos Reservados</div>   
        </div>

     </div> 

    
    <div id="menu" class="menu">

        <div><a href="./Tela de cadastro de usuários.php">Cadastrar usuários</a></div>
        <div><a href="./Tela de cadastro de livros.php">Cadastrar livros</a></div>
        <div><a href="Por fazer">Usuários cadastrados</a></div>
        <div><a href="./Tela de relatórios.php">Relatórios</a></div>
        <div><a href="./Tela inicial do adm.php">Tela inicial</a></div>
        <div><a href="./Tela dos livros cadastrados.php">Estoque de livros</a></div>
        <hr>
        <div><a href="../usuario/login.php">Logout</a></div>

    </div>

    <script src="../../../public/js/admin/Histórico de empréstimos.js"></script>

 </body>
</html>



   