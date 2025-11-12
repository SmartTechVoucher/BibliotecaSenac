// --- VARIÁVEIS GLOBAIS ---
let avaliacaoSelecionada = 0;

// 🚨 CORREÇÃO: Tenta ler as variáveis globais que DEVEM ser definidas no PHP (livro-info.php)
// Se não estiverem definidas, define como null para evitar o ReferenceError
const LIVRO_ID_GLOBAL = typeof ID_LIVRO !== 'undefined' ? ID_LIVRO : (typeof LIVRO_ID !== 'undefined' ? LIVRO_ID : null);
const USUARIO_ID_GLOBAL = typeof ID_USUARIO !== 'undefined' ? ID_USUARIO : null;
// A variável URLBASE também deve ser definida no seu PHP


// --- FUNÇÕES DE UTILIDADE GERAL E UI ---

/**
 * Alterna a exibição do container de exemplares (Open/Close).
 */
function alternarExemplar() {
    const container = document.getElementById('containerExemplarOpen');
    const botao = document.getElementById('abrirExemplares');
    
    const estaFechado = container.style.display === 'none' || container.style.display === '';

    if (estaFechado) {
        // Assume que URLBASE está definido (Ex: http://localhost/BibliotecaSenac/projeto)
        if (botao) botao.src = URLBASE + "/public/assets/icons/Minus Math.png";
        container.style.display = 'block';
    } else {
        if (botao) botao.src = URLBASE + "/public/assets/icons/Plus Math.png";
        container.style.display = 'none';
    }
}

/**
 * Lógica de reserva/cancelamento de reserva.
 */
function reservaConcluida() {
    const botaoReserva = document.getElementById('botaoReserva');
    if (!botaoReserva) return;
    
    const estadoAtual = botaoReserva.getAttribute('data-status');
    
    // 🚨 ADICIONAR LÓGICA DE FETCH AQUI PARA O BACKEND
    
    if (estadoAtual === "livre") {
        // Reserva
        botaoReserva.setAttribute('data-status', 'reservado');
        botaoReserva.textContent = "Livro Reservado";
        botaoReserva.style.background = "#F68B1F";
    } else {
        // Cancelamento
        const confirmarCancelamento = window.confirm("Você realmente quer cancelar a reserva?");
        if (confirmarCancelamento) {
            botaoReserva.setAttribute('data-status', 'livre');
            botaoReserva.textContent = "Reservar";
            botaoReserva.style.background = "#004A90";
        }
    }
}

/**
 * Função para escapar HTML (segurança contra XSS) em strings antes de injetar no DOM.
 */
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
}


// --- LÓGICA DE AVALIAÇÃO (ESTRELAS) ---

/**
 * Destaca visualmente as estrelas até a contagem (count) fornecida.
 */
function highlightStars(count) {
    const estrelas = document.querySelectorAll('.estrela-input');
    estrelas.forEach((estrela) => {
        const estrelaValue = parseInt(estrela.dataset.value);

        if (estrelaValue <= count) {
            estrela.classList.add('active'); 
            estrela.style.opacity = '1';
            estrela.style.filter = 'brightness(1.2)';
        } else {
            estrela.classList.remove('active');
            estrela.style.opacity = '0.5';
            estrela.style.filter = 'brightness(0.8)';
        }
    });
}


// --- LÓGICA DE COMENTÁRIOS (FETCH/RENDER) ---

/**
 * Cria o elemento HTML de um único comentário.
 */
function criarElementoComentario(comentario) {
    const div = document.createElement('div');
    div.className = 'comment_2';
    
    const dataFormatada = comentario.data_formatada || (comentario.data_comentario ? new Date(comentario.data_comentario).toLocaleDateString('pt-BR') : 'Data não informada');
    const avaliacaoNum = parseInt(comentario.avaliacao) || 0;
    
    div.innerHTML = `
        <div class="commentName">
            <div class="estrela-placeholder-container">
                <img class="estrela-placeholder" 
                     src="${URLBASE}/public/assets/icons/estrelas${avaliacaoNum}.png" 
                     alt="Avaliação de ${avaliacaoNum} estrelas">
            </div>
            <h3 class="commentTitulo">${escapeHtml(comentario.nome_usuario || comentario.nome || 'Usuário Anônimo')}</h3>
        </div>
        <p class="commentUserinfo">Feito em: ${dataFormatada}</p>
        <p class="commentConteudo">${escapeHtml(comentario.comentario)}</p>
    `;
    
    return div;
}

