const button1 = document.getElementById('regularesBotao');
  const button2 = document.getElementById('bloqueadosBotao');

  button1.addEventListener('click', function regularclick() {
    button1.classList.add('clicado');
    button2.classList.remove('clicado');
  });

  button2.addEventListener('click', function blockclick() {
    button2.classList.add('clicado');
    button1.classList.remove('clicado');
  });

  const users = [
      { name: 'Tadinha Mora', registration: '970582024', status: 'Regular' },
      { name: 'Danica Wia', registration: '978362024', status: 'Regular' },
      { name: 'Tom Magaier', registration: '520362024', status: 'Regular' }
    ];

    let selectedUserIndex = null;

    function renderTable() {
      const userTable = document.getElementById('userTable');

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

    
    function renderBlockedTable() {
      const blockedTable = document.getElementById('blockedTable');
      
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

    function showUser(userName) {
      
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

    
    function blockUser() {
      if (selectedUserIndex !== null) {
        users[selectedUserIndex].status = 'Bloqueado';
        closeModal();
        renderTable();
        renderBlockedTable();
      }
    }

    function unblockUser(userName) {
      const index = users.findIndex(u => u.name === userName);
      if (index >= 0) {
        users[index].status = 'Regular';
        renderTable();
        renderBlockedTable();
      }
    }

    function closeModal() {
      document.getElementById('userModal').style.display = 'none';
    }

    function filterTable() {
    
      renderTable();
      renderBlockedTable();
    }

    function openTab(evt, tabName) {
      
      button1.addEventListener('click', function regularclick() { /* muda a cor dos botões qndo selecionado "regulares" */
        button1.style.backgroundColor = "#e6e6fa";
        button2.style.backgroundColor = "#d8d8f5";
      });

      button2.addEventListener('click', function blockclick() { /* muda a cor dos botões qndo selecionado "bloqueados" */
        button2.style.backgroundColor = "#e6e6fa";
        button1.style.backgroundColor = "#d8d8f5";
      });

      document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
      document.getElementById(tabName).style.display = 'block';
      document.querySelectorAll('.tab-link').forEach(tab => tab.classList.remove('active'));
      evt.currentTarget.classList.add('active');
    }

    window.onload = () => {
      renderTable();
      renderBlockedTable();
    };