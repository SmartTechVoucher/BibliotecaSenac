document.addEventListener('DOMContentLoaded', function () {
  const cloneBtn = document.getElementById('comentario-botao');
  const conteinerDeRevisoes = document.getElementById('reviewsContainer');

  // envia o comentário
  cloneBtn.addEventListener('click', async function () {
    const comentario = document.getElementById('comentario-input').value;
    const avaliacao = document.getElementById('rating-value').value;
    const idLivro = document.getElementById('idLivro').value; // input hidden no HTML
    const idUsuario = 1; // depois substitui pelo id do usuário logado

    const response = await fetch('comentarios.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id_usuario: idUsuario,
        id_livro: idLivro,
        comentario: comentario,
        avaliacao: avaliacao,
      }),
    });

    const result = await response.json();
    if (result.sucesso) {
      alert('Comentário enviado!');
      location.reload();
    } else {
      alert('Erro ao enviar: ' + (result.erro || 'desconhecido'));
    }
  });
});

// carrega comentários existentes
window.addEventListener('DOMContentLoaded', async () => {
  const idLivro = document.getElementById('idLivro').value;
  const conteinerDeRevisoes = document.getElementById('reviewsContainer');

  const response = await fetch(`comentarios.php?id_livro=${idLivro}`);
  const comentarios = await response.json();

  comentarios.forEach((c) => {
    const template = document.getElementById('commentTemplate');
    const novo = template.cloneNode(true);
    novo.style.display = 'block';
    novo.id = '';

    novo.querySelector('.commentTitulo').textContent = c.nome;
    novo.querySelector('.commentConteudo').textContent = c.comentario;
    novo.querySelector('.commentUserinfo').textContent =
      `Feito em: ${new Date(c.data_comentario).toLocaleDateString('pt-BR')}`;
    novo.querySelector('.estrela-placeholder').src =
      `../../../../projeto/public/assets/icons/estrelas${c.avaliacao}.png`;

    conteinerDeRevisoes.appendChild(novo);
  });
  document.addEventListener('DOMContentLoaded', function () {
  const cloneBtn = document.getElementById('comentario-botao');
  if (!cloneBtn) return; // previne erro se o botão não existir
  cloneBtn.addEventListener('click', async function () {
    const comentario = document.getElementById('comentario-input').value;
    const avaliacao = document.getElementById('rating-value').value;
    const idLivroEl = document.getElementById('idLivro');
    if (!idLivroEl) { alert('Falta input hidden #idLivro no HTML'); return; }
    const idLivro = idLivroEl.value;
    const idUsuario = 1; // ajustar depois para usuário logado

    try {
      const response = await fetch('comentarios.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_usuario: idUsuario, id_livro: idLivro, comentario, avaliacao })
      });
      const result = await response.json();
      if (result.sucesso) {
        alert('Comentário enviado!');
        location.reload();
      } else {
        alert('Erro ao enviar: ' + (result.erro || 'desconhecido'));
      }
    } catch (err) {
      alert('Erro de rede: ' + err.message);
      console.error(err);
    }
  });
});

// carrega comentários existentes
window.addEventListener('DOMContentLoaded', async () => {
  const idLivroEl = document.getElementById('idLivro');
  if (!idLivroEl) return;
  const idLivro = idLivroEl.value;
  const conteinerDeRevisoes = document.getElementById('reviewsContainer');
  if (!conteinerDeRevisoes) return;

  try {
    const response = await fetch(`comentarios.php?id_livro=${encodeURIComponent(idLivro)}`);
    if (!response.ok) {
      console.error('Fetch GET status', response.status, await response.text());
      return;
    }
    const comentarios = await response.json();
    comentarios.forEach(c => {
      const template = document.getElementById('commentTemplate');
      if (!template) return;
      const novo = template.cloneNode(true);
      novo.style.display = 'block';
      novo.id = '';

      const titulo = novo.querySelector('.commentTitulo');
      const conteudo = novo.querySelector('.commentConteudo');
      const userinfo = novo.querySelector('.commentUserinfo');
      const starImg = novo.querySelector('.estrela-placeholder');

      if (titulo) titulo.textContent = c.nome || 'Usuário';
      if (conteudo) conteudo.textContent = c.comentario;
      if (userinfo) userinfo.textContent = `Feito em: ${new Date(c.data_comentario).toLocaleDateString('pt-BR')}`;
      if (starImg) starImg.src = `../../../../projeto/public/assets/icons/estrelas${c.avaliacao}.png`;

      conteinerDeRevisoes.appendChild(novo);
    });
  } catch (err) {
    console.error('Erro carregando comentários', err);
  }
});
});
