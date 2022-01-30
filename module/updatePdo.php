<?php
include_once __DIR__ . '/../include/pdoSettings.php';

$isAdd = isset($_GET['action']) && $_GET['action'] == 'add';
$isDel = isset($_GET['action']) && $_GET['action'] == 'del';
$isModify = isset($_GET['action']) && $_GET['action'] == 'modify';
$direction = isset($_GET['to']) ? $_GET['to'] : 'none';

if ($direction === "student") {
    if ($isAdd) {

        $name = str_replace(" ","-",$pdo->quote(isset($_POST['name']) ? $_POST['name'] : 'non renseigné'));
        $firstname = str_replace(" ","-",$pdo->quote(isset($_POST['firstname']) ? $_POST['firstname'] : 'non renseigné'));
        $age = $pdo->quote(isset($_POST['age']) ? $_POST['age'] : -1);
        $dategone = $pdo->quote(isset($_POST['dategone']) ? $_POST['dategone'] : '1970-01-01');
        $classe = $pdo->quote(isset($_POST['classes']) ? $_POST['classes'] : 'non renseignée');
        $isVaccinate = isset($_POST['vaccin']) ? $_POST['vaccin'] : "Non";


        if ($isVaccinate != "Non")
            $isVaccinate = $pdo->quote("Oui");

        else
            $isVaccinate = $pdo->quote("Non");

        if ($age != -1) {
            if ($_POST['age'] < 12) {
                if ($dategone != '1970-01-01')
                    $datereturn = $pdo->quote(date('Y-m-d', strtotime($_POST['dategone'] . ' + 7 days')));
            } else if ((isset($_POST['vaccin']) ? $_POST['vaccin'] : "Non") != "Non") {
                if ($dategone != '1970-01-01')
                    $datereturn = $pdo->quote(date('Y-m-d', strtotime($_POST['dategone'] . ' + 7 days')));
            } else {
                if ($dategone != '1970-01-01')
                    $datereturn = $pdo->quote(date('Y-m-d', strtotime($_POST['dategone'] . ' + 10 days')));
            }
        } else
            $datereturn = $pdo->quote('1970-01-01');

        $pdo->exec("INSERT INTO registeredStudents (name,firstname,age,isVaccinate,dateOfContamination,dateOfReturn,'_id-classe') VALUES ($name,$firstname,$age,$isVaccinate,$dategone,$datereturn,$classe)");
    } else if ($isDel && isset($_POST['students'])) {

        $id = $pdo->quote($_POST['students']);
        $pdo->exec("DELETE FROM main.registeredStudents WHERE _id = $id");
    }
    else if ($isModify){
        $idStudent = $pdo->quote(isset($_POST['students']) ? $_POST['students'] : -1);
        $nameStudent = $pdo->quote(isset($_POST['name']) ? $_POST['name'] : "");
        $firstnameStudent = $pdo->quote(isset($_POST['firstname']) ? $_POST['firstname'] : "");
        $ageStudent = $pdo->quote(isset($_POST['age']) ? $_POST['age'] : "");
        $dateLeaveStudent = $pdo->quote(isset($_POST['dategone']) ? $_POST['dategone'] : "");
        $datereturnStudent = $pdo->quote(isset($_POST['datereturn']) ? $_POST['datereturn'] : "");
        $classeStudent =  $pdo->quote(isset($_POST['classes']) ? $_POST['classes'] : "");
        $isVaccinateStudent = isset($_POST['vaccin']) ? $_POST['vaccin'] : "Non";


        if ($isVaccinateStudent != "Non")
            $isVaccinateStudent = $pdo->quote("Oui");

        else
            $isVaccinateStudent = $pdo->quote("Non");

        $pdo->exec(
            "UPDATE registeredStudents 
            SET name=$nameStudent,
                firstname=$firstnameStudent,
                age=$ageStudent,
                isVaccinate=$isVaccinateStudent,
                dateOfContamination=$dateLeaveStudent,
                dateOfReturn=$datereturnStudent,
                '_id-classe'=$classeStudent
            WHERE main.registeredStudents._id = $idStudent"
        );
    }
    header("Location: ../index.php");
    exit();
}
else if ($direction === "group"){
    if ($isAdd){
        $groupName = str_replace(" ","-",$pdo->quote(isset($_POST['groupName']) ? $_POST['groupName'] : 'non renseigné'));
        $groupNbStudents = $pdo->quote(isset($_POST['groupNbStudents']) ? $_POST['groupNbStudents'] : '-1');

        $pdo->exec("INSERT INTO classe(name,nbStudents) VALUES ($groupName,$groupNbStudents)");
    }
    else if ($isDel && isset($_POST['classe'])){
        $classe = $pdo->quote(isset($_POST['classe']) ? $_POST['classe'] : 'none');

        $sql = $pdo->prepare("SELECT DISTINCT * FROM main.registeredStudents WHERE registeredStudents.'_id-classe' = $classe");
        $sql->execute();
        $result = $sql->fetchAll();
        if ($result) {
            foreach ($result as $a) {
                $pdo->exec("DELETE FROM main.registeredStudents WHERE _id = " . $a["_id"]);
            }
        }

        $pdo->exec("DELETE FROM main.classe WHERE main.classe._id = $classe");
    }
    else if ($isModify){
        $idGroup = $pdo->quote(isset($_POST['groups']) ? $_POST['groups'] : -1);
        $groupName = $pdo->quote(isset($_POST['groupName']) ? $_POST['groupName'] : '');
        $groupNbStudents = $pdo->quote(isset($_POST['groupNbStudents']) ? $_POST['groupNbStudents'] : '');
        $pdo->exec("UPDATE classe SET name = $groupName, nbStudents = $groupNbStudents WHERE _id = $idGroup");
    }
    header("Location: ../index.php");
    exit();
}

header("Location: ../index.php");