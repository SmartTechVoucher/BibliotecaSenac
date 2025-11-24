<?php
require "../../../config/constantes.php";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<main>
                <!-- Barra de pesquisa -->
                <div id="barra-pesquisa">
                    <label for="searchBox">Pesquisar usuário:</label>
                    <input type="text" id="searchBox" onkeyup="buscarUsuarios(this.value)" placeholder="Digite o nome, e-mail ou CPF...">
                    <div id="results"></div>
                </div>

                <!-- Card do usuário -->
                <div id="userCard">
                    <h3>Selecione um usuário</h3>
                    <p id="placeholder">Os dados aparecerão aqui após a seleção.</p>
                </div>

                <div id="loanSection">
                <label for="isbnInput">Registrar empréstimo</label>
                <div class="loan-input-group">
                    <input type="text" id="isbnInput" placeholder="Digite o ISBN do livro..." disabled>
                    <button id="cancelLoanBtn" class="cancel-btn" disabled>Cancelar</button>
                    <button id="confirmLoanBtn" disabled>Confirmar</button>
                    
                </div>
                <div id="loanMessage"></div>
            </div>

            <div id="loanTableContainer">
        </div>

    <div id="loanPaginationContainer"></div>
    </main>

    <script src="<?php echo $URLBASE ?>/public/js/admin/emprestimo.js"></script>
