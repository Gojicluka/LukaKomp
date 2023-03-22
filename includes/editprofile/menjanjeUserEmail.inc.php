<?php

session_start();

$dovoljeniTipovi = ['email', 'username'];

$unos = htmlspecialchars(strip_tags($_POST['unos']));
$userid = $_SESSION['userid'];
$tip = $_POST['tip'];
if (!in_array($tip, $dovoljeniTipovi)) {
    die('pogresan tip :(');
}
define("servcheck", true);
require_once "../serverinfo.inc.php";
if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
    try {
        //uspostavljanje konekcije
        $conn = new PDO("mysql:host=$server;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $conn->query("use $dBName");
        $conn->beginTransaction();

        //Gledamo da li je komponenta uneta vec u korpu 
        $stmt = $conn->prepare("SELECT $tip from korisnik where `$tip`=:unos");
        $stmt->bindParam(':unos', $unos);
        $stmt->execute();
        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $nizFetchovanih = $stmt->fetchAll();
        if (empty($nizFetchovanih)) {
            $stmt = $conn->prepare("UPDATE korisnik SET `$tip`=:unos where `id`=:id");
            $stmt->bindParam(':id', $userid);
            $stmt->bindParam(':unos', $unos);
            $stmt->execute();
            echo "<div style='color:rgb(145, 255, 0);'>Aržurirano!!!</div>";
        } else {
            die("<div style='color:red;'>Vec postoji $tip taj :(</div>");
        }

        $conn->commit();
    } catch (Exception $ex) {
        echo $ex;
    }
}
