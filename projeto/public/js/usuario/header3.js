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
