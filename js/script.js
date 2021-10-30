/**
 * navbar dynamique
 */
window.onscroll = function () {
    navbarFunction()
};

let navbar = document.getElementById("id-header-top");
let sticky = navbar.offsetTop;

function navbarFunction() {
    if (window.pageYOffset > sticky + 150) {
        navbar.classList.add("sticky")
    } else {
        navbar.classList.remove("sticky");
    }
}

/**
 * active class
 */

let home = document.getElementById("home");
home.className = "";
let pres = document.getElementById("pres");
pres.className = "";
let contact = document.getElementById("contact");
contact.className = "";
let blog = document.getElementById("blog");
blog.className = "";
let inscr = document.getElementById("inscr");
inscr.className = "";
let chiffres = document.getElementById("chiffres");
chiffres.className = "";

if (document.title == "Accueil") {
    home.className = "active";
} else if (document.title == "Présentation") {
    pres.className = "active";
} else if (document.title == "Contact") {
    contact.className = "active";
} else if (document.title == "Inscription") {
    inscr.className = "active";
} else if (document.title == "Les chiffres") {
    chiffres.className = "active";
} else {
    blog.className = "active";
}