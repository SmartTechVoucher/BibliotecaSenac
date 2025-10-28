<?php
session_start();
require(__DIR__ . '/../../../config/constantes.php');
require_once(__DIR__ . '/../../../src/controller/usuario/FiltroLivrosController.php');

$controller = new FiltroLivrosController();
$dados = $controller->prepararDadosView();

$livros = $dados['livros'];
$busca_atual = $dados['busca_atual'];
$paginacao = $dados['paginacao'];
$pagina_atual = $paginacao['pagina_atual'];
$total_paginas = $paginacao['total_paginas'];
$total_livros = $paginacao['total_livros'];

$filtro_area = $dados['filtro_area'];
$filtro_categoria = $dados['filtro_categoria'];
$filtro_unidade = $dados['filtro_unidade'];

$areas = $dados['areas'];
$categorias = $dados['categorias'];
$unidades = $dados['unidades'];
$destaques = $dados['destaques'];

// Dividir livros por categoria (4 livros por categoria)
$livros_por_categoria = array_chunk($livros, 4);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Filtro de Livros</title>

    <!-- Fontes e ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="../../../public/css/usuario/filtro-livros.css">
    <link rel="stylesheet" href="../../../public/css/components/usuario/card2.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
</head>

<body>
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <div class="container">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>

        <div class="content-wrapper">
            <!-- SIDEBAR COM FILTROS DINÂMICOS -->
            <div class="sidebar">
                <form method="GET" action="" id="form-filtros">
                    <!-- Filtro de Área -->
                    <div class="filter-group">
                        <label for="area">Área</label>
                        <select id="area" name="area" class="filter-select" onchange="document.getElementById('form-filtros').submit()">
                            <option value="">SELECIONE</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['id']; ?>" <?php echo $filtro_area == $area['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($area['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filtro de Categoria -->
                    <div class="filter-group">
                        <label for="categoria">Categoria/Tags</label>
                        <select id="categoria" name="categoria" class="filter-select" onchange="document.getElementById('form-filtros').submit()">
                            <option value="">SELECIONE</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>" <?php echo $filtro_categoria == $categoria['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filtro de Unidade -->
                    <div class="filter-group">
                        <label for="unidade">Editora</label>
                        <select id="unidade" name="unidade" class="filter-select" onchange="document.getElementById('form-filtros').submit()">
                            <option value="">SELECIONE</option>
                            <?php foreach ($unidades as $unidade): ?>
                                <option value="<?php echo $unidade['id']; ?>" <?php echo $filtro_unidade == $unidade['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($unidade['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Botão para limpar filtros -->
                    <?php if (!empty($filtro_area) || !empty($filtro_categoria) || !empty($filtro_unidade)): ?>
                        <div class="filter-group">
                            <button type="button" class="btn-limpar-filtros" onclick="window.location.href='?'">
                                <i class="fas fa-times-circle"></i> Limpar Filtros
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- CONTEÚDO PRINCIPAL -->
            <div class="main-content">
                <!-- Info de resultados -->
                <div class="resultados-info">
                    <p>Encontrados <strong><?php echo $total_livros; ?></strong> livros</p>
                    <?php if (!empty($busca_atual)): ?>
                        <p class="busca-ativa">Busca: "<?php echo htmlspecialchars($busca_atual); ?>"</p>
                    <?php endif; ?>
                </div>

                <?php if (empty($livros)): ?>
                    <div class="sem-resultados">
                        <p>📚 Nenhum livro encontrado.</p>
                        <p>Tente ajustar os filtros ou <a href="?">ver todos os livros</a>.</p>
                    </div>
                <?php else: ?>
                    <!-- Categorias com livros -->
                    <?php
                    $categorias_nomes = ['Tecnologia', 'Saúde', 'Gestão'];
                    $categoria_index = 0;
                    
                    foreach ($livros_por_categoria as $grupo_livros):
                        $nome_categoria = $categorias_nomes[$categoria_index] ?? 'Outros';
                        $categoria_index++;
                    ?>
                        <div class="category-section">
                            <div class="category-header">
                                <h2><?php echo $nome_categoria; ?></h2>
                            </div>
                            <div class="books-grid">
                                <?php foreach ($grupo_livros as $livro): ?>
                                    <div class="livroEstante1">
                                        <?php include "../../../public/components/usuario/card/card2.php"; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- PAGINAÇÃO -->
                    <?php if ($total_paginas > 1): ?>
                        <div class="pagination">
                            <?php
                            $params = $_GET;
                            unset($params['pagina']);
                            $base_url = '?' . http_build_query($params);
                            $base_url = empty($base_url) || $base_url === '?' ? '?pagina=' : $base_url . '&pagina=';
                            ?>

                            <?php if ($pagina_atual > 1): ?>
                                <button class="pagination-btn" onclick="window.location.href='<?php echo $base_url . ($pagina_atual - 1); ?>'">
                                    ‹ Anterior
                                </button>
                            <?php endif; ?>

                            <?php
                            $inicio = max(1, $pagina_atual - 2);
                            $fim = min($total_paginas, $pagina_atual + 2);
                            for ($i = $inicio; $i <= $fim; $i++):
                            ?>
                                <button class="pagination-btn <?php echo ($i == $pagina_atual) ? 'active' : ''; ?>" 
                                        onclick="window.location.href='<?php echo $base_url . $i; ?>'">
                                    <?php echo $i; ?>
                                </button>
                            <?php endfor; ?>

                            <?php if ($pagina_atual < $total_paginas): ?>
                                <button class="pagination-btn" onclick="window.location.href='<?php echo $base_url . ($pagina_atual + 1); ?>'">
                                    Próxima ›
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- SEÇÃO DE DESTAQUES -->
        <div class="highlights-section">
            <div class="section-header">
                <h2>Destaques</h2>
            </div>
            <div class="carousel-container">
                <button class="carousel-btn prev-btn" onclick="moverCarrossel(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="carousel-wrapper">
                    <div class="carousel-track" id="carousel-track">
                        <?php foreach ($destaques as $index => $livro): ?>
                            <div class="carousel-item <?php echo $index === 4 ? 'active' : ''; ?>">
                                <div class="carousel-book">
                                    <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                                    <div class="carousel-info">
                                        <h4><?php echo htmlspecialchars($livro['titulo']); ?></h4>
                                        <p>Autor - <?php echo htmlspecialchars($livro['autor']); ?></p>
                                        <p class="<?php echo $livro['status'] === 'Disponível' ? 'disponivel' : 'indisponivel'; ?>">
                                            <?php echo $livro['status']; ?>
                                        </p>
                                        <button class="carousel-reserve-btn" 
                                                onclick="window.location.href='<?php echo $URLBASE ?>/src/views/usuario/livro-info.php?id=<?php echo $livro['id_livro']; ?>'">
                                            Reservar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-btn next-btn" onclick="moverCarrossel(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>
    
    <script>
        // Carrossel de destaques
        let currentIndex = 4;
        const track = document.getElementById('carousel-track');
        const items = document.querySelectorAll('.carousel-item');
        const totalItems = items.length;

        function moverCarrossel(direction) {
            currentIndex += direction;
            
            if (currentIndex < 0) {
                currentIndex = totalItems - 1;
            } else if (currentIndex >= totalItems) {
                currentIndex = 0;
            }
            
            items.forEach((item, index) => {
                item.classList.remove('active');
                if (index === currentIndex) {
                    item.classList.add('active');
                }
            });
            
            const offset = -currentIndex * (220); // 200px width + 20px gap
            track.style.transform = `translateX(${offset}px)`;
        }

        // Auto-play do carrossel
        setInterval(() => {
            moverCarrossel(1);
        }, 5000);
    </script>

    <script src="<?php echo $URLBASE ?>/public/js/components/header.js"></script>
</body>

</html>