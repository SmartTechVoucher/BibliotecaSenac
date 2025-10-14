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
                'editora' => 'unidades',
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
     * Conta total de livros com filtros aplicados (para paginação).
     * @param string|null $busca Termo para busca
     * @param int|null $id_unidade ID da unidade
     * @return int Total de livros
     */
    private function contarLivrosComFiltros($busca = null, $id_unidade = null) {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    LEFT JOIN areas ar ON l.id_area = ar.id_area";

            $where_conditions = [];
            $params = [];

            if (!empty($busca)) {
                $where_conditions[] = "(l.titulo LIKE :busca OR l.isbn LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            if ($id_unidade > 0) {
                $where_conditions[] = "l.id_unidade = :id_unidade";
                $params[':id_unidade'] = $id_unidade;
            }

            if (!empty($where_conditions)) {
                $sql .= " WHERE " . implode(" AND ", $where_conditions);
            }

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return (int) $stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Erro ao contar livros: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lista livros com filtros e paginação.
     * @param string|null $busca Termo para busca em título ou ISBN
     * @param int|null $id_unidade ID da unidade para filtro
     * @param int $pagina Número da página (default 1)
     * @param int $limite Itens por página (default 10)
     * @return array
     */
    public function listarLivrosComFiltros($busca = null, $id_unidade = null, $pagina = 1, $limite = 10) {
        try {
            $pagina = max(1, (int) $pagina);
            $offset = ($pagina - 1) * $limite;

            $sql = "SELECT l.*,
                           a.nome as autor_nome,
                           c.nome as categoria_nome,
                           u.nome as unidade_nome,
                           i.nome as idioma_nome,
                           d.nome as documento_nome,
                           ar.nome as area_nome
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    LEFT JOIN areas ar ON l.id_area = ar.id_area";

            $where_conditions = [];
            $params = [];

            if (!empty($busca)) {
                $where_conditions[] = "(l.titulo LIKE :busca OR l.isbn LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }

            if ($id_unidade > 0) {
                $where_conditions[] = "l.id_unidade = :id_unidade";
                $params[':id_unidade'] = $id_unidade;
            }

            if (!empty($where_conditions)) {
                $sql .= " WHERE " . implode(" AND ", $where_conditions);
            }

            $sql .= " ORDER BY l.titulo LIMIT :limite OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll();

            // Adiciona contagens para cada livro usando dados da tabela exemplares
            foreach ($livros as &$livro) {
                $estoque = $this->getEstoqueByLivro($livro['id_livro']);
                if ($estoque) {
                    $livro['total_exemplares'] = $estoque['total_exemplares'];
                    $livro['disponiveis'] = $estoque['disponiveis'];
                    $livro['emprestados'] = $estoque['emprestados'];
                    $livro['reservas'] = $estoque['reservas'];
                } else {
                    $livro['total_exemplares'] = 1;
                    $livro['disponiveis'] = 1;
                    $livro['emprestados'] = 0;
                    $livro['reservas'] = 0;
                }
            }

            $total_livros = $this->contarLivrosComFiltros($busca, $id_unidade);
            $total_paginas = ceil($total_livros / $limite);

            return [
                'livros' => $livros,
                'total_paginas' => $total_paginas,
                'pagina_atual' => $pagina,
                'total_livros' => $total_livros
            ];

        } catch (PDOException $e) {
            error_log("Erro ao listar livros com filtros: " . $e->getMessage());
            return ['livros' => [], 'total_paginas' => 0, 'pagina_atual' => 1, 'total_livros' => 0];
        }
    }

    /**
     * Obtém os dados de estoque de um livro da tabela exemplares.
     * @param int $id_livro ID do livro
     * @return array|false Dados do estoque ou false se erro
     */
    public function getEstoqueByLivro($id_livro) {
        try {
            $sql = "SELECT * FROM exemplares WHERE id_livro = :id_livro";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return $this->criarEstoqueInicial($id_livro);
            }

            return $resultado;
        } catch (PDOException $e) {
            error_log("Erro ao obter estoque do livro $id_livro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cria um registro inicial de estoque para um livro.
     * @param int $id_livro ID do livro
     * @return array Dados do estoque criado
     */
    private function criarEstoqueInicial($id_livro) {
        try {
            $sql = "INSERT INTO exemplares (id_livro, total_exemplares, disponiveis, emprestados, reservas)
                    VALUES (:id_livro, 1, 1, 0, 0)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();

            return [
                'id_livro' => $id_livro,
                'total_exemplares' => 1,
                'disponiveis' => 1,
                'emprestados' => 0,
                'reservas' => 0,
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];
        } catch (PDOException $e) {
            error_log("Erro ao criar estoque inicial para livro $id_livro: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém o total de exemplares para um livro.
     * @param int $id_livro ID do livro
     * @return int Total de exemplares
     */
    public function getTotalExemplares($id_livro) {
        $estoque = $this->getEstoqueByLivro($id_livro);
        return $estoque ? $estoque['total_exemplares'] : 1;
    }

    /**
     * Obtém a quantidade de exemplares emprestados (ativos) de um livro.
     * @param int $id_livro ID do livro
     * @return int Quantidade de exemplares emprestados
     */
    public function getEmprestados($id_livro) {
        try {
            $sql = "SELECT COUNT(*) as count
                    FROM movimentacoes m
                    WHERE m.id_livro = :id_livro
                    AND m.status = 'emprestado'
                    AND m.data_real_devolucao IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar empréstimos do livro $id_livro: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ========================================
     * NOVOS MÉTODOS PARA PÁGINA INICIAL
     * ========================================
     */

    /**
     * Busca livros para exibição na página inicial
     * @param int $limit Limite de livros a retornar
     * @param int $offset Offset para paginação
     * @return array Array de livros
     */
    public function getLivros($limit = 10, $offset = 0) {
        global $URLBASE;
        
        try {
            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.isbn,
                        l.data_publicacao,
                        l.numero_paginas,
                        l.descricao,
                        l.foto,
                        l.notas,
                        l.resumo_livro,
                        l.id_autor,
                        l.id_categoria,
                        a.nome AS autor,
                        c.nome AS categoria,
                        i.nome AS idioma,
                        u.nome AS editora,
                        ar.nome AS area,
                        d.nome AS tipo_documento
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN areas ar ON l.id_area = ar.id_area
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    ORDER BY l.id_livro DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($livros as &$livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = $URLBASE . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = $URLBASE . '/public/assets/images/livro-default.png';
                }

                if (empty($livro['descricao']) && !empty($livro['resumo_livro'])) {
                    $livro['descricao'] = $livro['resumo_livro'];
                }

                if (empty($livro['descricao'])) {
                    $livro['descricao'] = 'Descrição não disponível.';
                }

                if (strlen($livro['descricao']) > 150) {
                    $livro['descricao'] = substr($livro['descricao'], 0, 150) . '...';
                }

                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
                $livro['categoria'] = $livro['categoria'] ?? 'Sem categoria';
                $livro['editora'] = $livro['editora'] ?? 'Editora não informada';
            }

            return $livros;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros: " . $e->getMessage());
            return [];
        }
    }

    /**
 * Busca livros ALEATÓRIOS para exibição na página inicial
 * @param int $limit Limite de livros a retornar
 * @return array Array de livros aleatórios
 */
    public function getLivrosAleatorios($limit = 9) {
        global $URLBASE;
        
        try {
            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.isbn,
                        l.data_publicacao,
                        l.numero_paginas,
                        l.descricao,
                        l.foto,
                        l.notas,
                        l.resumo_livro,
                        l.id_autor,
                        l.id_categoria,
                        a.nome AS autor,
                        c.nome AS categoria,
                        i.nome AS idioma,
                        u.nome AS editora,
                        ar.nome AS area,
                        d.nome AS tipo_documento
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN areas ar ON l.id_area = ar.id_area
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    ORDER BY RAND()
                    LIMIT :limit";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($livros as &$livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = $URLBASE . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = $URLBASE . '/public/assets/images/livro-default.png';
                }

                if (empty($livro['descricao']) && !empty($livro['resumo_livro'])) {
                    $livro['descricao'] = $livro['resumo_livro'];
                }

                if (empty($livro['descricao'])) {
                    $livro['descricao'] = 'Descrição não disponível.';
                }

                if (strlen($livro['descricao']) > 150) {
                    $livro['descricao'] = substr($livro['descricao'], 0, 150) . '...';
                }

                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
                $livro['categoria'] = $livro['categoria'] ?? 'Sem categoria';
                $livro['editora'] = $livro['editora'] ?? 'Editora não informada';
            }

            return $livros;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros aleatórios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca livros por categoria
     * @param string $categoria Nome da categoria
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getLivrosPorCategoria($categoria, $limit = 10) {
        global $URLBASE;
        
        try {
            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.descricao,
                        l.resumo_livro,
                        l.foto,
                        a.nome AS autor,
                        c.nome AS categoria
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    WHERE c.nome = :categoria
                    ORDER BY l.id_livro DESC
                    LIMIT :limit";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($livros as &$livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = $URLBASE . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = $URLBASE . '/public/assets/images/livro-default.png';
                }

                if (empty($livro['descricao']) && !empty($livro['resumo_livro'])) {
                    $livro['descricao'] = $livro['resumo_livro'];
                }
                
                if (empty($livro['descricao'])) {
                    $livro['descricao'] = 'Descrição não disponível.';
                }
                
                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
            }

            return $livros;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca livros para pesquisa (autocomplete)
     * @param string $termo Termo de busca
     * @param int $limit Limite de resultados
     * @return array
     */
    public function buscarLivros($termo, $limit = 10) {
        global $URLBASE;
        
        try {
            $termo_busca = "%{$termo}%";
            
            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.foto,
                        l.descricao,
                        l.resumo_livro,
                        a.nome AS autor
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    WHERE l.titulo LIKE :termo 
                       OR a.nome LIKE :termo
                       OR l.isbn LIKE :termo
                    ORDER BY l.titulo
                    LIMIT :limit";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':termo', $termo_busca, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($livros as &$livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = $URLBASE . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = $URLBASE . '/public/assets/images/livro-default.png';
                }
                
                if (empty($livro['descricao']) && !empty($livro['resumo_livro'])) {
                    $livro['descricao'] = $livro['resumo_livro'];
                }
                
                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
            }

            return $livros;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um livro específico por ID
     * @param int $id_livro
     * @return array|null
     */
    public function getLivroPorId($id_livro) {
        global $URLBASE;
        
        try {
            $sql = "SELECT 
                        l.*,
                        a.nome AS autor,
                        c.nome AS categoria,
                        i.nome AS idioma,
                        u.nome AS editora,
                        ar.nome AS area,
                        d.nome AS tipo_documento
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN areas ar ON l.id_area = ar.id_area
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    WHERE l.id_livro = :id
                    LIMIT 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_livro, PDO::PARAM_INT);
            $stmt->execute();

            $livro = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = $URLBASE . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = $URLBASE . '/public/assets/images/livro-default.png';
                }
                
                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
            }

            return $livro;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livro por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Conta total de livros cadastrados
     * @return int
     */
    public function contarTotalLivros() {
        try {
            $sql = "SELECT COUNT(*) as total FROM livros";
            $stmt = $this->conn->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar livros: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ATUALIZADO - getLivrosMock agora retorna livros reais
     * @return array Lista de livros
     */
    public function getLivrosMock() {
        return $this->getLivros(12, 0);
    }
}