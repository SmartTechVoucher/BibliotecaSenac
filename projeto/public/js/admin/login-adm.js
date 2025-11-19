document.addEventListener("DOMContentLoaded", function () {
    const cardLogin = document.getElementById("card-login");
    const cardRecuperar = document.getElementById("card-recuperar");
    const linkRecuperar = document.getElementById("link-recuperar");
    const linkVoltar = document.getElementById("link-voltar");

    linkRecuperar.addEventListener("click", function (e) {
        e.preventDefault();
        cardLogin.classList.add("hidden");
        cardRecuperar.classList.remove("hidden");
    });

    linkVoltar.addEventListener("click", function (e) {
        e.preventDefault();
        cardRecuperar.classList.add("hidden");
        cardLogin.classList.remove("hidden");
    });
});
