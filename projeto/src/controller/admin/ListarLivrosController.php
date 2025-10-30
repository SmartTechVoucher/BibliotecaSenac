<?php
/**
 * Controller para listagem de livros com sistema de filtro avançado
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';
require_once __DIR__ . '/../../../config/db/database.php';

class ListarLivrosController {
    private $livro_model;
    private $conn;

    public function __construct() {
        $this->livro_model = new LivroModel();
        $banco = new Database();
        $this->conn = $banco->Connect();
    }

    public function prepararDadosView() {
        $busca = trim($_GET['busca'] ?? '');
        $filtro_tipo = $_GET['filtro_tipo'] ?? '';
        $filtro_valor = $_GET['filtro_valor'] ?? '';
        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));
        $por_pagina = 10;

        // Buscar livros com filtros
        $resultado = $this->buscarLivrosComFiltros($busca, $filtro_tipo, $filtro_valor, $pagina, $por_pagina);

        // Buscar opções para os filtros
        $opcoes_filtros = $this->buscarOpcoesFiltros();

        return [
            'livros' => $resultado['livros'],
            'busca_atual' => $busca,
            'filtro_tipo' => $filtro_tipo,
            'filtro_valor' => $filtro_valor,
            'paginacao' => [
                'pagina_atual' => $pagina,
                'total_paginas' => $resultado['total_paginas'],
                'total_livros' => $resultado['total_livros']
            ],
            // Opções para os selects
            'areas' => $opcoes_filtros['areas'],
            'idiomas' => $opcoes_filtros['idiomas'],
            'anos' => $opcoes_filtros['anos'],
            'autores' => $opcoes_filtros['autores'],
            'categorias' => $opcoes_filtros['categorias'],
            'editoras' => $opcoes_filtros['editoras'],
            'documentos' => $opcoes_filtros['documentos']
        ];
    }

    private function buscarLivrosComFiltros($busca, $filtro_tipo, $filtro_valor, $pagina, $por_pagina) {
        try {
            $offset = ($pagina - 1) * $por_pagina;

            // Base da query
            $sql = "SELECT 
                        l.id_livro,
                        l.titulo,
                        l.isbn,
                        l.foto,
                        a.nome as autor_nome,
                        COALESCE(e.total_exemplares, 1) as total_exemplares,
                        COALESCE(e.disponiveis, 1) as disponiveis,
                        COALESCE(e.emprestados, 0) as emprestados,
                        COALESCE(e.reservas, 0) as reservas
                    FROM livros l
                    LEFT JOIN autores a ON l.id_autor = a.id_autor
                    LEFT JOIN exemplares e ON l.id_livro = e.id_livro
                    WHERE 1=1";

            $params = [];

            // Filtro de busca (título ou ISBN)
            if (!empty($busca)) {
                $sql .= " AND (l.titulo LIKE :busca OR l.isbn LIKE :busca)";
                $params[':busca'] = "%$busca%";
            }

            // Filtros avançados
            if (!empty($filtro_tipo) && !empty($filtro_valor)) {
                switch ($filtro_tipo) {
                    case 'area':
                        $sql .= " AND l.id_area = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'idioma':
                        $sql .= " AND l.id_idioma = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'ano':
                        $sql .= " AND YEAR(l.data_publicacao) = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'autor':
                        $sql .= " AND l.id_autor = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'categoria':
                        $sql .= " AND l.id_categoria = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'editora':
                        $sql .= " AND l.id_unidade = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                    
                    case 'documento':
                        $sql .= " AND l.id_documento = :filtro_valor";
                        $params[':filtro_valor'] = $filtro_valor;
                        break;
                }
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
            'idiomas' => [],
            'anos' => [],
            'autores' => [],
            'categorias' => [],
            'editoras' => [],
            'documentos' => []
        ];

        try {
            // Áreas
            $stmt = $this->conn->query("SELECT id_area as id, nome FROM areas ORDER BY nome");
            $opcoes['areas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

            // Categorias
            $stmt = $this->conn->query("SELECT id_categoria as id, nome FROM categorias ORDER BY nome");
            $opcoes['categorias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Editoras (unidades)
            $stmt = $this->conn->query("SELECT id_unidade as id, nome FROM unidades ORDER BY nome");
            $opcoes['editoras'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tipos de Documento
            $stmt = $this->conn->query("SELECT id_documento as id, nome FROM documentos ORDER BY nome");
            $opcoes['documentos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar opções de filtros: " . $e->getMessage());
        }

        return $opcoes;
    }
}