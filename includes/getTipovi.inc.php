<?php
@define("servcheck", true);
require_once "includes/serverinfo.inc.php";


try {
    //uspostavljanje konekcije
    $conn = new PDO("mysql:host=$server;", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    $conn->query("use $dBName");
    $conn->beginTransaction();
    
    $stmt = $conn->prepare("select * from tabele");
    $stmt->bindParam(':tip', $tip);
    //$stmt->bindParam(':id', $id);
    $stmt->execute();
    $conn->commit();
    $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $_SESSION['tipovi']=  $stmt->fetchAll()[0]["sveTabele"];
} catch (PDOException $error) {
    echo $error;
}