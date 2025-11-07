// Variável global para guardar o ID do usuário
let usuarioSelecionadoId = null;
// (NOVO) Variável global para guardar a página atual
let paginaAtualEmprestimos = 1;

// --- Funções da Barra de Busca ---

function buscarUsuarios(query) {
    const resultsDiv = document.getElementById("results");
    if (query.length < 2) {
        resultsDiv.innerHTML = "";
        return;
    }

    fetch("/BibliotecaSenac/projeto/src/controller/admin/BuscarUsuarioController.php?q=" + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => resultsDiv.innerHTML = data)
        .catch(err => console.error(err));
}

function mostrarUsuario(id) {
    // 1. Guarda o ID globalmente
    usuarioSelecionadoId = id;
    // (NOVO) Reseta a página para 1 ao selecionar novo usuário
    paginaAtualEmprestimos = 1; 

    // 2. Busca e exibe o Card do Usuário
    fetch("/BibliotecaSenac/projeto/src/controller/admin/CardDadosUsuarioController.php?id=" + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById("userCard").innerHTML = data;
            document.getElementById("results").innerHTML = "";
            document.getElementById("searchBox").value = "";
        })
        .catch(err => console.error(err));

    // 3. (ATUALIZADO) Busca e exibe a Tabela (página 1)
    carregarTabelaEmprestimos(id, 1); // <-- Envia a página 1

    // 4. Ativa os botões de empréstimo
    document.getElementById("isbnInput").disabled = false;
    document.getElementById("confirmLoanBtn").disabled = false;
    document.getElementById("cancelLoanBtn").disabled = false;
    document.getElementById("loanMessage").innerHTML = "";
}

// --- (ATUALIZADO) Funções de Empréstimo ---

/**
 * Busca a tabela de empréstimos PAGINADA de um usuário no backend.
 */
function carregarTabelaEmprestimos(idUsuario, pagina) {
    // (NOVO) Guarda a página que está sendo carregada
    if (pagina) { // Garante que a página só é atualizada se for fornecida
        paginaAtualEmprestimos = pagina; 
    }

    const tableContainer = document.getElementById("loanTableContainer");
    const paginationContainer = document.getElementById("loanPaginationContainer"); // (NOVO)
    
    tableContainer.innerHTML = "<p>Carregando empréstimos...</p>"; // Feedback
    paginationContainer.innerHTML = ""; // Limpa paginação antiga

    // (ATUALIZADO) Adiciona o parâmetro '&page=' ao fetch
    fetch(`/BibliotecaSenac/projeto/src/controller/admin/ExibirEmprestimosController.php?id=${idUsuario}&page=${pagina}`)
        .then(response => response.json()) // (ATUALIZADO) Espera JSON
        .then(data => {
            // (ATUALIZADO) Processa a resposta JSON
            if (data.success) {
                tableContainer.innerHTML = data.tabela_html;
                paginationContainer.innerHTML = data.paginacao_html;
            } else {
                tableContainer.innerHTML = `<p style='color: red;'>${data.message || 'Erro ao carregar empréstimos.'}</p>`;
            }
        })
        .catch(err => {
            tableContainer.innerHTML = "<p style='color: red;'>Erro fatal na requisição.</p>";
            console.error(err);
        });
}

/**
 * Tenta registrar um novo empréstimo.
 */
function registrarEmprestimo() {
    const isbn = document.getElementById("isbnInput").value;
    const messageDiv = document.getElementById("loanMessage");

    if (!usuarioSelecionadoId) {
        messageDiv.innerHTML = "<p style='color: red;'>Erro: Nenhum usuário selecionado.</p>";
        return;
    }
    if (isbn.length < 10) { // Validação simples de ISBN
        messageDiv.innerHTML = "<p style='color: red;'>Erro: ISBN inválido.</p>";
        return;
    }

    // Prepara os dados para enviar via POST
    const formData = new FormData();
    formData.append('id_usuario', usuarioSelecionadoId);
    formData.append('isbn', isbn);

    // Feedback de carregamento
    messageDiv.innerHTML = "<p>Registrando...</p>";
    document.getElementById("confirmLoanBtn").disabled = true;

    fetch("/BibliotecaSenac/projeto/src/controller/admin/RegistrarEmprestimosController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Esperamos uma resposta JSON
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = `<p style='color: green;'>${data.message}</p>`;
            // Limpa o input e atualiza a tabela
            document.getElementById("isbnInput").value = "";
            
            // (ATUALIZADO) Recarrega a página 1
            carregarTabelaEmprestimos(usuarioSelecionadoId, 1); 
        } else {
            messageDiv.innerHTML = `<p style='color: red;'>${data.message}</p>`;
        }
    })
    .catch(err => {
        messageDiv.innerHTML = "<p style='color: red;'>Erro fatal na requisição.</p>";
        console.error(err);
    })
    .finally(() => {
        // Reativa o botão
        document.getElementById("confirmLoanBtn").disabled = false;
    });
}

// --- Event Listeners ---
 
// Adiciona os eventos aos botões quando a página carregar
document.addEventListener("DOMContentLoaded", () => {
    // Botão Confirmar
    document.getElementById("confirmLoanBtn").addEventListener("click", registrarEmprestimo);

    // Botão Cancelar
    document.getElementById("cancelLoanBtn").addEventListener("click", () => {
        document.getElementById("isbnInput").value = "";
        document.getElementById("loanMessage").innerHTML = "";
    });
});

/**
 * Envia uma requisição para renovar um empréstimo.
 */
function renovarEmprestimo(idMovimentacao) {
    if (!confirm("Deseja realmente renovar este empréstimo por mais 5 dias?")) {
        return;
    }

    const formData = new FormData();
    formData.append('id_movimentacao', idMovimentacao);
    
    const messageDiv = document.getElementById("loanMessage");
    messageDiv.innerHTML = "<p>Renovando...</p>";

    fetch("/BibliotecaSenac/projeto/src/controller/admin/RenovarEmprestimoController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = `<p style='color: green;'>${data.message}</p>`;
            // (ATUALIZADO) Recarrega a PÁGINA ATUAL
            carregarTabelaEmprestimos(usuarioSelecionadoId, paginaAtualEmprestimos); 
        } else {
            messageDiv.innerHTML = `<p style='color: red;'>${data.message}</p>`;
        }
    })
    .catch(err => {
        messageDiv.innerHTML = "<p style='color: red;'>Erro fatal na requisição de renovação.</p>";
        console.error(err);
    });
}

/**
 * Envia uma requisição para devolver um livro.
 */
function devolverEmprestimo(idMovimentacao, idLivro) {
    if (!confirm("Deseja realmente marcar este livro como devolvido?")) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id_movimentacao', idMovimentacao);
    formData.append('id_livro', idLivro); // Precisamos do ID do livro para atualizar o estoque

    const messageDiv = document.getElementById("loanMessage");
    messageDiv.innerHTML = "<p>Processando devolução...</p>";

    fetch("/BibliotecaSenac/projeto/src/controller/admin/DevolverEmprestimoController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = `<p style='color: green;'>${data.message}</p>`;
            // (ATUALIZADO) Recarrega a PÁGINA ATUAL
            carregarTabelaEmprestimos(usuarioSelecionadoId, paginaAtualEmprestimos); 
        } else {
            messageDiv.innerHTML = `<p style='color: red;'>${data.message}</p>`;
        }
    })
    .catch(err => {
        messageDiv.innerHTML = "<p style='color: red;'>Erro fatal na requisição de devolução.</p>";
        console.error(err);
    });
}