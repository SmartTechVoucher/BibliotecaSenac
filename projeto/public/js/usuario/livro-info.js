// ============================================
// LIVRO-INFO.JS - SISTEMA COMPLETO UNIFICADO
// ============================================

let estrelaSelecionada = 0;
let avaliacaoEmEdicao = false;

// Variáveis globais (assumidas como definidas em outro lugar)
// Exemplo:
// const URLBASE = '/seu_backend';
// const ID_LIVRO = 1;
// const USUARIO_LOGADO = true;
// const ID_USUARIO = 123; 

// ============================================
// FUNÇÕES AUXILIARES (Definições mock para garantir a execução)
// ============================================

function mostrarMensagemSucesso(mensagem) {
    console.log('SUCESSO:', mensagem);
    // Implementação real deve atualizar o DOM para mostrar a mensagem
}

function mostrarErro(mensagem) {
    console.error('ERRO:', mensagem);
    // Implementação real deve atualizar o DOM para mostrar o erro
}

function formatarData(dataString) {
    if (!dataString) return 'N/A';
    try {
        // Exemplo de formatação simples
        const data = new Date(dataString.replace(' ', 'T') + 'Z'); // Adiciona 'Z' para tratar como UTC
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return data.toLocaleDateString('pt-BR', options);
    } catch (e) {
        return dataString.split(' ')[0].split('-').reverse().join('/'); // Formato d/m/a
    }
}

function formatarDataHora(dataHoraString) {
    if (!dataHoraString) return 'N/A';
    try {
        // Exemplo de formatação simples
        const data = new Date(dataHoraString.replace(' ', 'T') + 'Z'); // Adiciona 'Z' para tratar como UTC
        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
        return data.toLocaleTimeString('pt-BR', options);
    } catch (e) {
        return dataHoraString.replace('-', '/').replace(' ', ' às '); // Formato a/m/d às h:m:s
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, "&amp;")
               .replace(/</g, "&lt;")
               .replace(/>/g, "&gt;")
               .replace(/"/g, "&quot;")
               .replace(/'/g, "&#039;");
}

function mostrarModalSucesso(titulo, mensagem, detalhe) {
    // Implementação da função 'mostrarModalSucesso'
    console.log(`Modal Sucesso: ${titulo} - ${mensagem} (${detalhe})`);
    alert(`${titulo}\n${mensagem}\n${detalhe}`);
}

function mostrarModalFila(posicao, estimativa) {
    // Implementação da função 'mostrarModalFila'
    console.log(`Modal Fila: Posição ${posicao}, Estimativa ${estimativa}`);
    alert(`Você entrou na fila na posição ${posicao}. Estimativa de espera: ${estimativa}.`);
}


// ============================================
// INICIALIZAÇÃO
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    carregarDadosLivro();
    
    if (typeof USUARIO_LOGADO !== 'undefined' && USUARIO_LOGADO) {
        inicializarSistemaEstrelas();
        inicializarFormularioAvaliacao();
        carregarMinhaAvaliacao(); // Carrega a avaliação do usuário logado (para edição/exibição)
    }
    
    carregarAvaliacoes(); // Carrega as estatísticas e a lista de avaliações (incluindo o filtro da do usuário)
});

// ============================================
// SISTEMA DE ESTRELAS
// ============================================

function inicializarSistemaEstrelas() {
    const estrelas = document.querySelectorAll('.estrela-input');
    
    estrelas.forEach((estrela, index) => {
        // Hover effect
        estrela.addEventListener('mouseenter', () => {
            destacarEstrelas(index + 1);
        });
        
        // Click event
        estrela.addEventListener('click', () => {
            estrelaSelecionada = index + 1;
            document.getElementById('rating-value').value = estrelaSelecionada;
            destacarEstrelas(estrelaSelecionada);
            atualizarTextoEstrelas(estrelaSelecionada);
        });
    });
    
    // Reset ao sair do container
    const container = document.querySelector('.rating-container');
    if (container) {
        container.addEventListener('mouseleave', () => {
            destacarEstrelas(estrelaSelecionada);
        });
    }
}

function destacarEstrelas(quantidade) {
    const estrelas = document.querySelectorAll('.estrela-input');
    estrelas.forEach((estrela, index) => {
        if (index < quantidade) {
            estrela.classList.add('selected');
        } else {
            estrela.classList.remove('selected');
        }
    });
}

function atualizarTextoEstrelas(quantidade) {
    const texto = document.getElementById('estrelasSelecionadas');
    if (texto) {
        texto.textContent = quantidade === 1 ? '1 estrela' : `${quantidade} estrelas`;
    }
}

