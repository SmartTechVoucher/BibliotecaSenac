<?php

require_once __DIR__ . '/../../../config/db/database.php';

class LivroModel {
    private $conn;

    public function __construct() {
        $banco = new Database();
        $this->conn = $banco->Connect();
        if (!$this->conn) {
            throw new Exception('Falha na conexão com o banco de dados.');
        }
    }

    /**
     * Cadastro
     * Valida FKs com queries COUNT antes de inserir.
     * @param array $dados Dados do livro (titulo, id_autor, isbn, etc.)
     * @return int|false ID do livro inserido ou false se erro/validação falhou
     */
    public function cadastrarLivro($dados) {
        try {
            // Validações básicas
            if (empty($dados['titulo']) || empty($dados['isbn'])) {
                throw new Exception('Título e ISBN são obrigatórios.');
            }

            // Valida FKs
            $this->validarFk('autores', 'id_autor', $dados['id_autor'], 'Autor inválido (ID não encontrado).');
            $this->validarFk('categorias', 'id_categoria', $dados['id_categoria'], 'Categoria inválida (ID não encontrado).');
            $this->validarFk('unidades', 'id_unidade', $dados['id_unidade'], 'Unidade (editora) inválida (ID não encontrado).');
            $this->validarFk('idiomas', 'id_idioma', $dados['id_idioma'], 'Idioma inválido (ID não encontrado).');
            $this->validarFk('areas', 'id_area', $dados['id_area'], 'Área inválida (ID não encontrado).');
            $this->validarFk('documentos', 'id_documento', $dados['id_documento'], 'Tipo de documento inválido (ID não encontrado).');

            // Verifica se ISBN já existe
            $sql_check = "SELECT id_livro FROM livros WHERE isbn = :isbn";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':isbn', $dados['isbn']);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('ISBN já cadastrado no sistema.');
            }

            // Insere o livro
            $sql = "INSERT INTO livros (titulo, id_autor, isbn, data_publicacao, id_categoria, numero_paginas, descricao, id_unidade, foto, notas, resumo_livro, id_documento, id_idioma, id_area) 
                    VALUES (:titulo, :id_autor, :isbn, :data_publicacao, :id_categoria, :numero_paginas, :descricao, :id_unidade, :foto, :notas, :resumo_livro, :id_documento, :id_idioma, :id_area)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':titulo', $dados['titulo']);
            $stmt->bindParam(':id_autor', $dados['id_autor'], PDO::PARAM_INT);
            $stmt->bindParam(':isbn', $dados['isbn']);
            $stmt->bindParam(':data_publicacao', $dados['data_publicacao']);
            $stmt->bindParam(':id_categoria', $dados['id_categoria'], PDO::PARAM_INT);
            $stmt->bindParam(':numero_paginas', $dados['numero_paginas'], PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':id_unidade', $dados['id_unidade'], PDO::PARAM_INT);
            $stmt->bindParam(':foto', $dados['foto']);
            $stmt->bindParam(':notas', $dados['notas']);
            $stmt->bindParam(':resumo_livro', $dados['resumo_livro']);
            $stmt->bindParam(':id_documento', $dados['id_documento'], PDO::PARAM_INT);
            $stmt->bindParam(':id_idioma', $dados['id_idioma'], PDO::PARAM_INT);
            $stmt->bindParam(':id_area', $dados['id_area'], PDO::PARAM_INT);

            $stmt->execute();
            return $this->conn->lastInsertId();

        } catch (PDOException $e) {
            error_log("Erro ao cadastrar livro (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação ao cadastrar livro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida se FK existe com query COUNT.
     * @param string $tabela Tabela FK
     * @param string $campo_id Campo ID
     * @param int $id Valor ID
     * @param string $msg_erro Mensagem de erro
     * @throws Exception Se não existir
     */
    private function validarFk($tabela, $campo_id, $id, $msg_erro) {
        if ($id <= 0) {
            throw new Exception($msg_erro);
        }
        $sql = "SELECT COUNT(*) FROM $tabela WHERE $campo_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            throw new Exception($msg_erro);
        }
    }

    /**
     * Obtém opções para selects na view (autores, categorias, etc.)
     * @param string $tipo 'autores', 'categorias', 'unidades', 'idiomas', 'areas', 'documentos'
     * @return array Lista de opções ou vazio se erro
     */
    public function getOpcoesSelect($tipo) {
        try {
            $tabela_map = [
                'autores' => 'autores',
                'editora' => 'unidades',  // Editoras mapeadas para unidades
                'idioma' => 'idiomas',
                'categoria' => 'categorias',
                'area' => 'areas',
                'tipo-documento' => 'documentos'
            ];
            $tabela = $tabela_map[$tipo] ?? $tipo;
            $campos = match($tabela) {
                'autores' => 'id_autor as id, nome',
                'unidades' => 'id_unidade as id, nome',
                'idiomas' => 'id_idioma as id, nome',
                'categorias' => 'id_categoria as id, nome',
                'areas' => 'id_area as id, nome',
                'documentos' => 'id_documento as id, nome',
                default => 'id as id, nome'
            };
            $sql = "SELECT $campos FROM $tabela ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao obter opções $tipo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém todos os livros cadastrados
     * @return array Lista de livros ou vazio se erro
     */
    public function getTodosLivros() {
        try {
            $sql = "SELECT l.*, a.nome as autor_nome, c.nome as categoria_nome, u.nome as unidade_nome, i.nome as idioma_nome, d.nome as documento_nome, ar.nome as area_nome 
                    FROM livros l 
                    LEFT JOIN autores a ON l.id_autor = a.id_autor 
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria 
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade 
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma 
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento 
                    LEFT JOIN areas ar ON l.id_area = ar.id_area 
                    ORDER BY l.titulo";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao listar livros: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém livros mock para listagem (mantido para compatibilidade ou testes).
     * @return array Lista de livros de exemplo
     */
    public function getLivrosMock() {
        // Caso testes sejam necessários
        return [
        ];
    }
}
?>