-- ============================================
-- DATABASE COMPLETO - SISTEMA DE BIBLIOTECA
-- ============================================

DROP TABLE IF EXISTS notificacoes;
DROP TABLE IF EXISTS penalidades;
DROP TABLE IF EXISTS fila_reservas;
DROP TABLE IF EXISTS favoritos;
DROP TABLE IF EXISTS movimentacoes;
DROP TABLE IF EXISTS exemplares;
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

-- ============================================
-- TABELAS AUXILIARES
-- ============================================

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

-- ============================================
-- TABELA DE USUÁRIOS
-- ============================================

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    nome_social VARCHAR(100) NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NULL,
    endereco TEXT NULL, 
    genero ENUM('Masculino', 'Feminino', 'Não binario', 'Outros', 'Não informar') NULL,
    foto_perfil VARCHAR(255) NULL, 
    numero_matricula VARCHAR(50) NULL,
    categoria ENUM('Aluno', 'Docente', 'Bibliotecario') NOT NULL,
    unidade_senac ENUM('Senac Hub Academy', 'Senac Dourados', 'Senac Três Lagoas') NOT NULL,
    curso VARCHAR(100) NULL,
    turma VARCHAR(50) NULL,
    data_fim_curso DATE NULL,
    notas_usuario TEXT NULL,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE LIVROS
-- ============================================

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

-- ============================================
-- TABELA DE AVALIAÇÕES/COMENTÁRIOS
-- ============================================

