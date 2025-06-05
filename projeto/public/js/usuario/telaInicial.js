const input = document.querySelector(".pesquisa")
const listagem = document.querySelector(".listagem")
const historicoUL = document.querySelector(".listagem ul");

let historico = JSON.parse(localStorage.getItem('historicoPesquisa')) || [];
let clicandoExcluir = false;


function redirectToPage() {
  window.location.href = "src/views/usuario/login.php";
}

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
    excluirIcone.src = './public/assets/icons/excluir.svg';
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