document.addEventListener('DOMContentLoaded', function() {
    const campoNomeSocial = document.querySelector('.campo-apelido .input-field');
    const btnEditar = document.querySelector('.btn-editar');
    
    if (!campoNomeSocial || !btnEditar) {
        console.error('Elementos não encontrados');
        return;
    }
    
    let editando = false;
    let valorOriginal = '';
    
    btnEditar.addEventListener('click', function() {
        if (!editando) {
            // Entrar em modo de edição
            editando = true;
            valorOriginal = campoNomeSocial.value;
            
            // Habilitar o campo para edição
            campoNomeSocial.removeAttribute('readonly');
            campoNomeSocial.focus();
            campoNomeSocial.select();
            campoNomeSocial.classList.add('editando');
            
            // Mudar texto e estilo do botão
            btnEditar.textContent = 'Salvar';
            btnEditar.classList.add('btn-salvar');
            
            // Adicionar botão de cancelar
            const btnCancelar = document.createElement('button');
            btnCancelar.textContent = 'Cancelar';
            btnCancelar.className = 'btn-cancelar';
            btnCancelar.type = 'button';
            btnEditar.parentNode.appendChild(btnCancelar);
            
            // Evento para o botão cancelar
            btnCancelar.addEventListener('click', function() {
                cancelarEdicao();
            });
            
            // Permitir salvar com Enter
            const handlerEnter = function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    salvarNomeSocial();
                    campoNomeSocial.removeEventListener('keypress', handlerEnter);
                }
            };
            campoNomeSocial.addEventListener('keypress', handlerEnter);
            
            // Permitir cancelar com Escape
            const handlerEsc = function(e) {
                if (e.key === 'Escape') {
                    cancelarEdicao();
                    campoNomeSocial.removeEventListener('keydown', handlerEsc);
                }
            };
            campoNomeSocial.addEventListener('keydown', handlerEsc);
            
        } else {
            // Salvar alterações
            salvarNomeSocial();
        }
    });
    
    // Função para salvar o nome social no BANCO DE DADOS
    function salvarNomeSocial() {
        const novoNomeSocial = campoNomeSocial.value.trim();
        
        // Validar tamanho do nome social
        if (novoNomeSocial.length > 50) {
            mostrarMensagem('O nome social deve ter no máximo 50 caracteres!', 'erro');
            campoNomeSocial.focus();
            return;
        }
        
        // Desabilitar botão durante o salvamento
        btnEditar.disabled = true;
        btnEditar.textContent = 'Salvando...';
        
        // Enviar via AJAX para o servidor
        const formData = new FormData();
        formData.append('nome_social', novoNomeSocial);
        
        fetch(URLBASE + '/router.php?acao=atualizarNomeSocial', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                // Sucesso - atualizar o campo com o valor salvo
                campoNomeSocial.value = data.nome_social || novoNomeSocial;
                valorOriginal = campoNomeSocial.value;
                
                // Sair do modo de edição
                finalizarEdicao();
                
                // Mostrar mensagem de sucesso
                mostrarMensagem(data.mensagem || 'Nome social salvo com sucesso!', 'sucesso');
            } else {
                // Erro retornado pelo servidor
                mostrarMensagem(data.mensagem || 'Erro ao salvar nome social.', 'erro');
                btnEditar.disabled = false;
                btnEditar.textContent = 'Salvar';
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            mostrarMensagem('Erro de conexão. Tente novamente.', 'erro');
            btnEditar.disabled = false;
            btnEditar.textContent = 'Salvar';
        });
    }
    
    // Função para cancelar a edição
    function cancelarEdicao() {
        // Restaurar valor original
        campoNomeSocial.value = valorOriginal;
        
        // Sair do modo de edição
        finalizarEdicao();
        
        // Mostrar mensagem de cancelamento
        mostrarMensagem('Edição cancelada!', 'info');
    }
    
    // Função para finalizar a edição
    function finalizarEdicao() {
        editando = false;
        
        // Desabilitar o campo
        campoNomeSocial.setAttribute('readonly', 'readonly');
        campoNomeSocial.classList.remove('editando');
        
        // Restaurar botão original
        btnEditar.textContent = 'Editar nome social';
        btnEditar.classList.remove('btn-salvar');
        btnEditar.disabled = false;
        
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
        
        // Estilos inline para garantir visibilidade
        mensagem.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
        
        // Cores baseadas no tipo
        if (tipo === 'sucesso') {
            mensagem.style.backgroundColor = '#4CAF50';
            mensagem.style.color = 'white';
        } else if (tipo === 'erro') {
            mensagem.style.backgroundColor = '#f44336';
            mensagem.style.color = 'white';
        } else {
            mensagem.style.backgroundColor = '#2196F3';
            mensagem.style.color = 'white';
        }
        
        // Adicionar mensagem ao documento
        document.body.appendChild(mensagem);
        
        // Remover mensagem após 3 segundos
        setTimeout(() => {
            mensagem.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (mensagem.parentNode) {
                    mensagem.remove();
                }
            }, 300);
        }, 3000);
    }
});