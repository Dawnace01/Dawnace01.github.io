<?php

include_once "include/pdoSettings.php";

function printNbAbsents($pdo){
    $sql = $pdo->prepare("SELECT count(_id) FROM registeredStudents");
    $sql->execute();
    $result = $sql->fetch();
    if (!$result)
        return "NaN";
    return $result[0];
}

function printTotalStudents($pdo){
    $sql = $pdo->prepare("SELECT * FROM classe");
    $sql->execute();
    $result = $sql->fetchAlL();
    if (!$result)
        return "NaN";
    $ret = 0;
    foreach ($result as $a){
        $ret += $a['nbStudents'];
    }

    return $ret;
}

function printNbClasses($pdo){
    $sql = $pdo->prepare("SELECT count(_id) FROM classe");
    $sql->execute();
    $result = $sql->fetch();
    if (!$result)
        return "NaN";

    return $result[0];
}

function printAllStudentsInTable($pdo){
    $sql = $pdo->prepare("SELECT DISTINCT * FROM registeredStudents");
    $sql->execute();
    $result = $sql->fetchAll();
    if (!$result)
        return "";

    $ret = "";

    foreach ($result as $a){
        $ret .= "<tr>
    <td>" . $a['name'] . "</td>
    <td>" . $a['firstname'] . "</td>
    <td>" . $a['age'] . "</td>
    <td>" . $a['isVaccinate'] . "</td>
    <td>" . $a['dateOfContamination'] . "</td>
    <td>" . $a['dateOfReturn'] . "</td>
</tr>";
    }

    return $ret;
}

function getIdOfClasse($pdo,$name){

    $nameQuoted = $pdo->quote($name);
    $sql = $pdo->prepare("SELECT _id FROM classe WHERE name like " . $nameQuoted);
    $sql->execute();
    $result = $sql->fetch();

    if (!$result)
        return -1;

    return $result[0];
}

function printDropDownClasses($pdo){
    $sql = $pdo->prepare("SELECT DISTINCT * FROM classe");
    $sql->execute();
    $result = $sql->fetchAll();
    if (!$result)
        return "";

    $ret = "";

    foreach ($result as $a){
        $ret .= "<option value='" . getIdOfClasse($pdo, $a['name']) . "'>"
            . $a['name']
            . "</option>";
    }

    return $ret;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Décompte Absents</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
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
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Fermer</a>
        <a href="#vue-globale" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-eye fa-fw"></i>  Vue globale</a>
        <a href="#ajout-eleve" class="w3-bar-item w3-button w3-padding"><i class="fa fa-user fa-fw"></i>  Ajout d'un élève</a>
        <a href="#historique" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  Historique</a>
        <a href="#parametres" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  Paramètres</a>
    </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Header -->
    <div id="vue-globale" class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-eye fa-fw"></i>  Vue globale</b></h5>

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
            <h5>Statistiques générales</h5>
            <p>Total d'élèves présents</p>
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

        <div class="w3-container">
            <h5>Liste des élèves absents</h5>
            <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                <thead>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Classe</th>
                        <th>Schéma Vaccinal Complet</th>
                        <th>Départ</th>
                        <th>Retour</th>
                </thead>
                <tbody id="tableOfStudents">
                <?= printAllStudentsInTable($pdo) ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <!-- Header -->
    <div id="ajout-eleve" class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-user fa-fw"></i>  Ajout d'un élève</b></h5>

        <form action="module/updatePdo.php?action=add" method="post">
            <div class="w3-margin">
                <label class="w3-label" for="name">Nom</label>
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
                    <?= printDropDownClasses($pdo) ?>
                </select>
            </div>

            <div id="form-vaccinate" class="w3-margin">
                <p><b>Schéma Vaccinal complet ?</b>  <i>(seulement s'il a plus de 12 ans)</i></p>
                <label id="form-vaccinate" for="vaccin" class="switch">
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

    <hr>

    <!-- Header -->
    <div id="historique" class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-history fa-fw"></i>  Historique</b></h5>
    </div>

    <hr>

    <!-- Header -->
    <div id="parametres" class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-cog fa-fw"></i>  Paramètres</b></h5>
    </div>

    <hr>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
        <h4>Informations pratiques</h4>
        <p>Site web créé par ©<a href="https://www.github.com/Dawnace01/" target="_blank">Lucas Cécillon</a> et hébergé par <code>000webhost</code>.<br>Une idée de Quentin Boucard.</p>
    </footer>

    <!-- End page content -->
</div>

<script>
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

    document.getElementById("error-message").textContent = "Bienvenue sur ce site.";
    document.getElementById("error-message").setAttribute("class","w3-bar-item w3-right w3-black");
    document.getElementById("current_date-time").textContent = new Date(Date.now()).toLocaleDateString("fr");

    let totalStudents = document.getElementById("total-students").textContent;
    let registeredStudents = document.getElementById("nb_absents").textContent;
    let totalStudentsRegistered = Math.ceil((registeredStudents / totalStudents) * 100);
    document.getElementById("totalStudentsRegistred").setAttribute("style","width:" + (100 - totalStudentsRegistered) + "%");
    document.getElementById("totalStudentsRegistred").textContent = (100 - totalStudentsRegistered) + "%";

</script>
<!--<script src="script.js"></script>-->
</body>
</html>
