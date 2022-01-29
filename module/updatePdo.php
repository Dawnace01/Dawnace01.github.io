<?php
include_once __DIR__ . '/../include/pdoSettings.php';

$isAdd = isset($_GET['action']) && $_GET['action'] == 'add';

if ($isAdd){

    $name = $pdo->quote(isset($_POST['name']) ? $_POST['name'] : 'non renseigné');
    $firstname = $pdo->quote(isset($_POST['firstname']) ? $_POST['firstname'] : 'non renseigné');
    $age = $pdo->quote(isset($_POST['age']) ? $_POST['age'] : -1);
    $dategone = $pdo->quote(isset($_POST['dategone']) ? $_POST['dategone'] : '1970-01-01');
    $classe = $pdo->quote(isset($_POST['classes']) ? $_POST['classes'] : 'non renseignée');
    $isVaccinate = $pdo->quote(isset($_POST['vaccin']) ? $_POST['vaccin'] : 'Non');

    if ($isVaccinate !== "Non")
        $isVaccinate = $pdo->quote("Oui");

    if ($age != -1){
        if ($_POST['age'] < 12){
            var_dump($age);
            if ($dategone != '1970-01-01')
                $datereturn = $pdo->quote(date('Y-m-d',strtotime($_POST['dategone']. ' + 7 days')));
        }
        else if ($age > 12 && $isVaccinate === "Oui"){
            if ($dategone != '1970-01-01')
                $datereturn = $pdo->quote(date('Y-m-d',strtotime($_POST['dategone']. ' + 7 days')));
        }
        else {
            if ($dategone != '1970-01-01')
                $datereturn = $pdo->quote(date('Y-m-d',strtotime($_POST['dategone']. ' + 10 days')));
        }
    }
    else
        $datereturn = $pdo->quote('1970-01-01');

    $pdo->exec("INSERT INTO registeredStudents (name,firstname,age,isVaccinate,dateOfContamination,dateOfReturn,'_id-classe') VALUES ($name,$firstname,$age,$isVaccinate,$dategone,$datereturn,$classe)");

    header("Location: ../index.php");
    exit();
}

header("Location: ../index.php");