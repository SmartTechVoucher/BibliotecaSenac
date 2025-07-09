<?php
    require "../../../config/constantes.php"
?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de empréstimos</title>
        <?php
    require_once "../../../config/constantes.php";
  ?>
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
  <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
        <link rel="stylesheet" href="/BibliotecaSenac/projeto/public/css/admin/historico-emprestimo.css">
    </head>

 <body>
    <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->   
    <?php   
    include "../../../public/components/admin/header/header-admin.php";
  ?>

    <div class="main-container">
        <h2>Histórico de Empréstimos</h2>   
        <div class="filter-options">
            <span>Filtrar por Status:</span>
            <label>
                <input type="radio" name="statusFilter" value="Todos" checked onchange="applyFilterAndPaginate()"> Todos
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Finalizado" onchange="applyFilterAndPaginate()"> Finalizado
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Atrasado" onchange="applyFilterAndPaginate()"> Atrasado
            </label>
            <label>
                <input type="radio" name="statusFilter" value="Em andamento" onchange="applyFilterAndPaginate()"> Em andamento
            </label>
        </div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Exemplar</th>
                        <th>Leitor</th>
                        <th>Data</th>
                        <th>Prazo</th>
                        <th>Devolução</th>
                    </tr>
                </thead>
                <tbody id="userTable"></tbody>
                
            </table>

    <div class="pagination-controls">
        <button id="prevBtn" onclick="prevPage()">Anterior</button>
        <span id="pageInfo" class="pagination-info"></span>
        <button id="nextBtn" onclick="nextPage()">Próximo</button>
    </div>

    </div>     
     
   
    <?php
    include "../../../public/components/usuario/footer/footer.php";
    ?>
    <script src="../../../public/js/admin/historico-emprestimo.js"></script>

 </body>
</html>



   