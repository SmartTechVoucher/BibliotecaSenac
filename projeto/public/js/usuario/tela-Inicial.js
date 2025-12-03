/**
 * Variáveis Globais de Busca e Elementos
 */
const inputBusca = document.querySelector(".pesquisa");
const listaBusca = document.querySelector(".listagem");
const historicoUL = document.querySelector(".listagem ul");
// Pega o elemento select, mesmo que ele seja criado dinamicamente
let categoriaSelect = document.getElementById("categoria-select"); 

let timeoutBusca;
let categoriasCarregadas = [];
let clicandoExcluir = false; // Variável para controlar o evento blur/click

/**
 * URLs
 * Assume-se que 'URLBASE' está definido no PHP/HTML (ex: <script>const URLBASE = '...';</script>)
 */
const ENDPOINT_BUSCA = `${URLBASE}/src/controller/usuario/busca-controller.php`;

/**
 * --------------------------------
 * 1. Inicialização da Página
 * --------------------------------
 */

document.addEventListener("DOMContentLoaded", () => {
    // 1.1. Ajuste CSS (para o nome do usuário)
    ajustarSpanNomeUsuario();

    // 1.3. Inicializar eventos de busca e filtro
    inicializarBuscaEventos();
});


function ajustarSpanNomeUsuario() {
    const span = document.querySelector('.nome-usuario');
    if (span) {
        const texto = span.dataset.nome || span.textContent;
        const chLength = texto.length;
        // Estas propriedades CSS parecem ser usadas para animações de digitação (char-by-char)
        span.style.setProperty('--char-count', chLength);
        span.style.setProperty('--char-ch', `${chLength}ch`);
    }
}

// ------------------------------------------------------------------

/**
 * --------------------------------
 * 2. Funções de Busca AJAX
 * --------------------------------
 */

function inicializarBuscaEventos() {
    // Evento Foco/Blur (para mostrar/esconder a lista)
    inputBusca.addEventListener('focus', () => {
        listaBusca.classList.add('visivel');
        // Se o input estiver vazio, mostre a mensagem padrão
        if (inputBusca.value.trim() === '') {
            mostrarMensagemNenhumResultado('');
        }
    });

    inputBusca.addEventListener('blur', () => {
        // Delay para permitir o clique em um resultado antes de esconder
        setTimeout(() => {
            if (!clicandoExcluir) {
                listaBusca.classList.remove('visivel');
            }
            clicandoExcluir = false;
        }, 150);
    });

    // Evento de Digitação (Input)
    inputBusca.addEventListener('input', function() {
        // Limpar timeout anterior (debounce)
        if (timeoutBusca) {
            clearTimeout(timeoutBusca);
        }

        const termo = this.value.trim();
        listaBusca.classList.add('loading');
        
        // Se o termo estiver vazio, mostra mensagem padrão e limpa
        if (termo.length === 0) {
            listaBusca.classList.remove('loading');
            mostrarMensagemNenhumResultado('');
            return;
        }

        // Define novo timeout para buscar após 300ms
        timeoutBusca = setTimeout(() => {
            // Re-checa o elemento select caso ele tenha sido criado dinamicamente
            categoriaSelect = document.getElementById("categoria-select"); 
            const categoriaSelecionada = categoriaSelect ? categoriaSelect.value : '';
            buscarLivrosAjax(termo, categoriaSelecionada);
        }, 300);
    });

    // Evento de Tecla (Enter)
    inputBusca.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (timeoutBusca) {
                clearTimeout(timeoutBusca);
            }
            
            categoriaSelect = document.getElementById("categoria-select");
            const categoriaSelecionada = categoriaSelect ? categoriaSelect.value : '';
            const termo = inputBusca.value.trim();
            
            // Busca e esconde a lista (comportamento de busca "definitiva")
            buscarLivrosAjax(termo, categoriaSelecionada);
            listaBusca.classList.remove('visivel'); 
        }
    });
    
    // Evento do Botão Lupa (se houver um ID, como você mencionou 'lupaId' no HTML)
    const botaoLupa = document.getElementById('lupaId');
    if (botaoLupa) {
        botaoLupa.addEventListener('click', () => {
            categoriaSelect = document.getElementById("categoria-select");
            const categoriaSelecionada = categoriaSelect ? categoriaSelect.value : '';
            const termo = inputBusca.value.trim();
            buscarLivrosAjax(termo, categoriaSelecionada);
            listaBusca.classList.remove('visivel'); 
        });
    }
}

