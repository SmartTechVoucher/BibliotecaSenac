// cadastro-usuarios.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Preview da foto de perfil
    const arquivoDeEntrada = document.getElementById('foto-usuario'); 
    const fotoPerfil = document.getElementById('foto-perfil'); 

    if (arquivoDeEntrada && fotoPerfil) {
        arquivoDeEntrada.addEventListener('change', function (event) {
            if (event.target.files && event.target.files[0]) {
                const leitor = new FileReader();
                leitor.onload = function (e) {
                    fotoPerfil.src = e.target.result;
                };
                leitor.readAsDataURL(event.target.files[0]);
            }
        });
    }

    // Máscaras para campos
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }

    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Validação do formulário
    const form = document.getElementById('cadastro-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const senha = document.getElementById('senha_usuario').value;
            const senhaConfirm = document.getElementById('senha_usuario_confirm').value;

            if (senha !== senhaConfirm) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                return false;
            }

            if (senha.length < 6) {
                e.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres!');
                return false;
            }

            // Validação de email
            const email = document.getElementById('email').value;
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Digite um email válido!');
                return false;
            }
        });
    }

    // Botão cancelar
    const btnCancelar = document.querySelector('.botao-cancelar');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja cancelar? Todos os dados serão perdidos.')) {
                window.history.back();
            }
        });
    }
});