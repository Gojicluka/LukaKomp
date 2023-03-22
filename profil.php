<?php
define("navcheck", true);
require "nav.php";
?>
<link rel="stylesheet" href="css/profil.css">

<link rel="stylesheet" href="css/stvari.css">

<div class="main">
    <?php if (!empty($_SESSION['userid'])) { ?>
        <div style="font-size:90px;text-align:center;"> Profil </div>
        <div class="mainContainer">
            <div class="edit">
                <div>
                    <div style="font-weight:bold;font-size:30px;text-align:center;">Promeni ime</div>
                    <div id="updateOutput"></div>
                    <input type="text" class="inputtext" id="usernameUpdateText" placeholder="Username">  <br>
                    <input type="button" class="dugme" value="Aržuriraj" onclick="updateUserEmail('username');">
                </div>
                <div>
                    <div style="font-weight:bold;font-size:30px;text-align:center;">Promeni Email</div>
                    <input type="text" class="inputtext" id="emailUpdateText" placeholder="Email">
                    <br>
                    <input type="button" class="dugme" value="Aržuriraj" onclick="updateUserEmail('email');"> <br>
                </div>
                <div>
                    <div style="font-weight:bold;font-size:30px;text-align:center;">Promeni Sifru</div>
                    <input type="password" class="inputtext" id="stariPassword" placeholder="Stari password"> <br>
                    <input type="password" class="inputtext" id="noviPassword" placeholder="Novi password"> <br>
                    <input type="password" class="inputtext" id="noviPasswordPonavljanje" placeholder="Novi ponavljanje"> <br>
                    <input type="button" class="dugme"" value=" Aržuriraj" onclick="updateSifru()">
                </div>
            </div>
            <div style="font-size:50px;text-align:center;"> Racuni </div>
            <div>
                <?php
                define("servcheck", true);
                require_once "includes/serverinfo.inc.php";

                $korisnikid = $_SESSION['userid'];

                try {
                    //uspostavljanje konekcije
                    $conn = new PDO("mysql:host=$server;", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                    $conn->query("use $dBName");
                    $conn->beginTransaction();

                    $stmt = $conn->prepare("SELECT r.id as racunid,rm.kolicina as kolicina,k.cena as cena, k.Ime as ime,k.slika as slika,r.dostavljeno 
                    as dostavljeno ,uc.ukupnacena as ukupnacena, r.datum_naruceno as datum_naruceno, r.datum_dostavljeno as datum_dostavljeno
                    FROM racun as r
                    cross join (
                        SELECT SUM(k.cena*rm.kolicina) as ukupnacena, r.id as id FROM racun as r
                        inner join racun_medjutabela as rm on r.id = rm.idracun
                        inner join komponente as k on rm.idDrugeTabele = k.id 
                        group by r.id
                    ) as uc on r.id = uc.id
                    inner join racun_medjutabela as rm on r.id = rm.idracun
                    inner join komponente as k on rm.idDrugeTabele = k.id 
                    where `korisnik_id`=:korisnikid 
                    order by `dostavljeno`");
                    $stmt->bindParam(':korisnikid', $korisnikid);

                    $stmt->execute();
                    $conn->commit();
                    $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $brojac = 0;

                    $dostavljenoSwitched = false;
                    $trenutniRacunId = 0;
                    echo "<div style='font-weight:bold;font-size:30px;'>Na putu...</div>";
                    foreach ($stmt->fetchAll() as $k => $row) {
                        $slika = explode('|', $row['slika']);
                        if ($row['dostavljeno'] == 1 && !$dostavljenoSwitched) {
                            echo '</div>';
                            echo "<div style='font-weight:bold;font-size:30px;'>Dostavljeno</div>";
                            $dostavljenoSwitched = true;
                        }
                        if ($row['racunid'] != $trenutniRacunId) {
                            if ($brojac != 0) echo '</div>';
                            $trenutniRacunId = $row['racunid'];
                            echo '<div id="roditelj' . $brojac . '" style="text-align:left;">Racun ' . $row['racunid'] . ' Cena ' . $row['ukupnacena'] . ' Datum ' . $row['datum_naruceno'];
                            $brojac++;
                        }
                        echo '<div style="display:none" id="dete"><img style="height:20px;width:20px;" src="uploadImg/' . $slika[0] . '"> ' . $row['ime'] . ' Kolicina: '.$row['kolicina'].'</div>';
                    }
                    echo '</div>';
                } catch (Exception $ex) {
                    echo $ex;
                }

                ?>
            </div>
        <?php } else { ?>
            <div style="font-size:50px;text-align:center;"> NISTE ULOGOVANI </div>
        <?php } ?>
        </div>

</div>
<script>
    <?php
    for ($i = 0; $i < $brojac; $i++) 
    {
        echo '$("#roditelj' . $i . '").on("click",function(){$(this).children("#dete").toggle(); }); ';
        echo '$("#roditelj' . $i . '").css("cursor","pointer");';
        
        echo '$("#roditelj' . $i . '").hover(()=>{
            $(this).css("cursor","pointer");
        },()=>{
            $(this).css("cursor","initial");
        }); ';
        
    } 
    ?>
    $("#roditelj0").mouseover(()=>{
            $(this).css("cursor","pointer");
            $(this).css("color","pink");
        });
    function updateUserEmail(tip) {
        if (document.getElementById(tip + "UpdateText").value != "") {
            $("#updateOutput").load("includes/editprofile/menjanjeUserEmail.inc.php", {
                unos: document.getElementById(tip + "UpdateText").value,
                tip: tip,
                csrf: "<?php echo $_SESSION['csrf']; ?>"
            }, function() {});
        }
    }

    function updateSifru() {
        $("#updateOutput").load("includes/editprofile/menanjeSifre.inc.php", {
            stariPassword: document.getElementById("stariPassword").value,
            noviPassword: document.getElementById("noviPassword").value,
            noviPasswordPonavljanje: document.getElementById("noviPasswordPonavljanje").value,
            csrf: "<?php echo $_SESSION['csrf']; ?>"
        }, function() {});
    }
</script>