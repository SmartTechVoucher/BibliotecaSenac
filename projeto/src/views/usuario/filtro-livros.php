<?php
session_start();
require(__DIR__ . '/../../../config/constantes.php');

// Inclui o modelo de livros
require_once(__DIR__ . '/../../../src/model/usuario/livro-model.php');
$model = new LivroModel();
$livros = $model->getLivrosMock();

// Função para garantir que sempre temos livros para mostrar
function getLivroOuDefault($livros, $index) {
    if (isset($livros[$index])) {
        return $livros[$index];
    }
    // Retorna um livro padrão se não existir (com id padrão zerado)
    return [
        'id'     => 0,
        'titulo' => 'O guia do mochileiro das galaxias',
        'autor'  => 'Douglas Adams',
        'imagem' => 'https://i.pinimg.com/736x/a7/b2/0f/a7b20fc61df85a13f6ddcd365854966d.jpg'
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Livros</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/usuario/filtro-livros.css">
    <link rel="stylesheet" href="../../../public/css/components/usuario/card2.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/voltar.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/header.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<?php include "../../../public/components/usuario/header/header.php"; ?>

<div class="container">
    <?php include "../../../public/components/usuario/voltar/voltar.php"; ?>
    <div class="content-wrapper">
        <div class="sidebar">
            <div class="filter-group">
                <label for="area">Área</label>
                <select name="area" id="area" class="filter-select">
                    <option value="">SELECIONE</option>
                    <option value="Ciência_Sociais_Aplicadas">Ciência Sociais Aplicadas</option>
                    <option value="Economia">Economia</option>
                    <option value="Multidiciplinar">Multidiciplinar</option>
                    <option value="Ciências_Agrârias">Ciências Agrârias</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="categoria">Categoria/Tags</label>
                <select name="categoria" id="categoria" class="filter-select">
                    <option value="">SELECIONE</option>
                    <option value="Literatura">Literatura</option>
                    <option value="Folheto">Folheto</option>
                    <option value="Artigo_Periódico">Artigo Periódico</option>
                    <option value="Livro">Livro</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="tipo">Unidade</label>
                <select name="tipo" id="tipo" class="filter-select">
                    <option value="">SELECIONE</option>
                    <option value="PDF">BSCOR</option>
                    <option value="EPUB">BSDOU</option>
                    <option value="MOBI">BSHUB</option>
                    <option value="DOC">BSPOP</option>
                </select>
            </div>
        </div>

        <div class="main-content">
            <div class="category-section">
                <div class="category-header">
                    <h2>Tecnologia</h2>
                </div>
                <div class="books-grid" id="tecnologia-grid">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php $livro = getLivroOuDefault($livros, $i); ?>
                        <?php $link = htmlspecialchars($URLBASE . "/src/views/usuario/livro-info.php?id=" . $livro['id']); ?>
                        <div class="book-card">
                            <div class="book-image-container">
                                <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo $livro['titulo']; ?>">
                                <button class="favorite-btn" onclick="toggleFavorite(this)">
                                    <i class="fa-regular fa-heart icone-heart oco"></i>
                                    <i class="fa-solid fa-heart icone-heart preenchido"></i>
                                </button>
                            </div>
                            <div class="book-info">
                                <h3><?php echo $livro['titulo']; ?></h3>
                                <p class="author"><?php echo $livro['autor']; ?></p>
                                <p class="status disponivel">Disponível</p>
                                <button class="reserve-btn" onclick="window.location.href='<?php echo $link; ?>'">Reservar</button>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="category-section">
                <div class="category-header">
                    <h2>Saúde</h2>
                </div>
                <div class="books-grid" id="saude-grid">
                    <?php for ($i = 4; $i < 8; $i++): ?>
                        <?php $livro = getLivroOuDefault($livros, $i); ?>
                        <?php $link = htmlspecialchars($URLBASE . "/src/views/usuario/livro-info.php?id=" . $livro['id']); ?>
                        <div class="book-card">
                            <div class="book-image-container">
                                <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo $livro['titulo']; ?>">
                                <button class="favorite-btn" onclick="toggleFavorite(this)">
                                    <i class="fa-regular fa-heart icone-heart oco"></i>
                                    <i class="fa-solid fa-heart icone-heart preenchido"></i>
                                </button>
                            </div>
                            <div class="book-info">
                                <h3><?php echo $livro['titulo']; ?></h3>
                                <p class="author"><?php echo $livro['autor']; ?></p>
                                <p class="status disponivel">Disponível</p>
                                <button class="reserve-btn" onclick="window.location.href='<?php echo $link; ?>'">Reservar</button>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="category-section">
                <div class="category-header">
                    <h2>Gestão</h2>
                </div>
                <div class="books-grid books-grid-4" id="gestao-grid">
                    <?php for ($i = 8; $i < 12; $i++): ?>
                        <?php $livro = getLivroOuDefault($livros, $i); ?>
                        <?php $link = htmlspecialchars($URLBASE . "/src/views/usuario/livro-info.php?id=" . $livro['id']); ?>
                        <div class="book-card">
                            <div class="book-image-container">
                                <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo $livro['titulo']; ?>">
                                <button class="favorite-btn" onclick="toggleFavorite(this)">
                                    <i class="fa-regular fa-heart icone-heart oco"></i>
                                    <i class="fa-solid fa-heart icone-heart preenchido"></i>
                                </button>
                            </div>
                            <div class="book-info">
                                <h3><?php echo $livro['titulo']; ?></h3>
                                <p class="author"><?php echo $livro['autor']; ?></p>
                                <p class="status disponivel">Disponível</p>
                                <button class="reserve-btn" onclick="window.location.href='<?php echo $link; ?>'">Reservar</button>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="pagination">
                    <button class="pagination-btn" onclick="changePage('gestao', 1)">1</button>
                    <button class="pagination-btn" onclick="changePage('gestao', 2)">2</button>
                    <button class="pagination-btn active" onclick="changePage('gestao', 3)">3</button>
                    <button class="pagination-btn" onclick="changePage('gestao', 4)">4</button>
                    <button class="pagination-btn" onclick="changePage('gestao', 5)">5</button>
                </div>
            </div>
        </div>
    </div>

     <div class="highlights-section">
        <div class="section-header">
            <h2>Destaques</h2>
        </div>

        <div class="carousel-container">
            <button class="carousel-btn prev-btn" onclick="moveCarousel(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="carousel-wrapper">
                <div class="carousel-track">
                    <?php for ($i = 0; $i < 9; $i++): ?>
                        <?php $livro = getLivroOuDefault($livros, $i); ?>
                        <?php $link = htmlspecialchars($URLBASE . "/src/views/usuario/livro-info.php?id=" . $livro['id']); ?>
                        <div class="carousel-item <?php echo $i === 3 ? 'active' : ''; ?>">
                            <div class="carousel-book">
                                <img src="<?php echo $livro['imagem']; ?>" alt="<?php echo $livro['titulo']; ?>">
                                <div class="carousel-info">
                                    <h4><?php echo $livro['titulo']; ?></h4>
                                    <p>Autor - <?php echo $livro['autor']; ?></p>
                                    <p class="disponivel">(Jogue na mesa)</p>
                                    <p>Disponível</p>
                                    <button class="carousel-reserve-btn" onclick="window.location.href='<?php echo $link; ?>'">Reservar</button>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <button class="carousel-btn next-btn" onclick="moveCarousel(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php include "../../../public/components/usuario/footer/footer.php"; ?>

<script src="<?php echo $URLBASE ?>/public/js/usuario/filtro-livros.js"></script>

</body>
</html>
