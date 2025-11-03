<?php
require "../../../config/constantes.php"
?>


<div class="main-container">
    <h2>Histórico de Empréstimos</h2>
    <div class="filter-options">
        <span>Filtrar por Status:</span>
        <label>
            <input type="radio" name="statusFilter" value="Todos" checked onchange="aplicarFiltroEPaginacao()"> Todos
        </label>
        <label>
            <input type="radio" name="statusFilter" value="Finalizado" onchange="aplicarFiltroEPaginacao()"> Finalizado
        </label>
        <label>
            <input type="radio" name="statusFilter" value="Atrasado" onchange="aplicarFiltroEPaginacao()"> Atrasado
        </label>
        <label>
            <input type="radio" name="statusFilter" value="Em andamento" onchange="aplicarFiltroEPaginacao()"> Em andamento
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
        <button id="prevBtn" onclick="paginaAnterior()">Anterior</button>
        <span id="pageInfo" class="pagination-info"></span>
        <button id="nextBtn" onclick="proximaPagina()">Próximo</button>
    </div>

</div>

<script src="../../../public/js/admin/historico-emprestimo.js"></script>