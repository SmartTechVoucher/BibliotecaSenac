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
      { name: 'Tadinha Mora', registration: '970582024', status: 'Regular' , unidade: 'Senac Hub Academy', telefone: '6740028922', sexo: 'feminino', nomeSocial: 'Tadalafila' , CPF: '000.000.000-00' , nascimento: '2000-01-01'},
      { name: 'Danica Wia', registration: '978362024', status: 'Regular' , unidade: 'Senac Dourados', telefone: '6740028922', sexo: 'feminino' , nomeSocial: '' , CPF: '000.000.000-00' , nascimento: '2000-01-01'},
      { name: 'Tom Magaier', registration: '520362024', status: 'Regular' , unidade: 'Senac Três Lagoás', telefone: '6740028922', sexo: 'masculino' , nomeSocial: '', CPF: '000.000.000-00', nascimento: '2000-01-01'},
      { name: 'Thiago Neves', registration: '520362024', status: 'Regular' , unidade: 'Senac Hub Academy', telefone: '6740028922', sexo: 'masculino' , nomeSocial: 'Thiaguinho', CPF: '000.000.000-00', nascimento: '2000-01-01'},
      { name: 'Leticia Nunes', registration: '520362024', status: 'Regular' , unidade: 'Senac Hub Academy', telefone: '6740028922', sexo: 'outro' , nomeSocial: '', CPF: '000.000.000-00', nascimento: '2000-01-01'},
      { name: 'Isabela Bela', registration: '520362024', status: 'Regular' , unidade: 'Senac Dourados', telefone: '6740028922', sexo: 'feminino' , nomeSocial: 'Belinha', CPF: '000.000.000-00', nascimento: '2000-01-01'}
      
    ];

    let selectedUserIndex = null;

    function renderTable() {
      const userTable = document.getElementById('userTable');

      const regularUsers = users.filter(user => user.status === 'Regular');
      
      userTable.innerHTML = regularUsers.map((user, index) => `
        
        <tr>
        <td>${user.name}</td>
        <td>${user.registration}</td>
        <td>${user.unidade}</td>
        <td>${user.telefone}</td>
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
          <td>${user.unidade}</td>
          <td>${user.telefone}</td>
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
        document.getElementById('userName').value = user.name;
        document.getElementById('userNameSocial').value = user.nomeSocial;
        document.getElementById('userNascimento').value = user.nascimento;
        document.getElementById('userSexo').value = user.sexo;
        document.getElementById('userCPF').value = user.CPF;
        document.getElementById('userRegistration').value = user.registration;
        document.getElementById('userUnidade').value = user.unidade;
        document.getElementById('userTelefone').value = user.telefone;
        document.getElementById('userStatus').value = user.status;
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
        button1.style.backgroundColor = "#fbfaff";
        button2.style.backgroundColor = "#e2e1e6";
      });

      button2.addEventListener('click', function blockclick() { /* muda a cor dos botões qndo selecionado "bloqueados" */
        button2.style.backgroundColor = "#fbfaff";
        button1.style.backgroundColor = "#e2e1e6";
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