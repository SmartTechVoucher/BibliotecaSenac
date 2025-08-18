const inputFile = document.getElementById('foto-user'); // O campo de entrada de arquivo
        const fotoPerfil = document.getElementById('foto-perfil'); // A tag de imagem para pré-visualização

        // 2. Adicione um "ouvinte de evento" (event listener) ao campo de entrada de arquivo
        // Este ouvinte será ativado toda vez que o usuário selecionar um arquivo
        inputFile.addEventListener('change', function(event) {
            // Verifique se um arquivo foi realmente selecionado
            if (event.target.files && event.target.files[0]) {
                // 3. Crie um novo objeto FileReader
                const reader = new FileReader();

                // 4. Defina o que acontece quando o leitor termina de carregar o arquivo
                // O resultado (`reader.result`) será a URL de dados da imagem
                reader.onload = function(e) {
                    // 5. Atualize o atributo 'src' da tag de imagem com a nova URL de dados
                    fotoPerfil.src = e.target.result;
                };

                // 6. Leia o arquivo como uma URL de dados (base64)
                reader.readAsDataURL(event.target.files[0]);
            }
        });