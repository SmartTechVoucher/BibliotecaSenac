const users = [
  { name: 'Tadinha Mora', registration: '970582024', status: 'Regular' },
  { name: 'Danica Wia', registration: '978362024', status: 'Regular' },
  { name: 'Tom Magaier', registration: '520362024', status: 'Regular' }
];

let selectedUserIndex = null;

// Renderiza a tabela de usuários Regulares
function renderTable() {
  const userTable = document.getElementById('userTable');
  // Filtra apenas usuários com status "Regular"
  const regularUsers = users.filter(user => user.status === 'Regular');
  
  userTable.innerHTML = regularUsers.map((user, index) => `
    <tr>
      <td>${user.name}</td>
      <td>${user.registration}</td>
      <td>${user.status}</td>
      <td><button onclick="showUser('${user.name}')">Detalhes</button></td>
    </tr>
  `).join('');
}

// Renderiza a tabela de usuários Bloqueados
function renderBlockedTable() {
  const blockedTable = document.getElementById('blockedTable');
  // Filtra apenas usuários com status "Bloqueado"
  const blockedUsers = users.filter(user => user.status === 'Bloqueado');

  blockedTable.innerHTML = blockedUsers.map((user, index) => `
    <tr>
      <td>${user.name}</td>
      <td>${user.registration}</td>
      <td>${user.status}</td>
      <td><button onclick="unblockUser('${user.name}')">Desbloquear</button></td>
    </tr>
  `).join('');
}

// Exibe o modal com detalhes do usuário selecionado
function showUser(userName) {
  // Encontra o usuário pelo nome (poderia ser pela matrícula também)
  const index = users.findIndex(u => u.name === userName);
  if (index >= 0) {
    selectedUserIndex = index;
    const user = users[index];
    document.getElementById('userName').innerText = user.name;
    document.getElementById('userRegistration').innerText = user.registration;
    document.getElementById('userStatus').innerText = user.status;
    document.getElementById('userModal').style.display = 'flex';
  }
}

// Bloqueia o usuário selecionado
function blockUser() {
  if (selectedUserIndex !== null) {
    users[selectedUserIndex].status = 'Bloqueado';
    closeModal();
    renderTable();
    renderBlockedTable();
  }
}

// Desbloqueia um usuário (muda para "Regular")
function unblockUser(userName) {
  const index = users.findIndex(u => u.name === userName);
  if (index >= 0) {
    users[index].status = 'Regular';
    renderTable();
    renderBlockedTable();
  }
}

// Fecha o modal
function closeModal() {
  document.getElementById('userModal').style.display = 'none';
}

// Função de pesquisa (você pode personalizar)
function filterTable() {
  // Se quiser filtrar de verdade, aqui você pode criar um filter no array “users”
  // e então re-renderizar as tabelas com os resultados filtrados
  renderTable();
  renderBlockedTable();
}

// Abas de navegação
function openTab(evt, tabName) {
  document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
  document.getElementById(tabName).style.display = 'block';
  document.querySelectorAll('.tab-link').forEach(tab => tab.classList.remove('active'));
  evt.currentTarget.classList.add('active');
}

// Ao carregar a página, renderiza as tabelas
window.onload = () => {
  renderTable();
  renderBlockedTable();
};
  