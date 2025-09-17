<?php

/**
 * Cria e exibe um componente genérico de barra de pesquisa.
 *
 * @param array $lista A lista de dados (nomes, strings) para pesquisa.
 * @return void
 */
function barra_de_pesquisa($lista) {
    // Variável para armazenar o termo de pesquisa do usuário
    $termoDePesquisa = '';
    // Variável para armazenar os resultados filtrados
    $resultados = [];

    // Processa a pesquisa se o formulário foi submetido via GET
    if (isset($_GET['search_query'])) {
        $termoDePesquisa = strtolower($_GET['search_query']);
        // Filtra a lista para encontrar itens que contenham o termo de pesquisa
        foreach ($lista as $item) {
            if (strpos(strtolower($item), $termoDePesquisa) !== false) {
                $resultados[] = $item;
            }
        }
    } else {
        // Se não houver pesquisa, mostra a lista completa
        $resultados = $lista;
    }

    // Estrutura HTML do componente
    $html = '
    <div class="search-component">
        <style>
            .search-component {
                font-family: Arial, sans-serif;
                margin-bottom: 20px;
            }
            .search-component form {
                display: flex;
                gap: 5px;
            }
            .search-component input[type="text"] {
                padding: 8px;
                width: 300px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            .search-component button {
                padding: 8px 16px;
                background-color: #004A90;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .search-component ul {
                list-style-type: none;
                padding: 0;
                margin-top: 20px;
            }
            .search-component li {
                background-color: #f0f0f0;
                padding: 10px;
                margin-bottom: 5px;
                border-radius: 5px;
            }
            .search-component .no-results {
                color: #d9534f;
                font-style: italic;
            }
        </style>

        <form action="" method="get">
            <input type="text" name="search_query" placeholder="Pesquisar nomes..." value="' . htmlspecialchars($termoDePesquisa) . '">
            <button type="submit">Pesquisar</button>
        </form>';

    // Adiciona a lista de resultados ao HTML
    if (!empty($resultados)) {
        $html .= '<ul>';
        foreach ($resultados as $item) {
            $html .= '<li>' . htmlspecialchars($item) . '</li>';
        }
        $html .= '</ul>';
    } else {
        $html .= '<p class="no-results">Nenhum resultado encontrado.</p>';
    }

    $html .= '</div>';

    echo $html;
}

?>