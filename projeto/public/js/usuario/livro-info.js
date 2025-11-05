let exemplarFechado = true;

function alternarExemplar() {
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarFechado) {
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Minus Math.png";
        containerExemplarAberto.style.display = "block";
        exemplarFechado = false;
    } else {
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Plus Math.png";
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
    form.addEventListener('reset', () => {
        rankAtual = 0;
        valorDeRanqueamento.value = 0;
        atualizarEstrelas(rankAtual);
    });
});

// Carregar comentários ao abrir a página
document.addEventListener('DOMContentLoaded', function () {
    carregarComentarios();
});

// Função para carregar comentários do backend
function carregarComentarios() {
    const idLivro = obterIdLivro();
    
    fetch(`/BibliotecaSenac/projeto/src/views/usuario/comentarios.php?id_livro=${idLivro}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.erro) {
                console.error('Erro ao carregar comentários:', data.erro);
                return;
            }
            
            const container = document.getElementById('reviewsContainer');
            container.innerHTML = '';
            
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
    estrelas.src = `/BibliotecaSenac/projeto/public/assets/icons/estrelas${avaliacaoNum}.png`;

    // Adicionar no container
    const container = document.getElementById('reviewsContainer');
    container.insertBefore(novoComentario, container.firstChild);
}

// Enviar novo comentário
document.addEventListener('DOMContentLoaded', function () {
    const btnEnviar = document.getElementById('comentario-botao');
    
    btnEnviar.addEventListener('click', function () {
        const comentarioInput = document.getElementById('comentario-input');
        const avaliacaoInput = document.getElementById('rating-value');
        
        const comentario = comentarioInput.value.trim();
        const avaliacao = parseInt(avaliacaoInput.value);
        
        // Validações
        if (comentario === '') {
            alert('Por favor, escreva um comentário!');
            return;
        }
        
        if (avaliacao === 0 || isNaN(avaliacao)) {
            alert('Por favor, selecione uma avaliação!');
            return;
        }
        
        const idUsuario = obterIdUsuario();
        const idLivro = obterIdLivro();
        
        // Preparar dados para enviar
        const dados = {
            id_usuario: idUsuario,
            id_livro: idLivro,
            comentario: comentario,
            avaliacao: avaliacao
        };
        
        // Enviar para o backend
        fetch('/BibliotecaSenac/projeto/src/views/usuario/comentarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dados)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }
            return response.json();
        })
        .then(data => {
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
                alert('Erro ao enviar comentário: ' + (data.erro || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar comentário. Verifique o console para mais detalhes.');
        });
    });
});