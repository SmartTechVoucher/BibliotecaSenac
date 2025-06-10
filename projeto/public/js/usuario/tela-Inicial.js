
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