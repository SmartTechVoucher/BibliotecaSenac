const inputFile = document.getElementById('foto-usuario'); 
const fotoPerfil = document.getElementById('foto-perfil'); 


inputFile.addEventListener('change', function (event) {
   
    if (event.target.files && event.target.files[0]) {
        
        const leitor = new FileReader();

        leitor.onload = function (e) {
           
            fotoPerfil.src = e.target.result;
        };

        leitor.readAsDataURL(event.target.files[0]);
    }
});