// ============================================
// HISTÓRICO DE EMPRÉSTIMOS - SISTEMA COMPLETO
// ============================================

let paginaAtual = 1;
let statusFiltro = 'Todos';
const itensPorPagina = 5;

// ============================================
// INICIALIZAÇÃO
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    carregarEstatisticas();
    carregarEmprestimos();
    inicializarFiltros();
    inicializarBotoesPaginacao();
});

// ============================================
// ESTATÍSTICAS
// ============================================

async function carregarEstatisticas() {
    try {
        const response = await fetch(`${URLBASE}/src/controller/admin/HistoricoEmprestimosController.php?acao=estatisticas`);
        const data = await response.json();
        
        if (data.success && data.estatisticas) {
            mostrarEstatisticas(data.estatisticas);
        }
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

function mostrarEstatisticas(stats) {
    const container = document.getElementById('estatisticas-container');
    if (!container) return;
    
    container.innerHTML = `
        <div class="stat-card stat-total">
            <div class="stat-content">
                <div class="stat-number">${stats.total || 0}</div>
                <div class="stat-label">Total de Empréstimos</div>
            </div>
        </div>
        
        <div class="stat-card stat-andamento">
            <div class="stat-content">
                <div class="stat-number">${stats.em_andamento || 0}</div>
                <div class="stat-label">Em Andamento</div>
            </div>
        </div>
        
        <div class="stat-card stat-finalizado">
            <div class="stat-content">
                <div class="stat-number">${stats.finalizados || 0}</div>
                <div class="stat-label">Finalizados</div>
            </div>
        </div>
        
        <div class="stat-card stat-atrasado">
            <div class="stat-content">
                <div class="stat-number">${stats.atrasados || 0}</div>
                <div class="stat-label">Atrasados</div>
            </div>
        </div>
    `;
}

// ============================================
// CARREGAR EMPRÉSTIMOS
// ============================================

async function carregarEmprestimos() {
    try {
        const url = `${URLBASE}/src/controller/admin/HistoricoEmprestimosController.php?acao=listar&status=${encodeURIComponent(statusFiltro)}&pagina=${paginaAtual}&limite=${itensPorPagina}`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            renderizarTabela(data.emprestimos);
            atualizarPaginacao(data.paginacao);
        } else {
            mostrarErroTabela(data.message || 'Erro ao carregar empréstimos.');
        }
        
    } catch (error) {
        console.error('Erro ao carregar empréstimos:', error);
        mostrarErroTabela('Erro de conexão ao carregar empréstimos.');
    }
}

// ============================================
// RENDERIZAR TABELA
// ============================================

function renderizarTabela(emprestimos) {
    const tbody = document.getElementById('userTable');
    if (!tbody) return;
    
    if (!emprestimos || emprestimos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="empty-row">
                    <div style="padding: 40px; text-align: center;">
                        <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                        <p style="color: #666; font-size: 1.1rem; margin: 0;">
                            Nenhum empréstimo encontrado
                        </p>
                        <p style="color: #999; font-size: 0.9rem; margin-top: 10px;">
                            Tente ajustar os filtros ou verifique mais tarde
                        </p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = emprestimos.map(emp => `
        <tr class="table-row">
            <td>
                <span class="badge ${emp.classe_css}">
                    ${getStatusIcon(emp.status)} ${emp.status}
                </span>
            </td>
            <td class="isbn-cell">${emp.exemplar}</td>
            <td class="leitor-cell">${emp.leitor}</td>
            <td>${emp.data}</td>
            <td>${emp.prazo}</td>
            <td class="${emp.devolucao === 'Em andamento' ? 'andamento-text' : ''}">${emp.devolucao}</td>
        </tr>
    `).join('');
}

function getStatusIcon(status) {
    const icons = {
        'Finalizado': '',
        'Em andamento': '',
        'Atrasado': '',
        'Cancelado': ''
    };
    return icons[status] || '';
}

function mostrarErroTabela(mensagem) {
    const tbody = document.getElementById('userTable');
    if (!tbody) return;
    
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="error-row">
                <div style="padding: 30px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">❌</div>
                    <p style="color: #dc3545; font-size: 1.1rem; margin: 0;">${mensagem}</p>
                    <button onclick="carregarEmprestimos()" style="margin-top: 20px; padding: 10px 20px; background: #004A90; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Tentar Novamente
                    </button>
                </div>
            </td>
        </tr>
    `;
}

// ============================================
// PAGINAÇÃO
// ============================================

function atualizarPaginacao(paginacao) {
    const btnAnterior = document.getElementById('prevBtn');
    const btnProximo = document.getElementById('nextBtn');
    const infoPage = document.getElementById('pageInfo');
    
    if (!btnAnterior || !btnProximo || !infoPage) return;
    
    const { pagina_atual, total_paginas, total_registros } = paginacao;
    
    // Atualiza botões
    btnAnterior.disabled = (pagina_atual === 1);
    btnProximo.disabled = (pagina_atual >= total_paginas || total_paginas === 0);
    
    // Atualiza info
    if (total_paginas === 0) {
        infoPage.textContent = 'Nenhum registro';
    } else {
        const inicio = ((pagina_atual - 1) * itensPorPagina) + 1;
        const fim = Math.min(pagina_atual * itensPorPagina, total_registros);
        infoPage.textContent = `Exibindo ${inicio}-${fim} de ${total_registros} registros (Página ${pagina_atual}/${total_paginas})`;
    }
}

function inicializarBotoesPaginacao() {
    const btnAnterior = document.getElementById('prevBtn');
    const btnProximo = document.getElementById('nextBtn');
    
    if (btnAnterior) {
        btnAnterior.addEventListener('click', () => {
            if (paginaAtual > 1) {
                paginaAtual--;
                carregarEmprestimos();
            }
        });
    }
    
    if (btnProximo) {
        btnProximo.addEventListener('click', () => {
            paginaAtual++;
            carregarEmprestimos();
        });
    }
}

// ============================================
// FILTROS
// ============================================

function inicializarFiltros() {
    const filtros = document.querySelectorAll('input[name="statusFilter"]');
    
    filtros.forEach(filtro => {
        filtro.addEventListener('change', (e) => {
            statusFiltro = e.target.value;
            paginaAtual = 1; // Reset para primeira página
            carregarEmprestimos();
            carregarEstatisticas(); // Atualiza estatísticas também
        });
    });
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

function formatarData(dataString) {
    if (!dataString) return '---';
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
}

// Auto-refresh a cada 30 segundos (opcional)
// setInterval(() => {
//     carregarEmprestimos();
//     carregarEstatisticas();
// }, 30000);