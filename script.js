
// script.js

document.addEventListener("DOMContentLoaded", () => {

    const menuBtn = document.querySelector(".mobile-menu");
    const menu = document.querySelector(".menu");

    if (menuBtn && menu) {
        menuBtn.addEventListener("click", () => {
            menu.classList.toggle("show");
        });
    }

});
