<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Novo Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Registro de Novo Usuário</h1>
            <p>Preencha os dados abaixo para cadastrar um novo usuário.</p>
        </header>

        <form id="user-form">
            <fieldset class="form-section">
                <legend>Informações Pessoais</legend>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Maria da Silva" required>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" placeholder="999.999.999-99" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999">
                </div>
                
                <div class="form-group">
                    <label for="endereco">Endereço Completo</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Ex: Rua das Flores, 123, Centro">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome_social">Nome Social</label>
                        <input type="text" id="nome_social" name="nome_social" placeholder="Ex: João" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="genero">Gênero</label>
                        <select id="genero" name="genero">
                            <option value="">Selecione</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="nao_binario">Não Binário</option>
                            <option value="outros">Outros</option>
                            <option value="nao_informar">Prefiro não informar</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Informações Acadêmicas</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label for="matricula">Nº de Matrícula</label>
                        <input type="text" id="matricula" name="matricula" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione</option>
                            <option value="graduacao">Graduação</option>
                            <option value="pos">Pós-Graduação</option>
                            <option value="extensao">Extensão</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="curso">Curso</label>
                        <input type="text" id="curso" name="curso" placeholder="Ex: Engenharia Civil" required>
                    </div>
                    <div class="form-group">
                        <label for="turma">Turma</label>
                        <input type="text" id="turma" name="turma" placeholder="Ex: 2025-1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="data_fim_curso">Data de Término do Curso</label>
                    <input type="date" id="data_fim_curso" name="data_fim_curso">
                </div>
            </fieldset>

            <div class="button-group">
                <button type="submit">Salvar Usuário</button>
                <button type="button" class="cancel-button">Cancelar</button>
            </div>
        </form>
    </div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --background-color: #f8f9fa;
    --card-background: #ffffff;
    --border-color: #ced4da;
    --text-color: #333;
    --error-color: #dc3545;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: var(--background-color);
    color: var(--text-color);
    line-height: 1.6;
    padding: 2rem 1rem;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
    background-color: var(--card-background);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

header {
    text-align: center;
    margin-bottom: 2rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1.5rem;
}

h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

p {
    font-size: 1rem;
    color: var(--secondary-color);
}

.form-section {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

legend {
    font-size: 1.3rem;
    font-weight: 500;
    color: var(--primary-color);
    padding: 0 0.5rem;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.form-group {
    flex: 1 1 45%; /* Permite duas colunas em telas maiores */
    margin-bottom: 1rem;
}

label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #555;
}

input, select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 1rem;
    box-sizing: border-box; /* Garante que padding e border não aumentem a largura */
}

input:focus, select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
}

.error-message {
    color: var(--error-color);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: none; /* Inicia escondida */
}

.form-group.invalid input,
.form-group.invalid select {
    border-color: var(--error-color);
}

.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

button {
    padding: 12px 24px;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"] {
    background-color: var(--primary-color);
    color: white;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.cancel-button {
    background-color: var(--secondary-color);
    color: white;
}

.cancel-button:hover {
    background-color: #5a6268;
}

@media (max-width: 600px) {
    .form-row {
        flex-direction: column;
    }
}
    </style>
    
    <script src="script.js">
        document.addEventListener('DOMContentLoaded', () => {
    const userForm = document.getElementById('user-form');
    const cpfInput = document.getElementById('cpf');
    const telefoneInput = document.getElementById('telefone');

    // Máscara para CPF
    cpfInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o primeiro ponto
        value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona o segundo ponto
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona o traço
        e.target.value = value.substring(0, 14); // Limita o tamanho
    });

    // Máscara para Telefone
    telefoneInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
        if (value.length > 10) {
            value = value.replace(/^(\d\d)(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 5) {
            value = value.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d\d)(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/^(\d*)/, '($1');
        }
        e.target.value = value;
    });

    // Adiciona validação ao enviar o formulário
    userForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Impede o envio padrão do formulário
        
        // Simula uma validação mais complexa
        const formIsValid = validateForm();

        if (formIsValid) {
            alert('Usuário registrado com sucesso!');
            userForm.reset();
        } else {
            alert('Por favor, corrija os erros no formulário.');
        }
    });

    // Função de validação geral
    function validateForm() {
        let isValid = true;
        const requiredFields = document.querySelectorAll('input[required], select[required]');

        requiredFields.forEach(field => {
            const parentGroup = field.closest('.form-group');
            const errorMessage = parentGroup.querySelector('.error-message') || document.createElement('span');
            errorMessage.className = 'error-message';
            errorMessage.textContent = 'Este campo é obrigatório.';

            if (field.value.trim() === '') {
                parentGroup.classList.add('invalid');
                if (!parentGroup.contains(errorMessage)) {
                    parentGroup.appendChild(errorMessage);
                }
                errorMessage.style.display = 'block';
                isValid = false;
            } else {
                parentGroup.classList.remove('invalid');
                errorMessage.style.display = 'none';
            }
        });

        // Adicione validações específicas aqui, se necessário
        // Ex: validação de formato de CPF, etc.

        return isValid;
    }
});
    </script>
</body>
</html>