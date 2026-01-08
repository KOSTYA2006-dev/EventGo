
var menu = document.getElementById('mobileMenu');
var logo = document.getElementById('header-logo');
var logo2 = document.getElementById('header-logo2');
// ______________

function closeToggleMenu() {
    menu.style.right = "-1000px";
}

function toggleMenu() {
    menu.style.display = "block";
    menu.style.right = "0";
}

