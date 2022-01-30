function selViewStudent(n, litag) {
    let add = "none";
    let modify = "none";
    let del = "none";
    switch(n) {
        case 1:
            add = "inline";
            break;
        case 2:
            modify = "inline";
            break;
        case 3:
            del = "inline";
            break;
        // add how many cases you need
        default:
            break;
    }

    document.getElementById("add-student").style.display = add;
    document.getElementById("modify-student").style.display = modify;
    document.getElementById("del-student").style.display = del;
    let tabs = document.getElementById("tabsStudents");
    let ca = Array.prototype.slice.call(tabs.querySelectorAll("li"));
    ca.map(function(elem) {
        elem.style.background="#F0F0F0";
        elem.style.borderBottom="1px solid gray"
    });

    litag.style.borderBottom = "1px solid white";
    litag.style.background = "white";
}

function selViewGroup(n, litag) {
    let add = "none";
    let modify = "none";
    let del = "none";
    switch(n) {
        case 1:
            add = "inline";
            break;
        case 2:
            modify = "inline";
            break;
        case 3:
            del = "inline";
            break;
        // add how many cases you need
        default:
            break;
    }

    document.getElementById("add-group").style.display = add;
    document.getElementById("modify-group").style.display = modify;
    document.getElementById("del-group").style.display = del;
    let tabs = document.getElementById("tabsGroups");
    let ca = Array.prototype.slice.call(tabs.querySelectorAll("li"));
    ca.map(function(elem) {
        elem.style.background="#F0F0F0";
        elem.style.borderBottom="1px solid gray"
    });

    litag.style.borderBottom = "1px solid white";
    litag.style.background = "white";
}


function selInit() {
    let tabs = document.getElementById("tabs");
    let litag = tabs.querySelector("li");   // first li
    litag.style.borderBottom = "1px solid white";
    litag.style.background = "white";
}

/* ------------------------------------------ */

const sections = document.querySelectorAll('section');
const links = document.querySelectorAll(".link");
const nav = document.querySelector("nav");
const navPos = nav.getBoundingClientRect();
const scrollMargin = -200;

function updateHash(hash){
    const currentHash = window.location.hash;
    currentHash != hash && window.history.replaceState(null,null,hash);
}

window.onscroll = () => {
    sections.forEach((section,index)=>{
        const sectionPos = section.getBoundingClientRect();

        if(sectionPos.top <= navPos.bottom + scrollMargin && sectionPos.bottom >= 0){
            links.forEach((link) => link.classList.remove('w3-blue'));
            links[index].classList.add('w3-blue');
            const hash = links[index].getAttribute('href');
            updateHash(hash);
        }
    })
}