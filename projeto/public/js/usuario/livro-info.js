let exemplarFechado = true
function alternarExemplar(){
    const containerExemplarAberto = document.getElementById('containerExemplarOpen');
    const botaoExemplar = document.getElementById('abrirExemplares');
    if (exemplarFechado){
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Minus Math.png"
        containerExemplarAberto.style.display ="block";
        exemplarFechado = false;
    }
        
    else{
        botaoExemplar.src = "/BibliotecaSenac/projeto/public/assets/icons/Plus Math.png"
        containerExemplarAberto.style.display = "none";
        exemplarFechado = true;
    }
}

function reservaConcluida(){
    const botaoReserva = document.getElementById('botaoReserva');
    const estadoAtual = botaoReserva.getAttribute('data-status');
    if (estadoAtual == "livre"){
        botaoReserva.setAttribute('data-status', 'reservado');
        botaoReserva.textContent = "Livro Reservado"
        botaoReserva.style.background = "#F68B1F";
    }
    else{
        const confirmarCancelamento = window.confirm("Você realmente quer cancelar a reserva?");
        if(confirmarCancelamento){
            botaoReserva.setAttribute('data-status', 'livre');
            botaoReserva.textContent = "Reservar"
            botaoReserva.style.background = "#004A90";
        }
    }
        

}

document.addEventListener('DOMContentLoaded', function () {
    const estrelas = document.querySelectorAll('.estrela-input');
    const valorDeRanqueamento = document.getElementById('rating-value');
    let rankAtual = 0;

    
    function atualizarEstrelas(avaliacao) {
        estrelas.forEach(estrela => {
            if (estrela.dataset.value <= avaliacao) {
                estrela.classList.add('active');
            } else {
                estrela.classList.remove('active');
            }
        });
    }

    estrelas.forEach(estrela => {
        
        estrela.addEventListener('mouseover', () => {
            
            atualizarEstrelas(estrela.dataset.value);
        });

        estrela.addEventListener('mouseout', () => {
            
            atualizarEstrelas(rankAtual);
        });

        
        estrela.addEventListener('click', () => {
            // Define a classificação ao clicar
            rankAtual = estrela.dataset.value;
            valorDeRanqueamento.value = rankAtual; 
            atualizarEstrelas(rankAtual); 

            console.log(`Avaliação modificada para: ${rankAtual}`); 
        });
    });

    
    const form = document.getElementById('commentForm');
    form.addEventListener('reset', () => {
        rankAtual = 0;
        valorDeRanqueamento.value = 0;
        atualizarEstrelas(rankAtual);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const cloneBtn = document.getElementById('comentario-botao'); 
    const conteinerDeRevisoes = document.getElementById('reviewsContainer');

    cloneBtn.addEventListener('click', function () {
        const nomeDeUsuario = "Cristiano Ronaldo";
        const comentario = document.getElementById('comentario-input').value;
        const avaliacao = document.getElementById('rating-value').value;
        console.log(avaliacao)
        // clonar o template
        const template = document.getElementById('commentTemplate');
        const novoComentario = template.cloneNode(true);
        novoComentario.style.display = 'block'; 
        novoComentario.id = ''; 

    
        novoComentario.querySelector('.commentTitulo').textContent = nomeDeUsuario;
        novoComentario.querySelector('.commentConteudo').textContent = comentario;

        // pega a data atual
        const hoje = new Date();
        const dataFormatada = hoje.toLocaleDateString('pt-BR');
        novoComentario.querySelector('.commentUserinfo').textContent = `Feito em: ${dataFormatada}`;

        // escolhe uma das 4 variacao de img de acordo com a nota do usuário
        const estrelas =  novoComentario.querySelector('.estrela-placeholder');
        estrelas.src = `../../../../projeto/public/assets/icons/estrelas${avaliacao}.png`; 
        console.log(estrelas.src)

        // Adicionar no container
        conteinerDeRevisoes.insertBefore(novoComentario, conteinerDeRevisoes.firstChild);
    });
});
