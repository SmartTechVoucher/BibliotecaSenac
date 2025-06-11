function toggleMenu(abrir) {
    const navMenu = document.getElementById("nav-menu");
    if (abrir) {
        navMenu.style.display = "block";
    } else {
        navMenu.style.display = "none";
    }
}

function toggleMenuPerfil(abrir) {
    const navPerfil = document.getElementById("nav-menu-perfil");
    if (abrir) {
        navPerfil.style.display = "block";
    } else {
        navPerfil.style.display = "none";
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
