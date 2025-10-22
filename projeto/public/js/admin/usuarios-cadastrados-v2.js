/**
 * Sistema de Gerenciamento de Usuários - Versão com Banco de Dados
 * Funcionalidades: Listagem, busca, paginação, bloqueio/desbloqueio
 */

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
        // Tabs
        const tabs = document.querySelectorAll('.tab-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => this.abrirTab(e));
        });

        // Busca
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

        // Botão de bloquear/desbloquear
        const botaoBloquear = document.getElementById('botao-bloquear');
        if (botaoBloquear) {
            botaoBloquear.addEventListener('click', () => this.alternarBloqueio());
        }

        // Botão de edição
        const botaoEdicao = document.getElementById('botao-edicao');
        if (botaoEdicao) {
            botaoEdicao.addEventListener('click', () => this.alternarEdicao());
        }
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
            console.error('Erro ao fazer busca AJAX:', error);
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
            console.error('Erro ao fazer busca AJAX:', error);
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

        // Configurar paginação
        if (botaoVoltar) botaoVoltar.disabled = this.paginaAtualRegulares === 1;
        if (botaoAvancar) botaoAvancar.disabled = this.paginaAtualRegulares >= this.dadosRegulares.total_paginas;

        // Atualizar contador
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

        // Configurar paginação
        if (botaoVoltar) botaoVoltar.disabled = this.paginaAtualBloqueados === 1;
        if (botaoAvancar) botaoAvancar.disabled = this.paginaAtualBloqueados >= this.dadosBloqueados.total_paginas;

        // Atualizar contador
        this.atualizarContador('bloqueados', this.dadosBloqueados.total);
    }

    atualizarContador(tipo, total) {
        // Você pode adicionar um contador visual se desejar
        console.log(`Total de usuários ${tipo}: ${total}`);
    }

    formatarNome(nome) {
        if (!nome) return 'N/A';
        return nome.length > 30 ? nome.substring(0, 30) + '...' : nome;
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

        // Configurar botão de bloqueio
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

                // Recarregar dados
                this.carregarUsuariosRegulares();
                this.carregarUsuariosBloqueados();

                // Fechar modal após 1 segundo
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

    alternarEdicao() {
        const todosInputs = document.querySelectorAll('.inputs-editaveis');
        const botaoEdicao = document.getElementById('botao-edicao');
        const isReadOnly = todosInputs[0] && todosInputs[0].hasAttribute('readonly');

        todosInputs.forEach(input => {
            if (isReadOnly) {
                input.removeAttribute('readonly');
            } else {
                input.setAttribute('readonly', 'true');
            }
        });

        if (isReadOnly) {
            botaoEdicao.textContent = 'Salvar dados';
            if (todosInputs.length > 0) {
                todosInputs[0].focus();
            }
        } else {
            botaoEdicao.textContent = 'Editar dados';
            // Aqui você poderia implementar o salvamento dos dados editados
        }
    }

    abrirTab(event) {
        const tabName = event.target.textContent.toLowerCase().trim();

        // Atualizar classes dos botões
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');

        // Mostrar/esconder conteúdo das tabs
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
        // Criar elemento de mensagem de erro se não existir
        let erroDiv = document.querySelector('.erro-mensagem');
        if (!erroDiv) {
            erroDiv = document.createElement('div');
            erroDiv.className = 'erro-mensagem';
            erroDiv.style.cssText = 'background: #ffebee; color: #c62828; padding: 10px; margin: 10px 0; border-radius: 4px; font-weight: 500;';
            document.querySelector('.container').prepend(erroDiv);
        }

        erroDiv.textContent = mensagem;
        erroDiv.style.display = 'block';

        // Esconder após 5 segundos
        setTimeout(() => {
            erroDiv.style.display = 'none';
        }, 5000);
    }

    mostrarMensagemSucesso(mensagem) {
        // Criar elemento de mensagem de sucesso se não existir
        let sucessoDiv = document.querySelector('.sucesso-mensagem');
        if (!sucessoDiv) {
            sucessoDiv = document.createElement('div');
            sucessoDiv.className = 'sucesso-mensagem';
            sucessoDiv.style.cssText = 'background: #e8f5e8; color: #2e7d32; padding: 10px; margin: 10px 0; border-radius: 4px; font-weight: 500;';
            document.querySelector('.container').prepend(sucessoDiv);
        }

        sucessoDiv.textContent = mensagem;
        sucessoDiv.style.display = 'block';

        // Esconder após 3 segundos
        setTimeout(() => {
            sucessoDiv.style.display = 'none';
        }, 3000);
    }
}

// Funções globais para serem chamadas pelo HTML
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

// Inicializar quando DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    window.gerenciadorUsuarios = new GerenciadorUsuarios();
});