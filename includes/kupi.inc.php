<?php
session_start();
$kolicinaNiz = $_POST['kolicinaNiz'];
$idNiz =  $_POST['idNiz'];


@$idKorisnika = $_SESSION['userid'];

if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
    if (!empty($idKorisnika)) {
        try {
            define("servcheck", true);
            require_once "serverinfo.inc.php";
            $conn = new PDO("mysql:host=$server;", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            $conn->query("use $dBName");

            $conn->beginTransaction();

            //PROMENI ID
            $stmt = $conn->prepare("insert into racun (`korisnik_id`)values(:idkorisnika)");
            $stmt->bindParam(':idkorisnika', $idKorisnika);
            $stmt->execute();
            $idRacuna = $conn->lastInsertId();

            $naredba = "";
            //SADA BINDUJEMO PODTABELE NA TAJ RACUN
            for ($i = 0; $i < count($idNiz); $i++) {
                if ($i != 0) $naredba .= ",";

                $naredba .= "(:idRacuna,:kolicina$i,:id$i)";
            }
            $stmt = $conn->prepare("insert into racun_medjuTabela (idracun,kolicina,idDrugeTabele)values$naredba");

            $updateNaredbaCase = "";
            $updateNaredbaListaIdova = "";

            $stmt->bindParam(":idRacuna", $idRacuna);
            for ($i = 0; $i < count($idNiz); $i++) {
                $stmt->bindParam(":kolicina$i", $kolicinaNiz[$i]);
                $stmt->bindParam(":id$i", $idNiz[$i]);

                //Setujemo statement za updatovanje komponenti
                $updateNaredbaCase .= ' WHEN ' . $idNiz[$i] . ' THEN kolicina-' . $kolicinaNiz[$i] . ' ';

                if ($i == 0) $updateNaredbaListaIdova .= $idNiz[$i];
                else $updateNaredbaListaIdova .= ',' . $idNiz[$i];
            }
            $stmt->execute();

            $stmt = $conn->prepare("delete from korpa where `korisnik_id`=:idkorisnika");
            $stmt->bindParam(':idkorisnika', $idKorisnika);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE komponente
        SET kolicina = CASE id $updateNaredbaCase END
        WHERE id IN($updateNaredbaListaIdova);");
            $stmt->execute();

            $conn->commit();
            echo "<div style='text-align:center;color:rgb(145, 255, 0);font-size:100px;'>Kupljeno!</div>";
        } catch (PDOException $error) {
            echo $error;
        }
    }
}
