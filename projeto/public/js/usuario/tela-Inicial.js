
console.log("JS carregado!");

function redirectToPage2() {
    window.location.href = "../usuario/login.php";
}

window.addEventListener("DOMContentLoaded", () => {
    const span = document.querySelector('.nome-usuario');
    if (span) {
        const texto = span.dataset.nome || span.textContent;
        const chLength = texto.length;
        span.style.setProperty('--char-count', chLength);
        span.style.setProperty('--char-ch', `${chLength}ch`);
    }

    // Carregar categorias no dropdown
    carregarCategorias();

    // Inicializar busca AJAX
    inicializarBuscaAjax();
});

const input = document.querySelector(".pesquisa");
const listagem = document.querySelector(".listagem");
const historicoUL = document.querySelector(".listagem ul");
const categoriaSelect = document.getElementById("categoria-select");

let timeoutBusca;
let categoriasCarregadas = [];
let clicandoExcluir = false;

input.addEventListener('focus', () => {
    listagem.classList.add('visivel');
});

input.addEventListener('blur', () => {
    setTimeout(() => {
        if (!clicandoExcluir) {
            listagem.classList.remove('visivel');
        }
        clicandoExcluir = false;
    }, 150);
});

function inicializarBuscaAjax() {
    // Busca em tempo real conforme usuário digita (AJAX Key Tracking)
    input.addEventListener('input', function() {
        const termo = this.value.trim();
        const categoriaSelecionada = categoriaSelect ? categoriaSelect.value : '';

        // Mostra indicador de loading
        listagem.classList.add('loading');

        // Limpa timeout anterior
        if (timeoutBusca) {
            clearTimeout(timeoutBusca);
        }

        // Define novo timeout para buscar após 300ms (otimizado para key tracking)
        timeoutBusca = setTimeout(() => {
            buscarLivrosAjax(termo, categoriaSelecionada);
        }, 300);
    });

    // Busca também quando categoria é alterada
    if (categoriaSelect) {
        categoriaSelect.addEventListener('change', function() {
            const termo = input.value.trim();
            const categoriaSelecionada = this.value;

            // Busca imediata quando categoria muda
            buscarLivrosAjax(termo, categoriaSelecionada);
        });
    }

    // Enter para buscar
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const termo = input.value.trim();
            const categoriaSelecionada = categoriaSelect ? categoriaSelect.value : '';

            if (timeoutBusca) {
                clearTimeout(timeoutBusca);
            }

            buscarLivrosAjax(termo, categoriaSelecionada);
            listagem.classList.remove('visivel');
        }
    });
}

