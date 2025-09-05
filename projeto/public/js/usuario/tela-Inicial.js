
console.log("JS carregado!");

function redirectToPage() {
    window.location.href = "src/views/usuario/login.php";
}

window.addEventListener("DOMContentLoaded", () => {
    const span = document.querySelector('.nome-usuario');
    if (span) {
        const texto = span.dataset.nome || span.textContent;
        const chLength = texto.length;
        span.style.setProperty('--char-count', chLength);
        span.style.setProperty('--char-ch', `${chLength}ch`);
    }
});


const toggleMenuLateral = document.getElementById('menu-toggle');
const menuLateral = document.getElementById('menu-lateral');
const overlay = document.getElementById('overlay');

toggleMenuLateral.addEventListener("click", () => {
    console.log("clicou");
    menuLateral.classList.toggle("ativo");
    overlay.classList.toggle("ativo");
});

overlay.addEventListener("click", () => {
    menuLateral.classList.remove("ativo");
    overlay.classList.remove("ativo");
});



const input = document.querySelector(".pesquisa")
const listagem = document.querySelector(".listagem")
const historicoUL = document.querySelector(".listagem ul");

let historico = JSON.parse(localStorage.getItem('historicoPesquisa')) || [];
let clicandoExcluir = false;

input.addEventListener('focus', () => {
    listagem.classList.add('visivel');
});

input.addEventListener('blur', () => {
    setTimeout(() => {
        listagem.classList.remove('visivel');
    }, 200);
});

function mostrarHistorico() {
    historicoUL.innerHTML = '';

    if (historico.length === 0) {
        const catalogoMock = [
            'Literatura Brasileira',
            'Ciência',
            'História',
            'Infantil',
            'Artes',
            'Filosofia',
            'Tecnologia',
            'Biografias'
        ];
        catalogoMock.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            li.className = 'listagem-item';
            li.style.cursor = 'pointer';

            li.addEventListener('mousedown', () => {
                input.value = item;
                listagem.classList.remove('visivel');

            });

            const excluirIcone = document.createElement('img');
            excluirIcone.src = './public/assets/icons/excluir.svg';
            console.log(excluirIcone);

            excluirIcone.alt = 'Excluir';
            excluirIcone.style.width = '20px';
            excluirIcone.style.height = '20px';
            excluirIcone.style.cursor = 'pointer';
            excluirIcone.style.marginLeft = '10px';

            excluirIcone.addEventListener('mousedown', (e) => {
                clicandoExcluir = true;
                e.preventDefault();
                e.stopPropagation();
                historico = historico.filter(h => h !== item);
                localStorage.setItem('historicoPesquisa', JSON.stringify(historico));

                li.remove();
            });

            historicoUL.appendChild(li);
        });

        listagem.classList.add('visivel');
        return;
    }

    let sugestoes = [];

    if (input.value.trim() === '') {
        sugestoes = historico;
    } else {
        const valor = input.value.trim().toLowerCase();
        sugestoes = historico.filter(item => item.toLowerCase().startsWith(valor));
    }

    sugestoes.forEach(item => {
        const li = document.createElement('li');
        li.className = 'listagem-item';
        li.style.display = 'flex';
        li.style.justifyContent = 'space-between';
        li.style.alignItems = 'center';

        const spanTexto = document.createElement('span');
        spanTexto.textContent = item;
        spanTexto.style.cursor = 'pointer';

        spanTexto.addEventListener('mousedown', () => {
            input.value = item;
            listagem.classList.remove('visivel');
        });

        const excluirIcone = document.createElement('img');
        excluirIcone.src = './public/assets/icons/excluir.png';
        excluirIcone.alt = 'Excluir';
        excluirIcone.style.width = '20px';
        excluirIcone.style.height = '20px';
        excluirIcone.style.cursor = 'pointer';
        excluirIcone.style.marginLeft = '10px';
        excluirIcone.style.hover = 'background-color: #dadada;'

        excluirIcone.addEventListener('mousedown', (e) => {
            clicandoExcluir = true;
            e.preventDefault();
            e.stopPropagation();
            historico = historico.filter(h => h !== item);
            localStorage.setItem('historicoPesquisa', JSON.stringify(historico));

            li.remove();
        });

        li.appendChild(spanTexto);
        li.appendChild(excluirIcone);

        historicoUL.appendChild(li);
    });

    listagem.classList.add('visivel');
}

input.addEventListener('input', mostrarHistorico);
input.addEventListener('focus', mostrarHistorico);

input.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const valor = input.value.trim();

        if (valor && !historico.includes(valor)) {
            historico.unshift(valor);
            if (historico.length > 10) historico.pop();
            localStorage.setItem('historicoPesquisa', JSON.stringify(historico));
        }

        listagem.classList.remove('visivel');
        historicoUL.innerHTML = '';
    }
});

input.addEventListener('blur', () => {
    setTimeout(() => {
        if (!clicandoExcluir) {
            listagem.classList.remove('visivel');
        }
        clicandoExcluir = false;
    }, 150);
});


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


