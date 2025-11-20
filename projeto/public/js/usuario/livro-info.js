// public/js/usuario/livro-info.js

document.addEventListener('DOMContentLoaded', function() {
    // Inicializa com exemplares fechados
    const containerExemplar = document.getElementById('containerExemplarOpen');
    if (containerExemplar) {
        containerExemplar.style.display = 'none';
    }
    
    carregarDadosLivro();
});

/**
 * Carrega todos os dados do livro via AJAX
 */
async function carregarDadosLivro() {
    try {
        const response = await fetch(`${URLBASE}/router.php?acao=buscarLivroDetalhes&id=${ID_LIVRO}`);
        const data = await response.json();

        if (!data.sucesso) {
            mostrarErro(data.mensagem || 'Erro ao carregar livro: Dados de retorno inválidos.');
            return;
        }

        // Preenche os dados na página
        preencherDadosLivro(data.livro);
        preencherExemplares(data.exemplares);
        preencherBotaoAcao(data.disponibilidade);
        preencherAvaliacoes(data.avaliacoes);

    } catch (error) {
        console.error('Erro ao carregar livro (Fetch Error):', error);
        mostrarErro('Erro de conexão ao carregar informações do livro.');
    }
}

/**
 * Preenche dados básicos do livro
 */
function preencherDadosLivro(livro) {
    // Título da página
    if (livro.titulo && document.getElementById('pageTitle')) {
        document.getElementById('pageTitle').textContent = `${livro.titulo} - Biblioteca SENAC`;
    }

    // Preenche campos, verificando se o elemento existe
    if (document.getElementById('livroTitulo')) {
        document.getElementById('livroTitulo').textContent = livro.titulo || 'Título não informado';
    }
    if (document.getElementById('livroIsbn')) {
        document.getElementById('livroIsbn').textContent = `ISBN: ${livro.isbn || 'Não informado'}`;
    }
    
    // LINHA CRÍTICA: REMOVIDO O FALLBACK. 
    // AGORA SÓ USA O QUE VEM DA API.
    if (document.getElementById('livroFoto')) {
        document.getElementById('livroFoto').src = livro.foto; 
    }
    
    if (document.getElementById('livroDescricao')) {
        document.getElementById('livroDescricao').textContent = livro.descricao || livro.resumo || 'Sem descrição disponível';
    }
    if (document.getElementById('livroAutor')) {
        document.getElementById('livroAutor').textContent = livro.autor || 'Autor desconhecido';
    }
    if (document.getElementById('livroPublicacao')) {
        document.getElementById('livroPublicacao').textContent = livro.editora 
            ? `${livro.editora}, ${livro.data_publicacao || ''}` 
            : 'Não informado';
    }
    if (document.getElementById('livroPaginas')) {
        document.getElementById('livroPaginas').textContent = livro.numero_paginas || 'Não informado';
    }
}

/**
 * Preenche tabela de exemplares
 */
function preencherExemplares(exemplares) {
    const container = document.getElementById('containerExemplarOpen');
    if (!container) return; 

    container.innerHTML = '';

    if (!exemplares || exemplares.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 20px; color: #666;">Nenhum exemplar cadastrado</p>';
        return;
    }

    // Cria os cabeçalhos da tabela uma vez
    const headerGrid = `
        <div class="containerGrid">
            <div class="gridA"><u><b>Unidade</b></u></div>
            <div class="gridA"><b>Exemplares</b></div>
            <div class="gridA"><b>Disponível</b></div>
            <div class="gridA"><b>Emprestados</b></div>
            <div class="gridA"><b>Reservados</b></div>
        </div>
    `;
    container.innerHTML += headerGrid;

    // Adiciona as linhas de dados
    exemplares.forEach(ex => {
        const grid = document.createElement('div');
        grid.className = 'containerGrid';
        grid.innerHTML = `
            <div class="gridB">${ex.unidade || 'N/A'}</div>
            <div class="gridB">${ex.quantidade_total || 0}</div>
            <div class="gridB">${ex.quantidade_disponivel || 0}</div>
            <div class="gridB">${ex.quantidade_emprestada || 0}</div>
            <div class="gridB">${ex.quantidade_reservada || 0}</div>
        `;
        container.appendChild(grid);
    });
}

