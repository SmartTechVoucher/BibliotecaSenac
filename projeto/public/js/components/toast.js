function mostrarToast(mensagem, tipo = 'success') {
  const toastExistente = document.querySelector('.toast');
  if (toastExistente) toastExistente.remove();

  const toast = document.createElement('div');
  toast.className = `toast toast-${tipo}`;
  toast.style.cssText = `
    position: fixed;
    top: 6%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background-color: ${tipo === 'success' ? '#1F7E4D' : 'red'};
    color: white;
    padding: 5px 10px;
    border-radius: 30px;
    font-size: 20px;
    font-family: Poppins, sans-serif;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    opacity: 1;
    transition: opacity 0.5s ease, transform 0.5s ease;
  `;
  toast.textContent = mensagem;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translate(-50%, -100%)';
  }, 3000);

  setTimeout(() => {
    toast.remove();
  }, 3500);
}

document.addEventListener("DOMContentLoaded", () => {
  const toast = document.querySelector(".toast");
  if (toast) {
    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translate(-50%, -100%)";
    }, 3000);

    setTimeout(() => {
      toast.remove();
    }, 5000);
  }
});
