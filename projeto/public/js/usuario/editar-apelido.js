// Função para carregar o apelido salvo quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        const campoApelido = document.querySelector('.campo-apelido .input-field');
        const btnEditar = document.querySelector('.btn-editar');
        
        // Carregar apelido salvo do localStorage
        const apelidoSalvo = localStorage.getItem('usuario_apelido');
        if (apelidoSalvo) {
            campoApelido.value = apelidoSalvo;
        }
        
        // Variável para controlar o estado de edição
        let editando = false;
        
        // Função para alternar entre modo de edição e visualização
        btnEditar.addEventListener('click', function() {
            if (!editando) {
                // Entrar em modo de edição
                editando = true;
                
                // Habilitar o campo para edição
                campoApelido.removeAttribute('readonly');
                campoApelido.focus();
                campoApelido.classList.add('editando');
                
                // Mudar texto e estilo do botão
                btnEditar.textContent = 'Salvar';
                btnEditar.classList.add('btn-salvar');
                
                // Adicionar botão de cancelar
                const btnCancelar = document.createElement('button');
                btnCancelar.textContent = 'Cancelar';
                btnCancelar.className = 'btn-cancelar';
                btnEditar.parentNode.appendChild(btnCancelar);
                
                // Salvar o valor original para caso de cancelamento
                btnEditar.dataset.valorOriginal = campoApelido.value;
                
                // Evento para o botão cancelar
                btnCancelar.addEventListener('click', function() {
                    cancelarEdicao();
                });
                
                // Permitir salvar com Enter
                campoApelido.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        salvarApelido();
                    }
                });
                
                // Permitir cancelar com Escape
                campoApelido.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        cancelarEdicao();
                    }
                });
                
            } else {
                // Salvar alterações
                salvarApelido();
            }
        });
        
        // Função para salvar o apelido
        function salvarApelido() {
            const novoApelido = campoApelido.value.trim();
            
            // Validar se o apelido não está vazio
            if (novoApelido === '') {
                alert('O apelido não pode estar vazio!');
                campoApelido.focus();
                return;
            }
            
            // Validar tamanho do apelido (opcional)
            if (novoApelido.length > 20) {
                alert('O apelido deve ter no máximo 20 caracteres!');
                campoApelido.focus();
                return;
            }
            
            // Salvar no localStorage
            localStorage.setItem('usuario_apelido', novoApelido);
            
            // Sair do modo de edição
            finalizarEdicao();
            
            // Mostrar mensagem de sucesso
            mostrarMensagem('Apelido salvo com sucesso!', 'sucesso');
        }
        
        // Função para cancelar a edição
        function cancelarEdicao() {
            // Restaurar valor original
            campoApelido.value = btnEditar.dataset.valorOriginal;
            
            // Sair do modo de edição
            finalizarEdicao();
            
            // Mostrar mensagem de cancelamento
            mostrarMensagem('Edição cancelada!', 'info');
        }
        
        // Função para finalizar a edição (salvar ou cancelar)
        function finalizarEdicao() {
            editando = false;
            
            // Desabilitar o campo
            campoApelido.setAttribute('readonly', 'readonly');
            campoApelido.classList.remove('editando');
            
            // Restaurar botão original
            btnEditar.textContent = 'Editar apelido';
            btnEditar.classList.remove('btn-salvar');
            
            // Remover botão de cancelar se existir
            const btnCancelar = document.querySelector('.btn-cancelar');
            if (btnCancelar) {
                btnCancelar.remove();
            }
        }
        
        // Função para mostrar mensagens temporárias
        function mostrarMensagem(texto, tipo) {
            // Remover mensagem anterior se existir
            const mensagemAnterior = document.querySelector('.mensagem-temporaria');
            if (mensagemAnterior) {
                mensagemAnterior.remove();
            }
            
            // Criar elemento de mensagem
            const mensagem = document.createElement('div');
            mensagem.className = 'mensagem-temporaria';
            mensagem.textContent = texto;
            
            // Adicionar classe baseada no tipo
            if (tipo === 'erro') mensagem.classList.add('erro');
            if (tipo === 'info') mensagem.classList.add('info');
            if (tipo === 'sucesso') mensagem.classList.add('sucesso');
            
            // Adicionar mensagem ao documento
            document.body.appendChild(mensagem);
            
            // Remover mensagem após 3 segundos
            setTimeout(() => {
                mensagem.classList.add('saindo');
                setTimeout(() => {
                    if (mensagem.parentNode) {
                        mensagem.remove();
                    }
                }, 300);
            }, 3000);
        }
    });