let botao = document.getElementsByClassName("hamburguer")
let menu = document.getElementById("menu")
let corpo = document.getElementsByTagName("body")

let menuAberto = false

botao[0].addEventListener("click", function(){
    
    //Chamar o menu para o botão e definir sua posição com position 
    //absolute para aparecer abaixo do menu. Linhas 15 e 16

    menu.style.position="absolute" 
    menu.style.bottom="60px" 

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

let cadastrar = document.getElementsByClassName("caixa")[0]
let overlay = document.getElementsByClassName("overlayCadastro")[0]

let menuAberto3 = false

cadastrar.addEventListener("click", function(){

    if(menuAberto3==false){
        menuAberto3=true
        overlay.style.display="block"
    }

    else{
        menuAberto3=false
        overlay.style.display="none" 
    }

})
