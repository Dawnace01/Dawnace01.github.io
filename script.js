
//JSON parsage
let header = document.querySelector('header');
let section = document.querySelector('section');

let nbAbsentStudent = 0;

let requestURL  = 'https://dawnace01.github.io/JSON/database.json';
//let requestURL = 'https://mdn.github.io/learning-area/javascript/oojs/json/superheroes.json';
let request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
request.send();
request.onload = function() {
    let students = request.response;
    let registeredStudents = students['registeredStudents'].length;
    let classes = students['classes'].length;
    let totalStudents = 0;

    document.getElementById("nb_absents").textContent = registeredStudents;
    document.getElementById("nb_classes").textContent = classes;
    for (let i = 0; i < students["classes"].length; i++){
        totalStudents += students["classes"][i].nbStudent;
    }
    document.getElementById("total-students").textContent = totalStudents;
    let totalStudentsRegistred = Math.ceil((registeredStudents / totalStudents) * 100);
    document.getElementById("totalStudentsRegistred").setAttribute("style","width:" + totalStudentsRegistred + "%");
    document.getElementById("totalStudentsRegistred").textContent = totalStudentsRegistred + "%";

    for (let i = 0; i < students['registeredStudents'].length; i++) {
        let row = document.createElement('tr');
        let heading_1 = document.createElement('td');
        heading_1.innerHTML = students['registeredStudents'][i].name;
        let heading_2 = document.createElement('td');
        heading_2.innerHTML = students['registeredStudents'][i].firstname ;
        let heading_3 = document.createElement('td');
        heading_3.innerHTML = students['registeredStudents'][i].classe;
        let heading_4 = document.createElement('td');
        heading_4.innerHTML = new Date(students['registeredStudents'][i].dateOfContamination).toLocaleDateString("fr");
        let heading_5 = document.createElement('td');
        heading_5   .innerHTML = new Date(students['registeredStudents'][i].dateOfReturn).toLocaleDateString("fr");

        row.appendChild(heading_1);
        row.appendChild(heading_2);
        row.appendChild(heading_3);
        row.appendChild(heading_4);
        row.appendChild(heading_5);
        document.getElementById("tableOfStudents").appendChild(row);
    }

    //populateHeader(students);
    //showStudents(students);
}

function populateHeader(jsonObj) {
    let myH1 = document.createElement('h1');
    myH1.textContent = jsonObj['college'];
    header.appendChild(myH1);
}

function showStudents(jsonObj) {
    let students = jsonObj['registeredStudents'];
    nbAbsentStudent = students.length;

    for (let i = 0; i < nbAbsentStudent; i++) {
        let myArticle = document.createElement('article');
        let myH2 = document.createElement('h2');
        let myPara1 = document.createElement('p');
        let myPara2 = document.createElement('p');
        let myPara3 = document.createElement('p');
        let myPara4 = document.createElement('p');
        let myPara5 = document.createElement('p');

        myH2.textContent = students[i].firstname + " " + students[i].name;
        myPara1.textContent = 'Age: ' + students[i].age;
        myPara2.textContent = 'Classe: ' + students[i].classe;
        myPara3.textContent = 'Vacciné.e:' + students[i].isVaccinate;

        tempDate = new Date(students[i].dateOfContamination);
        myPara4.textContent = 'Date de contamination: ' + tempDate.toLocaleDateString("fr");
        tempDate = new Date(students[i].dateOfReturn);
        myPara5.textContent = 'Date de retour: ' + tempDate.toLocaleDateString("fr");

        myArticle.appendChild(myH2);
        myArticle.appendChild(myPara1);
        myArticle.appendChild(myPara2);
        myArticle.appendChild(myPara3);
        myArticle.appendChild(myPara4);
        myArticle.appendChild(myPara5);

        section.appendChild(myArticle);
    }
}

document.getElementById("current_date-time").textContent = new Date(Date.now()).toLocaleDateString("fr");
