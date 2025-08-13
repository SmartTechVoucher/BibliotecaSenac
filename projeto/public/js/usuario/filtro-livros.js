function alternarFavorito(btn) {
    const icone = btn.querySelector('i');
    icone.classList.toggle('far');
    icone.classList.toggle('fas');
    btn.classList.toggle('favorited');
}

let slideAtual = 3;
const totalSlides = 7;

function moverCarrossel(direcao) {
    const trilha = document.querySelector('.carousel-track');
    const items = document.querySelectorAll('.carousel-item');
    
    // Remove a classe ativa do item atual
    items[slideAtual].classList.remove('active');
    
    // Calcula a nova posição do slide
    slideAtual += direcao;
    
    // Loop ao redor se necessário
    if (slideAtual >= totalSlides) {
        slideAtual = 0;
    } else if (slideAtual < 0) {
        slideAtual = totalSlides - 1;
    }
    
    // Adicionar classe ativa ao novo item
    items[slideAtual].classList.add('active');
    
    // Calcular translação
    const comprimentoItem = items[0].offsetWidth + 20; // item width + gap
    const translateX = -slideAtual * comprimentoItem + (trilha.offsetWidth / 2) - (comprimentoItem / 2);
    
    trilha.style.transform = `translateX(${translateX}px)`;
}

// Função para controlar a paginação
function mudarPagina(categoria, pagina) {
    // Encontra todos os botões de paginação da categoria específica
    const categoriaDaSecao = document.querySelector(`#${categoria}-grid`).closest('.category-section');
    const botoesDepaginacao = categoriaDaSecao.querySelectorAll('.pagination-btn');
    
    // Remove a classe 'active' de todos os botões
    botoesDepaginacao.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Adiciona a classe 'active' ao botão clicado
    const botaoClicado = Array.from(botoesDepaginacao).find(btn => 
        btn.textContent.trim() === pagina.toString()
    );
    
    if (botaoClicado) {
        botaoClicado.classList.add('active');
    }
    
    console.log(`Mudando para página ${pagina} da categoria ${categoria}`);
    
}

// Função opcional para carregar livros de uma página específica
// function carregarLivrosParaPagina(category, page) {
    // Aqui você faria uma requisição AJAX para o servidor
    // Por exemplo:
    /*
    fetch(`/api/books?category=${category}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            updateBooksGrid(category, data.books);
        })
        .catch(error => {
            console.error('Erro ao carregar livros:', error);
        });
    */
//}

// Função para atualizar o grid de livros
function atualizarGridLivros(category, livros) {
    const grid = document.querySelector(`#${category}-grid`);
    // Limpa o grid atual
    grid.innerHTML = '';
    
    // Adiciona os novos livros
    livros.forEach(livro => {
        const cartaoLivro = createBookCard(livro);
        grid.appendChild(cartaoLivro);
    });
}

// Função para criar um card de livro
function criarCartaoLivro(livro) {
    const cartaoLivro = document.createElement('div');
    cartaoLivro.className = 'book-card';
    
    cartaoLivro.innerHTML = `
        <div class="book-image-container">
            <img src="${livro.imagem || 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg'}" alt="${livro.titulo || 'Livro'}">
            <button class="favorite-btn" onclick="toggleFavorite(this)">
                <i class="far fa-star"></i>
            </button>
        </div>
        <div class="book-info">
            <h3>${livro.titulo || 'Título do Livro'}</h3>
            <p class="author">${livro.autor || 'Autor'}</p>
            <p class="status disponivel">Disponível</p>
            <button class="reserve-btn">Reservar</button>
        </div>
    `;
    
    return cartaoLivro;
}

// Inicializar a posição do carrossel
document.addEventListener('DOMContentLoaded', function() {
    moverCarrossel(0);
    
    // Inicializa o estado da paginação
    // Certifica-se de que o primeiro botão de cada categoria está ativo
    const todasAsSecoesDePaginacao = document.querySelectorAll('.pagination');
    todasAsSecoesDePaginacao.forEach(section => {
        const primeiroBotao = section.querySelector('.pagination-btn');
        if (primeiroBotao && !section.querySelector('.pagination-btn.active')) {
            primeiroBotao.classList.add('active');
        }
    });
});