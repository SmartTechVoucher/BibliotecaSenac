document.addEventListener("DOMContentLoaded", function () {
    const cardLogin = document.getElementById("card-login");
    const cardRecuperar = document.getElementById("card-recuperar");
    const linkRecuperar = document.getElementById("link-recuperar");
    const linkVoltar = document.getElementById("link-voltar");
    const btnEnviar = document.getElementById("btn-enviar");
    const formRecuperar = document.getElementById("card-recuperar");

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

    if (formRecuperar && btnEnviar) {
        formRecuperar.addEventListener("submit", function (e) {
            e.preventDefault();

            const emailInput = document.getElementById("campo_email");
            if (!emailInput || !emailInput.value.trim()) {
                alert("Por favor, digite seu email.");
                return;
            }

            // Desabilita o botão para evitar múltiplos envios
            btnEnviar.disabled = true;
            btnEnviar.textContent = "Enviando...";

            // Simula envio (o form será submetido normalmente)
            setTimeout(() => {
                formRecuperar.submit();
            }, 500);
        });
    }
});
