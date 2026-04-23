document.querySelector('main').style.marginTop = document.querySelector("header").offsetHeight + "px";

window.addEventListener("scroll", function () {
    if (this.window.scrollY >= 100) {
        this.document.querySelector("header").style.padding = "0";
        this.document.querySelector('header a').style.padding = "20px 0";
    }
    else {
        this.document.querySelector("header").style.padding = "14px 0";
        this.document.querySelector("header a").style.padding = "34px 0";
    }
});