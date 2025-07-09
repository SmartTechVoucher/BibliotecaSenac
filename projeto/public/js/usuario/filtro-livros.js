function toggleFavorite(btn) {
    const icon = btn.querySelector('i');
    icon.classList.toggle('far');
    icon.classList.toggle('fas');
    btn.classList.toggle('favorited');
}

let currentSlide = 3;
const totalSlides = 7;

function moveCarousel(direction) {
    const track = document.querySelector('.carousel-track');
    const items = document.querySelectorAll('.carousel-item');
    
    // Remove active class from current item
    items[currentSlide].classList.remove('active');
    
    // Calculate new slide position
    currentSlide += direction;
    
    // Loop around if necessary
    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }
    
    // Add active class to new item
    items[currentSlide].classList.add('active');
    
    // Calculate translation
    const itemWidth = items[0].offsetWidth + 20; // item width + gap
    const translateX = -currentSlide * itemWidth + (track.offsetWidth / 2) - (itemWidth / 2);
    
    track.style.transform = `translateX(${translateX}px)`;
}

// Função para controlar a paginação
function changePage(category, page) {
    // Encontra todos os botões de paginação da categoria específica
    const categorySection = document.querySelector(`#${category}-grid`).closest('.category-section');
    const paginationButtons = categorySection.querySelectorAll('.pagination-btn');
    
    // Remove a classe 'active' de todos os botões
    paginationButtons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Adiciona a classe 'active' ao botão clicado
    const clickedButton = Array.from(paginationButtons).find(btn => 
        btn.textContent.trim() === page.toString()
    );
    
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
    
    // Aqui você pode adicionar lógica para carregar o conteúdo da página
    // Por exemplo, fazer uma requisição AJAX para buscar os livros da página específica
    console.log(`Mudando para página ${page} da categoria ${category}`);
    
    // Exemplo de como você poderia implementar a troca de conteúdo:
    // loadBooksForPage(category, page);
}

// Função opcional para carregar livros de uma página específica
function loadBooksForPage(category, page) {
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
}

// Função para atualizar o grid de livros
function updateBooksGrid(category, books) {
    const grid = document.querySelector(`#${category}-grid`);
    // Limpa o grid atual
    grid.innerHTML = '';
    
    // Adiciona os novos livros
    books.forEach(book => {
        const bookCard = createBookCard(book);
        grid.appendChild(bookCard);
    });
}

// Função para criar um card de livro
function createBookCard(book) {
    const bookCard = document.createElement('div');
    bookCard.className = 'book-card';
    
    bookCard.innerHTML = `
        <div class="book-image-container">
            <img src="${book.imagem || 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg'}" alt="${book.titulo || 'Livro'}">
            <button class="favorite-btn" onclick="toggleFavorite(this)">
                <i class="far fa-star"></i>
            </button>
        </div>
        <div class="book-info">
            <h3>${book.titulo || 'Título do Livro'}</h3>
            <p class="author">${book.autor || 'Autor'}</p>
            <p class="status disponivel">Disponível</p>
            <button class="reserve-btn">Reservar</button>
        </div>
    `;
    
    return bookCard;
}

// Initialize carousel position
document.addEventListener('DOMContentLoaded', function() {
    moveCarousel(0);
    
    // Inicializa o estado da paginação
    // Certifica-se de que o primeiro botão de cada categoria está ativo
    const allPaginationSections = document.querySelectorAll('.pagination');
    allPaginationSections.forEach(section => {
        const firstButton = section.querySelector('.pagination-btn');
        if (firstButton && !section.querySelector('.pagination-btn.active')) {
            firstButton.classList.add('active');
        }
    });
});