/**
 * Atualiza a média de avaliações e o contador no cabeçalho da seção.
 */
function atualizarReviewStats(stats) {
    const totalSpan = document.getElementById('totalReviews');
    const imgMedia = document.getElementById('avaliacaoMediaImg');

    const media = stats.media_estrelas || stats.media || 0;
    const total = stats.total_avaliacoes || stats.total || 0;
    
    if (totalSpan) {
        totalSpan.textContent = total;
    }

    if (imgMedia) {
        const mediaArredondada = Math.round(media);
        // Garante que a imagem está entre 0 e 5
        const estrelaImgIndex = Math.min(5, Math.max(0, mediaArredondada)); 
        imgMedia.src = `${URLBASE}/public/assets/icons/estrelas${estrelaImgIndex}.png`;
    }
}

/**
 * Renderiza todos os comentários no container.
 */
function renderizarComentarios(comentarios) {
    const container = document.getElementById('reviewsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!comentarios || comentarios.length === 0) {
        container.innerHTML = `<p style="text-align: center; color: #666; padding: 20px;">Nenhum comentário ainda. Seja o primeiro a avaliar!</p>`;
        atualizarReviewStats({ media: 0, total: 0 });
        return;
    }

    comentarios.forEach(comentario => {
        const comentarioDiv = criarElementoComentario(comentario);
        container.appendChild(comentarioDiv);
    });
}

/**
 * Carrega os comentários do backend (requisição GET).
 */
async function carregarComentarios() {
    try {
        // 🚨 CORRIGIDO: Usa a variável global consistente
        const livroId = LIVRO_ID_GLOBAL; 

        if (!livroId) {
            console.error('Erro: ID do livro não definido no escopo global (LIVRO_ID_GLOBAL).');
            return;
        }

        // 🟢 CORRIGIDO: Agora usa o router.php, que está configurado corretamente
        let url = `${URLBASE}/router.php?acao=comentarios&id_livro=${livroId}`;
        
        const response = await fetch(url);
        
        if (!response.ok) {
            // Se der 404, cai no catch com a mensagem de erro da requisição
            throw new Error(`Erro na resposta do servidor: ${response.status} (${response.statusText})`);
        }
        
        const data = await response.json();
        
        // Se o servidor retornar JSON de erro (ex: {erro: "...")
        if (data.erro) {
            throw new Error(data.erro);
        }

        const comentarios = data.comentarios || data.dados || [];
        // Espera a estrutura de resposta do ComentariosController
        const stats = data.estatisticas || data.stats || { media: 0, total: 0 };
        
        // Atualiza as estatísticas e renderiza
        atualizarReviewStats(stats);
        renderizarComentarios(comentarios);
        
    } catch (error) {
        console.error('Erro ao carregar comentários:', error);
        const container = document.getElementById('reviewsContainer');
        if (container) {
             container.innerHTML = `<p style="text-align: center; color: #cc0000; padding: 20px;">Erro ao carregar comentários: ${error.message || 'Falha de rede/servidor'}.</p>`;
        }
    }
}

/**
 * Envia o novo comentário e avaliação para o backend (requisição POST).
 */
