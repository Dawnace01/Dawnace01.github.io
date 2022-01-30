<?php

include_once "include/pdoSettings.php";
include_once "include/utils.php";

delStudentBack($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Décompte Absents</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Comfortaa">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
    <span class="w3-bar-item w3-right w3-red" id="error-message">!! Changer de navigateur !! (Chrome, Brave, Mozilla, Safari, Opera)</span>

</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>

    <div class="w3-container">
        <h5>Menu</h5>
    </div>
    <div class="w3-bar-block">
        <a href="" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Fermer</a>
        <a href="#vue-globale" class="w3-bar-item w3-button w3-padding w3-blue link"><i class="fa fa-eye fa-fw"></i>  Vue globale</a>
        <a href="#eleve" class="w3-bar-item w3-button w3-padding link"><i class="fa fa-user fa-fw"></i>  Gestion d'élèves absents <i class="number">(<?= printNbAbsents($pdo) ?>)</i></a>
        <a href="#groupe" class="w3-bar-item w3-button w3-padding link"><i class="fa fa-users fa-fw"></i>  Gestion de classes <i class="number">(<?= printNbClasses($pdo) ?>)</i></a>
        <!--<a href="#parametres" class="w3-bar-item w3-button w3-padding link"><i class="fa fa-cog fa-fw"></i>  Paramètres</a>-->
    </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <section id="vue-globale" class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-eye fa-fw"></i>  Vue globale</b></h3>

        <div class="w3-row-padding w3-margin-bottom">
            <div class="w3-quarter">
                <div class="w3-container w3-red w3-padding-16">
                    <div class="w3-left"><i class="fa fa-bookmark w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3 id="current_date-time"></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Aujourd'hui</h4>
                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-blue w3-padding-16">
                    <div class="w3-left"><i class="fa fa-laptop w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3 id="nb_absents"><?= printNbAbsents($pdo) ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Absents</h4>
                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-teal w3-padding-16">
                    <div class="w3-left"><i class="fa fa-user w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3 id="total-students"><?= printTotalStudents($pdo) ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Total d'élèves</h4>
                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-orange w3-text-white w3-padding-16">
                    <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3 id="nb_classes"><?= printNbClasses($pdo) ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Classes</h4>
                </div>
            </div>
        </div>

        <div class="w3-container">
            <h5>Total d'élèves présents</h5>
            <div class="w3-grey">
                <div class="w3-container w3-center w3-padding w3-green" id="totalStudentsRegistred" style="width:0%">0%</div>
            </div>
            <!--
            <p>New Users</p>
            <div class="w3-grey">
                <div class="w3-container w3-center w3-padding w3-orange" style="width:50%">50%</div>
            </div>

            <p>Bounce Rate</p>
            <div class="w3-grey">
                <div class="w3-container w3-center w3-padding w3-red" style="width:75%">75%</div>
            </div>
            -->
        </div>

        <div class="w3-container" id="table-of-students" >
            <h5>Liste des élèves absents : <b><?php
                    $var = isset($_GET['search']) ? $_GET['search'] : 'toutes classes';
                    if($var === "")
                        echo "toutes classes" ;
                    else
                        echo $var;

                        ?></b> / <b id="nbStudentSelected"></b> absent(s)</h5>
            <span style="visibility: hidden" id="url-get-search-name"><?= isset($_GET['search']) ? $_GET['search'] : '' ?></span>
            <div class="w3-white design-padding-5" id="search-container">
                <form action="index.php" method="get" class="underline-bottom design-padding-5" id="search-bar-table-student">
                    <label for="search" class="w3-left"><i class="fa fa-search w3-xxxsmall design-padding-10"></i> </label>
                    <input value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" name="search" id="search" class="design-input design-padding-5" placeholder='Entrez un nom de classe'>
                    <button type="submit" class="w3-button w3-black"><b>Rechercher</b></button>
                </form>
            </div>


            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-centered">
                <thead>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Classe</th>
                        <th>Schéma Vaccinal Complet</th>
                        <th>Départ</th>
                        <th>Retour</th>
                </thead>
                <tbody id="tableOfStudents">
                <?php
                $var = isset($_GET['search']) ? $_GET['search'] : 'toutes classes';
                if ($var === "toutes classes" || $var === ""){
                    echo printAllStudentsInTable($pdo);
                }
                else{
                    echo printAllStudentsInTableSorted($pdo,$var);
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>

    <hr>

    <!-- Header -->
    <section id="eleve" class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-user fa-fw"></i>  Gestion d'élèves absents <i class="number">(<?= printNbAbsents($pdo) ?>)</i></b></h3>
        <div id="tabsStudents">
            <ul>
                <li onClick="selViewStudent(1, this)" style="background: white; border-bottom: 1px solid white;"><b>Ajouter un élève absent</b></li>
                <li onClick="selViewStudent(2, this)" id="modify-student-li"><b>Modifier un élève absent</b></li>
                <li onClick="selViewStudent(3, this)"><b>Supprimer un élève absent</b></li>
            </ul>
        </div>

        <div id="tabcontent">
            <div id="add-student" class="tabpanel" style="display:inline">
                <div>
                    <form action="module/updatePdo.php?action=add&to=student" method="post">
                        <div class="w3-margin">
                            <label for="name">Nom</label>
                            <input class="w3-input w3-padding-16" id="name" name="name" type="text" placeholder="Nom de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="firstname">Prénom</label>
                            <input class="w3-input w3-padding-16" id="firstname" name="firstname" type="text" placeholder="Prénom de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="age">Age</label>
                            <input class="w3-input w3-padding-16" id="age" type="number" name="age" placeholder="Age de l'élève" max="110" min="5" required>
                        </div>

                        <div class="w3-margin">
                            <label for="dategone">Date de départ</label>
                            <input class="w3-input w3-padding-16" id="dategone" name="dategone" type="date" placeholder="Date de contamination de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="classes">Classe de l'élève</label>
                            <select class="w3-select w3-padding-16" name="classes" id="classes">
                                <?= printDropDownClasses($pdo,"") ?>
                            </select>
                        </div>

                        <div class="form-vaccinate w3-margin">
                            <p><b>Schéma Vaccinal complet ?</b>  <i>(seulement s'il a plus de 12 ans)</i></p>
                            <label for="vaccin" class="form-vaccinate switch">
                                <input id="vaccin" name="vaccin" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div>
                            <button class="strong w3-input w3-button w3-green" type="submit"><i class="fa fa-paper-plane"></i> <b>Ajouter</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="modify-student" class="tabpanel" style="display:none">
                <div>
                    <form action="module/updatePdo.php?action=modify&to=student" method="post">

                        <div class="w3-margin">
                            <label for="students">Choix de l'élève</label>
                            <select class="w3-select w3-padding-16" name="students" id="students">
                                <?= printDropDownStudents(
                                        $pdo,
                                        isset($_GET['name']) ? $_GET['name'] : 'none',
                                    isset($_GET['firstname']) ? $_GET['firstname'] : 'none',
                                    isset($_GET['classe']) ? $_GET['classe'] : 'none'
                                ) ?>
                            </select>
                        </div>

                        <div class="w3-margin">
                            <label class="w3-label" for="name">Nom</label>
                            <input <?= autoCompletion(isset($_GET['name']) ? $_GET['name'] : '') ?> class="w3-input w3-padding-16" id="name" name="name" type="text" placeholder="Nom de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="firstname">Prénom</label>
                            <input <?= autoCompletion(isset($_GET['firstname']) ? $_GET['firstname'] : '') ?> class="w3-input w3-padding-16" id="firstname" name="firstname" type="text" placeholder="Prénom de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="age">Age</label>
                            <input <?=
                            printAgeFromStudent(
                                    $pdo,
                                isset($_GET['name']) ? $_GET['name'] : '',
                                isset($_GET['firstname']) ? $_GET['firstname'] : '',
                                isset($_GET['classe']) ? $_GET['classe'] : ''
                            );
                            ?> class="w3-input w3-padding-16" id="age" type="number" name="age" placeholder="Age de l'élève" max="110" min="5" required>
                        </div>

                        <div class="w3-margin">
                            <label for="dategone">Date de départ</label>
                            <input <?=
                                printDateLeftFromStudent(
                                       $pdo,
                                       isset($_GET['name']) ? $_GET['name'] : '',
                                       isset($_GET['firstname']) ? $_GET['firstname'] : '',
                                       isset($_GET['classe']) ? $_GET['classe'] : ''
                                   );
                                   ?> class="w3-input w3-padding-16" id="dategone" name="dategone" type="date" placeholder="Date de contamination de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="datereturn">Date de retour</label>
                            <input <?=
                            printDateOfReturnFromStudent(
                                $pdo,
                                isset($_GET['name']) ? $_GET['name'] : '',
                                isset($_GET['firstname']) ? $_GET['firstname'] : '',
                                isset($_GET['classe']) ? $_GET['classe'] : ''
                            );
                            ?> class="w3-input w3-padding-16" id="datereturn" name="datereturn" type="date" placeholder="Date de retour de l'élève" required>
                        </div>

                        <div class="w3-margin">
                            <label for="classes">Classe de l'élève</label>
                            <select <?= isSelectedDropDownClasse($pdo,isset($_GET['classe']) ? $_GET['classe'] : '') ?>class="w3-select w3-padding-16" name="classes" id="classes">
                                <?= printDropDownClasses($pdo,isset($_GET['classe']) ? $_GET['classe'] : '') ?>
                            </select>
                        </div>

                        <div class="w3-margin form-vaccinate">
                            <p><b>Schéma Vaccinal complet ?</b>  <i>(seulement s'il a plus de 12 ans)</i></p>
                            <label for="vaccin2" class="form-vaccinate switch">
                                <input id="vaccin2" name="vaccin" type="checkbox" <?= isSelectedVaccinatedCheckbox($pdo,
                                    isset($_GET['name']) ? $_GET['name'] : '',
                                    isset($_GET['firstname']) ? $_GET['firstname'] : '',
                                    isset($_GET['classe']) ? $_GET['classe'] : '') ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div>
                            <button class="strong w3-input w3-button w3-orange" type="submit"><i class="fa fa-paper-plane"></i> <b>Modifier</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="del-student" class="tabpanel" style="display:none">
                <div>
                    <form action="module/updatePdo.php?action=del&to=student" method="post">

                        <div class="w3-margin">
                            <label for="classes">Choix de l'élève</label>
                            <select class="w3-select w3-padding-16" name="students" id="classes">
                                <?= printDropDownStudents($pdo,"","","") ?>
                            </select>
                        </div>
                        <div>
                            <button class="strong w3-input w3-button w3-red" type="submit"><i class="fa fa-paper-plane"></i> <b>Supprimer</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

    <hr>

    <section id="groupe" class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-users fa-fw"></i>  Gestion de classes <i class="number">(<?= printNbClasses($pdo) ?>)</i></b></h3>

        <div id="tabsGroups">
            <ul>
                <li onClick="selViewGroup(1, this)" style="background: white; border-bottom: 1px solid white;"><b>Ajouter une classe</b></li>
                <li onClick="selViewGroup(2, this)" id="modify-group-li"><b>Modifier une classe</b></li>
                <li onClick="selViewGroup(3, this)"><b>Supprimer une classe</b></li>
            </ul>
        </div>

        <div id="tabcontent">
            <div id="add-group" class="tabpanel" style="display:inline">
                <div>
                    <form action="module/updatePdo.php?action=add&to=group" method="post">

                        <div class="w3-margin">
                            <label for="groupName">Nom</label>
                            <input class="w3-input w3-padding-16" id="groupName" name="groupName" type="text" placeholder="Nom de la classe" required>
                        </div>

                        <div class="w3-margin">
                            <label for="groupNbStudents">Nombre d'élèves</label>
                            <input class="w3-input w3-padding-16" id="groupNbStudents" name="groupNbStudents" value="30" type="number" max="100" min="0" required>
                        </div>

                        <div>
                            <button class="strong w3-input w3-button w3-green" type="submit"><i class="fa fa-paper-plane"></i> <b>Ajouter</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="modify-group" class="tabpanel" style="display:none">
                <div>
                    <form action="module/updatePdo.php?action=modify&to=group" method="post">

                        <div class="w3-margin">
                            <label for="groups">Choix de l'élève</label>
                            <select class="w3-select w3-padding-16" name="groups" id="groups">
                                <?= printDropDownClasses($pdo,isset($_GET['nameGroup']) ? $_GET['nameGroup'] : ''); ?>
                            </select>
                        </div>

                        <div class="w3-margin">
                            <label for="groupName">Nom</label>
                            <input <?= autoCompletion(isset($_GET['nameGroup']) ? $_GET['nameGroup'] : '') ?> class="w3-input w3-padding-16" id="groupName" name="groupName" type="text" placeholder="Nom de la classe" required>
                        </div>
                        <div class="w3-margin">
                            <label for="groupNbStudents">Nombre d'élèves</label>
                            <input <?= autoCompletion(isset($_GET['nbStudentGroup']) ? $_GET['nbStudentGroup'] : '') ?> class="w3-input w3-padding-16" id="groupNbStudents" name="groupNbStudents" value="30" type="number" max="100" min="0" required>
                        </div>

                        <div>
                            <button class="strong w3-input w3-button w3-orange" type="submit"><i class="fa fa-paper-plane"></i> <b>Modifier</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="del-group" class="tabpanel" style="display:none">
                <div>
                    <form action="module/updatePdo.php?action=del&to=group" method="post">

                        <div class="w3-margin">
                            <label for="classes">Choix de la classe à supprimer</label>
                            <select class="w3-select w3-padding-16" name="classe" id="classes">
                                <?= printDropDownClasses($pdo,'') ?>
                            </select>
                        </div>

                        <div>
                            <button onclick="if(!confirm('Vous êtes sur le point de supprimer une classe.\nCeci entrainera la suppression de chaque étudiant la composant.')) document.getElementById('submit-del-group').setAttribute('formaction','')" class="strong w3-input w3-button w3-red" type="submit" id="submit-del-group"><i class="fa fa-paper-plane"></i> <b>Supprimer</b></button>
                            <button class="w3-input w3-button w3-grey" type="reset"><i class="fa fa-trash"></i> <b>Effacer</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

    <hr>

    <!--
    <section id="parametres" class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-cog fa-fw"></i>  Paramètres</b></h3>

    </section>

    <hr>
    -->
    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
        <h3><b><i class="fa fa-info fa-fw"></i>  Informations pratiques</b></h3>
        <p>Site web développé par ©<a href="https://www.github.com/Dawnace01/" target="_blank">Lucas Cécillon</a> et hébergé par <code>000webhost</code>.<br>Une idée de Quentin Boucard.</p>
    </footer>

    <!-- End page content -->
</div>

<script src="script.js"></script>
<script>
    document.getElementById("error-message").textContent = "Bienvenue sur ce site.";
    document.getElementById("error-message").setAttribute("class","w3-bar-item w3-right w3-black");
    document.getElementById("current_date-time").textContent = new Date(Date.now()).toLocaleDateString("fr");


    // Get the Sidebar
    let mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    let overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }

    let totalStudents = document.getElementById("total-students").textContent;
    let registeredStudents = document.getElementById("nb_absents").textContent;
    let totalStudentsRegistered = Math.ceil((registeredStudents / totalStudents) * 100);
    document.getElementById("totalStudentsRegistred").setAttribute("style","width:" + (100 - totalStudentsRegistered) + "%");
    document.getElementById("totalStudentsRegistred").textContent = (100 - totalStudentsRegistered) + "%";

    let array = document.getElementsByClassName("date-format");
    for (let i=0; i<array.length; i++){
        array[i].textContent = new Date(array[i].textContent).toLocaleDateString("fr");
    }

    let nbStudentSelected = document.getElementsByClassName('raw-student');
    document.getElementById('nbStudentSelected').textContent = nbStudentSelected.length;

    let modifyStudentDropDown = document.getElementById('students');
    let search = document.getElementById('url-get-search-name').textContent;
    modifyStudentDropDown.addEventListener("change", refresh);

    let modifyGroupDropDown = document.getElementById('groups');
    modifyGroupDropDown.addEventListener("change", refreshGroup);

    function refresh(){
        let modifyStudentDropDown = document.getElementById('students');
        let text = modifyStudentDropDown.options[modifyStudentDropDown.selectedIndex].text;
        let textWithoutComa = text.replace(",","");
        let splitText = textWithoutComa.split(" ");

        window.location.replace("/index.php?search=" + search + "&modify=student&name=" + splitText[0] + "&firstname=" + splitText[1] + "&classe=" + splitText[2] + "+" + splitText[3] + "#eleve");
    }

    function refreshGroup(){
        let modifyGroupDropDown = document.getElementById('groups');
        let text = modifyGroupDropDown.options[modifyGroupDropDown.selectedIndex].text;
        let textWithoutComa = text.replace(",","");
        let splitText = textWithoutComa.split(" ");

        window.location.replace("/index.php?search=" + search + "&modify=groups&nameGroup=" + splitText[0] + "+" + splitText[1] + "&nbStudentGroup=" + splitText[2] + "#groupe");
    }

    <?php
        if (isset($_GET['modify']) && $_GET['modify']=='student') {
            echo 'document.getElementById("modify-student-li").click();';
        }

        else if (isset($_GET['modify']) && $_GET['modify']=='groups') {
            echo 'document.getElementById("modify-group-li").click();';
    }
    ?>
</script>
</body>
</html>
