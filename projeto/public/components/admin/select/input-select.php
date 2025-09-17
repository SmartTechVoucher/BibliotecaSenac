<?php
// components/selectModal.php

/**
 * Componente genérico de input com dropdown + modal
 * @param string $name ID/nome do input
 * @param string $label Label que será exibida
 * @param array $items Array de objetos {id, nome} como mock de dados
 */
function getTipo($name) {
    $map = [
        'autor' => 'autor',
        'editora' => 'unidade',
        'idioma' => 'idioma',
        'categoria' => 'categoria',
        'area' => 'area',
        'tipo-documento' => 'documento'
    ];
    return $map[$name] ?? $name;
}

function renderSelectModal($name, $label, $items = [])
{
?>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        .input-container {
            position: relative;
            width: 280px;
        }

        input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        ul.dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            background: white;
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 250px;
            overflow-y: auto;
            border-radius: 0 0 5px 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        ul.dropdown li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            cursor: pointer;
        }

        ul.dropdown li:hover {
            background: #f0f0f0;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .actions button {
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 14px;
        }

        .actions button:hover {
            color: red;
        }

        .add-author {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px;
            font-weight: bold;
            color: #007BFF;
            cursor: pointer;
            border-top: 1px solid #eee;
        }

        .add-author:hover {
            background: #eaf2ff;
        }

        .add-author span {
            font-size: 18px;
            font-weight: bold;
        }

        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .modal-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-cancelar {
            background: #ccc;
        }

        .btn-salvar {
            background: #007BFF;
            color: white;
        }
    </style>    
    <label for="<?= $name ?>"><?= $label ?></label>
    <div class="input-container">
        <input type="text" id="<?= $name ?>" data-tipo="<?= getTipo($name) ?>" placeholder="Digite <?= strtolower($label) ?>">
        <ul id="<?= $name ?>_dropdown" class="dropdown" style="display:none;"></ul>
    </div>
    <input type="hidden" name="<?= $name ?>" id="hidden-<?= $name ?>" value="">

    <div id="<?= $name ?>_modal" class="modal">
        <div class="modal-content">
            <h2 id="<?= $name ?>_modalTitle">Cadastrar <?= $label ?></h2>
            <input type="text" id="<?= $name ?>_novoItem" placeholder="Nome do <?= strtolower($label) ?>">
            <?php if (getTipo($name) === 'autor'): ?>
            <input type="text" id="<?= $name ?>_nacionalidade" placeholder="Nacionalidade (opcional)">
            <?php endif; ?>
            <div class="modal-buttons">
                <button class="btn-cancelar">Cancelar</button>
                <button class="btn-salvar">Salvar</button>
            </div>
        </div>
    </div>

    <script>
        /*FUNCAO IIFE*/
        (function() {
            const input = document.getElementById("<?= $name ?>");
            const dropdown = document.getElementById("<?= $name ?>_dropdown");
            const modal = document.getElementById("<?= $name ?>_modal");
            const novoInput = document.getElementById("<?= $name ?>_novoItem");
            const btnCancelar = modal.querySelector(".btn-cancelar");
            const btnSalvar = modal.querySelector(".btn-salvar");
            const modalTitle = document.getElementById("<?= $name ?>_modalTitle");

            const tipo = input.dataset.tipo;
            let items = [];
            let editId = null;

            // Função para carregar itens do servidor
            async function loadItems() {
                try {
                    const url = `../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=listar`;
                    console.log('AJAX Load URL:', url);
                    const response = await fetch(url);
                    console.log('Load Response Status:', response.status);
                    const data = await response.json();
                    console.log('Load Response:', data);
                    if (data.sucesso) {
                        items = data[`${tipo}s`] || [];
                        if (input.value.trim()) {
                            await searchItems(input.value.trim().toLowerCase());
                        }
                    } else {
                        console.error('Erro ao carregar itens:', data.mensagem);
                    }
                } catch (error) {
                    console.error('Erro na requisição:', error);
                }
            }

            // Função para buscar itens (filtro no servidor)
            async function searchItems(query) {
                try {
                    const url = `../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=buscar&query=${encodeURIComponent(query)}`;
                    console.log('AJAX Search URL:', url);
                    const response = await fetch(url);
                    console.log('Search Response Status:', response.status);
                    const data = await response.json();
                    console.log('Search Response:', data);
                    if (data.sucesso) {
                        items = data[`${tipo}s`] || [];
                        renderDropdown();
                    } else {
                        console.error('Erro ao buscar itens:', data.mensagem);
                    }
                } catch (error) {
                    console.error('Erro na requisição:', error);
                }
            }

            // Função para renderizar dropdown
            function renderDropdown() {
                dropdown.innerHTML = "";
                if (items.length === 0) {
                    dropdown.style.display = "none";
                    return;
                }

                items.forEach((item, i) => {
                    const li = document.createElement("li");
                    
                    const span = document.createElement("span");
                    span.textContent = item.nome;
                    span.style.cursor = "pointer";
                    span.onclick = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Item selected:', item.nome, 'ID:', item.id);
                        input.value = item.nome;
                        input.dataset.selectedId = item.id;
                        const hidden = document.getElementById('hidden-' + input.id);
                        if (hidden) {
                            hidden.value = item.id;
                            console.log('Hidden value set to:', item.id);
                        }
                        dropdown.style.display = "none";
                        input.blur();
                        console.log('Dropdown closed, input blurred');
                    };

                    const actions = document.createElement("div");
                    actions.classList.add("actions");

                    const btnEditar = document.createElement("button");
                    btnEditar.textContent = "✏️";
                    btnEditar.title = "Editar";
                    btnEditar.onclick = (e) => {
                        e.stopPropagation();
                        editId = item.id;
                        modalTitle.textContent = "Editar <?= $label ?>";
                        novoInput.value = item.nome;
                        modal.style.display = "flex";
                    };

                    const btnExcluir = document.createElement("button");
                    btnExcluir.textContent = "🗑️";
                    btnExcluir.title = "Excluir";
                    btnExcluir.onclick = async (e) => {
                        e.stopPropagation();
                        if (confirm(`Tem certeza que deseja excluir "${item.nome}"?`)) {
                            const formData = new FormData();
                            formData.append('id', item.id);
                            try {
                                const response = await fetch(`../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=excluir`, {
                                    method: 'POST',
                                    body: formData
                                });
                                const data = await response.json();
                                if (data.sucesso) {
                                    items = items.filter(it => it.id !== item.id);
                                    renderDropdown();
                                } else {
                                    alert(data.mensagem);
                                }
                            } catch (error) {
                                console.error('Erro ao excluir:', error);
                                alert('Erro ao excluir item.');
                            }
                        }
                    };

                    actions.appendChild(btnEditar);
                    actions.appendChild(btnExcluir);
                    
                    li.appendChild(span);
                    li.appendChild(actions);
                    dropdown.appendChild(li);
                });

                const addLi = document.createElement("li");
                addLi.classList.add("add-author");
                addLi.innerHTML = `<span>+</span> Cadastrar <?= $label ?>`;
                addLi.onclick = () => {
                    editId = null;
                    modalTitle.textContent = "Cadastrar <?= $label ?>";
                    novoInput.value = input.value.trim();
                    modal.style.display = "flex";
                };
                dropdown.appendChild(addLi);

                dropdown.style.display = "block";
            }

            // Carrega itens iniciais
            loadItems();

            input.addEventListener("input", async () => {
                const query = input.value.trim().toLowerCase();
                if (query.length >= 1) {
                    await searchItems(query);
                } else if (query.length === 0) {
                    await loadItems();
                }
            });

            btnSalvar.onclick = async () => {
                const nome = novoInput.value.trim();
                if (!nome) return;

                const formData = new FormData();
                formData.append('nome', nome);
                if (editId) {
                    formData.append('id', editId);
                }

                try {
                    const url = `../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=${editId ? 'atualizar' : 'cadastrar'}`;
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.sucesso) {
                        if (editId) {
                            // Atualiza item local
                            const index = items.findIndex(it => it.id == editId);
                            if (index > -1) {
                                items[index].nome = nome;
                            }
                        } else {
                            // Adiciona novo item
                            const newItem = {id: data.id, nome: nome};
                            items.unshift(newItem);
                            input.dataset.selectedId = data.id;
                        }
                        input.value = nome;
                        alert(data.mensagem);
                        await loadItems(); // Recarrega para consistência
                    } else {
                        alert(data.mensagem);
                    }
                } catch (error) {
                    console.error('Erro na requisição:', error);
                    alert('Erro ao salvar item.');
                }

                modal.style.display = "none";
                dropdown.style.display = "none";
                editId = null;
                novoInput.value = '';
            };

            btnCancelar.onclick = () => {
                modal.style.display = "none";
                editId = null;
                novoInput.value = '';
            };
            
            document.addEventListener("click", e => {
                if (!input.contains(e.target) && !dropdown.contains(e.target) && !modal.contains(e.target)) {
                    dropdown.style.display = "none";
                }
            });
        })(); // End of IIFE
    </script>
<?php
}
?>