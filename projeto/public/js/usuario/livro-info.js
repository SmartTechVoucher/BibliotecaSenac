let exemplarFechado = true;

function alternarExemplar() {
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarFechado) {
        botaoExemplar.src = URLBASE + "/public/assets/icons/Minus Math.png";
        containerExemplarAberto.style.display = "block";
        exemplarFechado = false;
    } else {
        botaoExemplar.src = URLBASE + "/public/assets/icons/Plus Math.png";
        containerExemplarAberto.style.display = "none";
        exemplarFechado = true;
    }
}

function reservaConcluida() {
    const botaoReserva = document.getElementById('botaoReserva');
    const estadoAtual = botaoReserva.getAttribute('data-status');
    if (estadoAtual == "livre") {
        botaoReserva.setAttribute('data-status', 'reservado');
        botaoReserva.textContent = "Livro Reservado";
        botaoReserva.style.background = "#F68B1F";
    } else {
        const confirmarCancelamento = window.confirm("Você realmente quer cancelar a reserva?");
        if (confirmarCancelamento) {
            botaoReserva.setAttribute('data-status', 'livre');
            botaoReserva.textContent = "Reservar";
            botaoReserva.style.background = "#004A90";
        }
    }
}

// Sistema de avaliação por estrelas
document.addEventListener('DOMContentLoaded', function () {
    const estrelas = document.querySelectorAll('.estrela-input');
    const valorDeRanqueamento = document.getElementById('rating-value');

    if (!estrelas.length || !valorDeRanqueamento) {
        console.log('Formulário de avaliação não encontrado (usuário não logado)');
        return;
    }

    let rankAtual = 0;

    function atualizarEstrelas(avaliacao) {
        estrelas.forEach(estrela => {
            if (estrela.dataset.value <= avaliacao) {
                estrela.classList.add('active');
            } else {
                estrela.classList.remove('active');
            }
        });
    }

    estrelas.forEach(estrela => {
        estrela.addEventListener('mouseover', () => {
            atualizarEstrelas(estrela.dataset.value);
        });

        estrela.addEventListener('mouseout', () => {
            atualizarEstrelas(rankAtual);
        });

        estrela.addEventListener('click', () => {
            rankAtual = estrela.dataset.value;
            valorDeRanqueamento.value = rankAtual;
            atualizarEstrelas(rankAtual);
        });
    });

    const form = document.getElementById('commentForm');
    if (form) {
        form.addEventListener('reset', () => {
            rankAtual = 0;
            valorDeRanqueamento.value = 0;
            atualizarEstrelas(rankAtual);
        });
    }
});

// Carregar comentários ao abrir a página
document.addEventListener('DOMContentLoaded', function () {
    carregarComentarios();
});

