function prevent(e) {
    e.preventDefault();
}

document.querySelectorAll(".portfolio-img-container").forEach(function (e) {
    e.addEventListener("click", function () {
        e.firstElementChild.classList.remove("hide");
        document.querySelector(".bg-gray").classList.remove("hide");
        window.addEventListener("wheel", prevent, {passive: false});
        window.addEventListener("touchmove", prevent, { passive: false });

        e.querySelector(".open-portfolio-img-close-btn")
            .addEventListener("click", function (event) {
                event.stopPropagation();
                e.firstElementChild.classList.add("hide");
                document.querySelector(".bg-gray").classList.add("hide");
                window.removeEventListener("wheel", prevent);
                window.removeEventListener("touchmove", prevent);
            });
    });
});

document.querySelector(".open-portfolio-img-carrousel").scrollLeft = document.querySelector(".open-portfolio-img.current").scrollLeft;