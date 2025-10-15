// toast.js - Sistema de notificações

/**
 * Mostra um toast (notificação temporária)
 * @param {string} mensagem - Texto da mensagem
 * @param {string} tipo - Tipo do toast: 'success', 'erro', 'info', 'warning'
 */
function mostrarToast(mensagem, tipo = 'success') {
  // Remove toast existente se houver
  const toastExistente = document.querySelector('.toast');
  if (toastExistente) toastExistente.remove();

  // Define as cores por tipo
  const cores = {
    'success': '#1F7E4D',
    'erro': '#dc3545',
    'error': '#dc3545',
    'info': '#17a2b8',
    'warning': '#ffc107'
  };

  // Cria o elemento toast
  const toast = document.createElement('div');
  toast.className = `toast toast-${tipo}`;
  toast.style.cssText = `
    position: fixed;
    top: 6%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background-color: ${cores[tipo] || cores['success']};
    color: ${tipo === 'warning' ? '#333' : 'white'};
    padding: 15px 25px;
    border-radius: 30px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    opacity: 1;
    transition: opacity 0.5s ease, transform 0.5s ease;
    min-width: 250px;
    max-width: 500px;
    text-align: center;
    justify-content: center;
  `;
  toast.textContent = mensagem;

  // Adiciona ao body
  document.body.appendChild(toast);

  // Animação de saída após 3 segundos
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translate(-50%, -100%)';
  }, 3000);

  // Remove do DOM após animação
  setTimeout(() => {
    toast.remove();
  }, 3500);
}

// Tratamento de toasts existentes no carregamento da página
document.addEventListener('DOMContentLoaded', () => {
  const toast = document.querySelector('.toast');
  if (toast) {
    // Se já existe um toast no HTML (ex: de erro PHP)
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translate(-50%, -100%)';
    }, 3000);

    setTimeout(() => {
      toast.remove();
    }, 3500);
  }
});