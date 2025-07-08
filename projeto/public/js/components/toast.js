document.addEventListener("DOMContentLoaded", () => {
    const toast = document.querySelector(".toast");
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translate(-50%, -100%)";
        }, 3000); // desaparece após 3 segundos

        setTimeout(() => {
            toast.remove();
        }, 4000); // remove do DOM após 4 segundos
    }
});