<?php
/**
 * Controller para listagem de livros cadastrados no painel admin.
 * Utiliza LivroModel para buscar livros com filtros por título/ISBN e unidade.
 * Prepara dados para a view, incluindo contagens de exemplares.
 */

require_once __DIR__ . '/../../model/usuario/LivroModel.php';

class ListarLivrosController {
    private $livro_model;

    public function __construct() {
        $this->livro_model = new LivroModel();
    }

    /**
     * Prepara dados para a view: lista de livros com paginação, filtros e opções de unidades.
     * @return array ['livros' => array, 'unidades' => array, 'busca_atual' => string, 'unidade_selecionada' => int, 'paginacao' => array]
     */
    public function prepararDadosView() {
        $busca = $_GET['busca'] ?? '';
        $unidade = (int) ($_GET['unidade'] ?? 0);
        $pagina = (int) ($_GET['pagina'] ?? 1);

        // Usa método do model para listar livros com filtros e paginação
        $resultado = $this->livro_model->listarLivrosComFiltros($busca, $unidade, $pagina);
        $unidades = $this->livro_model->getOpcoesSelect('editora');

        return [
            'livros' => $resultado['livros'],
            'unidades' => $unidades,
            'busca_atual' => $busca,
            'unidade_selecionada' => $unidade,
            'paginacao' => [
                'pagina_atual' => $resultado['pagina_atual'],
                'total_paginas' => $resultado['total_paginas'],
                'total_livros' => $resultado['total_livros']
            ]
        ];
    }
}
?>