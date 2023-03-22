<?php
define("navcheck", true);
require "nav.php";
?>
<link rel="stylesheet" href="css/komponente.css">
<link rel="stylesheet" href="css/konfigurator.css">

<link rel="stylesheet" href="css/stvari/gradient-border.css">
<div class="main">
    <div style="text-align:center;font-size:80px;font-weight:bold;" class="animiran-text">Konfigurator</div>
    <div style="position:relative">
        <div class="mainContainer" style="display:grid;">
            <?php

            echo "<script>maticneNiz=[]</script>";

            define("servcheck", true);
            require_once "includes/serverinfo.inc.php";
            try {
                //uspostavljanje konekcije
                $conn = new PDO("mysql:host=$server;", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                $conn->query("use $dBName");
                $conn->beginTransaction();


                $stmt = $conn->prepare("SELECT * from komponente where `kolicina`>0 order by `tip`");

                $stmt->execute();
                $conn->commit();
                $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $brojac = 0;
                $trenutniTip = "";
                foreach ($stmt->fetchAll() as $k => $row) {

                    if ($row['tip'] != $trenutniTip) {
                        if ($trenutniTip != "") {
                            echo "</div></div>";
                        }
                        echo "<div id='" . $row['tip'] . "Container'>";
                        echo "<div style='font-size:30px;font-weight:bold'>" . $row['tip'] . "</div>";
                        echo "<div class='container2'>";
                        $trenutniTip = $row['tip'];
                    }
                    //Za filtriranje maticnih
                    if ($row['tip'] == "maticne") echo "<script>maticneNiz.push(" . $row['id'] . ")</script>";

                    echo '<div class="komponenta-telo" id="main' . $row['id'] . '">';

                    $atributi = explode("|", $row['atributi']);
                    //Popunjavamo filter niz

                    for ($i = 0; $i < count($atributi); $i++) {
                        $atributiSub = explode(":", $atributi[$i]);
                        echo '<input type="hidden" id="' . $atributiSub[0] . $row['id'] . '" value="' . $atributiSub[1] . '">';
                    }

                    $slika = explode("|", $row['slika']);
                    $brojac++;
                    echo '
                                 <div style="position:relative;">
                                 <div style="opacity:0;height:15vh;">prazno</div>
                                 <img src="uploadImg/' . $slika[0] . '" alt="" class="komponenta-slika">
                                 </div>
                                 <div class="komponenta-naslov">' . $row['Ime'] . '</div>
                                 <div class="komponenta-cena" style="margin-bottom:40px;" id="cena' . $row['id'] . '">' . $row['cena'] . '</div>
                                 <div style="text-align:center;">
                                     <input type="button" class="komponenta-dugme2" value="Selektuj" onclick="selektovano(\'' . $row['id'] . '\',\'' . $row['tip'] . '\',' . $row['cena'] . ')">
                                 </div>
                             </div>';
                }

                echo '</div></div>';
                if ($brojac == 0) echo "Nema kompatibilnih delova trenutno u radnji :(";
            } catch (Exception $ex) {
            }

            ?>
        </div>
        <div id="errorBox" style="text-align:center;margin-top:10px;color:red;font-size:30px;"></div>
        <div style="text-align:center;font-size:50px;">
        <div  id="cena"></div>
        </div>
        
        


    </div>
</div>
<div id="kupiDugme" style="text-align:center;"></div>

<script>
    var selektovanoNiz = {};
    var nizCena = {};

    var tipovi = ['procesori', 'maticne', 'graficke', 'kuciste', 'disk', 'napajanje', 'ram'];

    //Sortiramo grupe komponenata po redu
    for (i = 1; i <= tipovi.length; i++) {
        $("#" + tipovi[i - 1] + "Container").css("order", i);
        if (i != 1) $("#" + tipovi[i - 1] + "Container").hide();
    }

    function selektovano(id, tip, cena) {

        //Menjamo border kako bi izgledalo da su selektovani
        $("#main" + selektovanoNiz[tip]).css("border", "1px solid black");
        $("#main" + id).css("border", "2px solid red");



        //Clearujemo sve erroe
        $("#errorBox").html("");
        //Setujemo cene i selektovani niz
        selektovanoNiz[tip] = id;
        nizCena[tip] = cena;
        //Proveravamo da li smo na kraju dodavanja grupa
        if (tipovi.indexOf(tip) != (tipovi.length - 1)) {

            for (i = (tipovi.indexOf(tip) + 1); i < tipovi.length; i++) {
                //Vracamo nazad na crveni ne selektovani border
                $("#" + tipovi[i] + "Container .container2").children().css("border", "1px solid black");
                //Sklanjamo elemente koji nisu potrebni
                $("#" + tipovi[i] + "Container").hide();
                nizCena[tipovi[i]] = 0;
                selektovanoNiz[tipovi[i]] = "";
            }
            //Removujemo kupi dugme ukoliko ne treba da bude tu
            document.getElementById("kupiDugme").innerHTML = "";
            //Prikazi sledecu grupu
            $("#" + tipovi[tipovi.indexOf(tip) + 1] + "Container").show();
            $('html, body').animate({
                scrollTop: $("#" + tipovi[tipovi.indexOf(tip) + 1] + "Container").offset().top
            }, 100);
            //Checukjemo da li maticna ima dobar selektovan socket
            if (tip == "procesori") {
                brojacMaticnih = 0;
                //Proveravamo koje maticne su kompatibilne
                for (i = 0; i < maticneNiz.length; i++) {
                    if ($("#socket" + maticneNiz[i]).val() == $("#socket" + id).val()) {
                        $("#main" + maticneNiz[i]).show();
                        brojacMaticnih++;
                    } else $("#main" + maticneNiz[i]).hide();
                }
                if (brojacMaticnih == 0) $("#errorBox").html("Nema kompatibilnih maticnih trenutno u prodaji")
            }

        } else {
            document.getElementById("kupiDugme").innerHTML = '<input onclick="dodajUKorpu()" type="button" value="Dodaj u korpu" class="dugme">';
        }
        //Racunamo cenu
        document.getElementById("cena").innerHTML = "Cena: " +getArraySum(nizCena);
    }

    function getArraySum(a) {
        var total = 0;
        for (var i in a) {
            total += a[i];
        }
        return total;
    }

    function dodajUKorpu() {
        $("#errorBox").load("includes/dodajUKorpuKonfigurator.inc.php", {
            niz: selektovanoNiz,
            csrf: "<?php echo $_SESSION['csrf']; ?>"
        }, function() {
            location.href = "korpa.php";
        });
    }
</script>