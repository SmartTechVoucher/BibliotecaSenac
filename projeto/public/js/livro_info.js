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