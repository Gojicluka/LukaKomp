<?php
$tip = htmlspecialchars(strip_tags($_POST['tip']));
@$filterPost = htmlspecialchars(strip_tags($_POST['filter']));

define("servcheck", true);
require_once "serverinfo.inc.php";
try {
    //uspostavljanje konekcije
    $conn = new PDO("mysql:host=$server;", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    $conn->query("use $dBName");
    $conn->beginTransaction();

    $dodatnaNaredba= "";
    if($tip=="maticne") $dodatnaNaredba = "AND `atributi` like :socket";

    $stmt = $conn->prepare("SELECT * from komponente where `tip`=:tip $dodatnaNaredba");

    $stmt->bindParam(':tip', $tip);
    if($tip=="maticne")
    {
        $filterPost = "%socket:".$filterPost."%";
        $stmt->bindParam(':socket', $filterPost);
    }
  
    echo "<div style='font-size:30px;font-weight:bold'>".$tip."</div>";
    echo "<div class='container2'>";
    $stmt->execute();
    $conn->commit();
    $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $brojac = 0;
    echo "<input type='hidden' id='selektovan$tip' value=''/>";
    foreach ($stmt->fetchAll() as $k => $row) {
        $filter = "";

        echo '<div class="komponenta-telo" id="main' . $row['id'] . '">';

        $atributi = explode("|", $row['atributi']);
        //Popunjavamo filter niz

        for ($i = 0; $i < count($atributi); $i++) {
            $atributiSub = explode(":", $atributi[$i]);
            echo '<input type="hidden" id="' . $atributiSub[0] . $row['id'] . '" value="' . $atributiSub[1] . '">';

            if($tip=="procesori"&&$atributiSub[0]=="socket"){$filter=$atributiSub[1];}
        }

        $slika = explode("|", $row['slika']);
        $brojac++;
        echo '
                            <div style="position:relative;">
                            <div style="opacity:0;height:15vh;">prazno</div>
                            <img src="uploadImg/' . $slika[0] . '" alt="" class="komponenta-slika">
                            </div>
                            <div class="komponenta-naslov">' . $row['Ime'] . '</div>
                            <div class="komponenta-cena" id="cena' . $row['id'] . '">' . $row['cena'] . '</div>
                            <div style="text-align:center;">
                                <input type="button" class="komponenta-dugme" value="Selektuj" onclick="selektovano(\''.$row['id'].'\',\''.$tip.'\',\''.$filter.'\','.$row['cena'].')">
                            </div>
                        </div>';
    }
    
    echo '</div>';
    if($brojac==0) echo "Nema kompatibilnih delova trenutno u radnji :(";

} catch (Exception $ex) {
}
