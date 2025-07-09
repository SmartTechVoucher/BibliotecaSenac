const button1 = document.getElementById('regularesBotao');
const botaoEdicao= document.getElementById('botao-edicao');
  const button2 = document.getElementById('bloqueadosBotao');

  const button = document.getElementsByClassName("bloqueadosBotao")

  button[0].addEventListener('click', function regularclick() {
    button[0].classList.add('active');
    button[1].classList.remove('active');
  });

  button[1].addEventListener('click', function blockclick() {
    button[1].classList.add('active');
    button[0].classList.remove('active');
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
    },
    {
                name: 'Gustavo Lima',
                registration: '600012024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99123-1234',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '666.666.666-66',
                nascimento: '1990-04-20',
                rgNumero: '12.123.123-1',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2010-01-05',
                nomePai: 'Antônio Lima',
                nomeMae: 'Fátima Lima',
                responsavel: 'Fátima Lima',
                telResidencial: '(67) 3344-5566',
                telComercial: '',
                celular: '(67) 99123-1234',
                outroTelefone: '',
                email: 'gustavo.lima@example.com',
                homepage: '',
                profissao: 'Engenheiro de Software',
                cargo: 'Desenvolvedor Pleno',
                endResidencial: 'Rua das Acácias, 250, Bairro Carandá, Campo Grande - MS',
                endComercial: 'Av. Ceará, 1000, Ed. Tech, Campo Grande - MS'
            },
            {
                name: 'Mariana Costa',
                registration: '600022024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99234-5678',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '777.777.777-77',
                nascimento: '1985-07-01',
                rgNumero: '45.456.456-7',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2008-03-10',
                nomePai: 'Paulo Costa',
                nomeMae: 'Márcia Costa',
                responsavel: 'Márcia Costa',
                telResidencial: '(67) 3522-1122',
                telComercial: '',
                celular: '(67) 99234-5678',
                outroTelefone: '',
                email: 'mariana.costa@example.com',
                homepage: '',
                profissao: 'Professora',
                cargo: 'Ensino Médio',
                endResidencial: 'Rua do Cedro, 300, Bairro Santos Dumont, Três Lagoas - MS',
                endComercial: 'Rua Paranaíba, 500, Centro, Três Lagoas - MS'
            },
            {
                name: 'Felipe Santos',
                registration: '600032024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99345-6789',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '888.888.888-88',
                nascimento: '1993-11-11',
                rgNumero: '78.789.789-0',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2012-06-20',
                nomePai: 'Ricardo Santos',
                nomeMae: 'Juliana Santos',
                responsavel: 'Juliana Santos',
                telResidencial: '(67) 3423-4455',
                telComercial: '',
                celular: '(67) 99345-6789',
                outroTelefone: '',
                email: 'felipe.santos@example.com',
                homepage: '',
                profissao: 'Arquiteto',
                cargo: 'Autônomo',
                endResidencial: 'Rua das Orquídeas, 120, Vila Progresso, Dourados - MS',
                endComercial: 'Av. Weimar Gonçalves, 800, Centro, Dourados - MS'
            },
            {
                name: 'Camila Pereira',
                registration: '600042024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99456-7890',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '999.999.999-99',
                nascimento: '1997-01-25',
                rgNumero: '01.012.012-3',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2015-08-15',
                nomePai: 'Marcelo Pereira',
                nomeMae: 'Fernanda Pereira',
                responsavel: 'Fernanda Pereira',
                telResidencial: '(67) 3388-9900',
                telComercial: '',
                celular: '(67) 99456-7890',
                outroTelefone: '',
                email: 'camila.pereira@example.com',
                homepage: '',
                profissao: 'Designer de Interiores',
                cargo: 'Júnior',
                endResidencial: 'Rua dos Cravos, 70, Bairro Monte Líbano, Campo Grande - MS',
                endComercial: 'Rua Barão do Rio Branco, 2000, Centro, Campo Grande - MS'
            },
            {
                name: 'Lucas Rocha',
                registration: '600052024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99567-8901',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '101.101.101-01',
                nascimento: '1989-09-03',
                rgNumero: '23.234.234-5',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2007-11-22',
                nomePai: 'Daniel Rocha',
                nomeMae: 'Beatriz Rocha',
                responsavel: 'Beatriz Rocha',
                telResidencial: '(67) 3322-3344',
                telComercial: '',
                celular: '(67) 99567-8901',
                outroTelefone: '',
                email: 'lucas.rocha@example.com',
                homepage: '',
                profissao: 'Contador',
                cargo: 'Sênior',
                endResidencial: 'Rua das Rosas, 150, Bairro Chácara Cachoeira, Campo Grande - MS',
                endComercial: 'Av. Afonso Pena, 3000, Ed. Empresarial, Campo Grande - MS'
            },
            {
                name: 'Julia Almeida',
                registration: '600062024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99678-9012',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '112.112.112-12',
                nascimento: '1994-12-10',
                rgNumero: '56.567.567-8',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2013-02-01',
                nomePai: 'Fernando Almeida',
                nomeMae: 'Gabriela Almeida',
                responsavel: 'Gabriela Almeida',
                telResidencial: '(67) 3424-6677',
                telComercial: '',
                celular: '(67) 99678-9012',
                outroTelefone: '',
                email: 'julia.almeida@example.com',
                homepage: '',
                profissao: 'Publicitária',
                cargo: 'Coordenadora de Mídia',
                endResidencial: 'Rua dos Ipês, 220, Jardim Tropical, Dourados - MS',
                endComercial: 'Rua Major Capilé, 700, Centro, Dourados - MS'
            },
            {
                name: 'Pedro Henrique',
                registration: '600072024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99789-0123',
                sexo: 'masculino',
                nomeSocial: 'PH',
                cpf: '123.123.123-23',
                nascimento: '1991-06-14',
                rgNumero: '89.890.890-1',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2009-09-09',
                nomePai: 'Marcos Henrique',
                nomeMae: 'Cristina Henrique',
                responsavel: 'Cristina Henrique',
                telResidencial: '(67) 3523-8899',
                telComercial: '',
                celular: '(67) 99789-0123',
                outroTelefone: '',
                email: 'pedro.henrique@example.com',
                homepage: '',
                profissao: 'Engenheiro Eletricista',
                cargo: 'Analista de Projetos',
                endResidencial: 'Rua das Acácias, 400, Bairro Jardim Primavera, Três Lagoas - MS',
                endComercial: 'Av. Rosário Congro, 1500, Centro, Três Lagoas - MS'
            },
            {
                name: 'Laura Martins',
                registration: '600082024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99890-1234',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '134.134.134-34',
                nascimento: '1999-03-08',
                rgNumero: '10.101.101-2',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2017-05-30',
                nomePai: 'Carlos Martins',
                nomeMae: 'Silvia Martins',
                responsavel: 'Silvia Martins',
                telResidencial: '(67) 3399-0011',
                telComercial: '',
                celular: '(67) 99890-1234',
                outroTelefone: '',
                email: 'laura.martins@example.com',
                homepage: '',
                profissao: 'Jornalista',
                cargo: 'Repórter',
                endResidencial: 'Rua das Violetas, 90, Bairro Tiradentes, Campo Grande - MS',
                endComercial: 'Av. Calógeras, 200, Centro, Campo Grande - MS'
            },
            {
                name: 'Diego Souza',
                registration: '600092024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99012-3456',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '145.145.145-45',
                nascimento: '1987-10-17',
                rgNumero: '34.345.345-6',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2006-07-07',
                nomePai: 'Roberto Souza',
                nomeMae: 'Valéria Souza',
                responsavel: 'Valéria Souza',
                telResidencial: '(67) 3355-6677',
                telComercial: '',
                celular: '(67) 99012-3456',
                outroTelefone: '',
                email: 'diego.souza@example.com',
                homepage: '',
                profissao: 'Advogado',
                cargo: 'Associado',
                endResidencial: 'Rua dos Lírios, 180, Bairro Santa Fé, Campo Grande - MS',
                endComercial: 'Rua 15 de Novembro, 1500, Centro, Campo Grande - MS'
            },
            {
                name: 'Sofia Gomes',
                registration: '600102024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99123-4567',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '156.156.156-56',
                nascimento: '1996-05-29',
                rgNumero: '67.678.678-9',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2014-10-10',
                nomePai: 'André Gomes',
                nomeMae: 'Paula Gomes',
                responsavel: 'Paula Gomes',
                telResidencial: '(67) 3425-9988',
                telComercial: '',
                celular: '(67) 99123-4567',
                outroTelefone: '',
                email: 'sofia.gomes@example.com',
                homepage: '',
                profissao: 'Psicóloga',
                cargo: 'Clínica',
                endResidencial: 'Rua das Acácias, 300, Jardim Guanabara, Dourados - MS',
                endComercial: 'Av. Presidente Vargas, 200, Centro, Dourados - MS'
            },
            {
                name: 'Arthur Pires',
                registration: '600112024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99234-5678',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '167.167.167-67',
                nascimento: '1986-08-01',
                rgNumero: '90.901.901-2',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2005-04-12',
                nomePai: 'Gustavo Pires',
                nomeMae: 'Renata Pires',
                responsavel: 'Renata Pires',
                telResidencial: '(67) 3524-1122',
                telComercial: '',
                celular: '(67) 99234-5678',
                outroTelefone: '',
                email: 'arthur.pires@example.com',
                homepage: '',
                profissao: 'Gerente de Vendas',
                cargo: 'Regional',
                endResidencial: 'Rua das Oliveiras, 50, Bairro Vila Nova, Três Lagoas - MS',
                endComercial: 'Av. Antônio Trajano, 1000, Centro, Três Lagoas - MS'
            },
            {
                name: 'Helena Costa',
                registration: '600122024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99345-6789',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '178.178.178-78',
                nascimento: '2000-02-20',
                rgNumero: '23.234.234-5',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2018-09-01',
                nomePai: 'Roberto Costa',
                nomeMae: 'Isabel Costa',
                responsavel: 'Isabel Costa',
                telResidencial: '(67) 3311-2233',
                telComercial: '',
                celular: '(67) 99345-6789',
                outroTelefone: '',
                email: 'helena.costa@example.com',
                homepage: '',
                profissao: 'Estudante',
                cargo: 'Estagiária de RH',
                endResidencial: 'Rua dos Girassóis, 120, Bairro Nova Lima, Campo Grande - MS',
                endComercial: 'Rua Padre João Crippa, 800, Centro, Campo Grande - MS'
            },
            {
                name: 'João Victor',
                registration: '600132024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99456-7890',
                sexo: 'masculino',
                nomeSocial: 'JV',
                cpf: '189.189.189-89',
                nascimento: '1995-07-12',
                rgNumero: '56.567.567-8',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2013-11-11',
                nomePai: 'Carlos Victor',
                nomeMae: 'Ana Victor',
                responsavel: 'Ana Victor',
                telResidencial: '(67) 3366-7788',
                telComercial: '',
                celular: '(67) 99456-7890',
                outroTelefone: '',
                email: 'joao.victor@example.com',
                homepage: '',
                profissao: 'Analista de Sistemas',
                cargo: 'Pleno',
                endResidencial: 'Rua das Acácias, 50, Bairro São Francisco, Campo Grande - MS',
                endComercial: 'Av. Mato Grosso, 1500, Ed. Tech, Campo Grande - MS'
            },
            {
                name: 'Manuela Silva',
                registration: '600142024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99567-8901',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '190.190.190-90',
                nascimento: '1988-01-05',
                rgNumero: '89.890.890-1',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2007-03-20',
                nomePai: 'Pedro Silva',
                nomeMae: 'Mariana Silva',
                responsavel: 'Mariana Silva',
                telResidencial: '(67) 3426-3344',
                telComercial: '',
                celular: '(67) 99567-8901',
                outroTelefone: '',
                email: 'manuela.silva@example.com',
                homepage: '',
                profissao: 'Gerente de Projetos',
                cargo: 'Sênior',
                endResidencial: 'Rua das Flores, 100, Jardim América, Dourados - MS',
                endComercial: 'Rua João Cândido Câmara, 500, Centro, Dourados - MS'
            },
            {
                name: 'Miguel Oliveira',
                registration: '600152024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99678-9012',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '201.201.201-01',
                nascimento: '1993-09-28',
                rgNumero: '12.123.123-4',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2011-12-05',
                nomePai: 'Marcelo Oliveira',
                nomeMae: 'Patrícia Oliveira',
                responsavel: 'Patrícia Oliveira',
                telResidencial: '(67) 3525-4455',
                telComercial: '',
                celular: '(67) 99678-9012',
                outroTelefone: '',
                email: 'miguel.oliveira@example.com',
                homepage: '',
                profissao: 'Desenvolvedor Web',
                cargo: 'Full Stack',
                endResidencial: 'Rua dos Pinheiros, 200, Bairro Santa Luzia, Três Lagoas - MS',
                endComercial: 'Av. Capitão Olinto Mancini, 800, Centro, Três Lagoas - MS'
            },
            {
                name: 'Alice Rodrigues',
                registration: '600162024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99789-0123',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '212.212.212-12',
                nascimento: '1997-04-10',
                rgNumero: '34.345.345-6',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2015-01-15',
                nomePai: 'Ricardo Rodrigues',
                nomeMae: 'Sandra Rodrigues',
                responsavel: 'Sandra Rodrigues',
                telResidencial: '(67) 3377-8899',
                telComercial: '',
                celular: '(67) 99789-0123',
                outroTelefone: '',
                email: 'alice.rodrigues@example.com',
                homepage: '',
                profissao: 'Marketing Digital',
                cargo: 'Especialista em SEO',
                endResidencial: 'Rua das Margaridas, 60, Bairro Pioneiros, Campo Grande - MS',
                endComercial: 'Rua 7 de Setembro, 1200, Centro, Campo Grande - MS'
            },
            {
                name: 'Davi Fernandes',
                registration: '600172024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99890-1234',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '223.223.223-23',
                nascimento: '1989-11-03',
                rgNumero: '56.567.567-8',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2008-06-01',
                nomePai: 'Antônio Fernandes',
                nomeMae: 'Cláudia Fernandes',
                responsavel: 'Cláudia Fernandes',
                telResidencial: '(67) 3300-4455',
                telComercial: '',
                celular: '(67) 99890-1234',
                outroTelefone: '',
                email: 'davi.fernandes@example.com',
                homepage: '',
                profissao: 'Gerente de TI',
                cargo: 'Coordenador',
                endResidencial: 'Rua dos Coqueiros, 25, Bairro Coronel Antonino, Campo Grande - MS',
                endComercial: 'Av. Afonso Pena, 4000, Ed. Corporativo, Campo Grande - MS'
            },
            {
                name: 'Lara Lima',
                registration: '600182024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99012-3456',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '234.234.234-34',
                nascimento: '1996-02-14',
                rgNumero: '78.789.789-0',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2014-07-25',
                nomePai: 'José Lima',
                nomeMae: 'Beatriz Lima',
                responsavel: 'Beatriz Lima',
                telResidencial: '(67) 3427-5566',
                telComercial: '',
                celular: '(67) 99012-3456',
                outroTelefone: '',
                email: 'lara.lima@example.com',
                homepage: '',
                profissao: 'Veterinária',
                cargo: 'Clínica Geral',
                endResidencial: 'Rua das Acácias, 150, Jardim Caramuru, Dourados - MS',
                endComercial: 'Rua Cuiabá, 1000, Centro, Dourados - MS'
            },
            {
                name: 'Enzo Martins',
                registration: '600192024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99123-4567',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '245.245.245-45',
                nascimento: '1991-08-22',
                rgNumero: '90.901.901-2',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2009-10-10',
                nomePai: 'Paulo Martins',
                nomeMae: 'Ana Martins',
                responsavel: 'Ana Martins',
                telResidencial: '(67) 3526-6677',
                telComercial: '',
                celular: '(67) 99123-4567',
                outroTelefone: '',
                email: 'enzo.martins@example.com',
                homepage: '',
                profissao: 'Engenheiro Mecânico',
                cargo: 'Supervisor de Produção',
                endResidencial: 'Rua das Aroeiras, 80, Bairro Jardim Imperial, Três Lagoas - MS',
                endComercial: 'Av. Ranulpho Marques Leal, 2000, Distrito Industrial, Três Lagoas - MS'
            },
            {
                name: 'Luiza Costa',
                registration: '600202024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99234-5678',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '256.256.256-56',
                nascimento: '1998-03-01',
                rgNumero: '12.123.123-4',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2016-05-05',
                nomePai: 'Fernando Costa',
                nomeMae: 'Lúcia Costa',
                responsavel: 'Lúcia Costa',
                telResidencial: '(67) 3388-9900',
                telComercial: '',
                celular: '(67) 99234-5678',
                outroTelefone: '',
                email: 'luiza.costa@example.com',
                homepage: '',
                profissao: 'Fisioterapeuta',
                cargo: 'Clínica',
                endResidencial: 'Rua das Hortênsias, 15, Bairro Rita Vieira, Campo Grande - MS',
                endComercial: 'Rua Rui Barbosa, 2500, Centro, Campo Grande - MS'
            },
            {
                name: 'Gabriel Silva',
                registration: '600212024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99345-6789',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '267.267.267-67',
                nascimento: '1990-12-05',
                rgNumero: '34.345.345-6',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2009-02-18',
                nomePai: 'João Silva',
                nomeMae: 'Carla Silva',
                responsavel: 'Carla Silva',
                telResidencial: '(67) 3322-1122',
                telComercial: '',
                celular: '(67) 99345-6789',
                outroTelefone: '',
                email: 'gabriel.silva@example.com',
                homepage: '',
                profissao: 'Desenvolvedor Mobile',
                cargo: 'Júnior',
                endResidencial: 'Rua das Azaleias, 70, Bairro Aero Rancho, Campo Grande - MS',
                endComercial: 'Av. Salgado Filho, 1000, Ed. Comercial, Campo Grande - MS'
            },
            {
                name: 'Vitória Santos',
                registration: '600222024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99456-7890',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '278.278.278-78',
                nascimento: '1994-06-25',
                rgNumero: '56.567.567-8',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2012-08-01',
                nomePai: 'Pedro Santos',
                nomeMae: 'Ana Santos',
                responsavel: 'Ana Santos',
                telResidencial: '(67) 3428-7788',
                telComercial: '',
                celular: '(67) 99456-7890',
                outroTelefone: '',
                email: 'vitoria.santos@example.com',
                homepage: '',
                profissao: 'Enfermeira',
                cargo: 'UTI',
                endResidencial: 'Rua das Acácias, 280, Jardim Flórida, Dourados - MS',
                endComercial: 'Rua Hayel Bon Faker, 1200, Centro, Dourados - MS'
            },
            {
                name: 'Samuel Pereira',
                registration: '600232024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99567-8901',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '289.289.289-89',
                nascimento: '1987-02-10',
                rgNumero: '78.789.789-0',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2006-04-15',
                nomePai: 'Daniel Pereira',
                nomeMae: 'Sofia Pereira',
                responsavel: 'Sofia Pereira',
                telResidencial: '(67) 3527-8899',
                telComercial: '',
                celular: '(67) 99567-8901',
                outroTelefone: '',
                email: 'samuel.pereira@example.com',
                homepage: '',
                profissao: 'Eletricista',
                cargo: 'Industrial',
                endResidencial: 'Rua das Jabuticabeiras, 90, Bairro São Jorge, Três Lagoas - MS',
                endComercial: 'Av. Olinto Mancini, 500, Centro, Três Lagoas - MS'
            },
            {
                name: 'Maria Clara',
                registration: '600242024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99678-9012',
                sexo: 'feminino',
                nomeSocial: 'Clarinha',
                cpf: '290.290.290-90',
                nascimento: '1999-07-07',
                rgNumero: '90.901.901-2',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2017-10-20',
                nomePai: 'Fernando Clara',
                nomeMae: 'Lúcia Clara',
                responsavel: 'Lúcia Clara',
                telResidencial: '(67) 3399-0011',
                telComercial: '',
                celular: '(67) 99678-9012',
                outroTelefone: '',
                email: 'maria.clara@example.com',
                homepage: '',
                profissao: 'Estudante',
                cargo: 'Estagiária de Design',
                endResidencial: 'Rua das Cerejeiras, 40, Bairro São Bento, Campo Grande - MS',
                endComercial: 'Rua Cândido Mariano, 1000, Centro, Campo Grande - MS'
            },
            {
                name: 'João Pedro',
                registration: '600252024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99789-0123',
                sexo: 'masculino',
                nomeSocial: 'JP',
                cpf: '301.301.301-01',
                nascimento: '1992-04-01',
                rgNumero: '12.123.123-4',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2010-01-01',
                nomePai: 'Antônio Pedro',
                nomeMae: 'Maria Pedro',
                responsavel: 'Maria Pedro',
                telResidencial: '(67) 3344-5566',
                telComercial: '',
                celular: '(67) 99789-0123',
                outroTelefone: '',
                email: 'joao.pedro@example.com',
                homepage: '',
                profissao: 'Engenheiro de Produção',
                cargo: 'Analista de Processos',
                endResidencial: 'Rua dos Jatobás, 110, Bairro Universitário, Campo Grande - MS',
                endComercial: 'Av. Tamandaré, 300, Centro, Campo Grande - MS'
            },
            {
                name: 'Fernanda Rocha',
                registration: '600262024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99888-7777',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '312.312.312-12',
                nascimento: '1991-09-19',
                rgNumero: '13.134.134-5',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2009-03-01',
                nomePai: 'Carlos Rocha',
                nomeMae: 'Beatriz Rocha',
                responsavel: 'Beatriz Rocha',
                telResidencial: '(67) 3429-1122',
                telComercial: '',
                celular: '(67) 99888-7777',
                outroTelefone: '',
                email: 'fernanda.rocha@example.com',
                homepage: '',
                profissao: 'Arquiteta',
                cargo: 'Urbanista',
                endResidencial: 'Rua das Mangueiras, 50, Jardim Tropical, Dourados - MS',
                endComercial: 'Rua João Cândido Câmara, 800, Centro, Dourados - MS'
            },
            {
                name: 'Bruno Alves',
                registration: '600272024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99999-0000',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '323.323.323-23',
                nascimento: '1985-05-12',
                rgNumero: '35.356.356-7',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2004-08-20',
                nomePai: 'Ricardo Alves',
                nomeMae: 'Sandra Alves',
                responsavel: 'Sandra Alves',
                telResidencial: '(67) 3528-3344',
                telComercial: '',
                celular: '(67) 99999-0000',
                outroTelefone: '',
                email: 'bruno.alves@example.com',
                homepage: '',
                profissao: 'Engenheiro de Produção',
                cargo: 'Coordenador',
                endResidencial: 'Rua dos Cajueiros, 120, Bairro Colinos, Três Lagoas - MS',
                endComercial: 'Av. Ranulpho Marques Leal, 1000, Distrito Industrial, Três Lagoas - MS'
            },
            {
                name: 'Gabriela Nunes',
                registration: '600282024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99111-3333',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '334.334.334-34',
                nascimento: '1993-01-30',
                rgNumero: '57.578.578-9',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2011-06-10',
                nomePai: 'Pedro Nunes',
                nomeMae: 'Mariana Nunes',
                responsavel: 'Mariana Nunes',
                telResidencial: '(67) 3311-4455',
                telComercial: '',
                celular: '(67) 99111-3333',
                outroTelefone: '',
                email: 'gabriela.nunes@example.com',
                homepage: '',
                profissao: 'Analista de RH',
                cargo: 'Generalista',
                endResidencial: 'Rua das Acácias, 200, Bairro São Francisco, Campo Grande - MS',
                endComercial: 'Rua 14 de Julho, 500, Centro, Campo Grande - MS'
            },
            {
                name: 'Rafael Costa',
                registration: '600292024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99222-4444',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '345.345.345-45',
                nascimento: '1989-08-08',
                rgNumero: '79.790.790-1',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2007-11-01',
                nomePai: 'André Costa',
                nomeMae: 'Patrícia Costa',
                responsavel: 'Patrícia Costa',
                telResidencial: '(67) 3366-8899',
                telComercial: '',
                celular: '(67) 99222-4444',
                outroTelefone: '',
                email: 'rafael.costa@example.com',
                homepage: '',
                profissao: 'Engenheiro Civil',
                cargo: 'Fiscal de Obras',
                endResidencial: 'Rua dos Flamboyants, 30, Bairro Vila Rica, Campo Grande - MS',
                endComercial: 'Av. Afonso Pena, 2500, Ed. Comercial, Campo Grande - MS'
            },
            {
                name: 'Larissa Lima',
                registration: '600302024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99333-5555',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '356.356.356-56',
                nascimento: '1995-04-03',
                rgNumero: '01.012.012-3',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2013-09-15',
                nomePai: 'Roberto Lima',
                nomeMae: 'Cristina Lima',
                responsavel: 'Cristina Lima',
                telResidencial: '(67) 3430-6677',
                telComercial: '',
                celular: '(67) 99333-5555',
                outroTelefone: '',
                email: 'larissa.lima@example.com',
                homepage: '',
                profissao: 'Nutricionista',
                cargo: 'Clínica',
                endResidencial: 'Rua das Violetas, 80, Jardim Paulista, Dourados - MS',
                endComercial: 'Rua Cuiabá, 1500, Centro, Dourados - MS'
            },
            {
                name: 'Thiago Oliveira',
                registration: '600312024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99444-6666',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '367.367.367-67',
                nascimento: '1990-11-20',
                rgNumero: '23.234.234-5',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2008-02-28',
                nomePai: 'Fernando Oliveira',
                nomeMae: 'Sofia Oliveira',
                responsavel: 'Sofia Oliveira',
                telResidencial: '(67) 3529-7788',
                telComercial: '',
                celular: '(67) 99444-6666',
                outroTelefone: '',
                email: 'thiago.oliveira@example.com',
                homepage: '',
                profissao: 'Analista de Marketing',
                cargo: 'Digital',
                endResidencial: 'Rua das Acácias, 10, Bairro Interlagos, Três Lagoas - MS',
                endComercial: 'Av. Filinto Müller, 200, Centro, Três Lagoas - MS'
            },
            {
                name: 'Amanda Souza',
                registration: '600322024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99555-7777',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '378.378.378-78',
                nascimento: '1997-06-18',
                rgNumero: '45.456.456-7',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2015-11-01',
                nomePai: 'Paulo Souza',
                nomeMae: 'Helena Souza',
                responsavel: 'Helena Souza',
                telResidencial: '(67) 3377-8899',
                telComercial: '',
                celular: '(67) 99555-7777',
                outroTelefone: '',
                email: 'amanda.souza@example.com',
                homepage: '',
                profissao: 'Estudante',
                cargo: 'Estagiária de TI',
                endResidencial: 'Rua dos Lírios, 220, Bairro Jardim Paulista, Campo Grande - MS',
                endComercial: 'Rua Dom Aquino, 1800, Centro, Campo Grande - MS'
            },
            {
                name: 'Pedro Rocha',
                registration: '600332024',
                Status: 'Regular',
                unidade: 'Senac Campo Grande',
                telefone: '(67) 99666-8888',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '389.389.389-89',
                nascimento: '1988-03-05',
                rgNumero: '67.678.678-9',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2007-07-10',
                nomePai: 'Gustavo Rocha',
                nomeMae: 'Lúcia Rocha',
                responsavel: 'Lúcia Rocha',
                telResidencial: '(67) 3300-1122',
                telComercial: '',
                celular: '(67) 99666-8888',
                outroTelefone: '',
                email: 'pedro.rocha@example.com',
                homepage: '',
                profissao: 'Gerente de Projetos',
                cargo: 'Sênior',
                endResidencial: 'Rua das Rosas, 300, Bairro Giocondo Orsi, Campo Grande - MS',
                endComercial: 'Av. Afonso Pena, 500, Ed. Comercial, Campo Grande - MS'
            },
            {
                name: 'Carolina Alves',
                registration: '600342024',
                Status: 'Regular',
                unidade: 'Senac Dourados',
                telefone: '(67) 99777-9999',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '390.390.390-90',
                nascimento: '1996-09-12',
                rgNumero: '89.890.890-1',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2014-12-01',
                nomePai: 'Marcelo Alves',
                nomeMae: 'Fernanda Alves',
                responsavel: 'Fernanda Alves',
                telResidencial: '(67) 3431-2233',
                telComercial: '',
                celular: '(67) 99777-9999',
                outroTelefone: '',
                email: 'carolina.alves@example.com',
                homepage: '',
                profissao: 'Designer Gráfico',
                cargo: 'Pleno',
                endResidencial: 'Rua das Palmeiras, 10, Jardim Flórida, Dourados - MS',
                endComercial: 'Rua Albino Torraca, 100, Centro, Dourados - MS'
            },
            {
                name: 'Mário Fernandes',
                registration: '600352024',
                Status: 'Regular',
                unidade: 'Senac Três Lagoas',
                telefone: '(67) 99888-0000',
                sexo: 'masculino',
                nomeSocial: '',
                cpf: '401.401.401-01',
                nascimento: '1991-01-25',
                rgNumero: '10.101.101-2',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2009-05-20',
                nomePai: 'João Fernandes',
                nomeMae: 'Sofia Fernandes',
                responsavel: 'Sofia Fernandes',
                telResidencial: '(67) 3530-3344',
                telComercial: '',
                celular: '(67) 99888-0000',
                outroTelefone: '',
                email: 'mario.fernandes@example.com',
                homepage: '',
                profissao: 'Engenheiro de Software',
                cargo: 'Desenvolvedor Backend',
                endResidencial: 'Rua das Acácias, 20, Bairro Jardim Alvorada, Três Lagoas - MS',
                endComercial: 'Av. Capitão Olinto Mancini, 300, Centro, Três Lagoas - MS'
            },
            {
                name: 'Beatriz Gomes',
                registration: '600362024',
                Status: 'Regular',
                unidade: 'Senac Hub Academy',
                telefone: '(67) 99999-1111',
                sexo: 'feminino',
                nomeSocial: '',
                cpf: '412.412.412-12',
                nascimento: '1998-08-03',
                rgNumero: '32.323.323-4',
                rgOrgaoEmissor: 'SSP/MS',
                rgUF: 'MS',
                rgPais: 'Brasil',
                rgDataEmissao: '2016-01-10',
                nomePai: 'Ricardo Gomes',
                nomeMae: 'Lúcia Gomes',
                responsavel: 'Lúcia Gomes',
                telResidencial: '(67) 3388-2233',
                telComercial: '',
                celular: '(67) 99999-1111',
                outroTelefone: '',
                email: 'beatriz.gomes@example.com',
                homepage: '',
                profissao: 'Psicóloga',
                cargo: 'Organizacional',
                endResidencial: 'Rua das Flores, 50, Bairro Carandá, Campo Grande - MS',
                endComercial: 'Rua Barão do Rio Branco, 800, Centro, Campo Grande - MS'
            }
];
    let selectedUserIndex = null;
    let currentPage = 1;
        let filteredRegularUsers = []; // To store regular users after filtering

        // Pagination variables for Blocked Users table
        let blockedCurrentPage = 1;
        let filteredBlockedUsers = []; // To store blocked users after filtering

        const usersPerPage = 6; // Number of users to display per page for both tables


    function renderTable() {
      const userTable = document.getElementById('userTable');
      const backButton = document.getElementById('backButton');
      const forwardButton = document.getElementById('forwardButton');

      const regularUsers = users.filter(user => user.Status === 'Regular');
      const startIndex = (currentPage - 1) * usersPerPage;
      const endIndex = startIndex + usersPerPage;

      const usersToDisplay = regularUsers.slice(startIndex, endIndex);
            
      
      userTable.innerHTML = usersToDisplay.map((user) => `
        
        <tr>
        <td>${user.name}</td>
        <td>${user.registration}</td>
        <td>${user.unidade}</td>
        <td>${user.telefone}</td>
        <td>${user.Status}</td>
        <td><button onclick="showUser('${user.name}')">Detalhes</button></td>
        </tr>
      `).join('');
      backButton.disabled = currentPage === 1;
            forwardButton.disabled = endIndex >= regularUsers.length;
    }

    
    function renderBlockedTable() {
      const blockedTable = document.getElementById('blockedTable');
      const backBlockedButton = document.getElementById('backBlockedButton');
            const forwardBlockedButton = document.getElementById('forwardBlockedButton');
      
      const blockedUsers = users.filter(user => user.Status === 'Bloqueado');
      const startIndex = (currentPage - 1) * usersPerPage;
      const endIndex = startIndex + usersPerPage;

      const blockedUsersToDisplay = blockedUsers.slice(startIndex, endIndex);

      blockedTable.innerHTML = blockedUsersToDisplay.map((user) => `
        <tr>
          <td>${user.name}</td>
          <td>${user.registration}</td>
          <td>${user.unidade}</td>
          <td>${user.telefone}</td>
          <td>${user.Status}</td>
          <td><button onclick="showUser('${user.name}')">Detalhes</button></td>
        </tr>
      `).join('');
      backBlockedButton.disabled = blockedCurrentPage === 1;
      forwardBlockedButton.disabled = endIndex >= blockedUsers.length;
    }

    function changeBlockedPage(direction) {
            blockedCurrentPage += direction;
            renderBlockedTable(); // Re-render the table for the new page
        }

 function changePage(direction) {
            currentPage += direction;
            renderTable(); // Re-render the table for the new page
        }

        // Function to filter the table (called on keyup in search input)
        function filterTable() {
            currentPage = 1; // Reset to the first page when filtering
            renderTable();
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
    const TodosInputs = document.querySelectorAll('.inputs-editaveis');
    botaoEdicao.addEventListener('click', () =>{
        
        const isReadOnly = TodosInputs[0].hasAttribute('readonly');
        TodosInputs.forEach(input => {
                if (isReadOnly) {
                    input.removeAttribute('readonly');
                } else {
                    input.setAttribute('readonly', 'true');
                }
      
            });
            if (isReadOnly) {
                botaoEdicao.textContent = 'Salvar dados';
                // Optionally focus the first input when they become editable
                if (TodosInputs.length > 0) {
                    TodosInputs[0].focus();
                }
            } else {
                botaoEdicao.textContent = 'Editar dados';
            }
        
        
    });
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
      
      button[0].addEventListener('click', function regularclick() { /* muda a cor dos botões qndo selecionado "regulares" */
        // button[0].style.backgroundColor = "#fbfaff";
        // button[1].style.backgroundColor = "#e2e1e6";
        button[0].classList.add('active');
        button[1].classList.remove('active');
      });

      button[1].addEventListener('click', function blockclick() { /* muda a cor dos botões qndo selecionado "bloqueados" */
        // button[1].style.backgroundColor = "#fbfaff";
        // button[0].style.backgroundColor = "#e2e1e6";
        button[1].classList.add('active');
        button[0].classList.remove('active');
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