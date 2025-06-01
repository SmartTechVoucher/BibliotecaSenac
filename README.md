# 📚 Biblioteca Digital - Projeto Integrador

Este projeto visa desenvolver uma Biblioteca Digital para facilitar o acesso a materiais acadêmicos. Realizado como parte do Projeto Integrador do curso de Análise e Desenvolvimento de Sistemas.

---

## 🚀 Funcionalidades

- 🔐 Cadastro e autenticação de usuários
- 📚 Cadastro, edição e exclusão de livros
- 🔍 Listagem e busca de livros
- 📊 Controle de empréstimos e devoluções

---

## 🛠️ Tecnologias Utilizadas

- PHP
- MySQL
- HTML5, CSS3, JavaScript
- Git e GitHub 

---

## ⚙️ Pré-requisitos

- PHP 7.3 ou superior
- MySQL 5.7 ou superior
- Navegador web (Chrome, Firefox, etc.)
- XAMPP v3.2.2 (ou equivalente)

---

## 🚀 Como Executar

1. Clone o repositório:  
   `git clone https://github.com/SmartTechVoucher/BibliotecaSenac.git`
2. Instale as dependências.
3. Configure o banco de dados (arquivo `config/db/database.php`).

**Como rodar:**  
- Coloque os arquivos na pasta `htdocs`.  
- Acesse via navegador: `http://localhost/nome-do-projeto`.  
- Alternativamente, use: `php -S localhost:8000 -t public`.

---

## 📂 Estrutura de Pastas

- Código fonte em `/src`, dividido em:
  - `/models` → regras de negócio e acesso aos dados
  - `/controllers` → lógica de controle e fluxo
  - `/views` → páginas que interagem com o usuário

- Arquivos de estilo, imagens e scripts ficam em `/public`.

---

## 📂 Estrutura e Padrão de Nomes

**Arquivos e pastas:**  
- Sempre em kebab-case: `usuario-controller.php`, `livro.php`.

**Classes:**  
- Sempre em PascalCase: `UsuarioController`, `Livro`.

**URLs:**  
- Sempre em kebab-case: `/usuario/cadastrar`, `/livro/listar-detalhes`.

**Exemplo:**  
Arquivo: `src/controllers/usuario-controller.php`  
Classe: `UsuarioController`

---

## 🔐 Perfis de Acesso

- **Usuário comum**: acesso ao catálogo, busca e empréstimos  
- **Administrador**: gerencia usuários, livros, relatórios e permissões  

---

## 👥 Equipe

- Marlon Oliveira
- Gabriel Costa 
- Vitótia Caetano 
- Bianca
- Vitor 
- Gustavo
- Mateus
- Gabriel Minervini