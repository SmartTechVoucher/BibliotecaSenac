const toggleMenuLateral = document.getElementById('menu-toggle');
const menuLateral = document.getElementById('menu-lateral');
const overlay = document.getElementById('overlay');

toggleMenuLateral.addEventListener("click", () => {
    console.log("clicou");
    menuLateral.classList.toggle("ativo");
    overlay.classList.toggle("ativo");
});

overlay.addEventListener("click", () => {
    menuLateral.classList.remove("ativo");
    overlay.classList.remove("ativo");
});
