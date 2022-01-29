<?php
// communication avec une BDD
try {
    $pdo = new PDO("sqlite:" . __DIR__ . "/database.sqlite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo   'Connexion échouée : ' . $e->getMessage();
    die(); // on arrête si problème
}

