/**
 * Variáveis globais para o Carrossel de Destaques (Highlights)
 * Elas serão inicializadas em DOMContentLoaded.
 */
let currentIndex = 0;
let carouselTrack = null;
let carouselItems = null;
let totalItems = 0;
const ITEM_WIDTH = 200; // Largura do item no CSS: .carousel-item { width: 200px; }
const ITEM_GAP = 20;    // Gap no CSS: .carousel-track { gap: 20px; }
const FULL_ITEM_WIDTH = ITEM_WIDTH + ITEM_GAP;


// =======================================================
// FUNÇÕES AUXILIARES
// =======================================================

/**
 * Alterna o ícone de favorito.
 * @param {HTMLElement} btn - O botão de favorito clicado.
 */
function alternarFavorito(btn) {
    const icone = btn.querySelector('i');
    // Alterna entre FontAwesome Regular (far) e Solid (fas)
    icone.classList.toggle('far');
    icone.classList.toggle('fas');
    btn.classList.toggle('favorited');
}

/**
 * Move o carrossel de destaques e centraliza o item ativo.
 * Esta função foi refeita para calcular dinamicamente a posição
 * e forçar a parada nos limites, eliminando o espaço em branco.
 * @param {number} direcao - Direção do movimento (1 para próximo, -1 para anterior, 0 para inicialização).
 */
function moverCarrossel(direcao) {
    // Verifica se as variáveis foram inicializadas
    if (!carouselTrack || !carouselItems || totalItems === 0) return;

    // 1. Calcula o novo índice
    let newIndex = currentIndex + direcao;

    // 2. Lógica de LOOP (Se o objetivo for rolar infinitamente)
    // Se você quer que ele PARE no início e no fim, COMENTE este bloco e DESCOMENTE o próximo.
    if (newIndex >= totalItems) {
        newIndex = 0;
    } else if (newIndex < 0) {
        newIndex = totalItems - 1;
    }
    
    /* // Lógica de PARADA (Se o objetivo é que PARE no primeiro e último item):
    if (newIndex < 0) {
        newIndex = 0;
        if (direcao === -1) return; // Para a navegação se já estiver no início
    } else if (newIndex >= totalItems) {
        newIndex = totalItems - 1;
        if (direcao === 1) return; // Para a navegação se já estiver no final
    }
    */
    
    currentIndex = newIndex;

    // 3. Atualiza Classes Ativas
    carouselItems.forEach((item, index) => {
        item.classList.remove('active');
        if (index === currentIndex) {
            item.classList.add('active');
        }
    });

    // 4. Cálculo da Translação para CENTRALIZAÇÃO com Limite
    const wrapper = document.querySelector('.carousel-wrapper');
    if (!wrapper) return;
    
    const wrapperWidth = wrapper.offsetWidth;

    // a) Deslocamento básico para alinhar o item ativo ao início da trilha
    let translateX = -currentIndex * FULL_ITEM_WIDTH;
    
    // b) Ajuste para centralizar o item ativo no meio do wrapper
    const centerAdjustment = (wrapperWidth / 2) - (ITEM_WIDTH / 2);
    translateX += centerAdjustment;

    // 5. Aplicar o Limite Final para ELIMINAR O ESPAÇO EM BRANCO (Solução do problema)
    const totalTrackWidth = (totalItems * FULL_ITEM_WIDTH) - ITEM_GAP;
    
    if (totalTrackWidth > wrapperWidth) {
        // Calcula o ponto máximo (negativo) que a trilha pode ir
        // garantindo que o final da trilha não saia da vista.
        const maxScrollLimit = -(totalTrackWidth - wrapperWidth);
        
        // Ajusta o limite para a centralização
        const adjustedMaxScrollLimit = maxScrollLimit + centerAdjustment;

        // O carrossel não pode ir além do limite mais negativo.
        // O valor 0 (sem rolagem) é o limite positivo (início).
        // Aqui, forçamos o limite máximo de rolagem negativa.
        if (translateX < adjustedMaxScrollLimit) {
            translateX = adjustedMaxScrollLimit;
        }
        
        // Garante que não role para o lado errado no início (valor máximo de 0)
        translateX = Math.min(translateX, centerAdjustment);
    } else {
        // Se a trilha for menor que o wrapper, centraliza a trilha inteira
        translateX = (wrapperWidth / 2) - (totalTrackWidth / 2);
    }

    carouselTrack.style.transform = `translateX(${translateX}px)`;
}


/**
 * Função para criar um card de livro (Mantida, mas renomeada para seguir convenção).
 * Esta função deve estar disponível para o escopo global.
 */
function criarCartaoLivro(livro) {
    const cartaoLivro = document.createElement('div');
    cartaoLivro.className = 'book-card';
    
    cartaoLivro.innerHTML = `
        <div class="book-image-container">
            <img src="${livro.imagem || 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg'}" alt="${livro.titulo || 'Livro'}">
            <button class="favorite-btn" onclick="alternarFavorito(this)">
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


// As funções abaixo (mudarPagina, atualizarGridLivros) foram mantidas,
// mas as variáveis globais fixas foram removidas.

function mudarPagina(categoria, pagina) {
    // ... Lógica mantida para paginação ...
    const categoriaDaSecao = document.querySelector(`#${categoria}-grid`).closest('.category-section');
    const botoesDepaginacao = categoriaDaSecao.querySelectorAll('.pagination-btn');
    
    botoesDepaginacao.forEach(btn => {
        btn.classList.remove('active');
    });
    
    const botaoClicado = Array.from(botoesDepaginacao).find(btn => 
        btn.textContent.trim() === pagina.toString()
    );
    
    if (botaoClicado) {
        botaoClicado.classList.add('active');
    }
    
    console.log(`Mudando para página ${pagina} da categoria ${categoria}`);
}

function atualizarGridLivros(category, livros) {
    const grid = document.querySelector(`#${category}-grid`);
    grid.innerHTML = '';
    
    livros.forEach(livro => {
        // Usando a função criarCartaoLivro que está no escopo global agora
        const cartaoLivro = criarCartaoLivro(livro); 
        grid.appendChild(cartaoLivro);
    });
}


// =======================================================
// INICIALIZAÇÃO DA PÁGINA
// =======================================================

document.addEventListener('DOMContentLoaded', function() {
    // Inicialização das variáveis do Carrossel:
    carouselTrack = document.querySelector('.carousel-track');
    carouselItems = document.querySelectorAll('.carousel-item');
    totalItems = carouselItems.length;

    // Inicializa o currentIndex baseado no item com a classe 'active' no HTML (se existir)
    carouselItems.forEach((item, index) => {
        if (item.classList.contains('active')) {
            currentIndex = index;
        }
    });

    // Move o carrossel para a posição inicial correta (centralizando o item inicial)
    moverCarrossel(0); 
    
    // Inicializa o estado da paginação
    const todasAsSecoesDePaginacao = document.querySelectorAll('.pagination');
    todasAsSecoesDePaginacao.forEach(section => {
        const primeiroBotao = section.querySelector('.pagination-btn');
        if (primeiroBotao && !section.querySelector('.pagination-btn.active')) {
            primeiroBotao.classList.add('active');
        }
    });
});