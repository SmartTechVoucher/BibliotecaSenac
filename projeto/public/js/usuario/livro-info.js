document.addEventListener("DOMContentLoaded", () => {
    registrarEventos();
    carregarComentarios(LIVRO_ID_GLOBAL);
    inicializarReserva();
});

function registrarEventos() {
    const stars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("ratingInput");
    const commentForm = document.getElementById("commentForm");

    if (stars) {
        stars.forEach(star => {
            star.addEventListener("click", () => {
                const valor = parseInt(star.getAttribute("data-value"));
                if (valor >= 1 && valor <= 5) {
                    ratingInput.value = valor;
                    highlightStars(valor);
                }
            });
        });
    }

    if (commentForm) {
        commentForm.addEventListener("submit", async (evento) => {
            evento.preventDefault();
            await enviarComentario();
        });
    }
}

function highlightStars(valor) {
    document.querySelectorAll(".star").forEach(star => {
        const starValue = parseInt(star.getAttribute("data-value"));
        star.classList.toggle("selected", starValue <= valor);
    });
}

function alternarExemplar(botao) {
    const miniaturaSelecionada = botao.closest(".miniatura");

    document.querySelectorAll(".miniatura").forEach(mini => {
        mini.classList.remove("selecionado");
    });

    miniaturaSelecionada.classList.add("selecionado");

    const caminhoImagem = miniaturaSelecionada.getAttribute("data-img");
    const estoque = miniaturaSelecionada.getAttribute("data-estoque");

    document.getElementById("imagemPrincipal").src = caminhoImagem;

    const textoDisponibilidade = document.getElementById("textoDisponibilidade");
    if (textoDisponibilidade) {
        textoDisponibilidade.textContent =
            estoque > 0 ? `${estoque} exemplar(es) disponível(eis)` : "Indisponível no momento";
    }
}

async function enviarComentario() {
    const texto = document.getElementById("commentText").value.trim();
    const rating = parseInt(document.getElementById("ratingInput").value);

    if (!texto || isNaN(rating)) {
        alert("Preencha o comentário e a nota.");
        return;
    }

    const body = new FormData();
    body.append("idLivro", LIVRO_ID_GLOBAL);
    body.append("idUsuario", USUARIO_ID_GLOBAL);
    body.append("comentario", texto);
    body.append("rating", rating);

    try {
        const resposta = await fetch(`${URLBASE}/router.php?acao=comentarios`, {
            method: "POST",
            body: body
        });

        const resultado = await resposta.json();

        if (resultado.success) {
            await carregarComentarios(LIVRO_ID_GLOBAL);
            document.getElementById("commentText").value = "";
            document.getElementById("ratingInput").value = "0";
            highlightStars(0);
        } else {
            alert("Erro ao enviar comentário.");
        }
    } catch (erro) {
        console.error("Erro:", erro);
        alert("Erro ao conectar ao servidor.");
    }
}

async function carregarComentarios(idLivro) {
    const container = document.getElementById("commentsList");

    if (!container) return;

    try {
        const resposta = await fetch(`${URLBASE}/router.php?acao=comentarios&idLivro=${idLivro}`);
        const { dados = [], media = 0 } = await resposta.json();

        container.innerHTML = "";

        const estrelasMedia = document.getElementById("estrelasMedia");
        if (estrelasMedia) estrelasMedia.innerHTML = gerarEstrelas(Math.round(media));

        dados.forEach(comentario => {
            adicionarComentarioNaTela(comentario);
        });
    } catch (erro) {
        console.error("Erro ao carregar comentários:", erro);
    }
}

function gerarEstrelas(quantidade) {
    let html = "";
    for (let i = 1; i <= 5; i++) {
        html += `<span class="star pequeña ${i <= quantidade ? "selecionada" : ""}">★</span>`;
    }
    return html;
}

function adicionarComentarioNaTela(comentario) {
    const container = document.getElementById("commentsList");
    const card = document.createElement("div");
    card.classList.add("comentarioCard");

    card.innerHTML = `
        <div class="commentAutor">${comentario.nome}</div>
        <div class="commentEstrelas">${gerarEstrelas(comentario.rating)}</div>
        <div class="commentTexto">${comentario.texto}</div>
        <div class="commentData">${comentario.data}</div>
    `;

    container.appendChild(card);
}

function inicializarReserva() {
    const botao = document.getElementById("botaoReserva");
    if (!botao) return;

    botao.addEventListener("click", async () => {
        const status = botao.getAttribute("data-status");

        if (status === "reservar") {
            await reservarLivro();
        } else if (status === "cancelar") {
            await cancelarReserva();
        }
    });
}

async function reservarLivro() {
    const body = new FormData();
    body.append("idLivro", LIVRO_ID_GLOBAL);
    body.append("idUsuario", USUARIO_ID_GLOBAL);

    try {
        const resposta = await fetch(`${URLBASE}/router.php?acao=reservar`, {
            method: "POST",
            body: body
        });

        const result = await resposta.json();
        if (result.success) {
            atualizarBotaoReserva("cancelar");
        }
    } catch (erro) {
        console.error("Erro:", erro);
    }
}

async function cancelarReserva() {
    const body = new FormData();
    body.append("idLivro", LIVRO_ID_GLOBAL);
    body.append("idUsuario", USUARIO_ID_GLOBAL);

    try {
        const resposta = await fetch(`${URLBASE}/router.php?acao=cancelar`, {
            method: "POST",
            body: body
        });

        const result = await resposta.json();
        if (result.success) {
            atualizarBotaoReserva("reservar");
        }
    } catch (erro) {
        console.error("Erro:", erro);
    }
}

function atualizarBotaoReserva(novoEstado) {
    const botao = document.getElementById("botaoReserva");

    if (novoEstado === "cancelar") {
        botao.textContent = "Cancelar Reserva";
        botao.setAttribute("data-status", "cancelar");
        botao.classList.add("cancelar");
    } else {
        botao.textContent = "Reservar";
        botao.setAttribute("data-status", "reservar");
        botao.classList.remove("cancelar");
    }
}