/**
 * Define o botão de ação (Reservar ou Solicitar Empréstimo)
 */
function preencherBotaoAcao(disponibilidade) {
    const statusEl = document.getElementById('statusDisponibilidade');
    const botaoEl = document.getElementById('botaoAcao');

    if (!statusEl || !botaoEl) return; 

    botaoEl.onclick = null;
    botaoEl.disabled = false;
    
    if (disponibilidade.total_disponivel > 0) {
        statusEl.textContent = 'Disponível';
        statusEl.style.color = '#28a745';
        
        botaoEl.textContent = 'Solicitar Empréstimo';
        botaoEl.className = 'btn-disponivel';
        botaoEl.onclick = solicitarEmprestimo;
        
    } else if (disponibilidade.total_exemplares > 0) {
        statusEl.textContent = 'Indisponível';
        statusEl.style.color = '#dc3545';
        
        botaoEl.textContent = 'Entrar na Fila de Reserva';
        botaoEl.className = 'btn-indisponivel';
        botaoEl.onclick = entrarNaFila;
        
    } else {
        statusEl.textContent = 'Sem exemplares';
        statusEl.style.color = '#666';
        
        botaoEl.textContent = 'Indisponível';
        botaoEl.disabled = true;
        botaoEl.className = 'btn-disabled';
    }
}

/**
 * Preenche seção de avaliações
 */
function preencherAvaliacoes(avaliacoes) {
    const container = document.getElementById('reviewsContainer');
    const totalReviewsEl = document.getElementById('totalReviews');

    if (!container) return; 
    
    if (totalReviewsEl) {
        totalReviewsEl.textContent = avaliacoes ? avaliacoes.length : 0;
    }
    
    if (!avaliacoes || avaliacoes.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum comentário ainda. Seja o primeiro!</p>';
        return;
    }

    container.innerHTML = '';
    avaliacoes.forEach(av => {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'comment_2';
        commentDiv.innerHTML = `
            <div class="commentName">
                <div class="estrela-placeholder-container">
                    <img class="estrela-placeholder" src="${URLBASE}/public/assets/icons/estrelas${av.nota || 1}.png" alt="Avaliação ${av.nota} estrelas">
                </div>
                <h3 class="commentTitulo">${av.usuario_nome || 'Usuário Anônimo'}</h3>
            </div>
            <p class="commentUserinfo">Feito em: ${formatarData(av.data_avaliacao)}</p>
            <p class="commentConteudo">${av.comentario || 'Sem comentário.'}</p>
        `;
        container.appendChild(commentDiv);
    });
}

function solicitarEmprestimo() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para solicitar empréstimo!');
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }
    alert('Funcionalidade em desenvolvimento: Solicitar Empréstimo');
}

function entrarNaFila() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para entrar na fila!');
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }
    alert('Funcionalidade em desenvolvimento: Fila de Reserva');
}

function alternarExemplar() {
    const container = document.getElementById('containerExemplarOpen');
    const icone = document.getElementById('abrirExemplares');
    
    if (!container || !icone) return;

    if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';
        icone.style.transform = 'rotate(45deg)';
    } else {
        container.style.display = 'none';
        icone.style.transform = 'rotate(0deg)';
    }
}

function formatarData(dataString) {
    if (!dataString) return 'Data desconhecida';
    const data = new Date(dataString.replace(/-/g, '/')); 
    
    if (isNaN(data.getTime())) return 'Data inválida';

    return data.toLocaleDateString('pt-BR');
}

function mostrarErro(mensagem) {
    const container = document.querySelector('.containerConteudo');
    if (container) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px; min-height: 50vh;">
                <p style="color: #dc3545; font-size: 1.2rem; margin-bottom: 20px;">Ops! Algo deu errado ao carregar:</p>
                <p style="color: #6c757d; margin-bottom: 30px;">${mensagem}</p>
                <button onclick="window.history.back()" style="padding: 10px 20px; background: #004A90; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Voltar
                </button>
            </div>
        `;
    } else {
        alert(`Erro: ${mensagem}`);
    }
}