async function buscarLivrosAjax(termo, categoria) {
    try {
        const params = new URLSearchParams({
            ajax: '1',
            acao: 'buscar_livros',
            termo: termo,
            limit: '10'
        });

        if (categoria) {
            params.append('categoria', categoria);
        }

        const response = await fetch(`${URLBASE}/src/controller/usuario/busca-controller.php?${params}`);

        if (!response.ok) {
            throw new Error(`Erro HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();

        // Remove indicador de loading
        listagem.classList.remove('loading');

        if (data.sucesso) {
            mostrarResultadosBusca(data.livros, termo);

            // Log para debug (apenas em desenvolvimento)
            if (data.livros.length > 0) {
                console.log(`Encontrados ${data.livros.length} livros para "${termo}"`);
            }
        } else {
            console.error('Erro na busca:', data.erro);
            mostrarMensagemErro(data.erro || 'Erro ao buscar livros');
        }
    } catch (error) {
        console.error('Erro ao fazer busca AJAX:', error);
        listagem.classList.remove('loading');
        mostrarMensagemErro('Erro de conexão. Verifique sua internet e tente novamente.');
    }
}

function mostrarResultadosBusca(livros, termo) {
    historicoUL.innerHTML = '';

    if (livros.length === 0) {
        mostrarMensagemNenhumResultado(termo);
        return;
    }

    livros.forEach(livro => {
        const li = document.createElement('li');
        li.className = 'listagem-item livro-item';
        li.style.display = 'flex';
        li.style.alignItems = 'center';
        li.style.padding = '8px 12px';
        li.style.cursor = 'pointer';
        li.style.borderBottom = '1px solid #eee';

        const img = document.createElement('img');
        img.src = livro.imagem;
        img.alt = livro.titulo;
        img.style.width = '40px';
        img.style.height = '50px';
        img.style.objectFit = 'cover';
        img.style.marginRight = '12px';
        img.style.borderRadius = '4px';

        const divInfo = document.createElement('div');
        divInfo.style.flex = '1';

        const titulo = document.createElement('div');
        titulo.className = 'titulo-livro-busca';
        titulo.textContent = livro.titulo;
        titulo.style.fontWeight = 'bold';
        titulo.style.fontSize = '14px';
        titulo.style.marginBottom = '2px';

        const autor = document.createElement('div');
        autor.className = 'autor-livro-busca';
        autor.textContent = livro.autor;
        autor.style.fontSize = '12px';
        autor.style.color = '#666';

        const categoria = document.createElement('div');
        categoria.className = 'categoria-livro-busca';
        categoria.textContent = livro.categoria_nome;
        categoria.style.fontSize = '11px';
        categoria.style.color = '#888';

        divInfo.appendChild(titulo);
        divInfo.appendChild(autor);
        divInfo.appendChild(categoria);

        li.appendChild(img);
        li.appendChild(divInfo);

        li.addEventListener('mousedown', () => {
            input.value = livro.titulo;
            listagem.classList.remove('visivel');

            // Redirecionar para página do livro
            window.location.href = `${URLBASE}/src/views/usuario/livro-info.php?id=${livro.id_livro}`;
        });

        historicoUL.appendChild(li);
    });

    listagem.classList.add('visivel');
}

function mostrarMensagemNenhumResultado(termo) {
    historicoUL.innerHTML = '';

    const li = document.createElement('li');
    li.className = 'listagem-item nenhum-resultado';
    li.style.padding = '16px';
    li.style.textAlign = 'center';
    li.style.color = '#666';
    li.style.fontSize = '14px';

    if (termo) {
        li.textContent = `Nenhum livro encontrado para "${termo}"`;
    } else {
        li.textContent = 'Digite algo para buscar livros';
    }

    historicoUL.appendChild(li);
    listagem.classList.add('visivel');
}

function mostrarMensagemErro(erro) {
    historicoUL.innerHTML = '';

    const li = document.createElement('li');
    li.className = 'listagem-item erro-busca';
    li.style.padding = '16px';
    li.style.textAlign = 'center';
    li.style.color = '#d32f2f';
    li.style.fontSize = '14px';

    li.textContent = erro;

    historicoUL.appendChild(li);
    listagem.classList.add('visivel');
}

async function carregarCategorias() {
    try {
        const response = await fetch(`${URLBASE}/src/controller/usuario/busca-controller.php?ajax=1&acao=get_categorias`);

        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }

        const data = await response.json();

        if (data.sucesso) {
            categoriasCarregadas = data.categorias;
            criarDropdownCategorias(data.categorias);
        } else {
            console.error('Erro ao carregar categorias:', data.erro);
        }
    } catch (error) {
        console.error('Erro ao carregar categorias:', error);
    }
}

function criarDropdownCategorias(categorias) {
    // Verificar se o container já existe
    let filtrosContainer = document.getElementById('filtros-busca-container');

    if (!filtrosContainer) {
        filtrosContainer = document.createElement('div');
        filtrosContainer.className = 'filtros-busca';
        filtrosContainer.id = 'filtros-busca-container';
        filtrosContainer.style.display = 'flex';
        filtrosContainer.style.alignItems = 'center';
        filtrosContainer.style.gap = '12px';
        filtrosContainer.style.marginTop = '10px';
        filtrosContainer.style.padding = '10px';
        filtrosContainer.style.backgroundColor = '#f8f9fa';
        filtrosContainer.style.borderRadius = '8px';
        filtrosContainer.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';

        // Inserir após da barra de pesquisa
        const barraPesquisa = document.querySelector('.barrapesquisa');
        if (barraPesquisa) {
            barraPesquisa.parentNode.insertBefore(filtrosContainer, barraPesquisa.nextSibling);
        }
    }

    // Criar label
    const label = document.createElement('label');
    label.textContent = 'Filtrar por categoria:';
    label.style.fontSize = '14px';
    label.style.fontWeight = '500';
    label.style.color = '#333';
    label.setAttribute('for', 'categoria-select');

    // Criar select
    const select = document.createElement('select');
    select.id = 'categoria-select';
    select.className = 'categoria-select';
    select.style.flex = '1';
    select.style.padding = '10px 12px';
    select.style.border = '1px solid #ddd';
    select.style.borderRadius = '6px';
    select.style.fontSize = '14px';
    select.style.backgroundColor = 'white';
    select.style.cursor = 'pointer';
    select.style.transition = 'border-color 0.2s ease';

    // Opção padrão
    const opcaoPadrao = document.createElement('option');
    opcaoPadrao.value = '';
    opcaoPadrao.textContent = '🔍 Todas as categorias';
    select.appendChild(opcaoPadrao);

    // Adicionar categorias do banco
    categorias.forEach(categoria => {
        const opcao = document.createElement('option');
        opcao.value = categoria.id;
        opcao.textContent = categoria.nome;
        select.appendChild(opcao);
    });

    // Limpar container e adicionar elementos
    filtrosContainer.innerHTML = '';
    filtrosContainer.appendChild(label);
    filtrosContainer.appendChild(select);

    // Tornar container visível
    filtrosContainer.style.display = 'flex';

    console.log(`Dropdown de categorias criado com ${categorias.length} categorias`);
}


window.addEventListener("DOMContentLoaded", () => { })

function toggleMenu() {
    const navMenu = document.getElementById("nav-menu");
    const isOpen = navMenu.style.display === "block";

    navMenu.style.display = isOpen ? "none" : "block";

    // Se abrir, ativa escuta para cliques fora
    if (!isOpen) {
        document.addEventListener('click', handleClickForaMenuGeral);
    }
}


function handleClickForaMenuGeral(event) {
    const menuGeral = document.getElementById("nav-menu");
    const iconeGeral = document.getElementById("menu-icone");

    if (!menuGeral.contains(event.target) && !iconeGeral.contains(event.target)) {
        menuGeral.style.display = "none";
        document.removeEventListener('click', handleClickForaMenuGeral);
    }
}



function toggleMenuPerfil() {
    const navPerfil = document.getElementById("nav-menu-perfil");
    const isOpen = navPerfil.style.display === "block";
    navPerfil.style.display = isOpen ? "none" : "block";

    // Se abrir, ativa escuta para cliques fora
    if (!isOpen) {
        document.addEventListener('click', handleClickForaMenu);
    }
}

function handleClickForaMenu(event) {
    const menu = document.getElementById("nav-menu-perfil");
    const icone = document.getElementById("icone-pessoa");

    // Se o clique for fora do menu e fora do ícone, fecha o menu
    if (!menu.contains(event.target) && !icone.contains(event.target)) {
        menu.style.display = "none";
        document.removeEventListener('click', handleClickForaMenu); // remove listener
    }
}


function confirmarSaida(event) {
    event.preventDefault();
    showModal(
        'confirmModal',
        'Você tem certeza que deseja sair?',
        function () {
            window.location.href = baseUrl + '/index.php';
        }
    );
}

function showModal(modalId, mensagem, onConfirm) {
    const modal = document.getElementById(modalId);
    const messageEl = document.getElementById(`${modalId}Message`);
    const confirmBtn = document.getElementById(`${modalId}ConfirmBtn`);
    const cancelBtn = document.getElementById(`${modalId}CancelBtn`);

    if (messageEl) messageEl.textContent = mensagem;

    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.addEventListener('click', () => {
        closeModal(modalId);
        if (onConfirm) onConfirm();
    });

    cancelBtn.addEventListener('click', () => closeModal(modalId));

    modal.style.display = 'flex';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
}

function toggleMenu(event) {
    event.stopPropagation(); // evita que o clique feche imediatamente
    const menu = event.currentTarget.querySelector(".menu-dropdown");
    const isVisible = menu.style.display === "block";
    document.querySelectorAll(".menu-dropdown").forEach(m => m.style.display = "none");
    menu.style.display = isVisible ? "none" : "block";
}

document.addEventListener("click", () => {
    document.querySelectorAll(".menu-dropdown").forEach(m => m.style.display = "none");
});


