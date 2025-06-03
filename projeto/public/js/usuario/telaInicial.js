
function redirectToPage() {
     window.location.href = "src/views/usuario/login.php";
}


function expandir() {
  document.getElementById('inputExpandido').classList.add('expandido');
}

function recolher() {
  document.getElementById('inputExpandido').classList.remove('expandido');
}

function focusInput() {
  document.querySelector('.pesquisa').focus();
}