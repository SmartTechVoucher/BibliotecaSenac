const emprestimos = [
    { status: 'Finalizado', exemplar: '402864', leitor: 'Hugo Vitor', data: '23/04/2024', prazo: '26/04/2024', devolucao: '25/04/2024' },
    { status: 'Finalizado', exemplar: '785930', leitor: 'Gabriel Linos', data: '30/04/2024', prazo: '06/05/2024', devolucao: '05/05/2024' },
    { status: 'Atrasado', exemplar: '944512', leitor: 'Lulu Oliveira', data: '04/05/2024', prazo: '05/05/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '402864', leitor: 'Tomás Chell', data: '04/05/2024', prazo: '05/05/2024', devolucao: '05/05/2024' },
    { status: 'Finalizado', exemplar: '789360', leitor: 'Vitória Pacheco', data: '12/05/2024', prazo: '14/05/2024', devolucao: '13/05/2024' },
    { status: 'Finalizado', exemplar: '762910', leitor: 'Davi Matos', data: '15/05/2024', prazo: '17/05/2024', devolucao: '17/05/2024' },
    { status: 'Atrasado', exemplar: '666834', leitor: 'Walter Branco', data: '20/05/2024', prazo: '22/05/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '402864', leitor: 'Jéssica Lourdes', data: '20/05/2024', prazo: '20/05/2024', devolucao: '21/05/2024' },
    { status: 'Em andamento', exemplar: '762910', leitor: 'Carlos Dalva', data: '25/05/2024', prazo: '27/05/2024', devolucao: '28/07/2024' },
    { status: 'Finalizado', exemplar: '112233', leitor: 'Ana Clara', data: '01/06/2024', prazo: '05/06/2024', devolucao: '04/06/2024' },
    { status: 'Em andamento', exemplar: '445566', leitor: 'Bruno Costa', data: '03/06/2024', prazo: '10/06/2024', devolucao: 'Em demanda' },
    { status: 'Atrasado', exemplar: '778899', leitor: 'Carla Dias', data: '05/06/2024', prazo: '08/06/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '990011', leitor: 'Daniel Lima', data: '07/06/2024', prazo: '11/06/2024', devolucao: '10/06/2024' },
    { status: 'Em andamento', exemplar: '223344', leitor: 'Eduarda Silva', data: '10/06/2024', prazo: '15/06/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '556677', leitor: 'Felipe Rocha', data: '12/06/2024', prazo: '16/06/2024', devolucao: '16/06/2024' },
    { status: 'Atrasado', exemplar: '889900', leitor: 'Giovana Santos', data: '14/06/2024', prazo: '17/06/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '101122', leitor: 'Heloísa Mello', data: '16/06/2024', prazo: '20/06/2024', devolucao: '19/06/2024' },
    { status: 'Em andamento', exemplar: '334455', leitor: 'Igor Fernandes', data: '18/06/2024', prazo: '24/06/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '667788', leitor: 'Julia Pereira', data: '20/06/2024', prazo: '25/06/2024', devolucao: '24/06/2024' },
    { status: 'Atrasado', exemplar: '990001', leitor: 'Kevin Borges', data: '22/06/2024', prazo: '26/06/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '012345', leitor: 'Larissa Nunes', data: '24/06/2024', prazo: '28/06/2024', devolucao: '27/06/2024' },
    { status: 'Em andamento', exemplar: '678901', leitor: 'Marcelo Pires', data: '26/06/2024', prazo: '02/07/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '234567', leitor: 'Natália Costa', data: '28/06/2024', prazo: '03/07/2024', devolucao: '03/07/2024' },
    { status: 'Atrasado', exemplar: '890123', leitor: 'Otávio Martins', data: '30/06/2024', prazo: '03/07/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '456789', leitor: 'Paula Gomes', data: '02/07/2024', prazo: '07/07/2024', devolucao: '06/07/2024' },
    { status: 'Em andamento', exemplar: '010101', leitor: 'Ricardo Alves', data: '04/07/2024', prazo: '10/07/2024', devolucao: 'Em demanda' },
    { status: 'Finalizado', exemplar: '121212', leitor: 'Sofia Ribeiro', data: '06/07/2024', prazo: '11/07/2024', devolucao: '11/07/2024' },
    { status: 'Atrasado', exemplar: '343434', leitor: 'Thiago Mendes', data: '08/07/2024', prazo: '11/07/2024', devolucao: 'Em demanda' },
    { status: 'Em andamento', exemplar: '565656', leitor: 'Ursula Castro', data: '10/07/2024', prazo: '15/07/2024', devolucao: 'Em demanda' }
];
        const TabelaDeUsuario = document.getElementById('userTable'); 
        const botaoAnterior = document.getElementById('prevBtn');
        const proximoBotao = document.getElementById('nextBtn');
        const informacoesDaPagina = document.getElementById('pageInfo');

        const itens_por_pagina = 5;
        let paginaAtual = 1;
        let dadoAtualFiltrado = []; 

        // Function to render the table with the data for the current page
        function renderizarTabelaDePaginas() {
            const indiceInicial = (paginaAtual - 1) * itens_por_pagina;
            const indiceFinal = indiceInicial + itens_por_pagina;
            const itensEmTela = dadoAtualFiltrado.slice(indiceInicial, indiceFinal);

            TabelaDeUsuario.innerHTML = itensEmTela.map(user => `
                <tr>
                    <td>${user.status}</td>
                    <td>${user.exemplar}</td>
                    <td>${user.leitor}</td>
                    <td>${user.data}</td>
                    <td>${user.prazo}</td>
                    <td>${user.devolucao}</td>
                </tr>
            `).join('');

            atualizarControlesDePaginacao();
        }

        
        function atualizarControlesDePaginacao() {
            const paginasTotais = Math.ceil(dadoAtualFiltrado.length / itens_por_pagina);

            botaoAnterior.disabled = (paginaAtual === 1);
            proximoBotao.disabled = (paginaAtual === paginasTotais || paginasTotais === 0);

            informacoesDaPagina.textContent = `Página ${paginaAtual} de ${paginasTotais || 1}`; 
        }

        function paginaAnterior() {
            if (paginaAtual > 1) {
                paginaAtual--;
                renderizarTabelaDePaginas();
            }
        }

        function proximaPagina() {
            const paginasTotais = Math.ceil(dadoAtualFiltrado.length / itens_por_pagina);
            if (paginaAtual < paginasTotais) {
                paginaAtual++;
                renderizarTabelaDePaginas();
            }
        }

        function aplicarFiltroEPaginacao() {
            const statusSelecionado = document.querySelector('input[name="statusFilter"]:checked').value;
            
            if (statusSelecionado === 'Todos') {
                dadoAtualFiltrado = emprestimos;
            } else {
                dadoAtualFiltrado = emprestimos.filter(emprestimo => emprestimo.status === statusSelecionado);
            }
            
            paginaAtual = 1; 
            renderizarTabelaDePaginas();
        }

        document.addEventListener('DOMContentLoaded', () => {
            aplicarFiltroEPaginacao();
        });
           
// let botao = document.getElementsByClassName("hamburguer")
// let menu = document.getElementById("menu")
// let corpo = document.getElementsByTagName("body")
     
// let menuAberto = false
     
// botao[0].addEventListener("click", function(){
         
//Chamar o menu para o botão e definir sua posição com position 
//absolute para aparecer abaixo do menu. Linhas 15 e 16
     
// menu.style.position="absolute" 
// menu.style.bottom="50px" 
     
//   if(menuAberto==false){
//     menuAberto=true
//     menu.style.display="block"
//    }
     
//   else{
//     menuAberto=false
//     menu.style.display="none"
//   }
     
// })
     
//Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone.
     
// let icone = document.getElementById("iconeComandante")
// let minhaConta = document.getElementsByClassName("minhaConta")[0]
     
// let menuAberto2 = false
     
// icone.addEventListener("click", function(){
     
//   if(menuAberto2==false){
//     menuAberto2=true
//     minhaConta.style.display="block"
//    }
     
//   else{
//     menuAberto2=false
//     minhaConta.style.display="none"
//   }
     
// })
     
//Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone. //Mostrar as opções do ícone.
     



