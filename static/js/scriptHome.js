window.onscroll = function (event) {
    if (window.pageYOffset > 20) {
        addClass(document.getElementById("fixed-top"), "nav-bar-active");
    } else {
        removeClass(document.getElementById("fixed-top"), "nav-bar-active");
    }
    if  (window.pageYOffset > screen.height) {
        document.getElementById("back-to-top").style.display = "inline";
    } else {
        document.getElementById("back-to-top").style.display = "none";
    }
}