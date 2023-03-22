<?php
define("navcheck", true);
require "nav.php";
?>
<link rel="stylesheet" href="css/komponente.css">
<link rel="stylesheet" href="css/stvari/gradient-border.css">

<body>

    <div class="main">
        <div class="naslov"></div>
        <div>
            <div style="font-size:60px;text-align:center">Konfiguracije</div>
            <div class="komponente">
                <div class="glavni">
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
                        $stmt = $conn->prepare("SELECT * FROM `konfiguracije` as konf 
                        cross join (
                                    SELECT SUM(k.cena*konf.kolicina) as ukupnacena,konf.ime as ime FROM konfiguracije as konf
                                    inner join komponente as k on konf.idKomponente = k.id 
                                    group by konf.ime
                                            ) as uc on konf.ime = uc.ime
                        inner join komponente k on k.id = konf.idKomponente");
                        $stmt->execute();
                        $conn->commit();
                        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $brojac = 0;
                        foreach ($stmt->fetchAll() as $k => $row) {
                            if ($row['tip'] == "kuciste") {
                                echo '
                                <div class="komponenta-telo" id="main' . $row['id'] . '">';

                                $slika = explode("|", $row['slika']);
                                $brojac++;
                                echo '
                            <div style="position:relative;">
                            <div style="opacity:0;height:18vh;">prazno</div>
                            <img src="uploadImg/' . $slika[0] . '" alt="" class="komponenta-slika">
                            </div>
                            <div class="komponenta-naslov">' . $row['ime'] . '</div>
                            <div class="komponenta-cena" id="cena' . $row['id'] . '">' . $row['ukupnacena'] . '</div>
                            <div style="text-align:center;">
                                <input type="button" class="komponenta-dugme2" value="Opis" onclick="location.href=\'konfiguracija.php?id=' . $row['ime'] . '\'">
                            </div>
                        </div>';
                            }
                        }
                    } catch (PDOException $error) {
                        echo $error;
                    }

                    ?>

                </div>
            </div>
</body>