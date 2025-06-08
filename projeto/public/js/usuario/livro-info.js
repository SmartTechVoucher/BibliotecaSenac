let exemplarClosed = true
function exemplarToggle(){
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarClosed){
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Minus Math.png"
        containerExemplarAberto.style.display ="block";
        exemplarClosed = false;
    }
        
    else{
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Plus Math.png"
        containerExemplarAberto.style.display = "none";
        exemplarClosed = true;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.estrela-input');
    const ratingValue = document.getElementById('rating-value');
    let currentRating = 0;

    
    function updateStars(rating) {
        stars.forEach(star => {
            if (star.dataset.value <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    stars.forEach(star => {
        
        star.addEventListener('mouseover', () => {
            
            updateStars(star.dataset.value);
        });

        star.addEventListener('mouseout', () => {
            
            updateStars(currentRating);
        });

        
        star.addEventListener('click', () => {
            // Set the permanent rating on click
            currentRating = star.dataset.value;
            ratingValue.value = currentRating; 
            updateStars(currentRating); 

            console.log(`Rating set to: ${currentRating}`); 
        });
    });

    
    const form = document.getElementById('commentForm');
    form.addEventListener('reset', () => {
        currentRating = 0;
        ratingValue.value = 0;
        updateStars(currentRating);
    });
});
 


document.addEventListener('DOMContentLoaded', function () {
    const cloneBtn = document.getElementById('comentario-botao'); 
    const reviewsContainer = document.getElementById('reviewsContainer');

    cloneBtn.addEventListener('click', function () {
        const username = "Cristiano Ronaldo";
        const comment = document.getElementById('comentario-input').value;
        const rating = document.getElementById('rating-value').value;
        console.log(rating)
        // clonar o template
        const template = document.getElementById('commentTemplate');
        const newComment = template.cloneNode(true);
        newComment.style.display = 'block'; 
        newComment.id = ''; 

    
        newComment.querySelector('.commentTitulo').textContent = username;
        newComment.querySelector('.commentConteudo').textContent = comment;

        // pegaa a data atual
        const today = new Date();
        const dataFormatada = today.toLocaleDateString('pt-BR');
        newComment.querySelector('.commentUserinfo').textContent = `Feito em: ${dataFormatada}`;

        // escolhe uma das 4 variacao de img de acordo com a nota do usueridofosdfsofd
        const estrelas =  newComment.querySelector('.estrela-placeholder');
        estrelas.src = `../../../../projeto/public/assets/icons/estrelas${rating}.png`; 
        console.log(estrelas.src)

        // Adicionar no container
        reviewsContainer.insertBefore(newComment, reviewsContainer.firstChild);
    });
});