// Função para carregar comentários do backend
function carregarComentarios() {
    const url = `${URLBASE}/router.php?acao=comentarios&id_livro=${ID_LIVRO}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);

            if (data.erro) {
                console.error('Erro ao carregar comentários:', data.erro);
                mostrarMensagemContainer('Erro ao carregar comentários.');
                return;
            }

            const container = document.getElementById('reviewsContainer');
            container.innerHTML = '';

            const comentarios = data.comentarios || [];
            const stats = data.estatisticas || { total_avaliacoes: 0, media_estrelas: 0 };

            if (comentarios.length === 0) {
                mostrarMensagemContainer('Nenhum comentário ainda. Seja o primeiro a avaliar!');
                atualizarMediaAvaliacoes(0, 0);
                return;
            }

            comentarios.forEach(comentario => {
                adicionarComentarioNaTela(comentario);
            });

            atualizarMediaAvaliacoes(stats.media_estrelas, stats.total_avaliacoes);
            console.log(`Média: ${stats.media_estrelas} estrelas | Total: ${stats.total_avaliacoes} avaliações`);
            console.log('Distribuição:', stats.distribuicao);
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            mostrarMensagemContainer('Erro ao carregar comentários. Tente novamente mais tarde.');
        });
}

function mostrarMensagemContainer(mensagem) {
    const container = document.getElementById('reviewsContainer');
    container.innerHTML = `<p style="text-align: center; color: #666; padding: 20px;">${mensagem}</p>`;
}

function atualizarMediaAvaliacoes(media, total) {
    const imgMedia = document.getElementById('avaliacaoMediaImg');
    const totalSpan = document.getElementById('totalReviews');

    if (imgMedia && media > 0) {
        imgMedia.src = `${URLBASE}/public/assets/icons/estrelas${media}.png`;
    }

    if (totalSpan) {
        totalSpan.textContent = total;
    }
}

// Função para adicionar comentário na tela
function adicionarComentarioNaTela(comentario) {
    const template = document.getElementById('commentTemplate');
    const novoComentario = template.cloneNode(true);
    novoComentario.style.display = 'block';
    novoComentario.id = '';

    novoComentario.querySelector('.commentTitulo').textContent = comentario.nome || 'Usuário';
    novoComentario.querySelector('.commentConteudo').textContent = comentario.comentario;

    let dataFormatada;
    if (comentario.data_comentario) {
        const data = new Date(comentario.data_comentario);
        dataFormatada = data.toLocaleDateString('pt-BR');
    } else {
        dataFormatada = new Date().toLocaleDateString('pt-BR');
    }
    novoComentario.querySelector('.commentUserinfo').textContent = `Feito em: ${dataFormatada}`;

    const estrelas = novoComentario.querySelector('.estrela-placeholder');
    const avaliacaoNum = parseInt(comentario.avaliacao) || 1;
    estrelas.src = `${URLBASE}/public/assets/icons/estrelas${avaliacaoNum}.png`;

    const container = document.getElementById('reviewsContainer');
    container.appendChild(novoComentario);
}

// Enviar novo comentário
document.addEventListener('DOMContentLoaded', function () {
    const btnEnviar = document.getElementById('comentario-botao');

    if (!btnEnviar) {
        console.log('Botão de enviar não encontrado (usuário não logado)');
        return;
    }

    btnEnviar.addEventListener('click', function () {
        if (!USUARIO_LOGADO) {
            alert('Você precisa estar logado para comentar!');
            window.location.href = `${URLBASE}/src/views/usuario/login.php`;
            return;
        }

        const comentarioInput = document.getElementById('comentario-input');
        const avaliacaoInput = document.getElementById('rating-value');

        const comentario = comentarioInput.value.trim();
        const avaliacao = parseInt(avaliacaoInput.value);

        if (comentario === '') {
            alert('Por favor, escreva um comentário!');
            comentarioInput.focus();
            return;
        }

        if (avaliacao === 0 || isNaN(avaliacao)) {
            alert('Por favor, selecione uma avaliação (clique nas estrelas)!');
            return;
        }

        const dados = {
            id_usuario: ID_USUARIO,
            id_livro: ID_LIVRO,
            comentario: comentario,
            avaliacao: avaliacao
        };

        console.log('Enviando comentário:', dados);

        btnEnviar.disabled = true;
        btnEnviar.textContent = 'Enviando...';

        fetch(`${URLBASE}/router.php?acao=comentarios`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dados)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Resposta do servidor:', data);

                if (data.sucesso) {
                    alert('Comentário enviado com sucesso!');
                    comentarioInput.value = '';
                    avaliacaoInput.value = '0';
                    document.querySelectorAll('.estrela-input').forEach(e => e.classList.remove('active'));
                    carregarComentarios();
                } else {
                    alert('Erro ao enviar comentário: ' + (data.erro || data.mensagem || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao enviar comentário. Verifique sua conexão e tente novamente.');
            })
            .finally(() => {
                btnEnviar.disabled = false;
                btnEnviar.textContent = 'Enviar';
            });
    });
});
