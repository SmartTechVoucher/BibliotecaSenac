/**
 * Gerenciador de Usuários - Sistema Biblioteca SENAC
 * Responsável por toda a lógica de interface e comunicação com o backend
 */

class GerenciadorUsuarios {
    constructor() {
        // Estado da aplicação
        this.state = {
            paginaRegulares: 1,
            paginaBloqueados: 1,
            itensPorPagina: 10,
            termoBusca: '',
            usuarioAtual: null,
            modoEdicao: false,
            abaSelecionada: 'regulares'
        };

        // Cache de dados
        this.cache = {
            regulares: { usuarios: [], total: 0, total_paginas: 0 },
            bloqueados: { usuarios: [], total: 0, total_paginas: 0 },
            estatisticas: null
        };

        this.init();
    }

    /**
     * Inicializa o gerenciador
     */
    async init() {
        this.setupEventListeners();
        await this.carregarEstatisticas();
        await this.carregarDados();
    }

    /**
     * Configura todos os event listeners
     */
    setupEventListeners() {
        // Busca
        const campoBusca = document.getElementById('campoBusca');
        if (campoBusca) {
            campoBusca.addEventListener('input', this.debounce((e) => {
                this.state.termoBusca = e.target.value;
                this.state.paginaRegulares = 1;
                this.state.paginaBloqueados = 1;
                this.carregarDados();
            }, 500));
        }

        // Abas
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.trocarAba(e.target.dataset.tab));
        });

        // Paginação Regulares
        document.getElementById('btnAnteriorRegulares')?.addEventListener('click', () => {
            if (this.state.paginaRegulares > 1) {
                this.state.paginaRegulares--;
                this.carregarUsuariosRegulares();
            }
        });

        document.getElementById('btnProximoRegulares')?.addEventListener('click', () => {
            if (this.state.paginaRegulares < this.cache.regulares.total_paginas) {
                this.state.paginaRegulares++;
                this.carregarUsuariosRegulares();
            }
        });

        // Paginação Bloqueados
        document.getElementById('btnAnteriorBloqueados')?.addEventListener('click', () => {
            if (this.state.paginaBloqueados > 1) {
                this.state.paginaBloqueados--;
                this.carregarUsuariosBloqueados();
            }
        });

        document.getElementById('btnProximoBloqueados')?.addEventListener('click', () => {
            if (this.state.paginaBloqueados < this.cache.bloqueados.total_paginas) {
                this.state.paginaBloqueados++;
                this.carregarUsuariosBloqueados();
            }
        });

        // Modal - Botões
        document.getElementById('btnBloquear')?.addEventListener('click', () => this.toggleBloqueio());
        document.getElementById('btnEditar')?.addEventListener('click', () => this.habilitarEdicao());
        document.getElementById('btnSalvar')?.addEventListener('click', () => this.salvarEdicao());
        document.getElementById('btnCancelar')?.addEventListener('click', () => this.cancelarEdicao());

        // Fechar modal ao clicar fora
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.fecharModal();
            }
        });
    }

    /**
     * Carrega dados iniciais
     */
    async carregarDados() {
        await Promise.all([
            this.carregarUsuariosRegulares(),
            this.carregarUsuariosBloqueados()
        ]);
    }

    /**
     * Carrega estatísticas
     */
    async carregarEstatisticas() {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'estatisticas'
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);

            if (data.sucesso) {
                this.cache.estatisticas = data.estatisticas;
                this.renderizarEstatisticas();
            }
        } catch (error) {
            console.error('Erro ao carregar estatísticas:', error);
        }
    }

    /**
     * Renderiza cards de estatísticas
     */
    renderizarEstatisticas() {
        const container = document.getElementById('estatisticas-container');
        if (!container || !this.cache.estatisticas) return;

        const stats = this.cache.estatisticas;
        container.innerHTML = `
            <div class="stat-card stat-primary">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${stats.total_geral || 0}</h3>
                    <p>Total de Usuários</p>
                </div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${stats.total_regulares || 0}</h3>
                    <p>Usuários Regulares</p>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${stats.total_bloqueados || 0}</h3>
                    <p>Usuários Bloqueados</p>
                </div>
            </div>
        `;
    }

    /**
     * Carrega usuários regulares
     */
    async carregarUsuariosRegulares() {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'listar_regulares',
                pagina: this.state.paginaRegulares,
                limite: this.state.itensPorPagina,
                busca: this.state.termoBusca
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);

            if (data.sucesso) {
                this.cache.regulares = {
                    usuarios: data.usuarios,
                    total: data.total,
                    pagina_atual: data.pagina_atual,
                    total_paginas: data.total_paginas
                };
                this.renderizarTabelaRegulares();
            }
        } catch (error) {
            this.mostrarErro('Erro ao carregar usuários regulares');
            console.error(error);
        }
    }

    /**
     * Carrega usuários bloqueados
     */
    async carregarUsuariosBloqueados() {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'listar_bloqueados',
                pagina: this.state.paginaBloqueados,
                limite: this.state.itensPorPagina,
                busca: this.state.termoBusca
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);

            if (data.sucesso) {
                this.cache.bloqueados = {
                    usuarios: data.usuarios,
                    total: data.total,
                    pagina_atual: data.pagina_atual,
                    total_paginas: data.total_paginas
                };
                this.renderizarTabelaBloqueados();
            }
        } catch (error) {
            this.mostrarErro('Erro ao carregar usuários bloqueados');
            console.error(error);
        }
    }

    /**
     * Renderiza tabela de usuários regulares
     */
    renderizarTabelaRegulares() {
        const tbody = document.getElementById('tabelaRegulares');
        const btnAnterior = document.getElementById('btnAnteriorRegulares');
        const btnProximo = document.getElementById('btnProximoRegulares');
        const info = document.getElementById('infoRegulares');

        if (!tbody) return;

        const { usuarios, total, pagina_atual, total_paginas } = this.cache.regulares;

        if (usuarios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon">🔍</div>
                        <p>Nenhum usuário regular encontrado</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = usuarios.map(user => `
                <tr class="table-row">
                    <td>
                        <div class="user-cell">
                            <img src="${user.foto_perfil || URLBASE + '/public/assets/img/NullUser.jpg'}" 
                                 alt="${user.nome}" 
                                 class="user-avatar">
                            <span>${this.truncateText(user.nome, 30)}</span>
                        </div>
                    </td>
                    <td>${user.numero_matricula || 'N/A'}</td>
                    <td>${user.unidade_senac || 'N/A'}</td>
                    <td>${this.formatPhone(user.telefone)}</td>
                    <td><span class="badge badge-success">Regular</span></td>
                    <td>
                        <button class="btn-action btn-view" onclick="gerenciadorUsuarios.abrirDetalhes(${user.id_usuario})">
                            👁️ Ver Detalhes
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Atualizar controles de paginação
        if (btnAnterior) btnAnterior.disabled = pagina_atual === 1;
        if (btnProximo) btnProximo.disabled = pagina_atual >= total_paginas;
        
        if (info) {
            const inicio = (pagina_atual - 1) * this.state.itensPorPagina + 1;
            const fim = Math.min(pagina_atual * this.state.itensPorPagina, total);
            info.textContent = `Mostrando ${inicio}-${fim} de ${total} usuários`;
        }
    }

    /**
     * Renderiza tabela de usuários bloqueados
     */
    renderizarTabelaBloqueados() {
        const tbody = document.getElementById('tabelaBloqueados');
        const btnAnterior = document.getElementById('btnAnteriorBloqueados');
        const btnProximo = document.getElementById('btnProximoBloqueados');
        const info = document.getElementById('infoBloqueados');

        if (!tbody) return;

        const { usuarios, total, pagina_atual, total_paginas } = this.cache.bloqueados;

        if (usuarios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon">✅</div>
                        <p>Nenhum usuário bloqueado</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = usuarios.map(user => `
                <tr class="table-row">
                    <td>
                        <div class="user-cell">
                            <img src="${user.foto_perfil || URLBASE + '/public/assets/img/NullUser.jpg'}" 
                                 alt="${user.nome}" 
                                 class="user-avatar">
                            <span>${this.truncateText(user.nome, 30)}</span>
                        </div>
                    </td>
                    <td>${user.numero_matricula || 'N/A'}</td>
                    <td>${user.unidade_senac || 'N/A'}</td>
                    <td>${this.formatPhone(user.telefone)}</td>
                    <td><span class="badge badge-danger">Bloqueado</span></td>
                    <td>
                        <button class="btn-action btn-view" onclick="gerenciadorUsuarios.abrirDetalhes(${user.id_usuario})">
                            👁️ Ver Detalhes
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Atualizar controles de paginação
        if (btnAnterior) btnAnterior.disabled = pagina_atual === 1;
        if (btnProximo) btnProximo.disabled = pagina_atual >= total_paginas;
        
        if (info) {
            const inicio = (pagina_atual - 1) * this.state.itensPorPagina + 1;
            const fim = Math.min(pagina_atual * this.state.itensPorPagina, total);
            info.textContent = `Mostrando ${inicio}-${fim} de ${total} usuários`;
        }
    }

    /**
     * Abre modal com detalhes do usuário
     */
    async abrirDetalhes(idUsuario) {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'buscar_usuario',
                id_usuario: idUsuario
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);

            if (data.sucesso && data.usuario) {
                this.state.usuarioAtual = data.usuario;
                this.preencherModal(data.usuario);
                document.getElementById('modalUsuario').style.display = 'flex';
            } else {
                this.mostrarErro(data.erro || 'Usuário não encontrado');
            }
        } catch (error) {
            this.mostrarErro('Erro ao carregar dados do usuário');
            console.error(error);
        }
    }

    /**
     * Preenche o modal com dados do usuário
     */
    preencherModal(usuario) {
        // Foto
        document.getElementById('userFoto').src = usuario.foto_perfil || URLBASE + '/public/assets/img/NullUser.jpg';

        // Dados pessoais
        document.getElementById('userName').value = usuario.nome || '';
        document.getElementById('userNomeSocial').value = usuario.nome_social || '';
        document.getElementById('userCPF').value = this.formatCPF(usuario.cpf) || '';
        document.getElementById('userNascimento').value = usuario.data_nascimento || '';
        document.getElementById('userGenero').value = usuario.genero || '';

        // Matrícula
        document.getElementById('userMatricula').value = usuario.numero_matricula || '';
        document.getElementById('userUnidade').value = usuario.unidade_senac || '';
        document.getElementById('userCategoria').value = usuario.categoria || '';

        // Contato
        document.getElementById('userEmail').value = usuario.email || '';
        document.getElementById('userTelefone').value = this.formatPhone(usuario.telefone) || '';
        document.getElementById('userEndereco').value = usuario.endereco || '';

        // Acadêmicos
        document.getElementById('userCurso').value = usuario.curso || '';
        document.getElementById('userTurma').value = usuario.turma || '';
        document.getElementById('userDataFimCurso').value = usuario.data_fim_curso || '';
        document.getElementById('userNotas').value = usuario.notas_usuario || '';

        // Atualizar botão de bloqueio
        const btnBloquear = document.getElementById('btnBloquear');
        if (btnBloquear) {
            btnBloquear.textContent = usuario.ativo ? '🔒 Bloquear Usuário' : '🔓 Desbloquear Usuário';
            btnBloquear.className = usuario.ativo ? 'btn btn-danger' : 'btn btn-success';
        }
    }

    /**
     * Habilita edição dos campos
     */
    habilitarEdicao() {
        this.state.modoEdicao = true;

        // Campos editáveis
        const campos = [
            'userName', 'userNomeSocial', 'userNascimento', 'userGenero',
            'userEmail', 'userTelefone', 'userEndereco',
            'userCurso', 'userTurma', 'userDataFimCurso', 'userNotas'
        ];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.removeAttribute('readonly');
                if (campo.tagName === 'SELECT') {
                    campo.removeAttribute('disabled');
                }
            }
        });

        // Trocar botões
        document.getElementById('btnEditar').style.display = 'none';
        document.getElementById('btnSalvar').style.display = 'inline-block';
        document.getElementById('btnCancelar').style.display = 'inline-block';
    }

    /**
     * Salva edição do usuário
     */
    async salvarEdicao() {
        if (!this.state.usuarioAtual) {
            this.mostrarErro('Nenhum usuário selecionado');
            return;
        }

        try {
            // Coletar dados
            const dados = {
                nome: document.getElementById('userName').value,
                nome_social: document.getElementById('userNomeSocial').value,
                email: document.getElementById('userEmail').value,
                data_nascimento: document.getElementById('userNascimento').value,
                telefone: document.getElementById('userTelefone').value.replace(/\D/g, ''),
                endereco: document.getElementById('userEndereco').value,
                genero: document.getElementById('userGenero').value,
                curso: document.getElementById('userCurso').value,
                turma: document.getElementById('userTurma').value,
                data_fim_curso: document.getElementById('userDataFimCurso').value,
                notas_usuario: document.getElementById('userNotas').value
            };

            // Validações
            if (!dados.nome || !dados.email) {
                this.mostrarErro('Nome e email são obrigatórios');
                return;
            }

            if (!this.validarEmail(dados.email)) {
                this.mostrarErro('Email inválido');
                return;
            }

            const params = new URLSearchParams({
                ajax: '1',
                acao: 'atualizar_usuario',
                id_usuario: this.state.usuarioAtual.id_usuario,
                ...dados
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params
            });

            const data = await this.handleResponse(response);

            if (data.sucesso) {
                this.mostrarSucesso(data.mensagem);
                this.cancelarEdicao();
                await this.abrirDetalhes(this.state.usuarioAtual.id_usuario);
                await this.carregarDados();
                await this.carregarEstatisticas();
            } else {
                this.mostrarErro(data.erro || 'Erro ao atualizar usuário');
            }
        } catch (error) {
            this.mostrarErro('Erro ao salvar alterações');
            console.error(error);
        }
    }

    /**
     * Cancela edição
     */
    cancelarEdicao() {
        this.state.modoEdicao = false;

        // Desabilitar campos
        const campos = [
            'userName', 'userNomeSocial', 'userNascimento', 'userGenero',
            'userEmail', 'userTelefone', 'userEndereco',
            'userCurso', 'userTurma', 'userDataFimCurso', 'userNotas'
        ];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.setAttribute('readonly', 'readonly');
                if (campo.tagName === 'SELECT') {
                    campo.setAttribute('disabled', 'disabled');
                }
            }
        });

        // Trocar botões
        document.getElementById('btnEditar').style.display = 'inline-block';
        document.getElementById('btnSalvar').style.display = 'none';
        document.getElementById('btnCancelar').style.display = 'none';

        // Restaurar dados originais
        if (this.state.usuarioAtual) {
            this.preencherModal(this.state.usuarioAtual);
        }
    }

    /**
     * Toggle bloqueio/desbloqueio
     */
    async toggleBloqueio() {
        if (!this.state.usuarioAtual) {
            this.mostrarErro('Nenhum usuário selecionado');
            return;
        }

        const acao = this.state.usuarioAtual.ativo ? 'bloquearUsuario' : 'desbloquearUsuario';
        const mensagemConfirmacao = this.state.usuarioAtual.ativo 
            ? 'Deseja realmente bloquear este usuário?' 
            : 'Deseja realmente desbloquear este usuário?';

        if (!confirm(mensagemConfirmacao)) return;

        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: acao,
                id_usuario: this.state.usuarioAtual.id_usuario
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params
            });

            const data = await this.handleResponse(response);

            if (data.sucesso) {
                this.mostrarSucesso(data.mensagem);
                this.fecharModal();
                await this.carregarDados();
                await this.carregarEstatisticas();
            } else {
                this.mostrarErro(data.erro || 'Erro ao alterar status');
            }
        } catch (error) {
            this.mostrarErro('Erro ao alterar status do usuário');
            console.error(error);
        }
    }

    /**
     * Troca de aba
     */
    trocarAba(aba) {
        this.state.abaSelecionada = aba;

        // Atualizar botões
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === aba);
        });

        // Atualizar conteúdo
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.toggle('active', content.id === `tab-${aba}`);
        });
    }

    /**
     * Fecha modal
     */
    fecharModal() {
        document.getElementById('modalUsuario').style.display = 'none';
        this.state.usuarioAtual = null;
        this.state.modoEdicao = false;
        this.cancelarEdicao();
    }

    /**
     * Tratamento de resposta HTTP
     */
    async handleResponse(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    }

    /**
     * Utilities
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    truncateText(text, maxLength) {
        if (!text) return 'N/A';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    formatCPF(cpf) {
        if (!cpf) return '';
        cpf = cpf.replace(/\D/g, '');
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }

    formatPhone(phone) {
        if (!phone) return 'N/A';
        phone = phone.replace(/\D/g, '');
        if (phone.length === 11) {
            return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (phone.length === 10) {
            return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
        return phone;
    }

    validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Mensagens de feedback
     */
    mostrarSucesso(mensagem) {
        document.getElementById('mensagemSucesso').textContent = mensagem;
        document.getElementById('modalSucesso').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('modalSucesso').style.display = 'none';
        }, 2000);
    }

    mostrarErro(mensagem) {
        document.getElementById('mensagemErro').textContent = mensagem;
        document.getElementById('modalErro').style.display = 'flex';
    }
}

// Funções globais para fechar modais
function fecharModalErro() {
    document.getElementById('modalErro').style.display = 'none';
}

function fecharModal() {
    if (window.gerenciadorUsuarios) {
        window.gerenciadorUsuarios.fecharModal();
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    window.gerenciadorUsuarios = new GerenciadorUsuarios();
});