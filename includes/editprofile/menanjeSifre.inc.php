<?php

session_start();

$stariPassword = htmlspecialchars(strip_tags($_POST['stariPassword']));
$noviPassword = htmlspecialchars(strip_tags($_POST['noviPassword']));
$noviPasswordPonavljanje = htmlspecialchars(strip_tags($_POST['noviPasswordPonavljanje']));
$userid = $_SESSION['userid'];

if ($noviPassword != $noviPasswordPonavljanje) {
    die("<div style='color:red;'>Nisu passwordi isti</div>");
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
        $stmt = $conn->prepare("SELECT `sifra` from korisnik where `id`=:userid");
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($stmt->fetchAll() as $k => $row) {
            if (password_verify($stariPassword, $row['sifra'])) {

                $stmt = $conn->prepare("UPDATE korisnik SET `sifra`=:sifra where `id`=:userid");
                $hashovanPassword = password_hash($noviPassword, PASSWORD_DEFAULT);
                $stmt->bindParam(':userid', $userid);
                $stmt->bindParam(':sifra', $hashovanPassword);
                $stmt->execute();
                echo "<div style='color:rgb(145, 255, 0);'>Aržurirano!!!</div>";
            } else {
                echo "<div style='color:red;'>Stari password je pogresan</div>";
            }
        }

        $conn->commit();
    } catch (Exception $ex) {
        echo $ex;
    }
}
