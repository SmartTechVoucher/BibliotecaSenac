
function redirectToPage() {
     window.location.href = "src/views/usuario/login.php";
}

const input = document.querySelector(".pesquisa")
const listagem = document.querySelector(".listagem")
const items = document.querySelectorAll('.listagem-item');  


input.addEventListener('focus', () => {
  listagem.classList.add('visivel');
});

input.addEventListener('blur', () => {
  setTimeout(() => {
    listagem.classList.remove('visivel');
  }, 200);
});

items.forEach(item => {
  item.addEventListener('click', () => {
    input.value = item.textContent;
    listagem.classList.remove('visivel');
  });
});






// function expandir() {
//   document.getElementById('inputExpandido').classList.add('expandido');
// }

// function recolher() {
//   document.getElementById('inputExpandido').classList.remove('expandido');
// }

// function focusInput() {
//   document.querySelector('.pesquisa').focus();
// }