async function buscarLivrosAjax(termo, categoria) {
    if (termo.length < 2 && termo.length !== 0) {
        mostrarMensagemErro('Digite pelo menos 2 caracteres para buscar');
        listaBusca.classList.remove('loading');
        return;
    }
    
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

        const url = `${ENDPOINT_BUSCA}?${params}`;
        
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP ${response.status}: ${response.statusText}`);
        }

        // 🚨 NOVO TRATAMENTO DE JSON: Obtém o texto e tenta o parse
        const responseText = await response.text(); 
        let data;
        
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error("Erro ao fazer parse do JSON. Resposta do servidor (Verifique o PHP!):", responseText);
            throw new Error("A resposta do servidor não é JSON válida. (Erro de sintaxe PHP?)"); 
        }
        
        listaBusca.classList.remove('loading');

        if (data.sucesso) {
            mostrarResultadosBusca(data.livros, termo);
        } else {
            console.error('Erro na busca:', data.erro);
            mostrarMensagemErro(data.erro || 'Erro ao buscar livros');
        }
    } catch (error) {
        console.error('Erro de rede ou JSON inválido:', error);
        listaBusca.classList.remove('loading');
        // Mensagem genérica para erros de rede ou parsing
        mostrarMensagemErro('Erro de conexão ou resposta inválida. Tente novamente.');
    }
}

// ------------------------------------------------------------------

function criarDropdownCategorias(categorias) {
    let filtrosContainer = document.getElementById('filtros-busca-container');
    const barraPesquisa = document.querySelector('.barrapesquisa');

    // Cria o container de filtros se não existir
    if (!filtrosContainer) {
        filtrosContainer = document.createElement('div');
        filtrosContainer.className = 'filtros-busca';
        filtrosContainer.id = 'filtros-busca-container';
        filtrosContainer.style.cssText = 'display: flex; align-items: center; gap: 12px; margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);';
        
        if (barraPesquisa) {
             barraPesquisa.parentNode.insertBefore(filtrosContainer, barraPesquisa.nextSibling);
        }
    }
    
    // Cria ou seleciona o elemento select
    let select = document.getElementById('categoria-select');
    if (!select) {
        select = document.createElement('select');
        select.id = 'categoria-select';
        select.className = 'categoria-select';
        
        // Criar label e adicionar ao container
        const label = document.createElement('label');
        label.textContent = 'Filtrar por categoria:';
        label.setAttribute('for', 'categoria-select');
        label.style.cssText = 'font-size: 14px; font-weight: 500; color: #333;';
        
        filtrosContainer.innerHTML = ''; 
        filtrosContainer.appendChild(label);
        filtrosContainer.appendChild(select);
    }
    
    // Limpar opções existentes
    select.innerHTML = '';

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
    
    // Estilos do select
    select.style.cssText = 'flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background-color: white; cursor: pointer; transition: border-color 0.2s ease;';

    // Tornar container visível
    filtrosContainer.style.display = 'flex';
    
    console.log(`Dropdown de categorias criado com ${categorias.length} categorias`);
}

// ------------------------------------------------------------------

/**
 * --------------------------------
 * 4. Funções de Renderização de Resultados e Mensagens
 * --------------------------------
 */

function mostrarResultadosBusca(livros, termo) {
    historicoUL.innerHTML = '';

    if (livros.length === 0) {
        mostrarMensagemNenhumResultado(termo);
        return;
    }

    livros.forEach(livro => {
        const li = document.createElement('li');
        li.className = 'listagem-item livro-item';
        li.style.cssText = 'display: flex; align-items: center; padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;';
        
        const img = document.createElement('img');
        img.src = livro.imagem || `${URLBASE}/public/assets/images/placeholder.png`;
        img.alt = livro.titulo;
        img.style.cssText = 'width: 40px; height: 50px; object-fit: cover; margin-right: 12px; border-radius: 4px;';
        
        const divInfo = document.createElement('div');
        divInfo.style.flex = '1';

        const titulo = document.createElement('div');
        titulo.className = 'titulo-livro-busca';
        titulo.textContent = livro.titulo;
        titulo.style.cssText = 'font-weight: bold; font-size: 14px; margin-bottom: 2px;';

        const autor = document.createElement('div');
        autor.className = 'autor-livro-busca';
        autor.textContent = livro.autor || 'Autor Desconhecido';
        autor.style.cssText = 'font-size: 12px; color: #666;';

        const categoria = document.createElement('div');
        categoria.className = 'categoria-livro-busca';
        categoria.textContent = livro.categoria_nome || 'Sem Categoria';
        categoria.style.cssText = 'font-size: 11px; color: #888;';

        divInfo.appendChild(titulo);
        divInfo.appendChild(autor);
        divInfo.appendChild(categoria);

        li.appendChild(img);
        li.appendChild(divInfo);

        li.addEventListener('mousedown', () => {
            clicandoExcluir = true; 
            inputBusca.value = livro.titulo;
            listaBusca.classList.remove('visivel');

            window.location.href = `${URLBASE}/src/views/usuario/livro-info.php?id=${livro.id_livro}`;
        });

        historicoUL.appendChild(li);
    });

    listaBusca.classList.add('visivel');
}

function mostrarMensagemNenhumResultado(termo) {
    historicoUL.innerHTML = '';

    const li = document.createElement('li');
    li.className = 'listagem-item nenhum-resultado';
    li.style.cssText = 'padding: 16px; text-align: center; color: #666; font-size: 14px;';

    if (termo) {
        li.textContent = `Nenhum livro encontrado para "${termo}"`;
    } else {
        li.textContent = 'Digite algo para buscar livros';
    }

    historicoUL.appendChild(li);
    listaBusca.classList.add('visivel');
}

function mostrarMensagemErro(erro) {
    historicoUL.innerHTML = '';

    const li = document.createElement('li');
    li.className = 'listagem-item erro-busca';
    li.style.cssText = 'padding: 16px; text-align: center; color: #d32f2f; font-size: 14px;';

    li.textContent = `🚨 ${erro}`;

    historicoUL.appendChild(li);
    listaBusca.classList.add('visivel');
}

// ------------------------------------------------------------------

/**
 * --------------------------------
 * 5. Funções de Navegação e Menu (Componentes)
 * --------------------------------
 */

function redirectToPage2() {
    window.location.href = "../usuario/login.php";
}

function toggleMenuGeral() {
    const navMenu = document.getElementById("nav-menu");
    const isOpen = navMenu.style.display === "block";

    navMenu.style.display = isOpen ? "none" : "block";

    if (!isOpen) {
        document.addEventListener('click', handleClickForaMenuGeral);
    } else {
        document.removeEventListener('click', handleClickForaMenuGeral);
    }
}

function handleClickForaMenuGeral(event) {
    const menuGeral = document.getElementById("nav-menu");
    const iconeGeral = document.getElementById("menu-icone");

    if (menuGeral && iconeGeral && !menuGeral.contains(event.target) && !iconeGeral.contains(event.target)) {
        menuGeral.style.display = "none";
        document.removeEventListener('click', handleClickForaMenuGeral);
    }
}

function toggleMenuPerfil() {
    const navPerfil = document.getElementById("nav-menu-perfil");
    const isOpen = navPerfil.style.display === "block";
    navPerfil.style.display = isOpen ? "none" : "block";

    if (!isOpen) {
        document.addEventListener('click', handleClickForaMenuPerfil);
    } else {
        document.removeEventListener('click', handleClickForaMenuPerfil);
    }
}

function handleClickForaMenuPerfil(event) {
    const menu = document.getElementById("nav-menu-perfil");
    const icone = document.getElementById("icone-pessoa");

    if (menu && icone && !menu.contains(event.target) && !icone.contains(event.target)) {
        menu.style.display = "none";
        document.removeEventListener('click', handleClickForaMenuPerfil); 
    }
}

function confirmarSaida(event) {
    event.preventDefault();
    if (confirm('Você tem certeza que deseja sair?')) {
         window.location.href = URLBASE + '/index.php'; 
    }
}
