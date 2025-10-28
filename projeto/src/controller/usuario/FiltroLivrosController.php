<?php
/**
 * Controller para página de filtro de livros (usuário)
 * Com todos os filtros: Área, Categoria, Editora, Idioma, Ano, Autor, Tipo Documento
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';
require_once __DIR__ . '/../../../config/db/database.php';

class FiltroLivrosController {
    private $livro_model;
    private $conn;

    public function __construct() {
        $this->livro_model = new LivroModel();
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    public function prepararDadosView() {
        $busca = trim($_GET['busca'] ?? '');
        $filtro_area = $_GET['area'] ?? '';
        $filtro_categoria = $_GET['categoria'] ?? '';
        $filtro_unidade = $_GET['unidade'] ?? '';
        $filtro_idioma = $_GET['idioma'] ?? '';
        $filtro_ano = $_GET['ano'] ?? '';
        $filtro_autor = $_GET['autor'] ?? '';
        $filtro_documento = $_GET['documento'] ?? '';
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $por_pagina = 12;

        // Buscar livros com TODOS os filtros
        $resultado = $this->buscarLivrosComFiltros(
            $busca, 
            $filtro_area, 
            $filtro_categoria, 
            $filtro_unidade,
            $filtro_idioma,
            $filtro_ano,
            $filtro_autor,
            $filtro_documento,
            $pagina, 
            $por_pagina
        );

        // Buscar opções para os filtros
        $opcoes_filtros = $this->buscarOpcoesFiltros();

        return [
            'livros' => $resultado['livros'],
            'busca_atual' => $busca,
            'filtro_area' => $filtro_area,
            'filtro_categoria' => $filtro_categoria,
            'filtro_unidade' => $filtro_unidade,
            'filtro_idioma' => $filtro_idioma,
            'filtro_ano' => $filtro_ano,
            'filtro_autor' => $filtro_autor,
            'filtro_documento' => $filtro_documento,
            'paginacao' => [
                'pagina_atual' => $pagina,
                'total_paginas' => $resultado['total_paginas'],
                'total_livros' => $resultado['total_livros']
            ],
            'areas' => $opcoes_filtros['areas'],
            'categorias' => $opcoes_filtros['categorias'],
            'unidades' => $opcoes_filtros['unidades'],
            'idiomas' => $opcoes_filtros['idiomas'],
            'anos' => $opcoes_filtros['anos'],
            'autores' => $opcoes_filtros['autores'],
            'documentos' => $opcoes_filtros['documentos'],
            'destaques' => $this->buscarDestaques()
        ];
    }

    private function buscarLivrosComFiltros($busca, $filtro_area, $filtro_categoria, $filtro_unidade, $filtro_idioma, $filtro_ano, $filtro_autor, $filtro_documento, $pagina, $por_pagina) {
        try {
            $offset = ($pagina - 1) * $por_pagina;

            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.isbn,
                        l.foto,
                        l.descricao,
                        l.resumo_livro,
                        l.data_publicacao,
                        a.nome as autor,
                        c.nome as categoria,
                        ar.nome as area,
                        u.nome as unidade,
                        i.nome as idioma,
                        d.nome as documento,
                        COALESCE(e.disponiveis, 1) as disponiveis
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
                    LEFT JOIN areas ar ON l.id_area = ar.id_area
                    LEFT JOIN unidades u ON l.id_unidade = u.id_unidade
                    LEFT JOIN idiomas i ON l.id_idioma = i.id_idioma
                    LEFT JOIN documentos d ON l.id_documento = d.id_documento
                    LEFT JOIN exemplares e ON l.id_livro = e.id_livro
                    WHERE 1=1";

            $params = [];

            // Filtro de busca
            if (!empty($busca)) {
                $sql .= " AND (l.titulo LIKE :busca OR a.nome LIKE :busca OR l.isbn LIKE :busca)";
                $params[':busca'] = "%$busca%";
            }

            // Filtro de área
            if (!empty($filtro_area)) {
                $sql .= " AND l.id_area = :area";
                $params[':area'] = $filtro_area;
            }

            // Filtro de categoria
            if (!empty($filtro_categoria)) {
                $sql .= " AND l.id_categoria = :categoria";
                $params[':categoria'] = $filtro_categoria;
            }

            // Filtro de unidade (editora)
            if (!empty($filtro_unidade)) {
                $sql .= " AND l.id_unidade = :unidade";
                $params[':unidade'] = $filtro_unidade;
            }

            // Filtro de idioma
            if (!empty($filtro_idioma)) {
                $sql .= " AND l.id_idioma = :idioma";
                $params[':idioma'] = $filtro_idioma;
            }

            // Filtro de ano
            if (!empty($filtro_ano)) {
                $sql .= " AND YEAR(l.data_publicacao) = :ano";
                $params[':ano'] = $filtro_ano;
            }

            // Filtro de autor
            if (!empty($filtro_autor)) {
                $sql .= " AND l.id_autor = :autor";
                $params[':autor'] = $filtro_autor;
            }

            // Filtro de tipo de documento
            if (!empty($filtro_documento)) {
                $sql .= " AND l.id_documento = :documento";
                $params[':documento'] = $filtro_documento;
            }

            // Contar total
            $sql_count = "SELECT COUNT(*) as total FROM ($sql) as temp";
            $stmt_count = $this->conn->prepare($sql_count);
            foreach ($params as $key => $value) {
                $stmt_count->bindValue($key, $value);
            }
            $stmt_count->execute();
            $total_livros = (int) $stmt_count->fetchColumn();

            // Buscar livros com paginação
            $sql .= " ORDER BY l.titulo ASC LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Processar imagens e status
            global $URLBASE;
            foreach ($livros as &$livro) {
                if (!empty($livro['foto'])) {
                    $livro['imagem'] = $URLBASE . '/public/' . $livro['foto'];
                } else {
                    $livro['imagem'] = $URLBASE . '/public/uploads/default-capa.jpg';
                }

                $livro['status'] = $livro['disponiveis'] > 0 ? 'Disponível' : 'Indisponível';
                
                // Descrição
                if (empty($livro['descricao']) && !empty($livro['resumo_livro'])) {
                    $livro['descricao'] = $livro['resumo_livro'];
                }
                if (empty($livro['descricao'])) {
                    $livro['descricao'] = 'Descrição não disponível.';
                }
                if (strlen($livro['descricao']) > 150) {
                    $livro['descricao'] = substr($livro['descricao'], 0, 150) . '...';
                }
            }

            return [
                'livros' => $livros,
                'total_livros' => $total_livros,
                'total_paginas' => ceil($total_livros / $por_pagina)
            ];

        } catch (PDOException $e) {
            error_log("Erro ao buscar livros: " . $e->getMessage());
            return [
                'livros' => [],
                'total_livros' => 0,
                'total_paginas' => 0
            ];
        }
    }

    private function buscarOpcoesFiltros() {
        $opcoes = [
            'areas' => [],
            'categorias' => [],
            'unidades' => [],
            'idiomas' => [],
            'anos' => [],
            'autores' => [],
            'documentos' => []
        ];

        try {
            // Áreas
            $stmt = $this->conn->query("SELECT id_area as id, nome FROM areas ORDER BY nome");
            $opcoes['areas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Categorias
            $stmt = $this->conn->query("SELECT id_categoria as id, nome FROM categorias ORDER BY nome");
            $opcoes['categorias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Unidades (Editoras)
            $stmt = $this->conn->query("SELECT id_unidade as id, nome FROM unidades ORDER BY nome");
            $opcoes['unidades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Idiomas
            $stmt = $this->conn->query("SELECT id_idioma as id, nome FROM idiomas ORDER BY nome");
            $opcoes['idiomas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Anos (dos livros cadastrados)
            $stmt = $this->conn->query("SELECT DISTINCT YEAR(data_publicacao) as id, YEAR(data_publicacao) as nome 
                                        FROM livros 
                                        WHERE data_publicacao IS NOT NULL 
                                        ORDER BY nome DESC");
            $opcoes['anos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Autores
            $stmt = $this->conn->query("SELECT id_autor as id, nome FROM autores ORDER BY nome");
            $opcoes['autores'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tipos de Documento
            $stmt = $this->conn->query("SELECT id_documento as id, nome FROM documentos ORDER BY nome");
            $opcoes['documentos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar opções de filtros: " . $e->getMessage());
        }

        return $opcoes;
    }

    private function buscarDestaques() {
        try {
            return $this->livro_model->getLivrosAleatorios(18);
        } catch (Exception $e) {
            error_log("Erro ao buscar destaques: " . $e->getMessage());
            return [];
        }
    }
}