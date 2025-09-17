DROP TABLE IF EXISTS favoritos;
DROP TABLE IF EXISTS movimentacoes;
DROP TABLE IF EXISTS livros;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS categorias_usuario;
DROP TABLE IF EXISTS cursos;
DROP TABLE IF EXISTS areas;
DROP TABLE IF EXISTS idiomas;
DROP TABLE IF EXISTS documentos;
DROP TABLE IF EXISTS unidades;
DROP TABLE IF EXISTS autores;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS adminstrador;

CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE autores (
    id_autor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    nacionalidade VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE unidades (
    id_unidade INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    cnpj VARCHAR(18) NOT NULL UNIQUE,
    sigla VARCHAR(10) NOT NULL,
    endereco VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE documentos (
    id_documento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE idiomas (
    id_idioma INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE areas (
    id_area INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cursos (
    id_curso INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categorias_usuario (
    id_categoria_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    nome_social VARCHAR(100),
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    numero_matricula VARCHAR(50) NOT NULL UNIQUE,
    id_categoria_usuario INT NOT NULL,
    id_curso INT, 
    data_inicio DATE,
    data_fim DATE,
    genero VARCHAR(50),
    senha VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_categoria_usuario) REFERENCES categorias_usuario(id_categoria_usuario),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE livros (
    id_livro INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    id_autor INT NOT NULL,
    isbn VARCHAR(30) NOT NULL UNIQUE,
    data_publicacao DATE,
    id_categoria INT NOT NULL,
    numero_paginas SMALLINT UNSIGNED NOT NULL,
    descricao TEXT, 
    id_unidade INT NOT NULL,
    foto VARCHAR(255),
    notas TEXT,
    resumo_livro TEXT,
    id_documento INT NOT NULL,
    id_idioma INT NOT NULL,
    id_area INT NOT NULL,
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_unidade) REFERENCES unidades(id_unidade),
    FOREIGN KEY (id_documento) REFERENCES documentos(id_documento),
    FOREIGN KEY (id_idioma) REFERENCES idiomas(id_idioma),
    FOREIGN KEY (id_area) REFERENCES areas(id_area)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE movimentacoes (
    id_movimentacao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_movimentacao DATETIME NOT NULL, 
    data_prevista_devolucao DATE, 
    data_real_devolucao DATETIME,
    status varchar (130) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE favoritos (
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_livro),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE adminstrador (
    id_administrador INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha varchar(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    genero VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dados de teste para FKs (autores, categorias, etc.)
INSERT INTO categorias (nome) VALUES 
('Ficção'), ('Não-ficção'), ('Romance'), ('Suspense'), ('Fantasia'), ('Ficção Científica'), ('Biografia'), ('Autoajuda'), ('História'), ('Culinária'), ('Infantil'), ('Poesia'), ('Aventura'), ('Humor');

INSERT INTO autores (nome, nacionalidade) VALUES 
('Machado de Assis', 'Brasileiro'), ('Clarice Lispector', 'Ucraniano-Brasileira'), ('Graciliano Ramos', 'Brasileiro'), ('Carlos Drummond de Andrade', 'Brasileiro'), ('José Saramago', 'Português'), ('Fernando Pessoa', 'Português'), ('J.R.R. Tolkien', 'Britânico'), ('George Orwell', 'Britânico'), ('Gabriel García Márquez', 'Colombiano'), ('Stephen King', 'Americano'), ('Agatha Christie', 'Britânica'), ('Isaac Asimov', 'Russo-Americano'), ('Virginia Woolf', 'Britânica'), ('H.P. Lovecraft', 'Americano'), ('Albert Camus', 'Francês'), ('J.K. Rowling', 'Britânica'), ('Jane Austen', 'Britânica'), ('C.S. Lewis', 'Britânico');

INSERT INTO unidades (nome, cnpj, sigla, endereco, email) VALUES 
('Companhia das Letras', '12.345.678/0001-99', 'CDL', 'Rua Exemplo 100', 'contato@cdl.com'), ('Editora Rocco', '98.765.432/0001-00', 'ROC', 'Av. Rocco 200', 'info@rocco.com'), ('Editora Record', '11.222.333/0001-11', 'REC', 'Rua Record 300', 'record@editora.com'), ('Penguin Random House', '44.555.666/0001-44', 'PRH', 'Av. Penguin 400', 'prh@penguin.com'), ('Grupo Editorial Pensamento', '77.888.999/0001-77', 'GEP', 'Rua Pensamento 500', 'gep@editora.com'), ('Intrínseca', '00.111.222/0001-00', 'INT', 'Av. Intrinseca 600', 'intrinseca@livros.com'), ('Globo Livros', '33.444.555/0001-33', 'GLO', 'Rua Globo 700', 'globo@livros.com'), ('Editora Martins Fontes', '66.777.888/0001-66', 'EMF', 'Av. Martins 800', 'martins@fontes.com'), ('HarperCollins Brasil', '99.000.111/0001-99', 'HCB', 'Rua Harper 900', 'harper@brasil.com'), ('Saraiva', '22.333.444/0001-22', 'SAR', 'Av. Saraiva 1000', 'saraiva@livros.com'), ('Editora 34', '55.666.777/0001-55', 'E34', 'Rua 34 1100', 'editora34@livros.com'), ('Zahar', '88.999.000/0001-88', 'ZAH', 'Av. Zahar 1200', 'zahar@editora.com'), ('Editora Aleph', '11.122.333/0001-11', 'ALE', 'Rua Aleph 1300', 'aleph@livros.com'), ('Cengage Learning', '44.455.566/0001-44', 'CEN', 'Av. Cengage 1400', 'cengage@learning.com'), ('Manole', '77.788.999/0001-77', 'MAN', 'Rua Manole 1500', 'manole@editora.com');

INSERT INTO documentos (nome) VALUES 
('Livro'), ('eBook'), ('Revista');

INSERT INTO idiomas (nome) VALUES 
('Português'), ('Inglês'), ('Espanhol'), ('Francês'), ('Alemão'), ('Italiano'), ('Japonês'), ('Chinês'), ('Russo'), ('Árabe');

INSERT INTO areas (nome) VALUES 
('Ciências Exatas'), ('Ciências Biológicas'), ('Ciências Humanas'), ('Ciências Sociais Aplicadas'), ('Engenharias'), ('Saúde'), ('Linguística, Letras e Artes'), ('Agricultura e Meio Ambiente'), ('Arquitetura e Urbanismo'), ('Computação e Informática');

INSERT INTO categorias_usuario (nome) VALUES 
('Aluno'), ('Professor'), ('Funcionário');

INSERT INTO cursos (nome) VALUES 
('Informática'), ('Administração'), ('Enfermagem'), ('Design');

-- Admin de teste (senha 'admin2020')
INSERT INTO adminstrador (nome, cpf, email, senha, data_nascimento, telefone, rua, bairro, genero) VALUES 
('Admin Principal', '123.456.789-10', 'admin@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1980-01-01', '(11) 99999-9999', 'Rua Admin 123', 'Centro', 'Masculino')
ON DUPLICATE KEY UPDATE senha = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; 

-- Usuário de teste (senha 'user2020')
INSERT INTO usuarios (nome, cpf, email, senha, data_nascimento, telefone, rua, bairro, numero_matricula, id_categoria_usuario, id_curso, genero) VALUES 
('João da Silva', '987.654.321-00', 'joao@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1990-01-01', '(11) 88888-8888', 'Rua João 456', 'Zona Norte', '12345678910', 1, 1, 'Masculino')
ON DUPLICATE KEY UPDATE senha = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';