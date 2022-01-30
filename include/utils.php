<?php
function delStudentBack($pdo){
    $currentDate = new DateTime();
    $quotedCrrentDate = $pdo->quote($currentDate->format('Y-m-d'));
    $pdo->exec("DELETE FROM registeredStudents WHERE main.registeredStudents.dateOfReturn <= " . $quotedCrrentDate);
}

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
        $ret .= "<tr class='raw-student'>
    <td>" . $a['name'] . "</td>
    <td>" . $a['firstname'] . "</td>
    <td>" . getClasseFromId($pdo,$a['_id-classe']) . "</td>
    <td>" . $a['isVaccinate'] . "</td>
    <td class='date-format'>" . $a['dateOfContamination'] . "</td>
    <td class='date-format'>" . $a['dateOfReturn'] . "</td>
</tr>";
    }

    return $ret;
}

function printAllStudentsInTableSorted($pdo,$sort){
    $sortQuoted = $pdo->quote($sort);
    $sql = $pdo->prepare("SELECT DISTINCT * FROM registeredStudents s JOIN classe c on s.'_id-classe' = c._id WHERE c.name like '%$sort%'");
    $sql->execute();
    $result = $sql->fetchAll();
    if (!$result)
        return "<tr><td colspan='6'>Pas de résultat</td></tr>";

    $ret = "";//"<tr><td colspan='6'>Nombre d'élève(s) malade(s) en " . $sort ." :   <b>" . sizeof($result) . "</b></td></tr>";

    foreach ($result as $a){
        $ret .= "<tr class='raw-student'>
    <td>" . $a[1] . "</td>
    <td>" . $a['firstname'] . "</td>
    <td>" . getClasseFromId($pdo,$a['_id-classe']) . "</td>
    <td>" . $a['isVaccinate'] . "</td>
    <td class='date-format'>" . $a['dateOfContamination'] . "</td>
    <td class='date-format'>" . $a['dateOfReturn'] . "</td>
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

function getClasseFromId($pdo,$id){

    $idQuoted = $pdo->quote($id);
    $sql = $pdo->prepare("SELECT name FROM classe WHERE _id like " . $idQuoted);
    $sql->execute();
    $result = $sql->fetch();

    if (!$result)
        return -1;

    return $result[0];
}

function printDropDownStudents($pdo,$getName,$getFirstname,$getClasse){
    $sql = $pdo->prepare("SELECT DISTINCT * FROM main.registeredStudents ORDER BY main.registeredStudents.'_id-classe' ASC");
    $sql->execute();
    $result = $sql->fetchAll();
    if (!$result)
        return "";

    $ret = "<option value='0' selected disabled>-- Selectionnez un élève --</option>";

    foreach ($result as $a){
        $text = $a['name'] . " " . $a['firstname'] . ", " . getClasseFromId($pdo,$a['_id-classe']);
        $ret .= "<option value='" . $a['_id'] . "'";

        if (isSelectedDropDownStudent($getName,$getFirstname,$getClasse,$text))
            $ret .= " selected";

        $ret .= ">" . $text . "</option>";
    }

    return $ret;
}

function printDropDownClasses($pdo,$getName){
    $sql = $pdo->prepare("SELECT DISTINCT * FROM main.classe ORDER BY _id");
    $sql->execute();
    $result = $sql->fetchAll();
    if (!$result)
        return "";

    $ret = "<option value='0' selected disabled>-- Selectionnez une classe --</option>";

    foreach ($result as $a){
        $text = $a['name'] . ", " . $a['nbStudents'] . " élèves";
        $ret .= "<option value='" . $a['_id'] . "'";

        if (isSelectedDropDownClasse($getName,$a['name']))
            $ret .= " selected";

        $ret .= ">" . $text . "</option>";
    }

    return $ret;
}

function isSelectedDropDownStudent($name,$firstname,$classe,$current){
    return ($current == ($name . " " . $firstname . ", " . $classe));
}

function isSelectedDropDownClasse($name,$current){
    return ($current == $name);
}

function isSelectedVaccinatedCheckbox($pdo,$name,$firstname,$classe){
    $nameQuoted = $pdo->quote($name);
    $firstNameQuoted = $pdo->quote($firstname);
    $classeQuoted = $pdo->quote(getIdOfClasse($pdo,$classe));
    $sql = $pdo->prepare("SELECT isVaccinate FROM main.registeredStudents WHERE name = $nameQuoted AND firstname = $firstNameQuoted AND registeredStudents.'_id-classe' = $classeQuoted");
    $sql->execute();
    $result = $sql->fetch();
    if (!$result)
        return "";

    if (strtolower($result[0]) == 'oui')
        return " checked";

    return "";
}

function autoCompletion($value){
    return 'value=\'' . $value . '\'';
}

function printAgeFromStudent($pdo, $name, $firstname, $classe){
    $nameQuoted = $pdo->quote($name);
    $firstNameQuoted = $pdo->quote($firstname);
    $classeQuoted = $pdo->quote(getIdOfClasse($pdo,$classe));
    $sql = $pdo->prepare("SELECT age FROM main.registeredStudents WHERE name = $nameQuoted AND firstname = $firstNameQuoted AND registeredStudents.'_id-classe' = $classeQuoted");
    $sql->execute();
    $result = $sql->fetch();

    if (!$result)
        return autoCompletion("");

    return autoCompletion($result[0]);
}

function printDateLeftFromStudent($pdo, $name, $firstname, $classe){
    $nameQuoted = $pdo->quote($name);
    $firstNameQuoted = $pdo->quote($firstname);
    $classeQuoted = $pdo->quote(getIdOfClasse($pdo,$classe));
    $sql = $pdo->prepare("SELECT dateOfContamination FROM main.registeredStudents WHERE name = $nameQuoted AND firstname = $firstNameQuoted AND registeredStudents.'_id-classe' = $classeQuoted");
    $sql->execute();
    $result = $sql->fetch();

    if (!$result)
        return autoCompletion("");

    return autoCompletion($result[0]);
}

function printDateOfReturnFromStudent($pdo, $name, $firstname, $classe){
    $nameQuoted = $pdo->quote($name);
    $firstNameQuoted = $pdo->quote($firstname);
    $classeQuoted = $pdo->quote(getIdOfClasse($pdo,$classe));
    $sql = $pdo->prepare("SELECT dateOfReturn FROM main.registeredStudents WHERE name = $nameQuoted AND firstname = $firstNameQuoted AND registeredStudents.'_id-classe' = $classeQuoted");
    $sql->execute();
    $result = $sql->fetch();

    if (!$result)
        return autoCompletion("");

    return autoCompletion($result[0]);
}