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
    {
        name: 'Tadinha Mora',
        registration: '970582024',
        Status: 'Regular',
        unidade: 'Senac Hub Academy',
        telefone: '(67) 4002-8922', // Formatei o telefone para melhor legibilidade
        sexo: 'feminino',
        nomeSocial: 'Tadi',
        cpf: '000.000.000-00',
        nascimento: '2000-01-01',
        rgNumero: '12.345.678-9',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2018-05-10',
        nomePai: 'Pedro Mora',
        nomeMae: 'Ana Paula Mora',
        responsavel: 'Ana Paula Mora',
        telResidencial: '(67) 3300-1122',
        telComercial: '',
        celular: '(67) 99123-4567',
        outroTelefone: '',
        email: 't.mora@example.com',
        homepage: 'www.tadinhamora.com.br',
        profissao: 'Designer Gráfico',
        cargo: 'Pleno',
        endResidencial: 'Rua das Palmeiras, 100, Centro, Campo Grande - MS',
        endComercial: 'Av. Afonso Pena, 1500, Sala 50, Centro, Campo Grande - MS'
    },
    {
        name: 'Danica Wia',
        registration: '978362024',
        Status: 'Regular',
        unidade: 'Senac Dourados',
        telefone: '(67) 4002-8922',
        sexo: 'feminino',
        nomeSocial: '',
        cpf: '111.111.111-11',
        nascimento: '1995-03-15',
        rgNumero: '98.765.432-1',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2015-09-20',
        nomePai: 'João Wia',
        nomeMae: 'Carla Wia',
        responsavel: 'Carla Wia',
        telResidencial: '(67) 3422-5566',
        telComercial: '',
        celular: '(67) 99876-5432',
        outroTelefone: '(67) 3422-9900',
        email: 'danica.wia@example.com',
        homepage: '',
        profissao: 'Enfermeira',
        cargo: 'Coordenadora',
        endResidencial: 'Rua das Flores, 200, Vila Planalto, Dourados - MS',
        endComercial: 'Av. Marcelino Pires, 300, Centro, Dourados - MS'
    },
    {
        name: 'Tom Magaier',
        registration: '520362024',
        Status: 'Regular',
        unidade: 'Senac Três Lagoas',
        telefone: '(67) 4002-8922',
        sexo: 'masculino',
        nomeSocial: '',
        cpf: '222.222.222-22',
        nascimento: '1988-11-22',
        rgNumero: '54.321.098-7',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2005-01-01',
        nomePai: 'Luís Magaier',
        nomeMae: 'Helena Magaier',
        responsavel: 'Luís Magaier',
        telResidencial: '(67) 3521-7788',
        telComercial: '(67) 3521-6655',
        celular: '(67) 99654-3210',
        outroTelefone: '',
        email: 'tom.magaier@example.com',
        homepage: 'www.tom-magaier.dev',
        profissao: 'Engenheiro Civil',
        cargo: 'Gerente de Projetos',
        endResidencial: 'Rua Projetada, 50, Jardim Alvorada, Três Lagoas - MS',
        endComercial: 'Av. Filinto Müller, 800, Centro, Três Lagoas - MS'
    },
    {
        name: 'Thiago Neves',
        registration: '520362024',
        Status: 'Regular',
        unidade: 'Senac Hub Academy',
        telefone: '(67) 4002-8922',
        sexo: 'masculino',
        nomeSocial: 'Thiaguinho',
        cpf: '333.333.333-33',
        nascimento: '1992-07-08',
        rgNumero: '11.223.344-5',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2010-02-28',
        nomePai: 'Roberto Neves',
        nomeMae: 'Sandra Neves',
        responsavel: 'Sandra Neves',
        telResidencial: '(67) 3030-4040',
        telComercial: '',
        celular: '(67) 99111-2222',
        outroTelefone: '',
        email: 'thiago.neves@example.com',
        homepage: '',
        profissao: 'Administrador',
        cargo: 'Analista Financeiro',
        endResidencial: 'Travessa do Sol, 30, Bairro Novo, Campo Grande - MS',
        endComercial: 'Rua 13 de Maio, 1000, Sala 10, Centro, Campo Grande - MS'
    },
    {
        name: 'Leticia Nunes',
        registration: '520362024',
        Status: 'Regular',
        unidade: 'Senac Hub Academy',
        telefone: '(67) 4002-8922',
        sexo: 'outro',
        nomeSocial: '',
        cpf: '444.444.444-44',
        nascimento: '2001-09-05',
        rgNumero: '77.889.900-1',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2019-04-01',
        nomePai: 'Fernando Nunes',
        nomeMae: 'Patrícia Nunes',
        responsavel: 'Patrícia Nunes',
        telResidencial: '',
        telComercial: '',
        celular: '(67) 99555-4444',
        outroTelefone: '',
        email: 'leticia.nunes@example.com',
        homepage: 'portfolio.leticianunes.com',
        profissao: 'Estudante',
        cargo: 'Estagiária de Marketing',
        endResidencial: 'Avenida Mato Grosso, 500, Vila dos Ipês, Campo Grande - MS',
        endComercial: 'Rua Ceará, 2500, Ed. Comercial, Campo Grande - MS'
    },
    {
        name: 'Isabela Bela',
        registration: '520362024',
        Status: 'Regular',
        unidade: 'Senac Dourados',
        telefone: '(67) 4002-8922',
        sexo: 'feminino',
        nomeSocial: 'Belinha',
        cpf: '555.555.555-55',
        nascimento: '1998-02-18',
        rgNumero: '33.445.566-7',
        rgOrgaoEmissor: 'SSP/MS',
        rgUF: 'MS',
        rgPais: 'Brasil',
        rgDataEmissao: '2016-11-01',
        nomePai: 'José Bela',
        nomeMae: 'Cláudia Bela',
        responsavel: 'Cláudia Bela',
        telResidencial: '(67) 3411-2233',
        telComercial: '',
        celular: '(67) 99777-8888',
        outroTelefone: '',
        email: 'isabela.bela@example.com',
        homepage: '',
        profissao: 'Nutricionista',
        cargo: 'Autônoma',
        endResidencial: 'Rua do Bosque, 80, Jardim Flórida, Dourados - MS',
        endComercial: 'Avenida Presidente Vargas, 1200, Sala 3, Centro, Dourados - MS'
    }
];
    let selectedUserIndex = null;

    function renderTable() {
      const userTable = document.getElementById('userTable');

      const regularUsers = users.filter(user => user.Status === 'Regular');
      
      userTable.innerHTML = regularUsers.map((user, index) => `
        
        <tr>
        <td>${user.name}</td>
        <td>${user.registration}</td>
        <td>${user.unidade}</td>
        <td>${user.telefone}</td>
        <td>${user.Status}</td>
        <td><button onclick="showUser('${user.name}')">Detalhes</button></td>
        </tr>
      `).join('');
    }

    
    function renderBlockedTable() {
      const blockedTable = document.getElementById('blockedTable');
      
      const blockedUsers = users.filter(user => user.Status === 'Bloqueado');

      blockedTable.innerHTML = blockedUsers.map((user, index) => `
        <tr>
          <td>${user.name}</td>
          <td>${user.registration}</td>
          <td>${user.unidade}</td>
          <td>${user.telefone}</td>
          <td>${user.Status}</td>
          <td><button onclick="showUser('${user.name}')">Detalhes</button></td>
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
        document.getElementById('userCPF').value = user.cpf;
        document.getElementById('userRegistration').value = user.registration; // Preenche Matrícula
        document.getElementById('userUnidade').value = user.unidade;       // Preenche Unidade
        document.getElementById('userStatus').value = user.Status;     // Preenche Situação
        document.getElementById('userRgNumero').value = user.rgNumero;
        document.getElementById('userRgOrgaoEmissor').value = user.rgOrgaoEmissor;
        document.getElementById('userRgUF').value = user.rgUF;
        document.getElementById('userRgPais').value = user.rgPais;
        document.getElementById('userRgDataEmissao').value = user.rgDataEmissao;
        document.getElementById('userNomePai').value = user.nomePai;
        document.getElementById('userNomeMae').value = user.nomeMae;
        document.getElementById('userResponsavel').value = user.responsavel;
        document.getElementById('userTelResidencial').value = user.telResidencial;
        document.getElementById('userTelComercial').value = user.telComercial;
        document.getElementById('userCelular').value = user.celular;
        document.getElementById('userOutroTelefone').value = user.outroTelefone;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userHomepage').value = user.homepage;
        document.getElementById('userProfissao').value = user.profissao;
        document.getElementById('userCargo').value = user.cargo;
        document.getElementById('userEndResidencial').value = user.endResidencial;
        document.getElementById('userEndComercial').value = user.endComercial;
        document.getElementById('userModal').style.display = 'flex';
      }
    }

    
    function blockUser() {
      if (selectedUserIndex !== null) {
        users[selectedUserIndex].Status = 'Bloqueado';
        closeModal();
        renderTable();
        renderBlockedTable();
        console.log("Bloqueado!")
      }
    }
    function editUser() {
        
        document.getElementById('userName').readOnly = false;
        document.getElementById('userNameSocial').readOnly = false;
        document.getElementById('userNascimento').readOnly = false;
        document.getElementById('userSexo').disabled = false;
        document.getElementById('userCPF').readOnly = false;
        document.getElementById('userRegistration').readOnly = false;
        document.getElementById('userUnidade').readOnly = false;
        document.getElementById('userStatus').readOnly = false;
        document.getElementById('userRgNumero').readOnly = false;
        document.getElementById('userRgOrgaoEmissor').readOnly = false;
        document.getElementById('userRgUF').readOnly = false;
        document.getElementById('userRgPais').readOnly = false;
        document.getElementById('userRgDataEmissao').readOnly = false;
        document.getElementById('userNomePai').readOnly = false;
        document.getElementById('userNomeMae').readOnly = false;
        document.getElementById('userResponsavel').readOnly = false;
        document.getElementById('userTelResidencial').readOnly = false;
        document.getElementById('userTelComercial').readOnly = false;
        document.getElementById('userCelular').readOnly = false;
        document.getElementById('userOutroTelefone').readOnly = false;
        document.getElementById('userEmail').readOnly = false;
        document.getElementById('userHomepage').readOnly = false;
        document.getElementById('userProfissao').readOnly = false;
        document.getElementById('userCargo').readOnly = false;
        document.getElementById('userEndResidencial').readOnly = false;
        document.getElementById('userEndComercial').readOnly = false;
    }
    function unblockUser(userName) {
      const index = users.findIndex(u => u.name === userName);
      if (index >= 0) {
        users[index].Status = 'Regular';
        closeModal();
        renderTable();
        renderBlockedTable();
        console.log("Desbloqueado!")
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
    document.getElementById("botao-bloquear").addEventListener("click", blockORunblock);
    function blockORunblock() {
      if (selectedUserIndex !== null) {
          const userToModify = users[selectedUserIndex];
  
          if (userToModify.Status === "Regular") {
              blockUser();
          } else if (userToModify.Status === "Bloqueado") {
              // Pass the userName to unblockUser
              unblockUser(userToModify.name);
          }
      }
  }