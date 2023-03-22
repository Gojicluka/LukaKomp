<?php

session_start();

@$korisnikId = $_SESSION['userid'];

$komponentaId = $_POST['id'];
$kolicinaPost = $_POST['kolicina'];
if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
    if (!empty($korisnikId)) {
        if (is_numeric($komponentaId)) {

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
                $stmt = $conn->prepare("select `kolicina_korpa` from korpa where `korisnik_id`=:korisnikid and `komponenta_id`=:komponenta");
                $stmt->bindParam(':korisnikid', $korisnikId);
                $stmt->bindParam(':komponenta', $komponentaId);
                $stmt->execute();
                $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $brojac = 0;
                foreach ($stmt->fetchAll() as $k => $row) {
                    $kolicinaZaDodavanje = $row['kolicina_korpa'] + $kolicinaPost;
                    $stmt = $conn->prepare("update korpa set `kolicina_korpa`=:kolicina where `korisnik_id`=:korisnikid and `komponenta_id`=:komponenta");
                    $stmt->bindParam(':korisnikid', $korisnikId);
                    $stmt->bindParam(':komponenta', $komponentaId);
                    $stmt->bindParam(':kolicina', $kolicinaZaDodavanje);
                    $stmt->execute();
                    $brojac++;
                }

                if ($brojac == 0) {
                    $stmt = $conn->prepare("insert into korpa values(:korisnikid,:komponenta,:kolicina)");
                    $stmt->bindParam(':korisnikid', $korisnikId);
                    $stmt->bindParam(':komponenta', $komponentaId);
                    $stmt->bindParam(':kolicina', $kolicinaPost);
                    $stmt->execute();
                }

                $conn->commit();

                echo "dodato";
            } catch (Exception $ex) {
                echo $ex;
            }
        }
    } else {
        echo "Ulogujte se prvo!";
    }
}else{
    echo "bad csrf";
}
//echo $_SESSION['korpa'];
