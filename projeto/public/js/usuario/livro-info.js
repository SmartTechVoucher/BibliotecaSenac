let exemplarFechado = true;

function alternarExemplar() {
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarFechado) {
<<<<<<< HEAD
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Minus Math.png";
        containerExemplarAberto.style.display = "block";
        exemplarFechado = false;
    } else {
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Plus Math.png";
=======
        botaoExemplar.src = URLBASE + "/public/assets/icons/Minus Math.png";
        containerExemplarAberto.style.display = "block";
        exemplarFechado = false;
    } else {
        botaoExemplar.src = URLBASE + "/public/assets/icons/Plus Math.png";
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
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

<<<<<<< HEAD
// Função para obter ID do livro da URL ou página
function obterIdLivro() {
    // Opção 1: Via URL (adicione ?id_livro=1 na URL)
    const urlParams = new URLSearchParams(window.location.search);
    const idLivro = urlParams.get('id_livro');
    
    if (idLivro) {
        return idLivro;
    }
    
    // Opção 2: Valor fixo por enquanto (você deve passar o ID correto)
    return 1; // ALTERE PARA O ID DO LIVRO CORRETO
}

// Função para obter ID do usuário logado
function obterIdUsuario() {
    // Você pode armazenar o ID na sessão PHP e injetar no JavaScript
    // ou fazer uma requisição para pegar da sessão
    return 1; // ALTERE PARA PEGAR DA SESSÃO
}

// Sistema de avaliação por estrelas
document.addEventListener('DOMContentLoaded', function () {
    const estrelas = document.querySelectorAll('.estrela-input');
    const valorDeRanqueamento = document.getElementById('rating-value');
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

=======
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

>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
        estrela.addEventListener('mouseout', () => {
            atualizarEstrelas(rankAtual);
        });

        estrela.addEventListener('click', () => {
            rankAtual = estrela.dataset.value;
            valorDeRanqueamento.value = rankAtual;
            atualizarEstrelas(rankAtual);
        });
<<<<<<< HEAD
    });

    const form = document.getElementById('commentForm');
    form.addEventListener('reset', () => {
        rankAtual = 0;
        valorDeRanqueamento.value = 0;
        atualizarEstrelas(rankAtual);
    });
=======
    });

    const form = document.getElementById('commentForm');
    if (form) {
        form.addEventListener('reset', () => {
            rankAtual = 0;
            valorDeRanqueamento.value = 0;
            atualizarEstrelas(rankAtual);
        });
    }
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
});

// Carregar comentários ao abrir a página
document.addEventListener('DOMContentLoaded', function () {
    carregarComentarios();
});

// Função para carregar comentários do backend
function carregarComentarios() {
<<<<<<< HEAD
    const idLivro = obterIdLivro();
    
    fetch(`/BibliotecaSenac/projeto/src/views/usuario/comentarios.php?id_livro=${idLivro}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
=======
    const url = `${URLBASE}/router.php?acao=comentarios&id_livro=${ID_LIVRO}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor: ' + response.status);
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            }
            return response.json();
        })
        .then(data => {
<<<<<<< HEAD
            if (data.erro) {
                console.error('Erro ao carregar comentários:', data.erro);
=======
            console.log('Dados recebidos:', data);
            
            if (data.erro) {
                console.error('Erro ao carregar comentários:', data.erro);
                mostrarMensagemContainer('Erro ao carregar comentários.');
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
                return;
            }
            
            const container = document.getElementById('reviewsContainer');
            container.innerHTML = '';
            
<<<<<<< HEAD
            if (data.length === 0) {
                console.log('Nenhum comentário encontrado');
                return;
            }
            
            data.forEach(comentario => {
                adicionarComentarioNaTela(comentario);
            });
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        });
}

=======
            // Novo formato: data.comentarios e data.estatisticas
            const comentarios = data.comentarios || [];
            const stats = data.estatisticas || { total_avaliacoes: 0, media_estrelas: 0 };
            
            if (comentarios.length === 0) {
                mostrarMensagemContainer('Nenhum comentário ainda. Seja o primeiro a avaliar!');
                atualizarMediaAvaliacoes(0, 0);
                return;
            }
            
            // Adicionar cada comentário na tela
            comentarios.forEach(comentario => {
                adicionarComentarioNaTela(comentario);
            });
            
            // Atualizar média com dados do backend
            atualizarMediaAvaliacoes(stats.media_estrelas, stats.total_avaliacoes);
            
            // Log das estatísticas
            console.log(`Média: ${stats.media_estrelas} estrelas | Total: ${stats.total_avaliacoes} avaliações`);
            console.log('Distribuição:', stats.distribuicao);
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            mostrarMensagemContainer('Erro ao carregar comentários. Tente novamente mais tarde.');
        });
}

// Função para mostrar mensagem no container
function mostrarMensagemContainer(mensagem) {
    const container = document.getElementById('reviewsContainer');
    container.innerHTML = `<p style="text-align: center; color: #666; padding: 20px;">${mensagem}</p>`;
}

// Função para atualizar a média de avaliações na parte superior
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

