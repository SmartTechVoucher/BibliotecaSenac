/**
 * Gerenciador de Usuários - Refatorado (compatível com seu controller/model)
 */

class GerenciadorUsuarios {
    constructor() {
        this.state = {
            paginaRegulares: 1,
            paginaBloqueados: 1,
            itensPorPagina: 10,
            termoBusca: '',
            usuarioAtual: null,
            modoEdicao: false,
            abaSelecionada: 'regulares'
        };

        this.cache = {
            regulares: { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 },
            bloqueados: { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 },
            estatisticas: null
        };

        // Ajuste: URLBASE deve existir no escopo global (como você já usava)
        if (typeof URLBASE === 'undefined') {
            console.warn('URLBASE não definido no escopo global — verifique suas constantes.');
        }

        this.init();
    }

    async init() {
        this.setupEventListeners();
        await this.carregarEstatisticas();
        await this.carregarDados();
    }

    setupEventListeners() {
        const campoBusca = document.getElementById('campoBusca');
        if (campoBusca) {
            campoBusca.addEventListener('input', this.debounce((e) => {
                this.state.termoBusca = e.target.value;
                this.state.paginaRegulares = 1;
                this.state.paginaBloqueados = 1;
                this.carregarDados();
            }, 450));
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.trocarAba(e.currentTarget.dataset.tab));
        });

        document.getElementById('btnAnteriorRegulares')?.addEventListener('click', () => {
            if (this.state.paginaRegulares > 1) {
                this.state.paginaRegulares--;
                this.carregarUsuariosRegulares();
            }
        });

        document.getElementById('btnProximoRegulares')?.addEventListener('click', () => {
            if (this.state.paginaRegulares < (this.cache.regulares.total_paginas || 1)) {
                this.state.paginaRegulares++;
                this.carregarUsuariosRegulares();
            }
        });

        document.getElementById('btnAnteriorBloqueados')?.addEventListener('click', () => {
            if (this.state.paginaBloqueados > 1) {
                this.state.paginaBloqueados--;
                this.carregarUsuariosBloqueados();
            }
        });

        document.getElementById('btnProximoBloqueados')?.addEventListener('click', () => {
            if (this.state.paginaBloqueados < (this.cache.bloqueados.total_paginas || 1)) {
                this.state.paginaBloqueados++;
                this.carregarUsuariosBloqueados();
            }
        });

        document.getElementById('btnBloquear')?.addEventListener('click', () => this.toggleBloqueio());
        document.getElementById('btnEditar')?.addEventListener('click', () => this.habilitarEdicao());
        document.getElementById('btnSalvar')?.addEventListener('click', () => this.salvarEdicao());
        document.getElementById('btnCancelar')?.addEventListener('click', () => this.cancelarEdicao());

        window.addEventListener('click', (e) => {
            if (e.target.classList && e.target.classList.contains('modal')) {
                this.fecharModal();
            }
        });
    }

    async carregarDados() {
        await Promise.all([
            this.carregarUsuariosRegulares(),
            this.carregarUsuariosBloqueados()
        ]);
    }

    // ---------- ESTATÍSTICAS ----------
    async carregarEstatisticas() {
        try {
            const params = new URLSearchParams({ ajax: '1', acao: 'estatisticas' });
            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);
            console.log('ESTATÍSTICAS API:', data);

            // Aceita várias formas: data.estatisticas, data.data.estatisticas, data.data (quando já é objeto)
            const stats = data.estatisticas ?? data.data?.estatisticas ?? data.data ?? data;
            if (data && data.sucesso && stats) {
                this.cache.estatisticas = stats;
                this.renderizarEstatisticas();
            } else {
                // se não houver sucesso, limpa visualmente
                this.cache.estatisticas = null;
                this.renderizarEstatisticas();
            }
        } catch (error) {
            console.error('Erro ao carregar estatísticas:', error);
            this.cache.estatisticas = null;
            this.renderizarEstatisticas();
        }
    }

    renderizarEstatisticas() {
        const container = document.getElementById('estatisticas-container');
        if (!container) return;

        const s = this.cache.estatisticas ?? {};
        // Aceita keys diferentes vindas do backend
        const totalGeral = s.total_geral ?? s.total ?? 0;
        const totalRegulares = s.total_regulares ?? s.ativos ?? s.regulares ?? 0;
        const totalBloqueados = s.total_bloqueados ?? s.bloqueados ?? 0;

        container.innerHTML = `
            <div class="stat-card stat-primary">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${Number(totalGeral)}</h3>
                    <p>Total de Usuários</p>
                </div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${Number(totalRegulares)}</h3>
                    <p>Usuários Regulares</p>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon"></div>
                <div class="stat-content">
                    <h3>${Number(totalBloqueados)}</h3>
                    <p>Usuários Bloqueados</p>
                </div>
            </div>
        `;
    }

    // ---------- HELPERS para imagem ----------
    /**
     * Monta URL de imagem de perfil.
     * - se 'path' começar com http(s) ou '/', usa direto
     * - senão considera que db guarda apenas nome do arquivo e monta /uploads/perfil/arquivo
     */
    buildImageUrl(path) {
        if (!path) return `${URLBASE}/public/assets/img/NullUser.jpg`;
        if (typeof path !== 'string') return `${URLBASE}/public/assets/img/NullUser.jpg`;

        const trimmed = path.trim();

        // já é absoluta (http ou /)
        if (/^https?:\/\//i.test(trimmed) || trimmed.startsWith('/')) {
            return trimmed.startsWith('/') ? trimmed : trimmed;
        }

        // caso comum: armazenou só o nome do arquivo -> montar rota pública /uploads/perfil/...
        // garantindo que URLBASE não duplique barras
        const base = (URLBASE ?? '').replace(/\/$/, '');
        return `${base}/uploads/perfil/${trimmed}`;
    }

    /**
     * Mapeia objeto do backend para formato consistente no frontend
     */
    mapUser(user) {
        if (!user) return null;
        return {
            id_usuario: user.id_usuario ?? user.id ?? user.ID ?? null,
            nome: user.nome ?? user.nome_completo ?? user.name ?? '',
            nome_social: user.nome_social ?? user.nomeSocial ?? '',
            cpf: user.cpf ?? user.CPF ?? user.documento ?? '',
            foto_perfil: user.foto_perfil ?? user.foto ?? user.avatar ?? '',
            numero_matricula: user.numero_matricula ?? user.matricula ?? user.matricula_num ?? '',
            unidade_senac: user.unidade_senac ?? user.unidade ?? user.campus ?? '',
            telefone: user.telefone ?? user.telefone_usuario ?? user.phone ?? '',
            email: user.email ?? '',
            endereco: user.endereco ?? '',
            curso: user.curso ?? '',
            turma: user.turma ?? '',
            data_nascimento: user.data_nascimento ?? user.nascimento ?? '',
            genero: user.genero ?? '',
            categoria: user.categoria ?? '',
            notas_usuario: user.notas_usuario ?? user.notas ?? '',
            ativo: (typeof user.ativo !== 'undefined') ? (Number(user.ativo) === 1 || user.ativo === true) : true,
            __raw: user
        };
    }

    // ---------- LISTAGENS ----------
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
            console.log('LISTAR_REGARes API:', data);

            if (data && data.sucesso) {
                const usuariosRaw = data.usuarios ?? data.data?.usuarios ?? [];
                const usuariosMapeados = usuariosRaw.map(u => this.mapUser(u)).filter(Boolean);

                const total = Number(data.total ?? data.data?.total ?? usuariosMapeados.length ?? 0);
                const paginaAtual = Number(data.pagina_atual ?? data.data?.pagina_atual ?? this.state.paginaRegulares);
                const totalPaginas = Number(data.total_paginas ?? data.data?.total_paginas ?? Math.max(1, Math.ceil(total / this.state.itensPorPagina)));

                this.cache.regulares = {
                    usuarios: usuariosMapeados,
                    total,
                    pagina_atual: paginaAtual,
                    total_paginas: totalPaginas
                };
            } else {
                this.cache.regulares = { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 };
            }

            this.renderizarTabelaRegulares();
        } catch (error) {
            console.error('Erro ao carregar usuários regulares:', error);
            this.cache.regulares = { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 };
            this.renderizarTabelaRegulares();
        }
    }

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
            console.log('LISTAR_BLOQUEADOS API:', data);

            if (data && data.sucesso) {
                const usuariosRaw = data.usuarios ?? data.data?.usuarios ?? [];
                const usuariosMapeados = usuariosRaw.map(u => this.mapUser(u)).filter(Boolean);

                const total = Number(data.total ?? data.data?.total ?? usuariosMapeados.length ?? 0);
                const paginaAtual = Number(data.pagina_atual ?? data.data?.pagina_atual ?? this.state.paginaBloqueados);
                const totalPaginas = Number(data.total_paginas ?? data.data?.total_paginas ?? Math.max(1, Math.ceil(total / this.state.itensPorPagina)));

                this.cache.bloqueados = {
                    usuarios: usuariosMapeados,
                    total,
                    pagina_atual: paginaAtual,
                    total_paginas: totalPaginas
                };
            } else {
                this.cache.bloqueados = { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 };
            }

            this.renderizarTabelaBloqueados();
        } catch (error) {
            console.error('Erro ao carregar usuários bloqueados:', error);
            this.cache.bloqueados = { usuarios: [], total: 0, pagina_atual: 1, total_paginas: 1 };
            this.renderizarTabelaBloqueados();
        }
    }

    // ---------- RENDERS ----------
    renderizarTabelaRegulares() {
        const tbody = document.getElementById('tabelaRegulares');
        const btnAnterior = document.getElementById('btnAnteriorRegulares');
        const btnProximo = document.getElementById('btnProximoRegulares');
        const info = document.getElementById('infoRegulares');

        if (!tbody) {
            console.warn('Elemento #tabelaRegulares não encontrado no DOM.');
            return;
        }

        const { usuarios, total, pagina_atual = 1, total_paginas = 1 } = this.cache.regulares;

        if (!usuarios || usuarios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon"></div>
                        <p>Nenhum usuário regular encontrado</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = usuarios.map(user => {
                const fotoUrl = this.buildImageUrl(user.foto_perfil);
                return `
                <tr class="table-row">
                    <td>
                        <div class="user-cell">
                            <img src="${fotoUrl}" 
                                 alt="${this.escapeHtml(user.nome)}" 
                                 class="user-avatar">
                            <span>${this.escapeHtml(this.truncateText(user.nome, 30))}</span>
                        </div>
                    </td>
                    <td>${this.escapeHtml(user.numero_matricula || 'N/A')}</td>
                    <td>${this.escapeHtml(user.unidade_senac || 'N/A')}</td>
                    <td>${this.escapeHtml(this.formatPhone(user.telefone))}</td>
                    <td><span class="badge badge-success">Regular</span></td>
                    <td>
                        <button class="btn-action btn-view" data-id="${user.id_usuario}" onclick="gerenciadorUsuarios.abrirDetalhes(${user.id_usuario})">
                            Ver Detalhes
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        if (btnAnterior) btnAnterior.disabled = pagina_atual === 1;
        if (btnProximo) btnProximo.disabled = pagina_atual >= total_paginas;

        if (info) {
            const inicio = (pagina_atual - 1) * this.state.itensPorPagina + 1;
            const fim = Math.min(pagina_atual * this.state.itensPorPagina, total);
            info.textContent = total === 0 ? 'Mostrando 0 usuários' : `Mostrando ${inicio}-${fim} de ${total} usuários`;
        }
    }

    renderizarTabelaBloqueados() {
        const tbody = document.getElementById('tabelaBloqueados');
        const btnAnterior = document.getElementById('btnAnteriorBloqueados');
        const btnProximo = document.getElementById('btnProximoBloqueados');
        const info = document.getElementById('infoBloqueados');

        if (!tbody) {
            console.warn('Elemento #tabelaBloqueados não encontrado no DOM.');
            return;
        }

        const { usuarios, total, pagina_atual = 1, total_paginas = 1 } = this.cache.bloqueados;

        if (!usuarios || usuarios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon"></div>
                        <p>Nenhum usuário bloqueado</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = usuarios.map(user => {
                const fotoUrl = this.buildImageUrl(user.foto_perfil);
                return `
                <tr class="table-row">
                    <td>
                        <div class="user-cell">
                            <img src="${fotoUrl}" 
                                 alt="${this.escapeHtml(user.nome)}" 
                                 class="user-avatar">
                            <span>${this.escapeHtml(this.truncateText(user.nome, 30))}</span>
                        </div>
                    </td>
                    <td>${this.escapeHtml(user.numero_matricula || 'N/A')}</td>
                    <td>${this.escapeHtml(user.unidade_senac || 'N/A')}</td>
                    <td>${this.escapeHtml(this.formatPhone(user.telefone))}</td>
                    <td><span class="badge badge-danger">Bloqueado</span></td>
                    <td>
                        <button class="btn-action btn-view" data-id="${user.id_usuario}" onclick="gerenciadorUsuarios.abrirDetalhes(${user.id_usuario})">
                            Ver Detalhes
                        </button>
                    </td>
                </tr>
            `;
            }).join('');
        }

        if (btnAnterior) btnAnterior.disabled = pagina_atual === 1;
        if (btnProximo) btnProximo.disabled = pagina_atual >= total_paginas;

        if (info) {
            const inicio = (pagina_atual - 1) * this.state.itensPorPagina + 1;
            const fim = Math.min(pagina_atual * this.state.itensPorPagina, total);
            info.textContent = total === 0 ? 'Mostrando 0 usuários' : `Mostrando ${inicio}-${fim} de ${total} usuários`;
        }
    }

    // ---------- DETALHES / MODAL ----------
    async abrirDetalhes(idUsuario) {
        try {
            const params = new URLSearchParams({ ajax: '1', acao: 'buscar_usuario', id_usuario: idUsuario });
            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const data = await this.handleResponse(response);
            console.log('BUSCAR_USUARIO API:', data);

            if (data && data.sucesso && (data.usuario || data.data?.usuario)) {
                const usuarioRaw = data.usuario ?? data.data?.usuario ?? data.data ?? null;
                const usuario = this.mapUser(usuarioRaw);
                this.state.usuarioAtual = usuario;
                this.preencherModal(usuario);
                document.getElementById('modalUsuario').style.display = 'flex';
            } else {
                this.mostrarErro(data.erro || 'Usuário não encontrado');
            }
        } catch (error) {
            console.error('Erro ao buscar usuário:', error);
            this.mostrarErro('Erro ao carregar dados do usuário');
        }
    }

    preencherModal(usuario) {
        const u = usuario ?? {};
        const foto = this.buildImageUrl(u.foto_perfil ?? u.foto ?? '');
        const elFoto = document.getElementById('userFoto');
        if (elFoto) elFoto.src = foto;

        const setVal = (id, value) => {
            const el = document.getElementById(id);
            if (!el) return;
            if ('value' in el) el.value = value ?? '';
            else el.textContent = value ?? '';
        };

        setVal('userName', u.nome ?? '');
        setVal('userNomeSocial', u.nome_social ?? '');
        setVal('userCPF', this.formatCPF(u.cpf ?? ''));
        setVal('userNascimento', u.data_nascimento ?? '');
        setVal('userGenero', u.genero ?? '');
        setVal('userMatricula', u.numero_matricula ?? '');
        setVal('userUnidade', u.unidade_senac ?? '');
        setVal('userCategoria', u.categoria ?? '');
        setVal('userEmail', u.email ?? '');
        setVal('userTelefone', this.formatPhone(u.telefone ?? ''));
        setVal('userEndereco', u.endereco ?? '');
        setVal('userCurso', u.curso ?? '');
        setVal('userTurma', u.turma ?? '');
        setVal('userDataFimCurso', u.data_fim_curso ?? '');
        setVal('userNotas', u.notas_usuario ?? '');

        const btnBloquear = document.getElementById('btnBloquear');
        if (btnBloquear) {
            const ativo = (typeof u.ativo !== 'undefined') ? Boolean(u.ativo) : true;
            btnBloquear.textContent = ativo ? 'Bloquear Usuário' : 'Desbloquear Usuário';
            btnBloquear.className = ativo ? 'btn btn-danger' : 'btn btn-success';
        }

        // esconder botões de edição se elemento não existir
        if (document.getElementById('btnEditar')) {
            document.getElementById('btnEditar').style.display = this.state.modoEdicao ? 'none' : 'inline-block';
        }
    }

    habilitarEdicao() {
        this.state.modoEdicao = true;
        const campos = [
            'userName', 'userNomeSocial', 'userNascimento', 'userGenero',
            'userEmail', 'userTelefone', 'userEndereco',
            'userCurso', 'userTurma', 'userDataFimCurso', 'userNotas'
        ];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.removeAttribute('readonly');
                if (campo.tagName === 'SELECT') campo.removeAttribute('disabled');
            }
        });

        const btnEditar = document.getElementById('btnEditar');
        if (btnEditar) btnEditar.style.display = 'none';
        const btnSalvar = document.getElementById('btnSalvar');
        if (btnSalvar) btnSalvar.style.display = 'inline-block';
        const btnCancelar = document.getElementById('btnCancelar');
        if (btnCancelar) btnCancelar.style.display = 'inline-block';
    }

    async salvarEdicao() {
        if (!this.state.usuarioAtual) {
            this.mostrarErro('Nenhum usuário selecionado');
            return;
        }

        try {
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

            if (!dados.nome || !dados.email) {
                this.mostrarErro('Nome e email são obrigatórios');
                return;
            }
            if (!this.validarEmail(dados.email)) {
                this.mostrarErro('Email inválido');
                return;
            }

            const id_usuario = this.state.usuarioAtual.id_usuario ?? this.state.usuarioAtual.__raw?.id_usuario ?? this.state.usuarioAtual.__raw?.id;

            const params = new URLSearchParams({ ajax: '1', acao: 'atualizar_usuario', id_usuario });
            Object.keys(dados).forEach(k => params.append(k, dados[k]));

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params
            });

            const data = await this.handleResponse(response);
            console.log('ATUALIZAR_USUARIO API:', data);

            if (data.sucesso) {
                this.mostrarSucesso(data.mensagem || 'Usuário atualizado');
                this.cancelarEdicao();
                await this.abrirDetalhes(id_usuario);
                await this.carregarDados();
                await this.carregarEstatisticas();
            } else {
                this.mostrarErro(data.erro || 'Erro ao atualizar usuário');
            }
        } catch (error) {
            console.error('Erro ao salvar edição:', error);
            this.mostrarErro('Erro ao salvar alterações');
        }
    }

    cancelarEdicao() {
        this.state.modoEdicao = false;
        const campos = [
            'userName', 'userNomeSocial', 'userNascimento', 'userGenero',
            'userEmail', 'userTelefone', 'userEndereco',
            'userCurso', 'userTurma', 'userDataFimCurso', 'userNotas'
        ];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.setAttribute('readonly', 'readonly');
                if (campo.tagName === 'SELECT') campo.setAttribute('disabled', 'disabled');
            }
        });

        const btnEditar = document.getElementById('btnEditar');
        if (btnEditar) btnEditar.style.display = 'inline-block';
        const btnSalvar = document.getElementById('btnSalvar');
        if (btnSalvar) btnSalvar.style.display = 'none';
        const btnCancelar = document.getElementById('btnCancelar');
        if (btnCancelar) btnCancelar.style.display = 'none';

        if (this.state.usuarioAtual) this.preencherModal(this.state.usuarioAtual);
    }

    async toggleBloqueio() {
        if (!this.state.usuarioAtual) {
            this.mostrarErro('Nenhum usuário selecionado');
            return;
        }

        const ativo = (typeof this.state.usuarioAtual.ativo !== 'undefined') ? Boolean(this.state.usuarioAtual.ativo) : true;
        const acao = ativo ? 'bloquearUsuario' : 'desbloquearUsuario';
        const mensagemConfirmacao = ativo ? 'Deseja realmente bloquear este usuário?' : 'Deseja realmente desbloquear este usuário?';

        if (!confirm(mensagemConfirmacao)) return;

        try {
            const id_usuario = this.state.usuarioAtual.id_usuario ?? this.state.usuarioAtual.__raw?.id_usuario ?? this.state.usuarioAtual.__raw?.id;
            const params = new URLSearchParams({ ajax: '1', acao, id_usuario });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params
            });

            const data = await this.handleResponse(response);
            console.log('TOGGLE_BLOQUEIO API:', data);

            if (data.sucesso) {
                this.mostrarSucesso(data.mensagem || 'Status alterado');
                this.fecharModal();
                await this.carregarDados();
                await this.carregarEstatisticas();
            } else {
                this.mostrarErro(data.erro || 'Erro ao alterar status');
            }
        } catch (error) {
            console.error('Erro ao alterar status:', error);
            this.mostrarErro('Erro ao alterar status do usuário');
        }
    }

    trocarAba(aba) {
        this.state.abaSelecionada = aba;
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.toggle('active', btn.dataset.tab === aba));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.toggle('active', content.id === `tab-${aba}`));
    }

    fecharModal() {
        const el = document.getElementById('modalUsuario');
        if (el) el.style.display = 'none';
        this.state.usuarioAtual = null;
        this.state.modoEdicao = false;
        this.cancelarEdicao();
    }

    async handleResponse(response) {
        if (!response.ok) {
            // tenta ler json de erro se existir
            let txt = `HTTP error! status: ${response.status}`;
            try {
                const j = await response.json();
                if (j && j.erro) txt += ` — ${j.erro}`;
            } catch (e) { /* ignore */ }
            throw new Error(txt);
        }
        return await response.json();
    }

    // ---------- UTIL ----------
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
        phone = String(phone).replace(/\D/g, '');
        if (phone.length === 11) return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        if (phone.length === 10) return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        return phone;
    }

    validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    escapeHtml(text) {
        if (text === null || typeof text === 'undefined') return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    mostrarSucesso(mensagem) {
        const el = document.getElementById('mensagemSucesso');
        if (el) el.textContent = mensagem;
        const modal = document.getElementById('modalSucesso');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => { modal.style.display = 'none'; }, 2000);
        }
    }

    mostrarErro(mensagem) {
        const el = document.getElementById('mensagemErro');
        if (el) el.textContent = mensagem;
        const modal = document.getElementById('modalErro');
        if (modal) modal.style.display = 'flex';
    }
}

// helpers globais
function fecharModalErro() { const m = document.getElementById('modalErro'); if (m) m.style.display = 'none'; }
function fecharModal() { if (window.gerenciadorUsuarios) window.gerenciadorUsuarios.fecharModal(); }

document.addEventListener('DOMContentLoaded', () => { window.gerenciadorUsuarios = new GerenciadorUsuarios(); });
