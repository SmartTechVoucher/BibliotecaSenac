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
    
    // Foto do livro
    if (document.getElementById('livroFoto')) {
        document.getElementById('livroFoto').src = livro.foto || ''; 
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

    // Preenche as tags (categoria)
    preencherTags(livro.categoria);
}

/**
 * Preenche as tags da categoria do livro
 */
function preencherTags(categoria) {
    const tagsContainer = document.querySelector('.tags');
    const tagsLista = document.querySelector('.tags2');
    
    if (!tagsContainer || !tagsLista) return;

    // Limpa tags existentes
    tagsLista.innerHTML = '';

    if (categoria && categoria !== 'Sem categoria') {
        // Mostra o container de tags
        tagsContainer.style.display = 'block';
        
        // Cria a tag da categoria
        const tagDiv = document.createElement('div');
        tagDiv.className = 'tag_icone';
        tagDiv.innerHTML = `<p>${categoria}</p>`;
        tagsLista.appendChild(tagDiv);
    } else {
        // Esconde o container se não houver categoria
        tagsContainer.style.display = 'none';
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

    // Adiciona as linhas de dados (CORRIGIDO: sem header duplicado)
    exemplares.forEach((ex, index) => {
        const grid = document.createElement('div');
        grid.className = 'containerGrid';
        
        // Primeira linha tem o header
        if (index === 0) {
            grid.innerHTML = `
                <div class="gridA"><u><b>Unidade</b></u></div>
                <div class="gridA"><b>Exemplares</b></div>
                <div class="gridA"><b>Disponível</b></div>
                <div class="gridA"><b>Emprestados</b></div>
                <div class="gridA"><b>Reservados</b></div>

                <div class="gridB">${ex.unidade || 'N/A'}</div>
                <div class="gridB">${ex.quantidade_total || 0}</div>
                <div class="gridB">${ex.quantidade_disponivel || 0}</div>
                <div class="gridB">${ex.quantidade_emprestada || 0}</div>
                <div class="gridB">${ex.quantidade_reservada || 0}</div>
            `;
        } else {
            // Demais linhas sem header
            grid.innerHTML = `
                <div class="gridB">${ex.unidade || 'N/A'}</div>
                <div class="gridB">${ex.quantidade_total || 0}</div>
                <div class="gridB">${ex.quantidade_disponivel || 0}</div>
                <div class="gridB">${ex.quantidade_emprestada || 0}</div>
                <div class="gridB">${ex.quantidade_reservada || 0}</div>
            `;
        }
        
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

    // Limpa onclick anterior e habilita botão
    botaoEl.onclick = null;
    botaoEl.disabled = false;
    botaoEl.removeAttribute('class');
    
    if (disponibilidade.total_disponivel > 0) {
        // Livro DISPONÍVEL
        statusEl.textContent = 'Disponível';
        statusEl.style.color = '#28a745';
        
        botaoEl.textContent = 'Reservar';
        botaoEl.style.backgroundColor = '#FF8C00'; // Laranja igual sua imagem
        botaoEl.style.color = 'white';
        botaoEl.style.border = 'none';
        botaoEl.style.padding = '12px 40px';
        botaoEl.style.borderRadius = '10px';
        botaoEl.style.cursor = 'pointer';
        botaoEl.style.fontSize = '16px';
        botaoEl.style.fontWeight = '600';
        botaoEl.style.transition = 'all 0.3s ease';
        botaoEl.onclick = solicitarEmprestimo;
        
        // Hover effect
        botaoEl.onmouseenter = function() {
            this.style.backgroundColor = '#FF8C00';
            this.style.transform = 'scale(1.05)';
        };
        botaoEl.onmouseleave = function() {
            this.style.backgroundColor = '#FF8C00';
            this.style.transform = 'scale(1)';
        };
        
    } else if (disponibilidade.total_exemplares > 0) {
        // Livro INDISPONÍVEL - Fila de Reserva
        statusEl.textContent = 'Indisponível';
        statusEl.style.color = '#dc3545';
        
        botaoEl.textContent = 'Entrar na Fila';
        botaoEl.style.backgroundColor = '#003162'; // Amarelo
        botaoEl.style.color = '#ffffffff';
        botaoEl.style.border = 'none';
        botaoEl.style.padding = '12px 40px';
        botaoEl.style.borderRadius = '10px';
        botaoEl.style.cursor = 'pointer';
        botaoEl.style.fontSize = '16px';
        botaoEl.style.fontWeight = '600';
        botaoEl.style.transition = 'all 0.3s ease';
        botaoEl.onclick = entrarNaFila;
        
        botaoEl.onmouseenter = function() {
            this.style.backgroundColor = '#FF8C00';
            this.style.transform = 'scale(1.05)';
        };
        botaoEl.onmouseleave = function() {
            this.style.backgroundColor = '#FF8C00';
            this.style.transform = 'scale(1)';
        };
        
    } else {
        // SEM EXEMPLARES
        statusEl.textContent = 'Sem exemplares';
        statusEl.style.color = '#666';
        
        botaoEl.textContent = 'Indisponível';
        botaoEl.disabled = true;
        botaoEl.style.backgroundColor = '#ccc';
        botaoEl.style.color = '#666';
        botaoEl.style.border = 'none';
        botaoEl.style.padding = '12px 40px';
        botaoEl.style.borderRadius = '10px';
        botaoEl.style.cursor = 'not-allowed';
        botaoEl.style.fontSize = '16px';
        botaoEl.style.fontWeight = '600';
        botaoEl.onmouseenter = null;
        botaoEl.onmouseleave = null;
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

/**
 * Solicitar empréstimo/reserva
 */
async function solicitarEmprestimo() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para reservar um livro!');
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }

    // Confirma com o usuário
    if (!confirm('Deseja solicitar o empréstimo deste livro?\n\nVocê terá 48 horas para retirá-lo na biblioteca.')) {
        return;
    }

    // Desabilita botão durante requisição
    const botao = document.getElementById('botaoAcao');
    const textoOriginal = botao.textContent;
    botao.disabled = true;
    botao.textContent = 'Processando...';

    try {
        const formData = new FormData();
        formData.append('id_livro', ID_LIVRO);

        console.log('Enviando requisição para:', `${URLBASE}/router.php?acao=solicitarEmprestimo`);
        console.log('ID do livro:', ID_LIVRO);

        const response = await fetch(`${URLBASE}/router.php?acao=solicitarEmprestimo`, {
            method: 'POST',
            body: formData
        });

        console.log('Status da resposta:', response.status);
        
        // Pega o texto bruto primeiro para debug
        const textResponse = await response.text();
        console.log('Resposta bruta:', textResponse);

        // Tenta parsear como JSON
        let data;
        try {
            data = JSON.parse(textResponse);
        } catch (parseError) {
            console.error('Erro ao parsear JSON:', parseError);
            console.error('Resposta recebida:', textResponse.substring(0, 500));
            throw new Error('Resposta inválida do servidor. Verifique o console para detalhes.');
        }

        if (data.sucesso) {
            // Mostra modal de sucesso
            mostrarModalSucesso(
                'Empréstimo Solicitado!',
                data.mensagem,
                data.data_limite_retirada ? 
                    'Você tem até ' + formatarDataHora(data.data_limite_retirada) + ' para retirar o livro na biblioteca.' :
                    'Retire o livro na biblioteca em até 48 horas.'
            );
            
            // Aguarda 2 segundos e recarrega a página
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            alert('Erro: ' + data.mensagem);
            botao.disabled = false;
            botao.textContent = textoOriginal;
        }

    } catch (error) {
        console.error('Erro ao solicitar empréstimo:', error);
        alert('Erro ao processar solicitação: ' + error.message);
        botao.disabled = false;
        botao.textContent = textoOriginal;
    }
}

/**
 * Entrar na fila de reserva
 */
async function entrarNaFila() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para entrar na fila!');
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }

    // Desabilita botão
    const botao = document.getElementById('botaoAcao');
    const textoOriginal = botao.textContent;
    botao.disabled = true;
    botao.textContent = 'Processando...';

    try {
        const formData = new FormData();
        formData.append('id_livro', ID_LIVRO);

        const response = await fetch(`${URLBASE}/router.php?acao=entrarNaFila`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.sucesso) {
            // Mostra modal com posição na fila
            mostrarModalFila(data.posicao, data.estimativa);
            
            // Recarrega após 3 segundos
            setTimeout(() => {
                location.reload();
            }, 3000);
        } else {
            alert('Erro: ' + data.mensagem);
            botao.disabled = false;
            botao.textContent = textoOriginal;
        }

    } catch (error) {
        console.error('Erro ao entrar na fila:', error);
        alert('Erro ao processar solicitação. Tente novamente.');
        botao.disabled = false;
        botao.textContent = textoOriginal;
    }
}

/**
 * Mostra modal de sucesso
 */
function mostrarModalSucesso(titulo, mensagem, detalhe) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        ">
            <div style="font-size: 50px; color: #28a745; margin-bottom: 20px;">✓</div>
            <h2 style="color: #28a745; margin-bottom: 15px;">${titulo}</h2>
            <p style="font-size: 16px; margin-bottom: 10px;">${mensagem}</p>
            <p style="font-size: 14px; color: #666; margin-bottom: 25px;">${detalhe}</p>
            <button onclick="this.closest('div').parentElement.remove()" style="
                background: #28a745;
                color: white;
                border: none;
                padding: 10px 30px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            ">Entendi</button>
        </div>
    `;

    document.body.appendChild(modal);

    // Fecha ao clicar fora
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

/**
 * Mostra modal com posição na fila
 */
function mostrarModalFila(posicao, estimativa) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        ">
            <div style="font-size: 50px; color: #ffc107; margin-bottom: 20px;">📋</div>
            <h2 style="color: #004A90; margin-bottom: 15px;">Você entrou na fila!</h2>
            <div style="background: #f0f0f0; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <p style="font-size: 18px; margin-bottom: 10px;">Sua posição:</p>
                <p style="font-size: 48px; font-weight: bold; color: #004A90; margin: 0;">${posicao}º</p>
            </div>
            <p style="font-size: 14px; color: #666; margin-bottom: 10px;">
                Tempo estimado de espera: <strong>${estimativa}</strong>
            </p>
            <p style="font-size: 12px; color: #999; margin-bottom: 25px;">
                Você será notificado quando o livro estiver disponível
            </p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="this.closest('div').parentElement.remove()" style="
                    background: #28a745;
                    color: white;
                    border: none;
                    padding: 10px 30px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                ">Ok, entendi</button>
                <button onclick="cancelarMinhaReserva()" style="
                    background: #dc3545;
                    color: white;
                    border: none;
                    padding: 10px 30px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                ">Cancelar Reserva</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

/**
 * Cancela a reserva do usuário
 */
async function cancelarMinhaReserva() {
    if (!confirm('Tem certeza que deseja cancelar sua reserva?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('id_livro', ID_LIVRO);

        const response = await fetch(`${URLBASE}/router.php?acao=cancelarReserva`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.sucesso) {
            alert(data.mensagem);
            location.reload();
        } else {
            alert('Erro: ' + data.mensagem);
        }

    } catch (error) {
        console.error('Erro ao cancelar reserva:', error);
        alert('Erro ao processar solicitação.');
    }
}

/**
 * Formata data e hora
 */
function formatarDataHora(dataString) {
    if (!dataString) return 'Data desconhecida';
    const data = new Date(dataString);
    return data.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Alterna visibilidade dos exemplares
 */
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

/**
 * Formata data para exibição
 */
function formatarData(dataString) {
    if (!dataString) return 'Data desconhecida';
    const data = new Date(dataString.replace(/-/g, '/')); 
    
    if (isNaN(data.getTime())) return 'Data inválida';

    return data.toLocaleDateString('pt-BR');
}

/**
 * Mostra mensagem de erro
 */
function mostrarErro(mensagem) {
    const container = document.querySelector('.containerConteudo');
    if (container) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px; min-height: 50vh;">
                <p style="color: #dc3545; font-size: 1.2rem; margin-bottom: 20px;">Ops! Algo deu errado:</p>
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