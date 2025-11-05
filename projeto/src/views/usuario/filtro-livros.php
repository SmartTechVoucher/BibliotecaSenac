<<<<<<< HEAD
=======
<?php
session_start();
require(__DIR__ . '/../../../config/constantes.php');
require_once(__DIR__ . '/../../../src/model/usuario/livro-model.php');

$model = new LivroModel();
$livros = $model->getLivrosMock();
function obterLivroOuPadrao($livros, $index)
{
    return $livros[$index] ?? [
        'id' => 0,
        'titulo' => 'O guia do mochileiro das galáxias',
        'autor' => 'Douglas Adams',
        'imagem' => 'https://i.pinimg.com/736x/a7/b2/0f/a7b20fc61df85a13f6ddcd365854966d.jpg',
        'status' => 'disponível',
        'area' => 'Ficção',
        'descricao' => 'Livro padrão inserido quando não há mais resultados.',
    ];
}
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
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/footer.css">
</head>

<body>
    <?php include "../../../public/components/usuario/header/header.php"; ?>

    <div class="container">
        <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>

        <div class="content-wrapper">
            <div class="sidebar">
                <!-- Filtros -->
                <div class="filter-group">
                    <label for="area">Área</label>
                    <select id="area" class="filter-select">
                        <option value="">SELECIONE</option>
                        <option value="Ciência_Sociais_Aplicadas">Ciência Sociais Aplicadas</option>
                        <option value="Economia">Economia</option>
                        <option value="Multidiciplinar">Multidisciplinar</option>
                        <option value="Ciências_Agrârias">Ciências Agrárias</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="categoria">Categoria/Tags</label>
                    <select id="categoria" class="filter-select">
                        <option value="">SELECIONE</option>
                        <option value="Literatura">Literatura</option>
                        <option value="Folheto">Folheto</option>
                        <option value="Artigo_Periódico">Artigo Periódico</option>
                        <option value="Livro">Livro</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="tipo">Unidade</label>
                    <select id="tipo" class="filter-select">
                        <option value="">SELECIONE</option>
                        <option value="PDF">BSCOR</option>
                        <option value="EPUB">BSDOU</option>
                        <option value="MOBI">BSHUB</option>
                        <option value="DOC">BSPOP</option>
                    </select>
                </div>
            </div>

            <div class="main-content">
                <!-- Categorias -->
                <?php
                $categorias = ['Tecnologia' => [0, 4], 'Saúde' => [4, 8], 'Gestão' => [8, 12]];
                foreach ($categorias as $titulo => [$inicio, $fim]):
                ?>
                    <div class="category-section">
                        <div class="category-header">
                            <h2><?php echo $titulo; ?></h2>
                        </div>
                        <div class="books-grid">
                            <?php for ($i = $inicio; $i < $fim; $i++): ?>
                                <?php $livro = obterLivroOuPadrao($livros, $i); ?>
                                <div class="livroEstante1">
                                    <?php include "../../../public/components/usuario/card/card2.php"; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <?php if ($titulo === 'Gestão'): ?>
                            <div class="pagination">
                                <button class="pagination-btn" onclick="mudarPagina('gestao', 1)">1</button>
                                <button class="pagination-btn" onclick="mudarPagina('gestao', 2)">2</button>
                                <button class="pagination-btn active" onclick="mudarPagina('gestao', 3)">3</button>
                                <button class="pagination-btn" onclick="mudarPagina('gestao', 4)">4</button>
                                <button class="pagination-btn" onclick="mudarPagina('gestao', 5)">5</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Destaques -->
        <div class="highlights-section">
            <div class="section-header">
                <h2>Destaques</h2>
            </div>
            <div class="carousel-container">
                <button class="carousel-btn prev-btn" onclick="moverCarrossel(-1)"><i class="fas fa-chevron-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                        <?php for ($i = 0; $i < 9; $i++): ?>
                            <?php $livro = obterLivroOuPadrao($livros, $i); ?>
                            <div class="carousel-item <?php echo $i === 3 ? 'active' : ''; ?>">
                                <div class="carousel-book">
                                    <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo $livro['titulo']; ?>">
                                    <div class="carousel-info">
                                        <h4><?php echo $livro['titulo']; ?></h4>
                                        <p>Autor - <?php echo $livro['autor']; ?></p>
                                        <p class="disponivel">(Jogue na mesa)</p>
                                        <p>Disponível</p>
                                        <button class="carousel-reserve-btn" onclick="window.location.href='<?php echo $URLBASE ?>/src/views/usuario/livro-info.php?id=<?php echo $livro['id']; ?>'">Reservar</button>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <button class="carousel-btn next-btn" onclick="moverCarrossel(1)"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <?php include "../../../public/components/usuario/footer/footer.php"; ?>
    <script src="<?php echo $URLBASE ?>/public/js/usuario/filtro-livros.js"></script>
    <script src="<?php echo $URLBASE ?>/public/js/components/header.js"></script>
</body>

</html>
>>>>>>> 03b43cd68fc781cc5bc4da7b5f50b2decb2537b2