>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
// Função para adicionar comentário na tela
function adicionarComentarioNaTela(comentario) {
    const template = document.getElementById('commentTemplate');
    const novoComentario = template.cloneNode(true);
    novoComentario.style.display = 'block';
    novoComentario.id = '';

    novoComentario.querySelector('.commentTitulo').textContent = comentario.nome || 'Usuário';
    novoComentario.querySelector('.commentConteudo').textContent = comentario.comentario;

    // Formatar data
    let dataFormatada;
    if (comentario.data_comentario) {
        const data = new Date(comentario.data_comentario);
        dataFormatada = data.toLocaleDateString('pt-BR');
    } else {
        dataFormatada = new Date().toLocaleDateString('pt-BR');
    }
    novoComentario.querySelector('.commentUserinfo').textContent = `Feito em: ${dataFormatada}`;

    // Escolher imagem de estrelas baseado na avaliação
    const estrelas = novoComentario.querySelector('.estrela-placeholder');
    const avaliacaoNum = parseInt(comentario.avaliacao) || 1;
<<<<<<< HEAD
    estrelas.src = `/BibliotecaSenac/projeto/public/assets/icons/estrelas${avaliacaoNum}.png`;

    // Adicionar no container
    const container = document.getElementById('reviewsContainer');
    container.insertBefore(novoComentario, container.firstChild);
=======
    estrelas.src = `${URLBASE}/public/assets/icons/estrelas${avaliacaoNum}.png`;

    // Adicionar no container
    const container = document.getElementById('reviewsContainer');
    container.appendChild(novoComentario);
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
}

// Enviar novo comentário
document.addEventListener('DOMContentLoaded', function () {
    const btnEnviar = document.getElementById('comentario-botao');
    
<<<<<<< HEAD
    btnEnviar.addEventListener('click', function () {
=======
    if (!btnEnviar) {
        console.log('Botão de enviar não encontrado (usuário não logado)');
        return;
    }
    
    btnEnviar.addEventListener('click', function () {
        // Verificar se usuário está logado
        if (!USUARIO_LOGADO) {
            alert('Você precisa estar logado para comentar!');
            window.location.href = `${URLBASE}/src/views/usuario/login.php`;
            return;
        }
        
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
        const comentarioInput = document.getElementById('comentario-input');
        const avaliacaoInput = document.getElementById('rating-value');
        
        const comentario = comentarioInput.value.trim();
        const avaliacao = parseInt(avaliacaoInput.value);
        
        // Validações
        if (comentario === '') {
            alert('Por favor, escreva um comentário!');
<<<<<<< HEAD
=======
            comentarioInput.focus();
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            return;
        }
        
        if (avaliacao === 0 || isNaN(avaliacao)) {
<<<<<<< HEAD
            alert('Por favor, selecione uma avaliação!');
            return;
        }
        
        const idUsuario = obterIdUsuario();
        const idLivro = obterIdLivro();
        
        // Preparar dados para enviar
        const dados = {
            id_usuario: idUsuario,
            id_livro: idLivro,
=======
            alert('Por favor, selecione uma avaliação (clique nas estrelas)!');
            return;
        }
        
        // Preparar dados para enviar
        const dados = {
            id_usuario: ID_USUARIO,
            id_livro: ID_LIVRO,
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            comentario: comentario,
            avaliacao: avaliacao
        };
        
<<<<<<< HEAD
        // Enviar para o backend
        fetch('/BibliotecaSenac/projeto/src/views/usuario/comentarios.php', {
=======
        console.log('Enviando comentário:', dados);
        
        // Desabilitar botão durante envio
        btnEnviar.disabled = true;
        btnEnviar.textContent = 'Enviando...';
        
        // Enviar para o backend
        fetch(`${URLBASE}/router.php?acao=comentarios`, {
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dados)
        })
        .then(response => {
            if (!response.ok) {
<<<<<<< HEAD
                throw new Error('Erro na resposta do servidor');
=======
                throw new Error('Erro na resposta do servidor: ' + response.status);
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            }
            return response.json();
        })
        .then(data => {
<<<<<<< HEAD
=======
            console.log('Resposta do servidor:', data);
            
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            if (data.sucesso) {
                alert('Comentário enviado com sucesso!');
                
                // Limpar formulário
                comentarioInput.value = '';
                avaliacaoInput.value = '0';
                
                // Resetar estrelas
                document.querySelectorAll('.estrela-input').forEach(e => e.classList.remove('active'));
                
                // Recarregar comentários
                carregarComentarios();
            } else {
<<<<<<< HEAD
                alert('Erro ao enviar comentário: ' + (data.erro || 'Erro desconhecido'));
=======
                alert('Erro ao enviar comentário: ' + (data.erro || data.mensagem || 'Erro desconhecido'));
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
            }
        })
        .catch(error => {
            console.error('Erro:', error);
<<<<<<< HEAD
            alert('Erro ao enviar comentário. Verifique o console para mais detalhes.');
=======
            alert('Erro ao enviar comentário. Verifique sua conexão e tente novamente.');
        })
        .finally(() => {
            // Reabilitar botão
            btnEnviar.disabled = false;
            btnEnviar.textContent = 'Enviar';
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
        });
    });
});