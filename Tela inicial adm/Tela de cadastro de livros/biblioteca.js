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

//Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone.

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

//Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone.


const titulo=document.getElementsByClassName("titulo2")[0]
const autor=document.getElementsByClassName("autor")[0]
const codigo=document.getElementsByClassName("codigo")[0]

function mostrarFalta(){
    
    if(titulo.value==""){
        titulo.style.border="3px solid"
        titulo.style.borderColor="red"
    }

    else{
        titulo.style.border="none"
    }

}

function mostrarFalta2(){
    if(autor.value==""){
       autor.style.border="3px solid"
       autor.style.borderColor="red"
    }

    else{
        autor.style.border="none"
    }

}

function mostrarFalta3(){
    if(codigo.value==""){
       codigo.style.border="3px solid"
       codigo.style.borderColor="red"
    }

    else{
        codigo.style.border="none"
    }

}

//Borda vermelha do segundo formulário que aparece a partir de 1280 pixels.

const titulo2=document.getElementsByClassName("titulo2")[1]
const autor2=document.getElementsByClassName("autor")[1]
const codigo2=document.getElementsByClassName("codigo")[1]

function mostrarFaltaSegundoForm(){
    if(titulo2.value==""){
        titulo2.style.border="3px solid"
        titulo2.style.borderColor="red"
    }

    else{
        titulo2.style.border="none"
    }
}

function mostrarFalta2SegundoForm(){
    if(autor2.value==""){
        autor2.style.border="3px solid"
        autor2.style.borderColor="red"
    }

    else{
        autor2.style.border="none"
    }
}

function mostrarFalta3SegundoForm(){
    if(codigo2.value==""){
        codigo2.style.border="3px solid"
        codigo2.style.borderColor="red"
    }

    else{
        codigo2.style.border="none"
    }
}

//Aparição do texto "imagem carregada".

function texto(){
    
    const imagemCarregada= document.createElement("p")
    imagemCarregada.textContent="Imagem carregada."
    imagemCarregada.style.color="green"
    
    let mensagem=document.getElementById("mensagem")
    mensagem.style.display="block"
    
   
    mensagem.appendChild(imagemCarregada)
}

function texto2(){
    
    const imagemCarregada= document.createElement("p")
    imagemCarregada.textContent="Imagem carregada."
    imagemCarregada.style.color="green"
    
    let mensagem=document.getElementById("mensagemPrimeiroForm")
    mensagem.style.display="block"
    
   
    mensagem.appendChild(imagemCarregada)
}
