document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded - attaching all event listeners');

    // Menu hamburguer
    let botao = document.getElementsByClassName("hamburguer");
    if (botao.length > 0) {
        let menu = document.getElementById("menu");
        if (menu) {
            let menuAberto = false;

            const hamburgerElement = botao[0];
            if (hamburgerElement) {
                hamburgerElement.addEventListener("click", function(){
                    console.log('T1: Hamburguer clicked');
                    menu.style.position = "absolute";
                    menu.style.bottom = "50px";

                    if (menuAberto == false) {
                        menuAberto = true;
                        menu.style.display = "block";
                    } else {
                        menuAberto = false;
                        menu.style.display = "none";
                    }
                });
            }
        }
    }

    // Menu perfil
    let icone = document.getElementById("iconeComandante");
    let minhaConta = document.getElementsByClassName("minhaConta")[0];
    let menuAberto2 = false;

    if (icone && minhaConta) {
        icone.addEventListener("click", function(){
            console.log('Perfil menu clicked');
            if(menuAberto2==false){
                menuAberto2=true
                minhaConta.style.display="block"
            } else {
                menuAberto2=false
                minhaConta.style.display="none"
            }
        });
    }

    // Validação bordas vermelhas
    const titulo = document.getElementsByClassName("titulo2")[0];
    const autor = document.getElementsByClassName("autor")[0];
    const codigo = document.getElementsByClassName("codigo")[0];

    function mostrarFalta(){
        if(titulo && titulo.value==""){
            titulo.style.border="3px solid red";
        } else if (titulo) {
            titulo.style.border="none";
        }
    }

    function mostrarFalta2(){
        if(autor && autor.value==""){
           autor.style.border="3px solid red";
        } else if (autor) {
            autor.style.border="none";
        }
    }

    function mostrarFalta3(){
        if(codigo && codigo.value==""){
           codigo.style.border="3px solid red";
        } else if (codigo) {
            codigo.style.border="none";
        }
    }

    // Segundo form
    const titulo2 = document.getElementsByClassName("titulo2")[1];
    const autor2 = document.getElementsByClassName("autor")[1];
    const codigo2 = document.getElementsByClassName("codigo")[1];

    function mostrarFaltaSegundoForm(){
        if(titulo2 && titulo2.value==""){
            titulo2.style.border="3px solid red";
        } else if (titulo2) {
            titulo2.style.border="none";
        }
    }

    function mostrarFalta2SegundoForm(){
        if(autor2 && autor2.value==""){
            autor2.style.border="3px solid red";
        } else if (autor2) {
            autor2.style.border="none";
        }
    }

    function mostrarFalta3SegundoForm(){
        if(codigo2 && codigo2.value==""){
            codigo2.style.border="3px solid red";
        } else if (codigo2) {
            codigo2.style.border="none";
        }
    }

    // Imagem carregada
    function texto(){
        const imagemCarregada = document.createElement("p");
        imagemCarregada.textContent="Imagem carregada.";
        imagemCarregada.style.color="green";
        
        let mensagem = document.getElementById("mensagem");
        if (mensagem) {
            mensagem.style.display="block";
            mensagem.appendChild(imagemCarregada);
        }
    }

    function texto2(){
        const imagemCarregada = document.createElement("p");
        imagemCarregada.textContent="Imagem carregada.";
        imagemCarregada.style.color="green";
        
        let mensagem = document.getElementById("mensagemPrimeiroForm");
        if (mensagem) {
            mensagem.style.display="block";
            mensagem.appendChild(imagemCarregada);
        }
    }

    // Preview de imagem da capa
    const fileInput = document.getElementById('capa-livro');
    const previewImg = document.getElementById('preview-capa');
    console.log('File input:', fileInput, 'Preview img:', previewImg);
    
    if (fileInput && previewImg) {
        fileInput.addEventListener('change', function(e) {
            console.log('File selected:', e.target.files[0]);
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Preview loaded');
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                console.log('Invalid file or no file');
                previewImg.src = '';
                previewImg.style.display = 'none';
                if (file && !file.type.startsWith('image/')) {
                    alert('Por favor, selecione uma imagem válida para a capa.');
                    fileInput.value = '';
                }
            }
        });
    } else {
        console.error('Preview elements not found');
    }

    // AJAX submit do form de cadastro
    const form = document.getElementById('cadastro-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate required hidden fields have IDs
            const requiredFields = ['autor', 'editora', 'idioma', 'categoria', 'area', 'tipo-documento'];
            let valid = true;
            requiredFields.forEach(field => {
                const hidden = document.getElementById('hidden-' + field);
                if (!hidden || !hidden.value || hidden.value <= 0) {
                    valid = false;
                    const input = document.getElementById(field);
                    if (input) {
                        input.style.border = '3px solid red';
                        input.placeholder = 'Selecione um ' + field;
                    }
                }
            });
            
            // Check text fields
            const tituloInput = document.getElementById('titulo-livro');
            const isbnInput = document.getElementById('isbn-livro');
            const paginasInput = document.getElementById('numero-paginas');
            if (!tituloInput.value.trim()) {
                tituloInput.style.border = '3px solid red';
                valid = false;
            }
            if (!isbnInput.value.trim()) {
                isbnInput.style.border = '3px solid red';
                valid = false;
            }
            if (!paginasInput.value || parseInt(paginasInput.value) <= 0) {
                paginasInput.style.border = '3px solid red';
                valid = false;
            }
            
            if (!valid) {
                alert('Por favor, preencha todos os campos obrigatórios corretamente.');
                return;
            }
            
            const formData = new FormData(form);
            formData.append('ajax', '1'); // Flag for AJAX
            const url = form.action;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.sucesso) {
                    showSuccessModal(result.mensagem || 'Livro cadastrado com sucesso!');
                    form.reset();
                    const previewImg = document.getElementById('preview-capa');
                    if (previewImg) previewImg.style.display = 'none';
                    
                    // Clear selections and borders
                    requiredFields.forEach(field => {
                        const input = document.getElementById(field);
                        const hidden = document.getElementById('hidden-' + field);
                        if (input) {
                            input.value = '';
                            input.style.border = '';
                            if (input.dataset.originalPlaceholder) {
                                input.placeholder = input.dataset.originalPlaceholder;
                            } else {
                                input.placeholder = 'Digite ou selecione ' + field;
                            }
                        }
                        if (hidden) hidden.value = '';
                    });
                    [tituloInput, isbnInput, paginasInput].forEach(el => {
                        if (el) el.style.border = '';
                    });
                } else {
                    alert(result.mensagem || 'Erro ao cadastrar livro.');
                    console.error('Server error:', result);
                }
            } catch (error) {
                console.error('Erro no submit AJAX:', error);
                alert('Erro de conexão. Tente novamente. Detalhes: ' + error.message);
            }
        });
    }

    console.log('All event listeners attached successfully');
});
