<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ukupnaCena = 0;

echo "<script>
    idNiz = [];
    </script>";
echo "<div id='mainContainer' >";
if (!empty($_SESSION['userid'])) {
    $korisnikId = $_SESSION['userid'];
    @$kolicinaNiz = $_POST['kolicinaNiz'];
    try {
        define("servcheck", true);

        if (!empty($_POST['brisiMode'])) {
            require_once "serverinfo.inc.php";
        } else {
            require_once "includes/serverinfo.inc.php";
        }

        $conn = new PDO("mysql:host=$server;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $conn->query("use $dBName");
        $conn->beginTransaction();

        if (!empty($_POST['brisiMode'])) {
            $idZaBrisanje = $_POST['idZaBrisanje'];
            $stmt = $conn->prepare("DELETE FROM korpa WHERE `korisnik_id`=:korisnikid AND `komponenta_id`=:idzabrisanje");
            $stmt->bindParam(":korisnikid", $korisnikId);
            $stmt->bindParam(":idzabrisanje", $idZaBrisanje);
            $stmt->execute();
        }

        $stmt = $conn->prepare("select * from korpa 
            inner join komponente as k on korpa.komponenta_id = k.id 
            where korpa.korisnik_id = :korisnikid");
        $stmt->bindParam(":korisnikid", $korisnikId);
        $stmt->execute();
        $conn->commit();
        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $brojac = 0;
        echo '<div class="container">';
        foreach ($stmt->fetchAll() as $k => $row) {
            $tip = $row['tip'];
            $slika = explode('|', $row['slika']);

            $maxKolicina =  $row['kolicina'];

            if (!empty($_POST['brisiMode'])) {
                $kolicina = $kolicinaNiz[0];
            } else {
                //Proveravamo da li u korpi ima vise kolicine nego sto imamo u stocku
                $kolicina = $row['kolicina_korpa'];
                

                if ($kolicina > $maxKolicina) {
                    $kolicina = $row['kolicina'];
                }
            }
            //Dodajemo u niz textBoxeva ime
            echo '<script>idNiz.push(' . $row['id'] . ')</script>';
            echo '
                <div class="slikaDiv">
                    <img src="uploadImg/' . $slika[0] . '" class="slika" id="mainSlika">
                </div>
                <div class="naslov">
                    ' . $row['Ime'] . '
                </div>
                <div class="kolicina">
                    
                    <div style="text-align:center">
                        <input type="button" class="dugmence" value="-" onclick="promeniKolicinu(-1,\'' . $row['id'] . '\',' . $maxKolicina . ')">
                        <input type="text" id="kolicinaTextBox|' . $row['id'] . '" readonly value="' . $kolicina . '" style="width:20px;">
                        <input type="button" class="dugmence"  value="+" onclick="promeniKolicinu(1,\'' . $row['id'] . '\',' . $maxKolicina . ')">
                    </div>
                </div>
                <div class="cena" id="cena|' . $row['id'] . '">
                   ' . $row['cena'] . '
                </div>
                <div class="obrisi" style="text-align:center;">
                    <input type="button" class="inputOption" value="x" onclick="obrisi(\'' . $row['id'] . '\')">
                </div> 
                ';
            $ukupnaCena += $kolicina* $row['cena'];
            $brojac++;
        }
        echo '</div>';
        if ($brojac == 0) {
            echo "<div style='text-align:center;'>Prazno</div>";
        } else {
            echo '<div style="text-align:center;font-size:30px;">Ukupna cena: <div id="ukupnaCena">' . $ukupnaCena . '</div></div>';
            
            echo '<div style="text-align:center;margin-top:10px;"><input onclick="funkcijaObrada()" type="button" value="Kupi" class="dugme"></div>';
            echo '<div id="output" style="color:rgb(145, 255, 0);text-align:center;"></div>';
            echo "</div>";
        }
        //echo $ispisNiz[$ispisNiz[$i]][$i];

    } catch (PDOException $error) {
        echo $error;
    }
} else {
    echo "<div style='text-align:center'>Molim vas ulogujute se prvo</div>";
}
echo "</div>";
