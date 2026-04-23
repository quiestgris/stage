let logoutBtnClicked;
let imageToDelete = false;

function preventDefault(e) {
    e.preventDefault();
}


document.querySelector(".logout-btn").addEventListener("click", function () {
    document.querySelector(".bg-black").classList.remove("hide");
    document.querySelector(".logout-question-container").classList.remove("hide");

    document.querySelector("body").addEventListener("touchmove", preventDefault, { passive: false });
    document.querySelector("body").addEventListener("wheel", preventDefault, { passive: false });
    document.querySelector("body").addEventListener("scroll", preventDefault, { passive: false });
});

document.querySelector(".logout-refuse-btn").addEventListener("click", function () {
    document.querySelector(".bg-black").classList.add("hide");
    document.querySelector(".logout-question-container").classList.add("hide");

    document.querySelector("body").removeEventListener("touchmove", preventDefault);
    document.querySelector("body").removeEventListener("wheel", preventDefault);
    document.querySelector("body").removeEventListener("scroll", preventDefault);
})


document.querySelectorAll(".delete-img-btn").forEach(function (e) {
    e.addEventListener("click", function () {
        document.querySelector(".delete-img-bg-black").classList.remove("hide");
        document.querySelector(".delete-img-question-container").classList.remove("hide");

        document.querySelector("body").addEventListener("touchmove", preventDefault, { passive: false });
        document.querySelector("body").addEventListener("wheel", preventDefault, { passive: false });
        document.querySelector("body").addEventListener("scroll", preventDefault, { passive: false });

        document.querySelector(".delete-img-acception-btn").setAttribute("href", "/delete-portfolio-img/" + imageToDelete);
    })
})


document.querySelector(".delete-img-refuse-btn").addEventListener("click", function () {
    document.querySelector(".delete-img-bg-black").classList.add("hide");
    document.querySelector(".delete-img-question-container").classList.add("hide");

    document.querySelector("body").removeEventListener("touchmove", preventDefault);
    document.querySelector("body").removeEventListener("wheel", preventDefault);
    document.querySelector("body").removeEventListener("scroll", preventDefault);

    document.querySelector(".delete-img-acception-btn").setAttribute("href", "");
    imageToDelete = false;
})

document.getElementById("image_imageFile_file").addEventListener("change", function () {
    document.querySelector(".upload-img-btn").classList.remove("hide");
});

let dropItem;

document.querySelectorAll(".portfolio-img-container img").forEach(function (e) {
    e.addEventListener("dragstart", function (el) {
        dropItem = el.target;
        el.target.style.opacity = "0.3";
    })

    e.addEventListener("dragend", function (e) {
        e.target.style.opacity = "1";
    })

    e.addEventListener("dragover", function (e) {
        dropZone = e;
        e.preventDefault();
        e.target.style.opacity = "0.8";
    })

    e.addEventListener("dragleave", function (e) {
        e.target.style.opacity = "1";
    })

    e.addEventListener("drop", function (e) {
        e.preventDefault();
        e.target.style.opacity = "1";

        let dropZoneOnClick = e.target.previousElementSibling.onclick;
        let dropItemOnClick = dropItem.previousElementSibling.onclick;

        
        e.target.previousElementSibling.onclick = dropItemOnClick;
        dropItem.previousElementSibling.onclick = dropZoneOnClick;

        let dropZone = e.target.parentElement.outerHTML;
        let newDropItem = dropItem;

        e.target.parentElement = newDropItem.parentElement;
        dropItem.parentElement = dropZone;
    })
});