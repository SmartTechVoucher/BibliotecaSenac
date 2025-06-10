
function showModal(modalId, message, onConfirm, onCancel = null) {
    const modal = document.getElementById(modalId);
    const messageElement = document.getElementById(modalId + 'Message');
    const confirmBtn = document.getElementById(modalId + 'ConfirmBtn');

    if (message) {
        messageElement.textContent = message;
    }

    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.addEventListener('click', function () {
        if (onConfirm && typeof onConfirm === 'function') {
            onConfirm();
        }
        closeModal(modalId);
    });

    modal.style.display = 'flex';

    modal.onclick = function (e) {
        if (e.target === modal) {
            closeModal(modalId);
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        }
    };

    modal.querySelector('.modal-container').onclick = function (e) {
        e.stopPropagation();
    };
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'none';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-overlay[style*="flex"]');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
    }
});
