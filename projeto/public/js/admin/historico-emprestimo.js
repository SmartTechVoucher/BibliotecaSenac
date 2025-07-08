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
        const userTable = document.getElementById('userTable'); // Renamed ID for clarity
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageInfoSpan = document.getElementById('pageInfo');

        const ITEMS_PER_PAGE = 5;
        let currentPage = 1;
        let currentFilteredData = []; // This will store the data currently being paginated

        // Function to render the table with the data for the current page
        function renderTablePage() {
            const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
            const endIndex = startIndex + ITEMS_PER_PAGE;
            const itemsToDisplay = currentFilteredData.slice(startIndex, endIndex);

            userTable.innerHTML = itemsToDisplay.map(user => `
                <tr>
                    <td>${user.status}</td>
                    <td>${user.exemplar}</td>
                    <td>${user.leitor}</td>
                    <td>${user.data}</td>
                    <td>${user.prazo}</td>
                    <td>${user.devolucao}</td>
                </tr>
            `).join('');

            updatePaginationControls();
        }

        // Function to update the state of pagination buttons and info
        function updatePaginationControls() {
            const totalPages = Math.ceil(currentFilteredData.length / ITEMS_PER_PAGE);

            prevBtn.disabled = (currentPage === 1);
            nextBtn.disabled = (currentPage === totalPages || totalPages === 0);

            pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages || 1}`; // Show "1 de 1" if no items
        }

        // Handles "Previous" button click
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTablePage();
            }
        }

        // Handles "Next" button click
        function nextPage() {
            const totalPages = Math.ceil(currentFilteredData.length / ITEMS_PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                renderTablePage();
            }
        }

        // Function to apply the filter and reset pagination
        function applyFilterAndPaginate() {
            const selectedStatus = document.querySelector('input[name="statusFilter"]:checked').value;
            
            if (selectedStatus === 'Todos') {
                currentFilteredData = emprestimos;
            } else {
                currentFilteredData = emprestimos.filter(emprestimo => emprestimo.status === selectedStatus);
            }
            
            currentPage = 1; // Reset to the first page whenever the filter changes
            renderTablePage();
        }

        // Initial load: apply default filter (Todos) and render the first page
        document.addEventListener('DOMContentLoaded', () => {
            applyFilterAndPaginate();
        });
        
        
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
     



