// Variável global para guardar o ID do usuário
let usuarioSelecionadoId = null;
// Variável global para guardar a página atual
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
    // Reseta a página para 1 ao selecionar novo usuário
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

    // 3. Busca e exibe a Tabela (página 1)
    carregarTabelaEmprestimos(id, 1);

    // 4. Ativa os botões de empréstimo
    document.getElementById("isbnInput").disabled = false;
    document.getElementById("confirmLoanBtn").disabled = false;
    document.getElementById("cancelLoanBtn").disabled = false;
    document.getElementById("loanMessage").innerHTML = "";
}

// --- Funções de Empréstimo ---

/**
 * Busca a tabela de empréstimos PAGINADA de um usuário no backend.
 */
function carregarTabelaEmprestimos(idUsuario, pagina) {
    // Guarda a página que está sendo carregada
    if (pagina) {
        paginaAtualEmprestimos = pagina; 
    }

    const tableContainer = document.getElementById("loanTableContainer");
    const paginationContainer = document.getElementById("loanPaginationContainer");
    
    tableContainer.innerHTML = "<p>Carregando empréstimos...</p>";
    paginationContainer.innerHTML = "";

    fetch(`/BibliotecaSenac/projeto/src/controller/admin/ExibirEmprestimosController.php?id=${idUsuario}&page=${pagina}`)
        .then(response => response.json())
        .then(data => {
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
    if (isbn.length < 10) {
        messageDiv.innerHTML = "<p style='color: red;'>Erro: ISBN inválido.</p>";
        return;
    }

    const formData = new FormData();
    formData.append('id_usuario', usuarioSelecionadoId);
    formData.append('isbn', isbn);

    messageDiv.innerHTML = "<p>Registrando...</p>";
    document.getElementById("confirmLoanBtn").disabled = true;

    fetch("/BibliotecaSenac/projeto/src/controller/admin/RegistrarEmprestimosController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = `<p style='color: green;'>${data.message}</p>`;
            document.getElementById("isbnInput").value = "";
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
        document.getElementById("confirmLoanBtn").disabled = false;
    });
}

/**
 * NOVO: Confirma um empréstimo pendente e inicia a contagem de 48h
 */
function confirmarEmprestimo(idMovimentacao) {
    if (!confirm("Confirmar a retirada deste livro? O prazo de 48 horas para devolução será iniciado.")) {
        return;
    }

    const formData = new FormData();
    formData.append('id_movimentacao', idMovimentacao);
    
    const messageDiv = document.getElementById("loanMessage");
    messageDiv.innerHTML = "<p>Confirmando empréstimo...</p>";

    fetch("/BibliotecaSenac/projeto/router.php?acao=confirmarEmprestimo", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = `<p style='color: green;'>${data.message}</p>`;
            // Recarrega a tabela na página atual
            carregarTabelaEmprestimos(usuarioSelecionadoId, paginaAtualEmprestimos); 
        } else {
            messageDiv.innerHTML = `<p style='color: red;'>${data.message}</p>`;
        }
    })
    .catch(err => {
        messageDiv.innerHTML = "<p style='color: red;'>Erro ao confirmar empréstimo.</p>";
        console.error(err);
    });
}

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
    formData.append('id_livro', idLivro);

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

// --- Event Listeners ---
 
document.addEventListener("DOMContentLoaded", () => {
    // Botão Confirmar
    document.getElementById("confirmLoanBtn").addEventListener("click", registrarEmprestimo);

    // Botão Cancelar
    document.getElementById("cancelLoanBtn").addEventListener("click", () => {
        document.getElementById("isbnInput").value = "";
        document.getElementById("loanMessage").innerHTML = "";
    });
});