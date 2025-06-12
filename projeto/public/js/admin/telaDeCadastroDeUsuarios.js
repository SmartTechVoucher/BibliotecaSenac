let botao = document.getElementsByClassName("hamburguer")
let menu = document.getElementById("menu")
let corpo = document.getElementsByTagName("body")

let menuAberto = false

botao[0].addEventListener("click", function(){
    
    //Chamar o menu para o botão e definir sua posição com position 
    //absolute para aparecer abaixo do menu. Linhas 15 e 16

    menu.style.position="absolute" 
    menu.style.bottom="50px" 

    if(menuAberto==false){
        menuAberto=true
        menu.style.display="block"
    }

    else{
        menuAberto=false
        menu.style.display="none"
    }

})

let icone = document.getElementById("iconeComandante")
let minhaConta = document.getElementsByClassName("minhaConta")[0]

let menuAberto2 = false

icone.addEventListener("click", function(){

    if(menuAberto2==false){
        menuAberto2=true
        minhaConta.style.display="block"
    }

    else{
        menuAberto2=false
        minhaConta.style.display="none"
    }

})

document.getElementById("btn-procurar").addEventListener("click", function() {
    document.getElementById("foto").click();    
});

//O código abaixo controla o aparecimento e desaparecimento do label
document.getElementById("foto").addEventListener("change", function() {
    let labelFoto = document.getElementById("labelFoto");
    let placeholder = document.getElementById("placeholder");

    if (this.files.length > 0) {
        labelFoto.style.display = "none"; // Oculta o label
        placeholder.value = "Imagem carregada"; // Atualiza o texto do input de texto
    }
});
//O código acima controla o aparecimento e desaparecimento do label

//O código abaixo exclui o arquivo selecionado pelo usuário
document.getElementById("foto").addEventListener("change", function() {
    let arquivo = this.files[0];
    if (arquivo) {
        document.getElementById("placeholder").value = arquivo.name;
    } else {
        document.getElementById("placeholder").value = "Nenhuma foto selecionada";
    }
});

document.getElementById("btn-excluir").addEventListener("click", function() {
    document.getElementById("foto").value = "";
    document.getElementById("placeholder").value = "Nenhuma foto selecionada";
});
//O código acima exclui o arquivo selecionado pelo usuário

