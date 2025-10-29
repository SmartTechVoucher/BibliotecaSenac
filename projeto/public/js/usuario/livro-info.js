let exemplarFechado = true
function alternarExemplar(){
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarFechado){
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Minus Math.png"
        containerExemplarAberto.style.display ="block";
        exemplarFechado = false;
    }
        
    else{
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Plus Math.png"
        containerExemplarAberto.style.display = "none";
        exemplarFechado = true;
    }
}

function reservaConcluida(){
    const botaoReserva = document.getElementById('botaoReserva');
    const estadoAtual = botaoReserva.getAttribute('data-status');
    if (estadoAtual == "livre"){
        botaoReserva.setAttribute('data-status', 'reservado');
        botaoReserva.textContent = "Livro Reservado"
        botaoReserva.style.background = "#F68B1F";
    }
    else{
        const confirmarCancelamento = window.confirm("Você realmente quer cancelar a reserva?");
        if(confirmarCancelamento){
            botaoReserva.setAttribute('data-status', 'livre');
            botaoReserva.textContent = "Reservar"
            botaoReserva.style.background = "#004A90";
        }
    }
        

}

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
            // Define a classificação ao clicar
            rankAtual = estrela.dataset.value;
            valorDeRanqueamento.value = rankAtual; 
            atualizarEstrelas(rankAtual); 

            console.log(`Avaliação modificada para: ${rankAtual}`); 
        });
    });

    
    const form = document.getElementById('commentForm');
    form.addEventListener('reset', () => {
        rankAtual = 0;
        valorDeRanqueamento.value = 0;
        atualizarEstrelas(rankAtual);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const cloneBtn = document.getElementById('comentario-botao'); 
    const conteinerDeRevisoes = document.getElementById('reviewsContainer');

    cloneBtn.addEventListener('click', function () {
        const nomeDeUsuario = "Cristiano Ronaldo";
        const comentario = document.getElementById('comentario-input').value;
        const avaliacao = document.getElementById('rating-value').value;
        console.log(avaliacao)
        // clonar o template
        const template = document.getElementById('commentTemplate');
        const novoComentario = template.cloneNode(true);
        novoComentario.style.display = 'block'; 
        novoComentario.id = ''; 

    
        novoComentario.querySelector('.commentTitulo').textContent = nomeDeUsuario;
        novoComentario.querySelector('.commentConteudo').textContent = comentario;

        // pega a data atual
        const hoje = new Date();
        const dataFormatada = hoje.toLocaleDateString('pt-BR');
        novoComentario.querySelector('.commentUserinfo').textContent = `Feito em: ${dataFormatada}`;

        // escolhe uma das 4 variacao de img de acordo com a nota do usuário
        const estrelas =  novoComentario.querySelector('.estrela-placeholder');
        estrelas.src = `../../../../projeto/public/assets/icons/estrelas${avaliacao}.png`; 
        console.log(estrelas.src)

        // Adicionar no container
        conteinerDeRevisoes.insertBefore(novoComentario, conteinerDeRevisoes.firstChild);
    });
});
let avaliacaoSelecionada = 0;

