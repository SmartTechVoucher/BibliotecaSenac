
class GerenciadorUsuarios {
    constructor() {
        this.paginaAtualRegulares = 1;
        this.paginaAtualBloqueados = 1;
        this.usuariosPorPagina = 10;
        this.usuarioSelecionado = null;
        this.buscaAtual = '';
        this.dadosRegulares = { usuarios: [], total: 0, total_paginas: 0 };
        this.dadosBloqueados = { usuarios: [], total: 0, total_paginas: 0 };

        this.inicializar();
    }

    inicializar() {
        this.carregarUsuariosRegulares();
        this.carregarUsuariosBloqueados();
        this.configurarEventListeners();
    }

    configurarEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('#confirmModalSalvar') || e.target.closest('#errorModalSalvar')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        const tabs = document.querySelectorAll('.tab-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => this.abrirTab(e));
        });

        const campoBusca = document.querySelector('input[type="text"]');
        if (campoBusca) {
            campoBusca.addEventListener('input', (e) => {
                this.buscaAtual = e.target.value;
                this.paginaAtualRegulares = 1;
                this.paginaAtualBloqueados = 1;
                this.carregarUsuariosRegulares();
                this.carregarUsuariosBloqueados();
            });
        }

        const botaoBloquear = document.getElementById('botao-bloquear');
        if (botaoBloquear) {
            botaoBloquear.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.alternarBloqueio();
            });
        }

         const botaoEdicao = document.getElementById('botao-edicao');
         if (botaoEdicao) {
             botaoEdicao.addEventListener('click', (e) => {
                 e.preventDefault();
                 e.stopPropagation();
                 this.alternarEdicao();
             });
         }

         const botaoCancelar = document.getElementById('cancelar-edicao');

        const modal = document.getElementById('userModal');
    }

    async carregarUsuariosRegulares() {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'listar_regulares',
                pagina: this.paginaAtualRegulares,
                limite: this.usuariosPorPagina,
                busca: this.buscaAtual
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);

            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }

            const data = await response.json();

            if (data.sucesso) {
                this.dadosRegulares = {
                    usuarios: data.usuarios,
                    total: data.total,
                    pagina_atual: data.pagina_atual,
                    total_paginas: data.total_paginas
                };
                this.renderizarTabelaRegulares();
            } else {
                console.error('Erro ao carregar usuários regulares:', data.erro);
                this.mostrarMensagemErro(data.erro || 'Erro ao carregar usuários');
            }
        } catch (error) {
            console.error('Erro ao fazer busca:', error);
            this.mostrarMensagemErro('Erro de conexão. Tente novamente.');
        }
    }

    async carregarUsuariosBloqueados() {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'listar_bloqueados',
                pagina: this.paginaAtualBloqueados,
                limite: this.usuariosPorPagina,
                busca: this.buscaAtual
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);

            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }

            const data = await response.json();

            if (data.sucesso) {
                this.dadosBloqueados = {
                    usuarios: data.usuarios,
                    total: data.total,
                    pagina_atual: data.pagina_atual,
                    total_paginas: data.total_paginas
                };
                this.renderizarTabelaBloqueados();
            } else {
                console.error('Erro ao carregar usuários bloqueados:', data.erro);
            }
        } catch (error) {
            console.error('Erro ao fazer busca:', error);
        }
    }

    renderizarTabelaRegulares() {
        const tabelaBody = document.getElementById('userTable');
        const botaoVoltar = document.getElementById('backButton');
        const botaoAvancar = document.getElementById('forwardButton');

        if (!tabelaBody) return;

        tabelaBody.innerHTML = '';

        if (this.dadosRegulares.usuarios.length === 0) {
            tabelaBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #666;">Nenhum usuário regular encontrado</td></tr>';
            if (botaoVoltar) botaoVoltar.disabled = true;
            if (botaoAvancar) botaoAvancar.disabled = true;
            return;
        }

        this.dadosRegulares.usuarios.forEach(usuario => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${this.formatarNome(usuario.nome)}</td>
                <td>${usuario.numero_matricula || 'N/A'}</td>
                <td>${usuario.unidade_senac || 'N/A'}</td>
                <td>${usuario.telefone || 'N/A'}</td>
                <td><span class="status-badge regular">Regular</span></td>
                <td><button class="btn-detalhes" onclick="gerenciadorUsuarios.mostrarDetalhesUsuario(${usuario.id_usuario})">Detalhes</button></td>
            `;
            tabelaBody.appendChild(tr);
        });

        if (botaoVoltar) botaoVoltar.disabled = this.paginaAtualRegulares === 1;
        if (botaoAvancar) botaoAvancar.disabled = this.paginaAtualRegulares >= this.dadosRegulares.total_paginas;

        this.atualizarContador('regulares', this.dadosRegulares.total);
    }

    renderizarTabelaBloqueados() {
        const tabelaBody = document.getElementById('blockedTable');
        const botaoVoltar = document.getElementById('backBlockedButton');
        const botaoAvancar = document.getElementById('forwardBlockedButton');

        if (!tabelaBody) return;

        tabelaBody.innerHTML = '';

        if (this.dadosBloqueados.usuarios.length === 0) {
            tabelaBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #666;">Nenhum usuário bloqueado encontrado</td></tr>';
            if (botaoVoltar) botaoVoltar.disabled = true;
            if (botaoAvancar) botaoAvancar.disabled = true;
            return;
        }

        this.dadosBloqueados.usuarios.forEach(usuario => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${this.formatarNome(usuario.nome)}</td>
                <td>${usuario.numero_matricula || 'N/A'}</td>
                <td>${usuario.unidade_senac || 'N/A'}</td>
                <td>${usuario.telefone || 'N/A'}</td>
                <td><span class="status-badge bloqueado">Bloqueado</span></td>
                <td><button class="btn-detalhes" onclick="gerenciadorUsuarios.mostrarDetalhesUsuario(${usuario.id_usuario})">Detalhes</button></td>
            `;
            tabelaBody.appendChild(tr);
        });

        if (botaoVoltar) botaoVoltar.disabled = this.paginaAtualBloqueados === 1;
        if (botaoAvancar) botaoAvancar.disabled = this.paginaAtualBloqueados >= this.dadosBloqueados.total_paginas;

        this.atualizarContador('bloqueados', this.dadosBloqueados.total);
    }

    atualizarContador(tipo, total) {
        console.log(`Total de usuários ${tipo}: ${total}`);
    }

    formatarNome(nome) {
        if (!nome) return 'N/A';
        return nome.length > 30 ? nome.substring(0, 30) + '...' : nome;
    }

    validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]/g, '');

        if (cpf.length !== 11) return false;

        if (/^(\d)\1+$/.test(cpf)) return false;

        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }

        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }

        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;

        return resto === parseInt(cpf.charAt(10));
    }

    validarTelefone(telefone) {
        telefone = telefone.replace(/[^\d]/g, '');

        return telefone.length >= 10 && telefone.length <= 11;
    }

    async mostrarDetalhesUsuario(idUsuario) {
        try {
            const params = new URLSearchParams({
                ajax: '1',
                acao: 'buscar_usuario',
                id_usuario: idUsuario
            });

            console.log('Fazendo requisição:', `${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);
            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php?${params}`);

            if (!response.ok) {
                console.error('Erro HTTP:', response.status, response.statusText);
                throw new Error('Erro na resposta do servidor');
            }

            const responseText = await response.text();
            console.log('Resposta do servidor:', responseText);

            const data = JSON.parse(responseText);

            if (data.sucesso && data.usuario) {
                this.preencherModalDetalhes(data.usuario);
                document.getElementById('userModal').style.display = 'flex';
            } else {
                this.mostrarMensagemErro(data.erro || 'Erro ao carregar dados do usuário');
            }
        } catch (error) {
            console.error('Erro ao buscar detalhes do usuário:', error);
            this.mostrarMensagemErro('Erro ao carregar dados do usuário');
        }
    }

    preencherModalDetalhes(usuario) {
        // Dados pessoais
        document.getElementById('userName').value = usuario.nome || '';
        document.getElementById('userNameSocial').value = usuario.nome_social || '';
        document.getElementById('userNascimento').value = usuario.data_nascimento || '';
        document.getElementById('userSexo').value = usuario.genero || '';
        document.getElementById('userCPF').value = usuario.cpf || '';

        // Dados de matrícula
        document.getElementById('userRegistration').value = usuario.numero_matricula || '';
        document.getElementById('userUnidade').value = usuario.unidade_senac || '';
        document.getElementById('userStatus').value = usuario.ativo ? 'Regular' : 'Bloqueado';

        // Dados de contato
        document.getElementById('userEmail').value = usuario.email || '';
        document.getElementById('userCelular').value = usuario.telefone || '';

        // Outros dados
        document.getElementById('userProfissao').value = usuario.categoria || '';
        document.getElementById('userEndResidencial').value = usuario.endereco || '';

        // Dados complementares
        document.getElementById('userCurso').value = usuario.curso || '';
        document.getElementById('userTurma').value = usuario.turma || '';
        document.getElementById('userDataFimCurso').value = usuario.data_fim_curso || '';
        document.getElementById('userNotas').value = usuario.notas_usuario || '';

        const botaoBloquear = document.getElementById('botao-bloquear');
        if (botaoBloquear) {
            botaoBloquear.textContent = usuario.ativo ? 'Bloquear usuário' : 'Desbloquear usuário';
        }

        this.usuarioSelecionado = usuario;
    }

    async alternarBloqueio() {
        if (!this.usuarioSelecionado) {
            this.mostrarMensagemErro('Nenhum usuário selecionado');
            return;
        }

        const acao = this.usuarioSelecionado.ativo ? 'bloquear_usuario' : 'desbloquear_usuario';

        try {
            const acaoMap = {
                'bloquear_usuario': 'bloquearUsuario',
                'desbloquear_usuario': 'desbloquearUsuario'
            };

            const params = new URLSearchParams({
                ajax: '1',
                acao: acaoMap[acao] || acao,
                id_usuario: this.usuarioSelecionado.id_usuario
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params
            });

            if (!response.ok) {
                throw new Error('Erro na resposta do servidor');
            }

            const data = await response.json();

            if (data.sucesso) {
                this.mostrarMensagemSucesso(data.mensagem);

                this.carregarUsuariosRegulares();
                this.carregarUsuariosBloqueados();

                setTimeout(() => {
                    this.fecharModal();
                }, 1000);

            } else {
                this.mostrarMensagemErro(data.erro || 'Erro ao alterar status do usuário');
            }
        } catch (error) {
            console.error('Erro ao alterar bloqueio:', error);
            this.mostrarMensagemErro('Erro de conexão. Tente novamente.');
        }
    }

    async alternarEdicao() {
        const todosInputs = document.querySelectorAll('.inputs-editaveis');
        const selects = document.querySelectorAll('select');
        const botaoEdicao = document.getElementById('botao-edicao');
        const botaoCancelar = document.getElementById('cancelar-edicao');
        const isReadOnly = todosInputs[0] && todosInputs[0].hasAttribute('readonly');
        console.log('🔄 === ALTERNANDO EDIÇÃO ===');
        console.log('Estado dos inputs:', isReadOnly ? 'Somente leitura' : 'Editável');
        console.log('Texto do botão:', botaoEdicao ? botaoEdicao.textContent : 'Botão não encontrado');

        if (isReadOnly) {
            todosInputs.forEach(input => {
                input.removeAttribute('readonly');
            });
            selects.forEach(select => {
                select.removeAttribute('disabled');
            });

            botaoEdicao.textContent = 'Salvar dados';
            botaoCancelar.style.display = 'inline-block';
            if (todosInputs.length > 0) {
                todosInputs[0].focus();
            }
        } else {
            console.log('wow teste');
            await this.salvarDadosEditados();
        }
    }

    async salvarDadosEditados() {
        console.log('teste salvando bglh');
        console.log('Usuário selecionado:', this.usuarioSelecionado);

        if (!this.usuarioSelecionado) {
            console.error('Nenhum usuário selecionado');
            this.mostrarModalErro('Nenhum usuário selecionado');
            return;
        }

        console.log('Usuário selecionado encontrado.');

        try {
            const getValue = (id) => {
                const element = document.getElementById(id);
                return element ? element.value : '';
            };

            const dadosFormulario = {
                nome: getValue('userName'),
                nome_social: getValue('userNameSocial'),
                email: getValue('userEmail'),
                data_nascimento: getValue('userNascimento'),
                telefone: getValue('userCelular'),
                endereco: getValue('userEndResidencial'),
                genero: getValue('userSexo'),
                numero_matricula: getValue('userRegistration'),
                categoria: getValue('userProfissao'),
                unidade_senac: getValue('userUnidade'),
                curso: getValue('userCurso'),
                turma: getValue('userTurma'),
                data_fim_curso: getValue('userDataFimCurso'),
                notas_usuario: getValue('userNotas')
            };

            if (!dadosFormulario.nome || !dadosFormulario.email) {
                this.mostrarMensagemErro('Nome e email são obrigatórios');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(dadosFormulario.email)) {
                this.mostrarMensagemErro('Email inválido');
                return;
            }

            if (dadosFormulario.cpf && !this.validarCPF(dadosFormulario.cpf)) {
                this.mostrarMensagemErro('CPF inválido');
                return;
            }

            if (dadosFormulario.telefone && !this.validarTelefone(dadosFormulario.telefone)) {
                this.mostrarMensagemErro('Telefone inválido');
                return;
            }

            const params = new URLSearchParams({
                ajax: '1',
                acao: 'atualizar_usuario',
                id_usuario: this.usuarioSelecionado.id_usuario,
                ...dadosFormulario
            });

            const response = await fetch(`${URLBASE}/src/controller/admin/GerenciarUsuariosController.php`, {
                method: 'POST',
                body: params,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            });

            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status} - ${response.statusText}`);
            }

            const responseText = await response.text();
            const data = JSON.parse(responseText);

            if (data.sucesso) {
                this.mostrarModalConfirmacao(data.mensagem);

                setTimeout(async () => {
                    await this.mostrarDetalhesUsuario(this.usuarioSelecionado.id_usuario);

                    this.carregarUsuariosRegulares();
                    this.carregarUsuariosBloqueados();

                    this.cancelarEdicao();
                }, 1500);

            } else {
                console.error('Erro do servidor:', data.erro);
                this.mostrarModalErro(data.erro || 'Erro ao atualizar usuário');
            }
        } catch (error) {
            console.error('Erro ao salvar dados:', error);
            this.mostrarMensagemErro('Erro de conexão. Tente novamente.');
        }
        console.log('fim do teste');
    }

    cancelarEdicao() {
        const todosInputs = document.querySelectorAll('.inputs-editaveis');
        const selects = document.querySelectorAll('select');
        const botaoEdicao = document.getElementById('botao-edicao');
        const botaoCancelar = document.getElementById('cancelar-edicao');

        todosInputs.forEach(input => {
            input.setAttribute('readonly', 'true');
        });
        selects.forEach(select => {
            select.setAttribute('disabled', 'true');
        });

        botaoEdicao.textContent = 'Editar dados';
        botaoCancelar.style.display = 'none';

        if (this.usuarioSelecionado) {
            this.preencherModalDetalhes(this.usuarioSelecionado);
        }
    }

    abrirTab(event) {
        const tabName = event.target.textContent.toLowerCase().trim();

        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.style.display = 'none';
        });

        if (tabName === 'regulares') {
            document.getElementById('regulares').style.display = 'block';
        } else if (tabName === 'bloqueados') {
            document.getElementById('bloqueados').style.display = 'block';
        }
    }

    mudarPagina(direction, tipo) {
        if (tipo === 'regulares') {
            this.paginaAtualRegulares += direction;
            this.carregarUsuariosRegulares();
        } else if (tipo === 'bloqueados') {
            this.paginaAtualBloqueados += direction;
            this.carregarUsuariosBloqueados();
        }
    }

    fecharModal() {
        document.getElementById('userModal').style.display = 'none';
        this.usuarioSelecionado = null;
    }

    mostrarMensagemErro(mensagem) {
        let erroDiv = document.querySelector('.erro-mensagem');
        if (!erroDiv) {
            erroDiv = document.createElement('div');
            erroDiv.className = 'erro-mensagem';
            erroDiv.style.cssText = 'background: #ffebee; color: #c62828; padding: 10px; margin: 10px 0; border-radius: 4px; font-weight: 500;';
            document.querySelector('.container').prepend(erroDiv);
        }

        erroDiv.textContent = mensagem;
        erroDiv.style.display = 'block';

        setTimeout(() => {
            erroDiv.style.display = 'none';
        }, 5000);
    }

    mostrarMensagemSucesso(mensagem) {
        let sucessoDiv = document.querySelector('.sucesso-mensagem');
        if (!sucessoDiv) {
            sucessoDiv = document.createElement('div');
            sucessoDiv.className = 'sucesso-mensagem';
            sucessoDiv.style.cssText = 'background: #e8f5e8; color: #2e7d32; padding: 10px; margin: 10px 0; border-radius: 4px; font-weight: 500;';
            document.querySelector('.container').prepend(sucessoDiv);
        }

        sucessoDiv.textContent = mensagem;
        sucessoDiv.style.display = 'block';

        setTimeout(() => {
            sucessoDiv.style.display = 'none';
        }, 3000);
    }

    mostrarModalConfirmacao(mensagem = 'Dados salvos com sucesso!') {
        console.log('modal de confirmação:', mensagem);
        const modal = document.getElementById('confirmModalSalvar');
        if (modal) {
            modal.style.display = 'flex';
        } else {
            console.error('modal de confirmação não encontrado (alg corrige)');
        }
    }

    mostrarModalErro(mensagem = 'Erro ao salvar dados') {
        console.log('mostrando modal de erro:', mensagem);
        const modal = document.getElementById('errorModalSalvar');
        const messageElement = document.getElementById('errorMessage');

        if (modal && messageElement) {
            messageElement.textContent = mensagem;
            modal.style.display = 'flex';
        } else {
            console.error('modal de erro não encontrado');
        }
    }
}

function mudarPagina(direction) {
    if (window.gerenciadorUsuarios) {
        window.gerenciadorUsuarios.mudarPagina(direction, 'regulares');
    }
}

function trocarParaPaginaDosBloqueados(direction) {
    if (window.gerenciadorUsuarios) {
        window.gerenciadorUsuarios.mudarPagina(direction, 'bloqueados');
    }
}

function fecharModalConfirmacao() {
    const modal = document.getElementById('confirmModalSalvar');
    if (modal) {
        modal.style.display = 'none';
    }
}

function fecharModalErro() {
    const modal = document.getElementById('errorModalSalvar');
    if (modal) {
        modal.style.display = 'none';
    }
}


document.addEventListener('DOMContentLoaded', () => {
    window.gerenciadorUsuarios = new GerenciadorUsuarios();
});