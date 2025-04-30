
function toggleMenu(abrir) {
    const navMenu = document.getElementById("nav-menu");
    if (abrir) {
        navMenu.style.display = "block"; // Mostra o menu
    } else {
        navMenu.style.display = "none";  // Esconde o menu
    }
}