async function enviarComentario() {
    // Verifica se o usuário está logado usando a variável global
    if (typeof USUARIO_LOGADO === 'undefined' || !USUARIO_LOGADO || !USUARIO_ID_GLOBAL) {
        alert('Você precisa estar logado para comentar!');
        // Redireciona e salva a página atual para voltar depois do login
        window.location.href = `${URLBASE}/router.php?acao=redirectLogin&url=${encodeURIComponent(window.location.href)}`;
        return;
    }

    const comentarioInput = document.getElementById('comentario-input');
    const ratingValue = document.getElementById('rating-value');
    const botao = document.getElementById('comentario-botao');
    
    if (!comentarioInput || !ratingValue || !botao) return;

    // 🚨 CORRIGIDO: Usa a variável global consistente
    const livroId = LIVRO_ID_GLOBAL; 
    const usuarioId = USUARIO_ID_GLOBAL;
    
    if (!livroId || !usuarioId) {
        alert('Erro interno: IDs necessários não encontrados.');
        return;
    }
    
    const comentario = comentarioInput.value.trim();
    const avaliacao = parseInt(ratingValue.value);
    
    // --- Validações ---
    if (avaliacao === 0 || isNaN(avaliacao)) {
        alert('Por favor, selecione uma avaliação (clique nas estrelas)!');
        return;
    }
    
    if (comentario === '') {
        alert('Por favor, escreva um comentário!');
        comentarioInput.focus();
        return;
    }
    
    // --- Preparação para Envio ---
    botao.disabled = true;
    botao.textContent = 'Enviando...';
    
    try {
        const dados = {
            id_usuario: usuarioId, 
            id_livro: livroId, 
            comentario: comentario,
            avaliacao: avaliacao
        };

        // 🟢 CORRIGIDO: Agora usa o router.php, que está configurado corretamente
        const response = await fetch(`${URLBASE}/router.php?acao=comentarios`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dados)
        });
        
        // 🚨 Tratamento robusto: Lê o texto primeiro para capturar erros não-JSON do PHP
        const responseText = await response.text();
        let data;

        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Erro de JSON na resposta. Resposta bruta:', responseText);
            // Isso captura o erro de SyntaxError: Unexpected token '<' (HTML de erro do PHP)
            throw new Error('Resposta do servidor não é JSON válida. Verifique seu router.php e Controller.');
        }

        if (data.sucesso) {
            alert('Comentário enviado com sucesso!');
            
            // Limpa o formulário e estrelas
            comentarioInput.value = '';
            ratingValue.value = '0';
            avaliacaoSelecionada = 0;
            highlightStars(0);
            
            // Recarrega os comentários para mostrar o novo
            await carregarComentarios(); 
        } else {
            alert('Erro ao enviar comentário: ' + (data.erro || data.mensagem || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro ao enviar comentário:', error);
        alert('Erro ao enviar comentário. Detalhes no console.');
    } finally {
        // Reabilita o botão
        botao.disabled = false;
        botao.textContent = 'Enviar';
    }
}


// --- INICIALIZAÇÃO E LISTENERS PRINCIPAIS (DOM LOADED) ---

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Configuração do sistema de avaliação por estrelas
    const estrelas = document.querySelectorAll('.estrela-input');
    const ratingValue = document.getElementById('rating-value');
    const inputRatingContainer = document.querySelector('.inputRating');
    const comentarioBotao = document.getElementById('comentario-botao');

    if (estrelas.length > 0 && ratingValue) {
        
        estrelas.forEach((estrela, index) => {
            // Mouseover (Hover)
            estrela.addEventListener('mouseenter', function() {
                highlightStars(index + 1);
            });
            
            // Click para selecionar
            estrela.addEventListener('click', function() {
                avaliacaoSelecionada = parseInt(this.getAttribute('data-value'));
                ratingValue.value = avaliacaoSelecionada;
                highlightStars(avaliacaoSelecionada);
            });
        });
        
        // Mouseleave (Sair do container)
        if (inputRatingContainer) {
            inputRatingContainer.addEventListener('mouseleave', function() {
                highlightStars(avaliacaoSelecionada);
            });
        }
        
        // Define o estado inicial da avaliação para 0
        ratingValue.value = '0';
        highlightStars(0);
    }
    
    // 2. Configuração do evento de enviar comentário
    if (comentarioBotao) {
        comentarioBotao.addEventListener('click', enviarComentario);
    }
    
    // 3. Inicia o carregamento dos comentários
    carregarComentarios();
});