// ============================================
// FORMULÁRIO DE AVALIAÇÃO
// ============================================

function inicializarFormularioAvaliacao() {
    const botaoEnviar = document.getElementById('comentario-botao');
    const botaoCancelar = document.getElementById('cancelar-edicao');
    
    if (botaoEnviar) {
        botaoEnviar.addEventListener('click', salvarAvaliacao);
    }
    
    if (botaoCancelar) {
        botaoCancelar.addEventListener('click', cancelarEdicao);
    }
}

async function salvarAvaliacao() {
    const estrelas = parseInt(document.getElementById('rating-value').value);
    const comentario = document.getElementById('comentario-input').value.trim();
    // O idAvaliacaoEdicao é opcional, usado para PUT (edição) ou POST (novo)
    const idAvaliacaoEdicao = document.getElementById('id-avaliacao-edicao').value;
    
    // Validação
    if (estrelas < 1 || estrelas > 5) {
        alert('Por favor, selecione uma avaliação de 1 a 5 estrelas.');
        return;
    }
    
    // Desabilita botão
    const botao = document.getElementById('comentario-botao');
    const textoOriginal = botao.textContent;
    botao.disabled = true;
    botao.textContent = 'Enviando...';
    
    try {
        const formData = new FormData();
        formData.append('id_livro', ID_LIVRO);
        formData.append('estrelas', estrelas);
        formData.append('comentario', comentario);
        
        // Se estiver em edição, anexa o ID da avaliação para o backend saber que é um UPDATE
        if (idAvaliacaoEdicao) {
            formData.append('id_avaliacao', idAvaliacaoEdicao);
        }
        
        const response = await fetch(`${URLBASE}/router.php?acao=salvarAvaliacao`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarMensagemSucesso(data.message);
            limparFormulario();
            
            // Recarrega avaliações após um pequeno delay para a mensagem ser lida
            setTimeout(() => {
                carregarAvaliacoes();
                carregarMinhaAvaliacao();
                carregarDadosLivro(); // Atualiza média
            }, 1000);
        } else {
            alert('Erro: ' + data.message);
        }
        
    } catch (error) {
        console.error('Erro ao salvar avaliação:', error);
        alert('Erro ao processar avaliação. Tente novamente.');
    } finally {
        botao.disabled = false;
        botao.textContent = textoOriginal;
    }
}

function limparFormulario() {
    document.getElementById('comentario-input').value = '';
    document.getElementById('rating-value').value = '0';
    document.getElementById('id-avaliacao-edicao').value = '';
    estrelaSelecionada = 0;
    destacarEstrelas(0);
    atualizarTextoEstrelas(0);
    
    // Reseta título do formulário
    const formTitulo = document.getElementById('formTitulo');
    if (formTitulo) {
        formTitulo.textContent = 'Deixe sua avaliação';
    }
    
    // Esconde botão cancelar
    const botaoCancelar = document.getElementById('cancelar-edicao');
    if (botaoCancelar) {
        botaoCancelar.style.display = 'none';
    }
    
    avaliacaoEmEdicao = false;
}

function cancelarEdicao() {
    limparFormulario();
}

// ============================================
// MINHA AVALIAÇÃO (Visualização e Ações)
// ============================================

async function carregarMinhaAvaliacao() {
    try {
        // Assume-se que 'ID_LIVRO' e 'URLBASE' estão definidos globalmente
        const response = await fetch(`${URLBASE}/router.php?acao=minhaAvaliacao&id_livro=${ID_LIVRO}`);
        const data = await response.json();
        
        const container = document.getElementById('minhaAvaliacaoContainer');
        if (!container) return;
        
        if (data.success && data.avaliou) {
            const av = data.avaliacao;
            const estrelasDisplay = '★'.repeat(av.estrelas) + '☆'.repeat(5 - av.estrelas);
            
            // Limpa o formulário para garantir que, se for uma edição, ele seja preenchido pelo 'editarMinhaAvaliacao'
            limparFormulario();
        
            
            // Esconde o formulário se necessário, ou move o scroll para a visualização da minha avaliação.
            // (Depende da UX, mas a lógica do 'limparFormulario' já reseta o formulário)
            
        } else {
            container.innerHTML = '';
            // Se o usuário não avaliou, garante que o formulário está limpo para nova avaliação
            limparFormulario(); 
        }
        
    } catch (error) {
        console.error('Erro ao carregar minha avaliação:', error);
    }
}