// Função para alternar exemplares
function alternarExemplar() {
    const container = document.getElementById('containerExemplarOpen');
    const botao = document.getElementById('abrirExemplares');
    
    if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

// Sistema de avaliação por estrelas
document.addEventListener('DOMContentLoaded', function() {
    const estrelas = document.querySelectorAll('.estrela-input');
    const ratingValue = document.getElementById('rating-value');
    
    estrelas.forEach((estrela, index) => {
        // Hover effect
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
    
    // Remove highlight quando sai do mouse
    document.querySelector('.inputRating').addEventListener('mouseleave', function() {
        highlightStars(avaliacaoSelecionada);
    });
    
    // Carregar comentários ao iniciar
    carregarComentarios();
    
    // Event listener para enviar comentário
    document.getElementById('comentario-botao').addEventListener('click', enviarComentario);
});

// Função para destacar estrelas
function highlightStars(count) {
    const estrelas = document.querySelectorAll('.estrela-input');
    estrelas.forEach((estrela, index) => {
        if (index < count) {
            estrela.style.opacity = '1';
            estrela.style.filter = 'brightness(1.2)';
        } else {
            estrela.style.opacity = '0.5';
            estrela.style.filter = 'brightness(0.8)';
        }
    });
}

// Função para enviar comentário
async function enviarComentario() {
    const comentarioInput = document.getElementById('comentario-input');
    const ratingValue = document.getElementById('rating-value');
    const livroId = document.getElementById('livro-id');
    const botao = document.getElementById('comentario-botao');
    
    const comentario = comentarioInput.value.trim();
    const avaliacao = parseInt(ratingValue.value);
    
    // Validações
    if (avaliacao === 0) {
        alert('Por favor, selecione uma avaliação (estrelas)');
        return;
    }
    
    if (comentario === '') {
        alert('Por favor, escreva um comentário');
        return;
    }
    
    // Desabilita o botão durante o envio
    botao.disabled = true;
    botao.textContent = 'Enviando...';
    
    try {
        const formData = new FormData();
        formData.append('livro_id', livroId.value);
        formData.append('comentario', comentario);
        formData.append('avaliacao', avaliacao);
        
        const response = await fetch(`${URLBASE}/app/backend/comentarios/salvar_comentario.php`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            
            // Limpa o formulário
            comentarioInput.value = '';
            ratingValue.value = '0';
            avaliacaoSelecionada = 0;
            highlightStars(0);
            
            // Recarrega os comentários
            carregarComentarios();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro ao enviar comentário:', error);
        alert('Erro ao enviar comentário. Tente novamente.');
    } finally {
        // Reabilita o botão
        botao.disabled = false;
        botao.textContent = 'Enviar';
    }
}

// Função para carregar comentários
async function carregarComentarios() {
    try {
        const response = await fetch(`${URLBASE}/app/backend/comentarios/buscar_comentarios.php?livro_id=${LIVRO_ID}`);
        const data = await response.json();
        
        if (data.success) {
            // Atualiza a contagem e média de reviews
            atualizarReviewStats(data.stats);
            
            // Renderiza os comentários
            renderizarComentarios(data.comentarios);
        }
    } catch (error) {
        console.error('Erro ao carregar comentários:', error);
    }
}

// Função para atualizar estatísticas de reviews
function atualizarReviewStats(stats) {
    const reviewCount = document.getElementById('reviewCount');
    const reviewStars = document.getElementById('reviewStars');
    
    if (reviewCount) {
        reviewCount.textContent = `${stats.total} reviews`;
    }
    
    if (reviewStars && stats.media > 0) {
        const mediaArredondada = Math.round(stats.media);
        reviewStars.src = `${URLBASE}/public/assets/icons/estrelas${mediaArredondada}.png`;
    }
}

// Função para renderizar comentários
function renderizarComentarios(comentarios) {
    const container = document.getElementById('reviewsContainer');
    container.innerHTML = '';
    
    comentarios.forEach(comentario => {
        const comentarioDiv = criarElementoComentario(comentario);
        container.appendChild(comentarioDiv);
    });
}

// Função para criar elemento de comentário
function criarElementoComentario(comentario) {
    const div = document.createElement('div');
    div.className = 'comment_2';
    
    div.innerHTML = `
        <div class="commentName">
            <div class="estrela-placeholder-container">
                <img class="estrela-placeholder" src="${URLBASE}/public/assets/icons/estrelas${comentario.avaliacao}.png" alt="">
            </div>
            <h3 class="commentTitulo">${escapeHtml(comentario.nome_usuario)}</h3>
        </div>
        <p class="commentUserinfo">Feito em: ${comentario.data_formatada}</p>
        <p class="commentConteudo">${escapeHtml(comentario.comentario)}</p>
    `;
    
    return div;
}

// Função para escapar HTML (segurança)
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Função de reserva (mantida do código original)
function reservaConcluida() {
    alert('Reserva concluída com sucesso!');
}
