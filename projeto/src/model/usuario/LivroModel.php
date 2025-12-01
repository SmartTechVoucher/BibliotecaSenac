<?php
// /src/model/usuario/LivroModel.php

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

    // ====================================================================
    // MÉTODOS DE BUSCA AJAX PARA TELA INICIAL
    // ====================================================================

    /**
     * Retorna a lista de categorias formatada para um dropdown.
     */
    public function getCategoriasParaDropdown() {
        return $this->getOpcoesSelect('categoria');
    }

    /**
     * Busca livros por termo e, opcionalmente, por ID de categoria.
     */
    public function buscarLivrosAjax($termo = '', $id_categoria = null, $limit = 10) {
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
                    WHERE (l.titulo LIKE :termo 
                        OR a.nome LIKE :termo
                        OR l.isbn LIKE :termo)";
            
            $params = [':termo' => $termo_busca];

            if ($id_categoria > 0) {
                $sql .= " AND l.id_categoria = :id_categoria";
                $params[':id_categoria'] = $id_categoria;
            }

            $sql .= " ORDER BY l.titulo LIMIT :limit";

            $stmt = $this->conn->prepare($sql);

            // Bind dos parâmetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lógica de formatação
            foreach ($livros as &$livro) {
                $livro['status'] = 'Disponível';

                if (!empty($livro['foto'])) {
                    $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                    $livro['imagem'] = ($GLOBALS['URLBASE'] ?? '') . '/public/uploads/' . $foto_limpa;
                } else {
                    $livro['imagem'] = '';
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
            }

            return $livros;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros AJAX: " . $e->getMessage());
            return [];
        }
    }


    // ==========================================
    // MÉTODOS AUXILIARES E CRUD
    // ==========================================

    public function cadastrarLivro($dados) {
        try {
            if (empty($dados['titulo']) || empty($dados['isbn'])) {
                throw new Exception('Título e ISBN são obrigatórios.');
            }

            $this->validarFk('autores', 'id_autor', $dados['id_autor'], 'Autor inválido (ID não encontrado).');
            $this->validarFk('categorias', 'id_categoria', $dados['id_categoria'], 'Categoria inválida (ID não encontrado).');
            $this->validarFk('unidades', 'id_unidade', $dados['id_unidade'], 'Unidade (editora) inválida (ID não encontrado).');
            $this->validarFk('idiomas', 'id_idioma', $dados['id_idioma'], 'Idioma inválido (ID não encontrado).');
            $this->validarFk('areas', 'id_area', $dados['id_area'], 'Área inválida (ID não encontrado).');
            $this->validarFk('documentos', 'id_documento', $dados['id_documento'], 'Tipo de documento inválido (ID não encontrado).');

            $sql_check = "SELECT id_livro FROM livros WHERE isbn = :isbn";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':isbn', $dados['isbn']);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('ISBN já cadastrado no sistema.');
            }

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

    private function criarEstoqueInicial($id_livro) {
        try {
            // Verifica se o livro existe antes de criar o estoque
            $check_sql = "SELECT id_livro FROM livros WHERE id_livro = :id_livro";
            $check_stmt = $this->conn->prepare($check_sql);
            $check_stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $check_stmt->execute();

            if ($check_stmt->rowCount() == 0) {
                 return [
                    'id_livro' => $id_livro,
                    'total_exemplares' => 0,
                    'disponiveis' => 0,
                    'emprestados' => 0,
                    'reservas' => 0,
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ];
            }


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


    public function getTotalExemplares($id_livro) {
        $estoque = $this->getEstoqueByLivro($id_livro);
        return $estoque ? $estoque['total_exemplares'] : 1;
    }

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
                    $livro['imagem'] = '';
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
                    $livro['imagem'] = '';
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
                    $livro['imagem'] = '';
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
                    $livro['imagem'] = '';
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
                    $livro['imagem'] = '';
                }
                
                $livro['autor'] = $livro['autor'] ?? 'Autor desconhecido';
            }

            return $livro;

        } catch (PDOException $e) {
            error_log("Erro ao buscar livro por ID: " . $e->getMessage());
            return null;
        }
    }

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

    public function getLivrosMock() {
        return $this->getLivros(12, 0);
    }

    public function atualizarLivro($dados) {
        try {
            if (empty($dados['id_livro']) || empty($dados['titulo']) || empty($dados['isbn'])) {
                throw new Exception('ID, Título e ISBN são obrigatórios.');
            }

            $this->validarFk('autores', 'id_autor', $dados['id_autor'], 'Autor inválido.');
            $this->validarFk('categorias', 'id_categoria', $dados['id_categoria'], 'Categoria inválida.');
            $this->validarFk('unidades', 'id_unidade', $dados['id_unidade'], 'Editora inválida.');
            $this->validarFk('idiomas', 'id_idioma', $dados['id_idioma'], 'Idioma inválido.');
            $this->validarFk('areas', 'id_area', $dados['id_area'], 'Área inválida.');
            $this->validarFk('documentos', 'id_documento', $dados['id_documento'], 'Tipo de documento inválido.');

            $sql_check = "SELECT id_livro FROM livros WHERE isbn = :isbn AND id_livro != :id_livro";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':isbn', $dados['isbn']);
            $stmt_check->bindParam(':id_livro', $dados['id_livro'], PDO::PARAM_INT);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                throw new Exception('ISBN já cadastrado em outro livro.');
            }

            $sql = "UPDATE livros SET 
                         titulo = :titulo,
                         id_autor = :id_autor,
                         isbn = :isbn,
                         data_publicacao = :data_publicacao,
                         id_categoria = :id_categoria,
                         numero_paginas = :numero_paginas,
                         descricao = :descricao,
                         id_unidade = :id_unidade,
                         foto = :foto,
                         notas = :notas,
                         resumo_livro = :resumo_livro,
                         id_documento = :id_documento,
                         id_idioma = :id_idioma,
                         id_area = :id_area
                     WHERE id_livro = :id_livro";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $dados['id_livro'], PDO::PARAM_INT);
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

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar livro (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro de validação ao atualizar livro: " . $e->getMessage());
            throw $e;
        }
    }

    public function deletarLivro($id_livro) {
        try {
            if ($id_livro <= 0) {
                throw new Exception('ID do livro inválido.');
            }

            $sql = "DELETE FROM livros WHERE id_livro = :id_livro";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao deletar livro (PDO): " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao deletar livro: " . $e->getMessage());
            return false;
        }
    }

    // ==========================================
    // MÉTODOS PARA LIVRO-INFO.PHP
    // ==========================================

    /**
     * Busca detalhes COMPLETOS de um livro para página livro-info
     * Retorna: dados do livro + disponibilidade de exemplares (para o LivroController)
     */
    public function getLivroDetalhes($id_livro) {
        global $URLBASE;
        
        try {
            // Busca dados completos do livro com JOINs
            $sql = "SELECT 
                        l.*,
                        a.nome as autor_nome,
                        c.nome as categoria_nome,
                        u.nome as editora_nome,
                        u.sigla as unidade_sigla,
                        i.nome as idioma_nome,
                        ar.nome as area_nome,
                        d.nome as documento_nome
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN areas ar ON l.id_area = ar.id_area
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    WHERE l.id_livro = :id_livro
                    LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            
            $livro = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$livro) {
                return null;
            }

            // Formata a foto
            if (!empty($livro['foto'])) {
                $foto_limpa = str_replace(['uploads/', 'public/'], '', $livro['foto']);
                $livro['foto_url'] = ($GLOBALS['URLBASE'] ?? '') . '/public/uploads/' . $foto_limpa;
            } else {
                $livro['foto_url'] = '';
            }

            // Busca dados de estoque da tabela exemplares
            $estoque = $this->getEstoqueByLivro($id_livro);
            
            // Adiciona as chaves de estoque ao array do livro (necessário para o LivroController)
            if ($estoque) {
                $livro['total_exemplares'] = $estoque['total_exemplares'] ?? 0;
                $livro['total_disponivel'] = $estoque['disponiveis'] ?? 0;
                $livro['total_emprestados'] = $estoque['emprestados'] ?? 0;
                $livro['total_reservados'] = $estoque['reservas'] ?? 0;
            } else {
                 $livro['total_exemplares'] = 0;
                 $livro['total_disponivel'] = 0;
                 $livro['total_emprestados'] = 0;
                 $livro['total_reservados'] = 0;
            }
            
            return $livro;
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar detalhes do livro: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Busca todas as avaliações e comentários para um livro.
     */
    public function getAvaliacoesLivro($id_livro) {
        try {
            $sql = "SELECT 
                        av.*, 
                        u.nome as nome_usuario
                    FROM avaliacoes av
                    JOIN usuarios u ON av.id_usuario = u.id_usuario
                    WHERE av.id_livro = :id_livro
                    ORDER BY av.data_avaliacao DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_livro', $id_livro, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar avaliações do livro $id_livro: " . $e->getMessage());
            return [];
        }
    }
} 