function editarMinhaAvaliacao(idAvaliacao, estrelas, comentario) {
    // Preenche o formulário
    document.getElementById('rating-value').value = estrelas;
    document.getElementById('comentario-input').value = comentario;
    document.getElementById('id-avaliacao-edicao').value = idAvaliacao;
    
    estrelaSelecionada = estrelas;
    destacarEstrelas(estrelas);
    atualizarTextoEstrelas(estrelas);
    
    // Atualiza título do formulário
    const formTitulo = document.getElementById('formTitulo');
    if (formTitulo) {
        formTitulo.textContent = 'Editar minha avaliação';
    }
    
    // Mostra botão cancelar
    const botaoCancelar = document.getElementById('cancelar-edicao');
    if (botaoCancelar) {
        botaoCancelar.style.display = 'inline-block';
    }
    
    avaliacaoEmEdicao = true;
    
    // Scroll para o formulário
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.scrollIntoView({ behavior: 'smooth' });
    }
}

async function deletarMinhaAvaliacao(idAvaliacao) {
    if (!confirm('Tem certeza que deseja deletar sua avaliação?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('id_avaliacao', idAvaliacao);
        
        const response = await fetch(`${URLBASE}/router.php?acao=deletarAvaliacao`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            mostrarMensagemSucesso('Avaliação deletada com sucesso!');
            
            setTimeout(() => {
                carregarAvaliacoes();
                carregarMinhaAvaliacao();
                carregarDadosLivro();
            }, 1000);
        } else {
            alert('Erro: ' + data.message);
        }
        
    } catch (error) {
        console.error('Erro ao deletar avaliação:', error);
        alert('Erro ao deletar avaliação.');
    }
}

// ============================================
// LISTAR AVALIAÇÕES E ESTATÍSTICAS
// ============================================

async function carregarAvaliacoes() {
    try {
        const response = await fetch(`${URLBASE}/router.php?acao=listarAvaliacoes&id_livro=${ID_LIVRO}`);
        const data = await response.json();
        
        if (!data.success) {
            mostrarErroAvaliacoes('Erro ao carregar avaliações.');
            return;
        }
        
        // Atualiza estatísticas
        if (data.estatisticas) {
            mostrarEstatisticas(data.estatisticas);
        }
        
        // Atualiza lista de avaliações
        if (data.avaliacoes && data.avaliacoes.length > 0) {
            mostrarListaAvaliacoes(data.avaliacoes);
        } else {
            mostrarSemAvaliacoes();
        }
        
    } catch (error) {
        console.error('Erro ao carregar avaliações:', error);
        mostrarErroAvaliacoes('Erro de conexão.');
    }
}

function mostrarEstatisticas(stats) {
    const container = document.getElementById('estatisticasContainer');
    if (!container) return;
    
    const total = parseInt(stats.total_avaliacoes || 0);
    if (total === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    
    const media = parseFloat(stats.media_arredondada || 0);
    
    // Calcula porcentagens
    const calc = (num) => total > 0 ? Math.round((num / total) * 100) : 0;
    
    container.innerHTML = `
        <div class="estatisticas-avaliacoes">
            <div class="media-estrelas">
                <div class="media-numero">${media.toFixed(1)}</div>
                <div style="color: #ffc107; font-size: 1.5rem; margin: 5px 0;">
                    ${'★'.repeat(Math.round(media))}${'☆'.repeat(5 - Math.round(media))}
                </div>
                <div class="media-texto">${total} ${total === 1 ? 'avaliação' : 'avaliações'}</div>
            </div>
            
            <div class="barras-distribuicao">
                <div class="barra-estrela">
                    <div class="barra-label">5 ★</div>
                    <div class="barra-progresso">
                        <div class="barra-preenchimento" style="width: ${calc(stats.cinco_estrelas || 0)}%"></div>
                    </div>
                    <div class="barra-numero">${stats.cinco_estrelas || 0}</div>
                </div>
                
                <div class="barra-estrela">
                    <div class="barra-label">4 ★</div>
                    <div class="barra-progresso">
                        <div class="barra-preenchimento" style="width: ${calc(stats.quatro_estrelas || 0)}%"></div>
                    </div>
                    <div class="barra-numero">${stats.quatro_estrelas || 0}</div>
                </div>
                
                <div class="barra-estrela">
                    <div class="barra-label">3 ★</div>
                    <div class="barra-progresso">
                        <div class="barra-preenchimento" style="width: ${calc(stats.tres_estrelas || 0)}%"></div>
                    </div>
                    <div class="barra-numero">${stats.tres_estrelas || 0}</div>
                </div>
                
                <div class="barra-estrela">
                    <div class="barra-label">2 ★</div>
                    <div class="barra-progresso">
                        <div class="barra-preenchimento" style="width: ${calc(stats.duas_estrelas || 0)}%"></div>
                    </div>
                    <div class="barra-numero">${stats.duas_estrelas || 0}</div>
                </div>
                
                <div class="barra-estrela">
                    <div class="barra-label">1 ★</div>
                    <div class="barra-progresso">
                        <div class="barra-preenchimento" style="width: ${calc(stats.uma_estrela || 0)}%"></div>
                    </div>
                    <div class="barra-numero">${stats.uma_estrela || 0}</div>
                </div>
            </div>
        </div>
    `;
}

function mostrarListaAvaliacoes(avaliacoes) {
    const container = document.getElementById('reviewsContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    avaliacoes.forEach(av => {
        // Pula se for a avaliação do usuário logado (já está em "Minha Avaliação")
        if (typeof USUARIO_LOGADO !== 'undefined' && USUARIO_LOGADO && av.id_usuario == ID_USUARIO) {
            return;
        }
        
        const div = document.createElement('div');
        div.className = 'comment_2';
        
        const estrelas = '★'.repeat(av.estrelas) + '☆'.repeat(5 - av.estrelas);
        
        div.innerHTML = `
            <div class="commentName">
                <div style="color: #ffc107; font-size: 1.2rem;">
                    ${estrelas}
                </div>
                <h3 class="commentTitulo">${escapeHtml(av.nome_usuario || 'Usuário')}</h3>
            </div>
            <p class="commentUserinfo">Avaliado em: ${formatarData(av.data_criacao)}</p>
            ${av.comentario ? `<p class="commentConteudo">${escapeHtml(av.comentario)}</p>` : '<p class="commentConteudo" style="font-style: italic; color: #999;">Sem comentário</p>'}
        `;
        
        container.appendChild(div);
    });
    
    // Se, após o filtro, não sobrar nenhuma avaliação, mostra a mensagem de "sem avaliações"
    if (container.children.length === 0) {
        mostrarSemAvaliacoes();
    }
}

function mostrarSemAvaliacoes() {
    const container = document.getElementById('reviewsContainer');
    if (!container) return;
    
    container.innerHTML = `
        <div class="sem-comentarios">
            <p>Nenhuma avaliação ainda.</p>
            <p>Seja o primeiro a avaliar este livro! ⭐</p>
        </div>
    `;
}

function mostrarErroAvaliacoes(mensagem) {
    const container = document.getElementById('reviewsContainer');
    if (!container) return;
    
    container.innerHTML = `
        <div style="text-align: center; padding: 20px; color: #dc3545;">
            <p>${mensagem}</p>
        </div>
    `;
}

// ============================================
// CARREGAR DADOS DO LIVRO (ORIGINAL)
// ============================================

async function carregarDadosLivro() {
    try {
        const response = await fetch(`${URLBASE}/router.php?acao=buscarLivroDetalhes&id=${ID_LIVRO}`);
        const data = await response.json();

        if (!data.sucesso) {
            mostrarErro(data.mensagem || 'Erro ao carregar livro.');
            return;
        }

        preencherDadosLivro(data.livro);
        preencherExemplares(data.exemplares);
        preencherBotaoAcao(data.disponibilidade);

    } catch (error) {
        console.error('Erro ao carregar livro:', error);
        mostrarErro('Erro de conexão ao carregar informações do livro.');
    }
}

function preencherDadosLivro(livro) {
    if (livro.titulo && document.getElementById('pageTitle')) {
        document.getElementById('pageTitle').textContent = `${livro.titulo} - Biblioteca SENAC`;
    }

    if (document.getElementById('livroTitulo')) {
        document.getElementById('livroTitulo').textContent = livro.titulo || 'Título não informado';
    }
    if (document.getElementById('livroIsbn')) {
        document.getElementById('livroIsbn').textContent = `ISBN: ${livro.isbn || 'Não informado'}`;
    }
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

    preencherTags(livro.categoria);
}

function preencherTags(categoria) {
    const tagsContainer = document.querySelector('.tags');
    const tagsLista = document.querySelector('.tags2');
    
    if (!tagsContainer || !tagsLista) return;

    tagsLista.innerHTML = '';

    if (categoria && categoria !== 'Sem categoria') {
        tagsContainer.style.display = 'block';
        
        const tagDiv = document.createElement('div');
        tagDiv.className = 'tag_icone';
        tagDiv.innerHTML = `<p>${categoria}</p>`;
        tagsLista.appendChild(tagDiv);
    } else {
        tagsContainer.style.display = 'none';
    }
}

function preencherExemplares(exemplares) {
    const container = document.getElementById('containerExemplarOpen');
    if (!container) return; 

    container.innerHTML = '';

    if (!exemplares || exemplares.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 20px; color: #666;">Nenhum exemplar cadastrado</p>';
        return;
    }

    exemplares.forEach((ex, index) => {
        const grid = document.createElement('div');
        grid.className = 'containerGrid';
        
        if (index === 0) {
            // Cabeçalho
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
            // Linhas de dados
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

function preencherBotaoAcao(disponibilidade) {
    const statusEl = document.getElementById('statusDisponibilidade');
    const botaoEl = document.getElementById('botaoAcao');

    if (!statusEl || !botaoEl) return; 

    // Reset estilos e eventos
    botaoEl.onclick = null;
    botaoEl.disabled = false;
    botaoEl.removeAttribute('class');
    
    // Estilos padrão/reutilizáveis para botões de ação
    const applyActionButtonStyle = (btn, bgColor) => {
        btn.style.backgroundColor = bgColor;
        btn.style.color = 'white';
        btn.style.border = 'none';
        btn.style.padding = '12px 40px';
        btn.style.borderRadius = '10px';
        btn.style.cursor = 'pointer';
        btn.style.fontSize = '16px';
        btn.style.fontWeight = '600';
        btn.style.transition = 'all 0.3s ease';
        btn.onmouseenter = function() {
            this.style.backgroundColor = '#FF8C00';
            this.style.transform = 'scale(1.05)';
        };
        btn.onmouseleave = function() {
            this.style.backgroundColor = bgColor;
            this.style.transform = 'scale(1)';
        };
    };

    if (disponibilidade.total_disponivel > 0) {
        // DISPONÍVEL
        statusEl.textContent = 'Disponível';
        statusEl.style.color = '#28a745';
        
        botaoEl.textContent = 'Reservar';
        applyActionButtonStyle(botaoEl, 'rgb(0, 49, 98)');
        botaoEl.onclick = solicitarEmprestimo;
        
    } else if (disponibilidade.total_exemplares > 0) {
        // INDISPONÍVEL (Mas tem exemplares, então entra na fila)
        statusEl.textContent = 'Indisponível';
        statusEl.style.color = '#dc3545';
        
        botaoEl.textContent = 'Entrar na Fila';
        applyActionButtonStyle(botaoEl, '#003162');
        botaoEl.onclick = entrarNaFila;
        
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

// ============================================
// AÇÕES DE EMPRÉSTIMO
// ============================================

async function solicitarEmprestimo() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para reservar um livro!');
        // Redireciona para login (assumindo a estrutura de URL)
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }

    if (!confirm('Deseja solicitar o empréstimo deste livro?\n\nVocê terá 48 horas para retirá-lo na biblioteca.')) {
        return;
    }

    const botao = document.getElementById('botaoAcao');
    const textoOriginal = botao.textContent;
    botao.disabled = true;
    botao.textContent = 'Processando...';

    try {
        const formData = new FormData();
        formData.append('id_livro', ID_LIVRO);

        const response = await fetch(`${URLBASE}/router.php?acao=solicitarEmprestimo`, {
            method: 'POST',
            body: formData
        });

        const textResponse = await response.text();
        let data;
        
        try {
            data = JSON.parse(textResponse);
        } catch (parseError) {
            console.error('Erro ao parsear JSON:', parseError);
            throw new Error('Resposta inválida do servidor: ' + textResponse);
        }

        if (data.sucesso) {
            mostrarModalSucesso(
                'Empréstimo Solicitado!',
                data.mensagem,
                data.data_limite_retirada ? 
                    'Você tem até ' + formatarDataHora(data.data_limite_retirada) + ' para retirar o livro na biblioteca.' :
                    'Retire o livro na biblioteca em até 48 horas.'
            );
            
            // Recarrega a página após sucesso para atualizar o estado do botão
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

async function entrarNaFila() {
    if (!USUARIO_LOGADO) {
        alert('Você precisa estar logado para entrar na fila!');
        window.location.href = `${URLBASE}/src/views/usuario/login.php`;
        return;
    }

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
            mostrarModalFila(data.posicao, data.estimativa);
            
            // Recarrega a página após sucesso para atualizar o estado do botão
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

// Fim do script unificado.**