CREATE TABLE avaliacoes (
    id_avaliacao INT PRIMARY KEY AUTO_INCREMENT,
    id_livro INT NOT NULL,
    id_usuario INT NOT NULL,
    estrelas INT NOT NULL CHECK (estrelas BETWEEN 1 AND 5),
    comentario TEXT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_user_book (id_usuario, id_livro),
    INDEX idx_livro (id_livro),
    INDEX idx_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Avaliações e comentários dos usuários sobre os livros';

-- ============================================
-- VIEW PARA ESTATÍSTICAS DE AVALIAÇÕES
-- ============================================

CREATE OR REPLACE VIEW vw_estatisticas_avaliacoes AS
SELECT 
    l.id_livro,
    l.titulo,
    COUNT(a.id_avaliacao) as total_avaliacoes,
    COALESCE(AVG(a.estrelas), 0) as media_estrelas,
    COALESCE(ROUND(AVG(a.estrelas), 1), 0) as media_arredondada,
    SUM(CASE WHEN a.estrelas = 5 THEN 1 ELSE 0 END) as cinco_estrelas,
    SUM(CASE WHEN a.estrelas = 4 THEN 1 ELSE 0 END) as quatro_estrelas,
    SUM(CASE WHEN a.estrelas = 3 THEN 1 ELSE 0 END) as tres_estrelas,
    SUM(CASE WHEN a.estrelas = 2 THEN 1 ELSE 0 END) as duas_estrelas,
    SUM(CASE WHEN a.estrelas = 1 THEN 1 ELSE 0 END) as uma_estrela
FROM livros l
LEFT JOIN avaliacoes a ON l.id_livro = a.id_livro
GROUP BY l.id_livro, l.titulo;

-- ============================================
-- QUERIES ÚTEIS
-- ============================================

-- Ver todas as avaliações de um livro:
-- SELECT a.*, u.nome as nome_usuario, u.foto_perfil
-- FROM avaliacoes a
-- JOIN usuarios u ON a.id_usuario = u.id_usuario
-- WHERE a.id_livro = 1
-- ORDER BY a.data_criacao DESC;

-- Ver estatísticas de um livro:
-- SELECT * FROM vw_estatisticas_avaliacoes WHERE id_livro = 1;

-- Ver avaliações de um usuário:
-- SELECT a.*, l.titulo, l.foto
-- FROM avaliacoes a
-- JOIN livros l ON a.id_livro = l.id_livro
-- WHERE a.id_usuario = 1
-- ORDER BY a.data_criacao DESC;

-- ============================================
-- TABELA DE EXEMPLARES (ESTOQUE)
-- ============================================

CREATE TABLE exemplares (
    id_livro INT PRIMARY KEY NOT NULL,
    total_exemplares INT NOT NULL DEFAULT 1,
    disponiveis INT NOT NULL DEFAULT 1,
    emprestados INT NOT NULL DEFAULT 0,
    reservas INT NOT NULL DEFAULT 0,
    em_quarentena INT NOT NULL DEFAULT 0 COMMENT 'Livros em período de quarentena (2 dias)',
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA DE MOVIMENTAÇÕES (EMPRÉSTIMOS)
-- ============================================

CREATE TABLE movimentacoes (
    id_movimentacao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_movimentacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_limite_retirada DATETIME NULL COMMENT 'Prazo de 48h para retirar o livro (Pendente)',
    data_prevista_devolucao DATE NULL COMMENT 'Prazo de 7 dias para devolver (Emprestado)',
    data_real_devolucao DATETIME NULL COMMENT 'Data efetiva da devolução',
    status ENUM('Pendente', 'Emprestado', 'Devolvido', 'Cancelado', 'Atrasado') DEFAULT 'Pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE,
    INDEX idx_status_data (status, data_prevista_devolucao),
    INDEX idx_usuario (id_usuario),
    INDEX idx_livro (id_livro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA DE FILA DE RESERVAS
-- ============================================

CREATE TABLE fila_reservas (
    id_fila INT PRIMARY KEY AUTO_INCREMENT,
    id_livro INT NOT NULL,
    id_usuario INT NOT NULL,
    data_entrada_fila DATETIME DEFAULT CURRENT_TIMESTAMP,
    posicao INT NOT NULL COMMENT 'Posição na fila',
    status ENUM('AGUARDANDO', 'NOTIFICADO', 'CANCELADO', 'EXPIRADO', 'CONVERTIDO') DEFAULT 'AGUARDANDO',
    data_estimada_disponibilidade DATE NULL COMMENT 'Estimativa de quando o livro estará disponível',
    data_notificacao DATETIME NULL COMMENT 'Data que o usuário foi notificado',
    data_disponivel_retirada DATETIME NULL COMMENT 'Data que o livro ficará disponível (após quarentena)',
    data_limite_retirada DATETIME NULL COMMENT 'Prazo de 48h para retirar após notificação',
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_livro_status (id_livro, status),
    INDEX idx_usuario (id_usuario),
    INDEX idx_fila_status (id_livro, status, posicao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA DE FAVORITOS
-- ============================================

CREATE TABLE favoritos (
    id_usuario INT NOT NULL,
    id_livro INT NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_livro),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA DE NOTIFICAÇÕES (PREPARADA PARA O FUTURO)
-- ============================================

CREATE TABLE notificacoes (
    id_notificacao INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_livro INT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('INFO', 'LIVRO_DISPONIVEL', 'ATUALIZACAO_FILA', 'RESERVA_EXPIRADA', 'EMPRESTIMO_VENCENDO', 'EMPRESTIMO_ATRASADO') DEFAULT 'INFO',
    status ENUM('PENDENTE', 'ENVIADA', 'LIDA', 'ERRO') DEFAULT 'PENDENTE',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_envio DATETIME NULL,
    data_leitura DATETIME NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE SET NULL,
    INDEX idx_usuario_status (id_usuario, status),
    INDEX idx_pendentes (status, data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Sistema de notificações para usuários';

-- ============================================
-- TABELA DE PENALIDADES
-- ============================================

CREATE TABLE penalidades (
    id_penalidade INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_movimentacao INT NULL,
    tipo ENUM('ATRASO', 'RESERVA_PERDIDA', 'EMPRESTIMO_NAO_RETIRADO') NOT NULL,
    dias_atraso INT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NULL,
    ativo BOOLEAN DEFAULT TRUE,
    observacao TEXT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_movimentacao) REFERENCES movimentacoes(id_movimentacao) ON DELETE SET NULL,
    INDEX idx_usuario_ativo (id_usuario, ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registro de penalidades aplicadas aos usuários';

-- ============================================
-- TABELA DE ADMINISTRADORES
-- ============================================

CREATE TABLE adminstrador (
    id_administrador INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    genero VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- VIEW PARA DASHBOARD
-- ============================================

CREATE OR REPLACE VIEW vw_dashboard_emprestimos AS
SELECT 
    'Empréstimos Ativos' as categoria,
    COUNT(*) as total,
    NULL as detalhes
FROM movimentacoes 
WHERE status = 'Emprestado'

UNION ALL

SELECT 
    'Empréstimos Atrasados' as categoria,
    COUNT(*) as total,
    GROUP_CONCAT(CONCAT(id_movimentacao, ':', DATEDIFF(CURDATE(), data_prevista_devolucao), ' dias') SEPARATOR '; ') as detalhes
FROM movimentacoes 
WHERE status IN ('Atrasado', 'Emprestado')
AND data_prevista_devolucao < CURDATE()

UNION ALL

SELECT 
    'Livros em Quarentena' as categoria,
    COALESCE(SUM(em_quarentena), 0) as total,
    NULL as detalhes
FROM exemplares

UNION ALL

SELECT 
    'Usuários na Fila' as categoria,
    COUNT(*) as total,
    NULL as detalhes
FROM fila_reservas 
WHERE status = 'AGUARDANDO'

UNION ALL

SELECT 
    'Notificações Pendentes' as categoria,
    COUNT(*) as total,
    NULL as detalhes
FROM notificacoes 
WHERE status = 'PENDENTE';

-- ============================================
-- FUNCTIONS E PROCEDURES
-- ============================================

DELIMITER $$

-- Função para calcular dias até disponibilidade
CREATE FUNCTION IF NOT EXISTS calcular_dias_estimativa(
    p_id_livro INT,
    p_posicao_fila INT
) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_emprestimos_ativos INT;
    DECLARE v_dias_estimados INT;
    DECLARE v_media_dias INT DEFAULT 15;
    DECLARE v_dias_quarentena INT DEFAULT 2;
    
    -- Conta empréstimos ativos
    SELECT COUNT(*) INTO v_emprestimos_ativos
    FROM movimentacoes 
    WHERE id_livro = p_id_livro 
    AND status = 'Emprestado';
    
    -- Calcula estimativa
    SET v_dias_estimados = ((v_emprestimos_ativos + p_posicao_fila - 1) * v_media_dias) + v_dias_quarentena;
    
    RETURN v_dias_estimados;
END$$

-- Procedure para processar fim da quarentena
CREATE PROCEDURE IF NOT EXISTS processar_fim_quarentena()
BEGIN
    -- Libera livros que completaram 2 dias de quarentena
    UPDATE exemplares e
    INNER JOIN (
        SELECT DISTINCT m.id_livro
        FROM movimentacoes m
        INNER JOIN fila_reservas fr ON m.id_livro = fr.id_livro
        WHERE m.status = 'Devolvido'
        AND fr.status = 'NOTIFICADO'
        AND fr.data_disponivel_retirada <= NOW()
        AND e.em_quarentena > 0
    ) livros_prontos ON e.id_livro = livros_prontos.id_livro
    SET e.em_quarentena = e.em_quarentena - 1,
        e.disponiveis = e.disponiveis + 1
    WHERE e.em_quarentena > 0;
END$$

DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

DELIMITER $$

-- Trigger para registrar penalidades automaticamente
CREATE TRIGGER IF NOT EXISTS trg_registrar_penalidade_atraso
AFTER UPDATE ON movimentacoes
FOR EACH ROW
BEGIN
    IF NEW.status = 'Atrasado' AND OLD.status = 'Emprestado' THEN
        INSERT INTO penalidades (id_usuario, id_movimentacao, tipo, dias_atraso, data_inicio)
        VALUES (
            NEW.id_usuario, 
            NEW.id_movimentacao, 
            'ATRASO',
            DATEDIFF(CURDATE(), NEW.data_prevista_devolucao),
            CURDATE()
        );
    END IF;
END$$

DELIMITER ;

-- ============================================
-- EVENT SCHEDULER (Processos Automáticos)
-- ============================================
-- OBS: Precisa habilitar: SET GLOBAL event_scheduler = ON;

CREATE EVENT IF NOT EXISTS evt_processar_emprestimos_atrasados
ON SCHEDULE EVERY 1 HOUR
STARTS CURRENT_TIMESTAMP
DO
    UPDATE movimentacoes 
    SET status = 'Atrasado' 
    WHERE status = 'Emprestado' 
    AND data_prevista_devolucao < CURDATE();

CREATE EVENT IF NOT EXISTS evt_cancelar_emprestimos_nao_retirados
ON SCHEDULE EVERY 2 HOUR
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Cancela empréstimos pendentes expirados
    UPDATE movimentacoes m
    INNER JOIN exemplares e ON m.id_livro = e.id_livro
    SET m.status = 'Cancelado',
        e.disponiveis = e.disponiveis + 1,
        e.emprestados = e.emprestados - 1
    WHERE m.status = 'Pendente' 
    AND m.data_limite_retirada < NOW();
END;

CREATE EVENT IF NOT EXISTS evt_expirar_reservas_nao_retiradas
ON SCHEDULE EVERY 3 HOUR
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Marca reservas como expiradas
    UPDATE fila_reservas 
    SET status = 'EXPIRADO' 
    WHERE status = 'NOTIFICADO' 
    AND data_limite_retirada < NOW();
END;

-- ============================================
-- DADOS INICIAIS (SEEDS)
-- ============================================

INSERT INTO categorias (nome) VALUES    
('Ficção'), ('Não-ficção'), ('Romance'), ('Suspense'), ('Fantasia'), 
('Ficção Científica'), ('Biografia'), ('Autoajuda'), ('História'), 
('Culinária'), ('Infantil'), ('Poesia'), ('Aventura'), ('Humor');

INSERT INTO autores (nome, nacionalidade) VALUES 
('Machado de Assis', 'Brasileiro'), 
('Clarice Lispector', 'Ucraniano-Brasileira'), 
('Graciliano Ramos', 'Brasileiro'), 
('Carlos Drummond de Andrade', 'Brasileiro'), 
('José Saramago', 'Português'), 
('Fernando Pessoa', 'Português'), 
('J.R.R. Tolkien', 'Britânico'), 
('George Orwell', 'Britânico'), 
('Gabriel García Márquez', 'Colombiano'), 
('Stephen King', 'Americano'), 
('Agatha Christie', 'Britânica'), 
('Isaac Asimov', 'Russo-Americano'), 
('Virginia Woolf', 'Britânica'), 
('H.P. Lovecraft', 'Americano'), 
('Albert Camus', 'Francês'), 
('J.K. Rowling', 'Britânica'), 
('Jane Austen', 'Britânica'), 
('C.S. Lewis', 'Britânico');

INSERT INTO unidades (nome, cnpj, sigla, endereco, email) VALUES 
('Companhia das Letras', '12.345.678/0001-99', 'CDL', 'Rua Exemplo 100', 'contato@cdl.com'), 
('Editora Rocco', '98.765.432/0001-00', 'ROC', 'Av. Rocco 200', 'info@rocco.com'), 
('Editora Record', '11.222.333/0001-11', 'REC', 'Rua Record 300', 'record@editora.com'), 
('Penguin Random House', '44.555.666/0001-44', 'PRH', 'Av. Penguin 400', 'prh@penguin.com'), 
('Grupo Editorial Pensamento', '77.888.999/0001-77', 'GEP', 'Rua Pensamento 500', 'gep@editora.com'), 
('Intrínseca', '00.111.222/0001-00', 'INT', 'Av. Intrinseca 600', 'intrinseca@livros.com'), 
('Globo Livros', '33.444.555/0001-33', 'GLO', 'Rua Globo 700', 'globo@livros.com'), 
('Editora Martins Fontes', '66.777.888/0001-66', 'EMF', 'Av. Martins 800', 'martins@fontes.com'), 
('HarperCollins Brasil', '99.000.111/0001-99', 'HCB', 'Rua Harper 900', 'harper@brasil.com'), 
('Saraiva', '22.333.444/0001-22', 'SAR', 'Av. Saraiva 1000', 'saraiva@livros.com'), 
('Editora 34', '55.666.777/0001-55', 'E34', 'Rua 34 1100', 'editora34@livros.com'), 
('Zahar', '88.999.000/0001-88', 'ZAH', 'Av. Zahar 1200', 'zahar@editora.com'), 
('Editora Aleph', '11.122.333/0001-11', 'ALE', 'Rua Aleph 1300', 'aleph@livros.com'), 
('Cengage Learning', '44.455.566/0001-44', 'CEN', 'Av. Cengage 1400', 'cengage@learning.com'), 
('Manole', '77.788.999/0001-77', 'MAN', 'Rua Manole 1500', 'manole@editora.com');

INSERT INTO documentos (nome) VALUES 
('Livro'), ('eBook'), ('Revista');

INSERT INTO idiomas (nome) VALUES 
('Português'), ('Inglês'), ('Espanhol'), ('Francês'), ('Alemão'), 
('Italiano'), ('Japonês'), ('Chinês'), ('Russo'), ('Árabe');

INSERT INTO areas (nome) VALUES 
('Ciências Exatas'), ('Ciências Biológicas'), ('Ciências Humanas'), 
('Ciências Sociais Aplicadas'), ('Engenharias'), ('Saúde'), 
('Linguística, Letras e Artes'), ('Agricultura e Meio Ambiente'), 
('Arquitetura e Urbanismo'), ('Computação e Informática');

INSERT INTO categorias_usuario (nome) VALUES 
('Aluno'), ('Professor'), ('Funcionário');

INSERT INTO cursos (nome) VALUES 
('Informática'), ('Administração'), ('Enfermagem'), ('Design');

-- Admin de teste (senha: admin2020)
INSERT INTO adminstrador (nome, cpf, email, senha, data_nascimento, telefone, rua, bairro, genero) VALUES 
('Admin Principal', '123.456.789-10', 'admin@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1980-01-01', '(11) 99999-9999', 'Rua Admin 123', 'Centro', 'Masculino')
ON DUPLICATE KEY UPDATE senha = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- ============================================
-- ÍNDICES ADICIONAIS PARA PERFORMANCE
-- ============================================

CREATE INDEX IF NOT EXISTS idx_movimentacoes_usuario_status ON movimentacoes(id_usuario, status);
CREATE INDEX IF NOT EXISTS idx_movimentacoes_livro_status ON movimentacoes(id_livro, status);
CREATE INDEX IF NOT EXISTS idx_fila_livro_posicao ON fila_reservas(id_livro, posicao);

-- ============================================
-- QUERIES ÚTEIS PARA MONITORAMENTO
-- ============================================

-- Ver status do sistema:
-- SELECT * FROM vw_dashboard_emprestimos;

-- Ver livros em quarentena:
-- SELECT l.titulo, e.em_quarentena, fr.data_disponivel_retirada
-- FROM exemplares e
-- JOIN livros l ON e.id_livro = l.id_livro
-- LEFT JOIN fila_reservas fr ON l.id_livro = fr.id_livro
-- WHERE e.em_quarentena > 0 AND fr.status = 'NOTIFICADO';

-- Ver usuários com empréstimos atrasados:
-- SELECT u.nome, u.email, m.id_movimentacao, l.titulo, 
--        DATEDIFF(CURDATE(), m.data_prevista_devolucao) as dias_atraso
-- FROM movimentacoes m
-- JOIN usuarios u ON m.id_usuario = u.id_usuario
-- JOIN livros l ON m.id_livro = l.id_livro
-- WHERE m.status IN ('Atrasado', 'Emprestado')
-- AND m.data_prevista_devolucao < CURDATE();

-- Ver fila de reservas com estimativas:
-- SELECT u.nome, l.titulo, fr.posicao, fr.data_estimada_disponibilidade,
--        DATEDIFF(fr.data_estimada_disponibilidade, CURDATE()) as dias_restantes
-- FROM fila_reservas fr
-- JOIN usuarios u ON fr.id_usuario = u.id_usuario
-- JOIN livros l ON fr.id_livro = l.id_livro
-- WHERE fr.status = 'AGUARDANDO'
-- ORDER BY l.titulo, fr.posicao;

-- ============================================
-- FIM DO SCRIPT
-- ============================================