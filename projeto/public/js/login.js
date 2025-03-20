
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#campo_senha');

togglePassword.addEventListener('click', function () {
    // Alterna o tipo de input entre 'password' e 'text'
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Alterna o ícone
    this.classList.toggle('fa-eye-slash');
});