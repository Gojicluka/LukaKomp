<?php

session_start();

$kupiNiz = $_POST['niz'];
$korisnikId = $_SESSION['userid'];



if (!empty($_SESSION['userid'])) {
    if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
        if (!in_array("", $kupiNiz)) {

            define("servcheck", true);
            require_once "serverinfo.inc.php";
            try {
                //uspostavljanje konekcije
                $conn = new PDO("mysql:host=$server;", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                $conn->query("use $dBName");
                $conn->beginTransaction();

                //Gledamo da li je komponenta uneta vec u korpu 
                $stmt = $conn->prepare("select `kolicina_korpa`,`komponenta_id` from korpa where `korisnik_id`=:korisnikid");
                $stmt->bindParam(':korisnikid', $korisnikId);
                $stmt->execute();
                $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                $updateNaredba = "";
                $updateIdovi = "";
                $updateNizIdova = [];

                $insertNaredba = "";
                $insertNizId = [];

                //Proveravamo kojim columnima moramo da updatujemo kolicinu
                foreach ($stmt->fetchAll() as $k => $row) {
                   
                    if (in_array($row['komponenta_id'], $kupiNiz)) {
                        if ($updateNaredba != "") $updateIdovi .= ",";
                        $updateNaredba .= ' WHEN ' . $row['komponenta_id'] . ' THEN kolicina_korpa+1 ';
                        $updateIdovi .= $row['komponenta_id'];
                        array_push($updateNizIdova, $row['komponenta_id']);
                    }
                }
                //Konvertujemo kupi niz da ne bude asocijativan
                $kupiNiz = array_values($kupiNiz);
                for ($i = 0; $i < count($kupiNiz); $i++) {
                    if (!in_array($kupiNiz[$i], $updateNizIdova)) {
                        array_push($insertNizId, $kupiNiz[$i]);
                        if ($insertNaredba != "") $insertNaredba .= ",";
                        $insertNaredba .= '(:korisnikid,:idkom' . (count($insertNizId) - 1) . ',1)';
                    }
                }

                //Updatujemo tabelu
                if ($updateNaredba != "") {
                    $stmt = $conn->prepare("UPDATE korpa
                    SET kolicina_korpa = CASE komponenta_id $updateNaredba END
                    WHERE komponenta_id IN($updateIdovi)");
                    $stmt->execute();
                }
                
                //Insertujemo u tabelu
                if ($insertNaredba != "") {
                    $stmt = $conn->prepare("insert into korpa values$insertNaredba");
                    $stmt->bindParam(':korisnikid', $korisnikId);

                    for ($i = 0; $i < count($insertNizId); $i++) {
                        $stmt->bindParam(":idkom$i" , $insertNizId[$i]);
                        echo ":idkom$i ". $insertNizId[$i]."<br>";
                    }
                    $stmt->execute();
                }

                $conn->commit();

                echo "dodato";
                
            } catch (Exception $ex) {
                echo $ex;
            }
        } else {
            echo "Niste izabrali neku stavku";
        }
        
    }
    else{
        echo "session timeout";
    }
} else {
    echo "Ulogujte se";
}
