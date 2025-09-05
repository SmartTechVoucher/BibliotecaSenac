<?php
// components/selectModal.php

/**
 * Componente genérico de input com dropdown + modal
 * @param string $name ID/nome do input
 * @param string $label Label que será exibida
 * @param array $items Array de objetos {id, nome} como mock de dados
 */
function renderSelectModal($name, $label, $items = [])
{
?>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
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
        <input type="text" id="<?= $name ?>" placeholder="Digite <?= strtolower($label) ?>">
        <ul id="<?= $name ?>_dropdown" class="dropdown" style="display:none;"></ul>
    </div>

    <div id="<?= $name ?>_modal" class="modal">
        <div class="modal-content">
            <h2 id="<?= $name ?>_modalTitle">Cadastrar <?= $label ?></h2>
            <input type="text" id="<?= $name ?>_novoItem" placeholder="Nome do <?= strtolower($label) ?>">
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

            let items = <?= json_encode($items) ?>;
            let editIndex = null;

            input.addEventListener("input", () => {
                renderDropdown(input.value.trim().toLowerCase());
            });

            function renderDropdown(query) {
                dropdown.innerHTML = "";
                if (!query) {
                    dropdown.style.display = "none";
                    return;
                }

                const filtrados = items.map((item, i) => ({
                        ...item,
                        i
                    }))
                    .filter(i => i.nome.toLowerCase().includes(query));

                filtrados.forEach(({ nome, id, i }) => {
                    const li = document.createElement("li");
                    
                    const span = document.createElement("span");
                    span.textContent = nome;
                    span.onclick = () => {
                        input.value = nome;
                        dropdown.style.display = "none";
                    };

                    const actions = document.createElement("div");
                    actions.classList.add("actions");

                    const btnEditar = document.createElement("button");
                    btnEditar.textContent = "✏️";
                    btnEditar.title = "Editar";
                    btnEditar.onclick = (e) => {
                        e.stopPropagation();
                        editIndex = i;
                        modalTitle.textContent = "Editar <?= $label ?>";
                        novoInput.value = items[i].nome;
                        modal.style.display = "flex";
                    };

                    const btnExcluir = document.createElement("button");
                    btnExcluir.textContent = "🗑️";
                    btnExcluir.title = "Excluir";
                    btnExcluir.onclick = (e) => {
                        e.stopPropagation();
                        if (confirm(`Tem certeza que deseja excluir "${nome}"?`)) {
                            items.splice(i, 1);
                            renderDropdown(query);
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
                    editIndex = null;
                    modalTitle.textContent = "Cadastrar <?= $label ?>";
                    novoInput.value = input.value.trim();
                    modal.style.display = "flex";
                };
                dropdown.appendChild(addLi);

                dropdown.style.display = "block";
            }

            btnSalvar.onclick = () => {
                const nome = novoInput.value.trim();
                if (!nome) return;

                if (editIndex !== null) {
                    items[editIndex].nome = nome;
                    input.value = nome;
                    alert("<?= $label ?> atualizado!");
                } else {
                    const novoId = items.length ? Math.max(...items.map(i => i.id)) + 1 : 1;
                    items.push({
                        id: novoId,
                        nome
                    });
                    input.value = nome;
                    alert("<?= $label ?> cadastrado!");
                }

                modal.style.display = "none";
                dropdown.style.display = "none";
                editIndex = null;
            };

            btnCancelar.onclick = () => {
                modal.style.display = "none";
                editIndex = null;
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