<?php 
define("navcheck",true);
require "nav.php"; 
?>
<link rel="stylesheet" href="css/komponente.css">
<link rel="stylesheet" href="css/stvari/gradient-border.css">
<body>

    <div class="main">
        <div class="container">

            <div class="komponente">
                <div style="position:relative;">
                    <select name="" id="selectOrder" onchange="promeniOrder()">
                        <option value="nazivRast">Naziv A-Z</option>
                        <option value="nazivOpad">Naziv Z-A</option>
                        <option value="cenaRast">Cena rastuća</option>
                        <option value="cenaOpad">Cena opadajuća</option>
                    </select>
                </div>
                <hr>

                <div class="glavni">

                    <?php

                    echo "<script> idniz = []; </script>";

                    @$tip = $_GET['tip'];
                    @$proizvodjac = $_GET['proizvodjac'];
                    if (empty($tip)) {
                        $tip = "graficke";
                    }

                    if (!in_array($tip, explode("|", $_SESSION['tipovi']))) {
                        die("badid");
                    }
                    if (!empty($proizvodjac) && !is_numeric($proizvodjac)) {
                        die("badproizvodjac");
                    }

                    @define("servcheck", true);
                    require_once "includes/serverinfo.inc.php";
                    try {
                        //uspostavljanje konekcije
                        $conn = new PDO("mysql:host=$server;", $user, $pass);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                        $conn->query("use $dBName");
                        $conn->beginTransaction();
                        $dodatnaNaredba = "";
                        if (!empty($proizvodjac)) $dodatnaNaredba = "AND `proizvodjac`=:proizvodjac";
                        $stmt = $conn->prepare("SELECT * from komponente where `tip`=:tip AND `kolicina`>0 $dodatnaNaredba order by `Ime`");
                        $stmt->bindParam(':tip', $tip);
                        if (!empty($proizvodjac)) $stmt->bindParam(':proizvodjac', $proizvodjac);
                        $stmt->execute();
                        $conn->commit();
                        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $brojac = 0;
                        $filteri = [];
                        foreach ($stmt->fetchAll() as $k => $row) {
                            echo "<script> idniz.push(" . $row['id'] . ") </script>";

                            echo '
                                <div class="komponenta-telo" id="main' . $row['id'] . '">';

                            $atributi = explode("|", $row['atributi']);
                            //Popunjavamo filter niz
                            if ($brojac == 0) {
                                for ($i = 0; $i < count($atributi); $i++) {
                                    $atributiSub = explode(":", $atributi[$i]);
                                    array_push($filteri, $atributiSub[0]);
                                    $filteri[$atributiSub[0]] = array();
                                    array_push($filteri[$atributiSub[0]],strtolower($atributiSub[1]));
                                    echo '<input type="hidden" id="' . $atributiSub[0] . $row['id'] . '" value="' .strtolower( $atributiSub[1]) . '">';
                                }
                            } else {
                                for ($i = 0; $i < count($atributi); $i++) {
                                    $atributiSub = explode(":", $atributi[$i]);
                                    if (!in_array(strtolower($atributiSub[1]), $filteri[$atributiSub[0]])) {
                                        array_push($filteri[$atributiSub[0]],strtolower( $atributiSub[1]));
                                    }
                                    echo '<input type="hidden" id="' . $atributiSub[0] . $row['id'] . '" value="' . strtolower($atributiSub[1]) . '">';
                                }
                            }

                            $slika = explode("|", $row['slika']);
                            $brojac++;
                            echo '
                            <div style="position:relative;">
                            <div style="opacity:0;height:18vh;">prazno</div>
                            <img src="uploadImg/' . $slika[0] . '" alt="" class="komponenta-slika">
                            </div>
                            <div class="komponenta-naslov">' . $row['Ime'] . '</div>
                            <div class="komponenta-cena" style="margin-bottom:40px;" id="cena' . $row['id'] . '">' . $row['cena'] . '</div>
                            <div style="text-align:center;">
                                <input type="button" class="komponenta-dugme2" value="Opis" onclick="location.href=\'komponenta.php?id=' . $row['id'] . '\'">
                            </div>
                        </div>';
                        }
                    } catch (PDOException $error) {
                        echo $error;
                    }

                    echo "</div>
                
                    </div>";
                    echo " <div class='opcije'>";
                    echo "<div style='font-size:50px;font-weight:bold;color:white;'>Filteri</div>";
                    for ($i = 0; $i < count($filteri) / 2; $i++) {
                        echo $filteri[$i];
                        echo "<br>";
                        echo '<hr class="hrdog">';
                        echo '<select style="width:100%;" id="select' . $i . '"  onchange="filterPromena(' . $i . ',\'' . $filteri[$i] . '\')">';
                        echo "<option></option>";
                        for ($j = 0; $j < count($filteri[$filteri[$i]]); $j++) {
                            echo "<option>" . $filteri[$filteri[$i]][$j] . "</option>";
                        }
                        echo '</select>';
                        echo "<br>";
                    }
                    echo "</div>";
                    ?>

                </div>
            </div>
            <script>
                //Kopiramo originalni niz kako bi smo mogli da ga koristimo prilikom sortiranja
                idnizOriginalni = idniz.slice();

                function promeniOrder() {
                    idniz = idnizOriginalni.slice();
                    vrednost = document.getElementById("selectOrder").value;
                    order = 1;
                    switch (vrednost) {
                        case "nazivOpad":
                            idniz.reverse();
                            break;
                        case "cenaRast":
                        case "cenaOpad":
                            //Radimo klasicno c sortiranje 
                            for (i = 0; i < idniz.length; i++) {
                                for (j = i + 1; j < idniz.length; j++) {
                                    if (document.getElementById('cena' + idniz[i]).innerHTML > document.getElementById('cena' + idniz[j]).innerHTML) {
                                        pom = idniz[i];
                                        idniz[i] = idniz[j];
                                        idniz[j] = pom;
                                    }
                                }
                            }
                            break;
                        default:
                            break;
                    }
                    if (vrednost == "cenaOpad") idniz.reverse();
                    for (i = 0; i < idniz.length; i++) {
                        $("#main" + idniz[i]).css("order", order);
                        order++;
                    }
                }

                //Sistem filtera
                var sviFilteri = [];

                function filterPromena(selectId, filterName) {
                    var filterValue = document.getElementById("select" + selectId).value;
                    if (filterValue == "") delete sviFilteri[filterName];
                    else sviFilteri[filterName] = filterValue;

                    filteriKljucevi = Object.keys(sviFilteri);

                    for (i = 0; i < idniz.length; i++) {
                        var provera = true;
                        for (j = 0; j < Object.keys(sviFilteri).length; j++) {
                            if (document.getElementById(filteriKljucevi[j] + idniz[i]).value != sviFilteri[filteriKljucevi[j]]) provera = false;
                        }
                        if (provera) $('#main' + idniz[i]).show();
                        else $('#main' + idniz[i]).hide();


                    }
                }
            </script>
</body>