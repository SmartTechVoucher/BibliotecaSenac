<?php
// components/selectModal.php

/**
 * Mapeia o nome do input (ex: 'autor') para o nome da entidade na API (ex: 'autor').
 * @param string $name ID/nome do input.
 * @return string Nome da entidade.
 */
function getTipo($name): string
{
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

/**
 * Detecta o artigo (conectivo) correto ("o", "a", "os", "as")
 * com base no gênero e número do label, priorizando casos especiais.
 *
 * @param string $label O label do campo (ex: 'Idioma').
 * @return string O artigo/conectivo (ex: 'o').
 */
function getConectivo($label): string
{
    $labelLower = strtolower($label);

    // 1. Prioriza Casos Especiais Comuns para evitar erros de terminação.
    $mapEspecifico = [
        'autor' => 'o',
        'autores' => 'os',
        'editora' => 'a',
        'editoras' => 'as',
        'categoria' => 'a',
        'categorias' => 'as',
        'idioma' => 'o', // <-- CORREÇÃO: Garante 'o' para Idioma
        'idiomas' => 'os',
        'área' => 'a',
        'áreas' => 'as',
        'tipo-documento' => 'o'
    ];

    if (isset($mapEspecifico[$labelLower])) {
        return $mapEspecifico[$labelLower];
    }
    
    // 2. Tenta a detecção automática por terminação.
    
    // Palavras terminadas em "a" geralmente são femininas
    if (preg_match('/a(s)?$/', $labelLower)) {
        return (str_ends_with($labelLower, 'as')) ? 'as' : 'a';
    }

    // Palavras terminadas em "o" ou consoante geralmente são masculinas
    return 'o';
}

/**
 * Renderiza o componente de input com dropdown e modal para cadastros auxiliares.
 */
function renderSelectModal($name, $label, $items = [])
{
    $artigo = getConectivo($label);
    $placeholder = "Digite $artigo $label";
    $tipo = getTipo($name);
    
    // O CSS deve ser movido para um arquivo .css externo em produção.
    // Para esta refatoração, mantive o CSS inline para completude, mas envolto em <style>
?>

    <style>
        /* [Seu CSS foi mantido aqui para a demonstração, mas deve ser movido para um arquivo separado.] */
        /* ... (seu bloco <style> anterior) ... */
        label {
            font-size: 14px;
            font-weight: bold;
        }

        .input-container {
            position: relative;
            /* Defina a largura apropriada para o seu layout */
            width: 100%; 
            max-width: 280px; 
        }

        input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input::placeholder {
            color: #888;
            font-style: italic;
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
        
        /* Modal Styles */
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
    </style>

    <label for="<?= $name ?>"><?= $label ?></label>
    <div class="input-container">
        <input type="text" id="<?= $name ?>" data-name="<?= $name ?>" data-tipo="<?= $tipo ?>" placeholder="<?= $placeholder ?>" autocomplete="off">
        <ul id="<?= $name ?>_dropdown" class="dropdown" style="display:none;"></ul>
    </div>
    <input type="hidden" name="<?= $name ?>" id="hidden-<?= $name ?>" value="">

    <div id="<?= $name ?>_modal" class="modal">
        <div class="modal-content">
            <h2 data-action="title">Cadastrar <?= $label ?></h2>
            <input type="text" data-input="nome" placeholder="Nome d<?= $artigo === 'a' ? 'a' : 'o' ?> <?= strtolower($label) ?>">
            <?php if ($tipo === 'autor'): ?>
            <input type="text" data-input="nacionalidade" placeholder="Nacionalidade (opcional)">
            <?php endif; ?>
            <div class="modal-buttons">
                <button class="btn-cancelar">Cancelar</button>
                <button class="btn-salvar">Salvar</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const componentName = "<?= $name ?>";
            const tipo = "<?= $tipo ?>";
            const label = "<?= $label ?>";
            const artigo = "<?= $artigo ?>";
            
            const input = document.getElementById(componentName);
            const dropdown = document.getElementById(componentName + '_dropdown');
            const modal = document.getElementById(componentName + '_modal');
            const novoInputNome = modal.querySelector('[data-input="nome"]');
            const nacionalidadeInput = modal.querySelector('[data-input="nacionalidade"]');
            const hiddenInput = document.getElementById('hidden-' + componentName);
            
            let items = [];
            let editId = null; // Guarda o ID do item sendo editado

            /**
             * Funções de Comunicação (Fetch)
             */
            async function fetchItems(subacao, params = {}) {
                try {
                    const url = new URL('../../../router.php');
                    url.searchParams.set('acao', 'auxEntity');
                    url.searchParams.set('tipo', tipo);
                    url.searchParams.set('subacao', subacao);
                    
                    for (const key in params) {
                        url.searchParams.set(key, params[key]);
                    }

                    const response = await fetch(url.toString());
                    return await response.json();
                } catch (error) {
                    console.error(`Erro ao executar ${subacao} para ${tipo}:`, error);
                    return { sucesso: false, mensagem: 'Erro de comunicação.' };
                }
            }
            
            /**
             * Carrega todos os itens ou um subconjunto.
             */
            async function loadItems(query = '') {
                const subacao = query ? 'buscar' : 'listar';
                const params = query ? { query: query } : {};
                
                const data = await fetchItems(subacao, params);
                
                if (data.sucesso) {
                    items = data[`${tipo}s`] || [];
                    renderDropdown();
                }
            }

            /**
             * Renderiza a lista de dropdown com os itens e botões de ação.
             */
            function renderDropdown() {
                dropdown.innerHTML = "";
                dropdown.style.display = items.length > 0 ? "block" : "none";
                
                if (items.length === 0 && input.value.trim() === "") {
                    return; // Não mostra dropdown se vazio e sem texto
                }

                items.forEach((item) => {
                    const li = document.createElement("li");
                    
                    // Span de seleção (clique no texto)
                    const span = document.createElement("span");
                    span.textContent = item.nome;
                    span.onclick = () => selectItem(item.id, item.nome);
                    span.style.flex = "1";
                    span.style.cursor = "pointer";

                    // Botões de Ação (Editar/Excluir)
                    const actions = document.createElement("div");
                    actions.classList.add("actions");

                    const btnEditar = createActionButton("✏️", "Editar", (e) => openModal(e, item));
                    const btnExcluir = createActionButton("🗑️", "Excluir", (e) => deleteItem(e, item));

                    actions.appendChild(btnEditar);
                    actions.appendChild(btnExcluir);

                    li.appendChild(span);
                    li.appendChild(actions);
                    dropdown.appendChild(li);
                });

                // Botão de Cadastrar Novo
                const addLi = document.createElement("li");
                addLi.classList.add("add-author");
                addLi.innerHTML = `<span>+</span> Cadastrar ${label}`;
                addLi.onclick = () => openModal(null, null, input.value.trim()); // Passa texto atual para o modal
                dropdown.appendChild(addLi);
            }
            
            /**
             * Helper para criar botões de ação do dropdown.
             */
            function createActionButton(text, title, onClick) {
                const btn = document.createElement("button");
                btn.textContent = text;
                btn.title = title;
                btn.onclick = (e) => {
                    e.stopPropagation(); // Evita que o clique no botão feche o dropdown
                    onClick(e);
                };
                return btn;
            }

            /**
             * Seleciona um item no input e campo hidden.
             */
            function selectItem(id, nome) {
                input.value = nome;
                hiddenInput.value = id;
                dropdown.style.display = "none";
            }

            /**
             * Abre o modal de cadastro/edição.
             */
            function openModal(e, item = null, initialName = '') {
                if (e) e.stopPropagation();
                
                const modalTitle = modal.querySelector('[data-action="title"]');

                if (item) {
                    // Modo Edição
                    editId = item.id;
                    modalTitle.textContent = `Editar ${label}`;
                    novoInputNome.value = item.nome;
                    
                    if (tipo === 'autor' && nacionalidadeInput) {
                        nacionalidadeInput.value = item.nacionalidade || ''; // Assumindo que a API retorna 'nacionalidade'
                    }
                } else {
                    // Modo Cadastro
                    editId = null;
                    modalTitle.textContent = `Cadastrar ${label}`;
                    novoInputNome.value = initialName;
                    
                    if (nacionalidadeInput) nacionalidadeInput.value = '';
                }

                modal.style.display = "flex";
                novoInputNome.focus();
                dropdown.style.display = "none";
            }
            
            /**
             * Fecha o modal e reseta o estado.
             */
            function closeModal() {
                modal.style.display = "none";
                editId = null;
                novoInputNome.value = '';
                if (nacionalidadeInput) nacionalidadeInput.value = '';
            }

            /**
             * Salva ou Atualiza um item (via modal).
             */
            modal.querySelector(".btn-salvar").onclick = async (e) => {
                e.preventDefault();
                const nome = novoInputNome.value.trim();
                if (!nome) return alert('Nome é obrigatório.');

                const formData = new FormData();
                formData.append('nome', nome);
                
                if (tipo === 'autor' && nacionalidadeInput) {
                    formData.append('nacionalidade', nacionalidadeInput.value.trim());
                }

                if (editId) formData.append('id', editId);

                const subacao = editId ? 'atualizar' : 'cadastrar';
                const response = await fetch(`../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=${subacao}`, { 
                    method: 'POST', 
                    body: formData 
                });
                const data = await response.json();

                if (data.sucesso) {
                    const savedId = editId || data.id;
                    const savedItem = { id: savedId, nome: nome };
                    
                    // Se for atualização, atualiza a lista interna
                    if (editId) {
                        const index = items.findIndex(it => it.id == savedId);
                        if (index > -1) items[index] = savedItem;
                    } 
                    
                    // Seleciona e fecha
                    selectItem(savedId, nome); 
                    closeModal();
                    alert(data.mensagem);
                    
                    // Recarrega a lista para refletir a mudança (e ordenação se houver)
                    await loadItems(input.value.trim()); 
                } else {
                    alert(data.mensagem || 'Erro ao salvar item.');
                }
            };

            /**
             * Exclui um item.
             */
            async function deleteItem(e, item) {
                e.stopPropagation();
                if (!confirm(`Tem certeza que deseja excluir "${item.nome}"?`)) return;

                const formData = new FormData();
                formData.append('id', item.id);
                
                const response = await fetch(`../../../router.php?acao=auxEntity&tipo=${tipo}&subacao=excluir`, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.sucesso) {
                    alert(data.mensagem);
                    
                    // Remove da lista interna e re-renderiza
                    items = items.filter(it => it.id !== item.id);
                    renderDropdown();
                    
                    // Limpa o campo se o item excluído era o selecionado
                    if (hiddenInput.value == item.id) {
                        input.value = '';
                        hiddenInput.value = '';
                    }
                } else {
                    alert(data.mensagem || 'Erro ao excluir item.');
                }
            }


            // =======================================================
            // EVENT LISTENERS
            // =======================================================

            // Evento de input (Pesquisa com debounce)
            let searchTimeout;
            input.addEventListener("input", (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                searchTimeout = setTimeout(() => {
                    loadItems(query);
                }, 300);
            });
            
            // Foco: Exibe o dropdown
            input.addEventListener("focus", () => {
                // Se estiver vazio, carrega a lista completa
                if (items.length === 0 && input.value.trim() === "") {
                    loadItems();
                } else {
                    renderDropdown(); // Apenas re-renderiza o que já tem
                }
            });

            // Botões do Modal
            modal.querySelector(".btn-cancelar").onclick = closeModal;

            // Fechar dropdown/modal ao clicar fora
            document.addEventListener("click", e => {
                const isInput = input.contains(e.target);
                const isDropdown = dropdown.contains(e.target);
                const isModal = modal.contains(e.target);
                
                // Fecha dropdown se o clique não foi no input, dropdown ou modal
                if (!isInput && !isDropdown && !isModal) {
                    dropdown.style.display = "none";
                }
            });

            // Pre-carrega itens ao iniciar a página (se necessário)
            // loadItems(); 
        })();
    </script>

<?php
}
?>