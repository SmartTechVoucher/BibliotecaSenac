function renderTable() {
      const userTable = document.getElementById('userTable');

      const emprestimos = [
        { status: 'Finalizado', exemplar: '402864', leitor: 'Hugo Vitor', data: '23/04/2024', prazo: '26/04/2024', devolucao: '25/04/2024' },
        { status: 'Finalizado', exemplar: '785930', leitor: 'Gabriel Linos', data: '30/04/2024', prazo: '06/05/2024', devolucao: '05/05/2024' },
        { status: 'Atrasado', exemplar: '944512', leitor: 'Lulu Oliveira', data: '04/05/2024', prazo: '05/05/2024', devolucao: 'Em demanda' },
        { status: 'Finalizado', exemplar: '402864', leitor: 'Tomás Chell', data: '04/05/2024', prazo: '05/05/2024', devolucao: '05/05/2024' },
        { status: 'Finalizado', exemplar: '789360', leitor: 'Vitória Pacheco', data: '12/05/2024', prazo: '14/05/2024', devolucao: '13/05/2024' },
        { status: 'Finalizado', exemplar: '762910', leitor: 'Davi Matos', data: '15/05/2024', prazo: '17/05/2024', devolucao: '17/05/2024' },
        { status: 'Atrasado', exemplar: '666834', leitor: 'Walter Branco', data: '20/05/2024', prazo: '22/05/2024', devolucao: 'Em demanda' },
        { status: 'Finalizado', exemplar: '402864', leitor: 'Jéssica Lourdes', data: '20/05/2024', prazo: '20/05/2024', devolucao: '21/05/2024' },
        { status: 'Em andamento', exemplar: '762910', leitor: 'Carlos Dalva', data: '25/05/2024', prazo: '27/05/2024', devolucao: '28/07/2024' }
      ];

      
      userTable.innerHTML = emprestimos.map((user, index) => `
        
        <tr>
        <td>${user.status}</td>
        <td>${user.exemplar}</td>
        <td>${user.leitor}</td>
        <td>${user.data}</td>
        <td>${user.prazo}</td>
        <td>${user.devolucao}</td>
        </tr>
      `).join('');
      
    }
     document.addEventListener('DOMContentLoaded', renderTable);

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
     



