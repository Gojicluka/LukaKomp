<?php 
define("navcheck",true);
require "nav.php"; 
?>
<link rel="stylesheet" href="css/komponenta.css">

<?php

?>

<div class="main">
    <?php

    @$id = $_GET['id'];

    if (!is_numeric($id)) {
        die('...');
    }
    if(!defined("servcheck")) define("servcheck", true);
    require_once "includes/serverinfo.inc.php";
    try {
        //uspostavljanje konekcije
        $conn = new PDO("mysql:host=$server;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $conn->query("use $dBName");
        $conn->beginTransaction();

        $stmt = $conn->prepare("select * from komponente where `id`=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $conn->commit();
        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $brojac = 0;
        foreach ($stmt->fetchAll() as $k => $row) {
            $kolicina = $row['kolicina'];
            $id = $row['id'];
            $slika = explode("|", $row['slika']);
            $brojac++;
            echo '<div style="" class="naslov">' . $row['Ime'] . '</div>
            <div class="container">
                <div class="slike" style="margin:0 auto;">
                    <div class="mainImg">
                        <img src="uploadImg/' . $slika[0] . '" class="slika" id="mainSlika">
                    </div>
                    <div style="position: relative;">
                        <div class="secondaryImg" style="display:block;margin:auto;">
                            <img src="uploadImg/' . $slika[0] . '" class="secSlika" onclick="promeniSliku(\'' . $slika[0] . '\')">';
            //DISPLAYUJEMO SVE SLIKE
            for ($i = 1; $i < count($slika); $i++) {
                echo '<img src="uploadImg/' . $slika[$i] . '" class="secSlika"  onclick="promeniSliku(\'' . $slika[$i] . '\')">';
            }
            echo '
                        </div>
                    </div>
        
                </div>
                <div class="container2" style="position:relative">
                    <div class="centerContainer2">
                        <h1>' . $row['Ime'] . '</h1>
                        <div>';
            //DODAJEMO ATRIBUTE NA DISPLAY
            $atributiNiz = explode("|", $row['atributi']);

            for ($i = 0; $i < count($atributiNiz); $i++) {
                $keyValue = explode(":", $atributiNiz[$i]);
                echo $keyValue[0] . ": " . $keyValue[1] . "<br>";
            }
            echo '
                        </div>
                        <div style="font-weight:bold">Cena: ' . $row['cena'] . '</div>
                        <div style="margin-bottom:5px;">
                            <input type="button" value="-" id="kolicinaMinus" onclick="promeniKolicinu(-1)">
                            <input type="text" id="kolicinaTextBox" readonly value="1" style="width:20px;text-align:center;">
                            <input type="button" id="kolicinaPlus" value="+" onclick="promeniKolicinu(1)">
                        </div>
                        ';
            //UKOLIKO NEMAMO VISE KOLICINE NE MOZEMO DA KUPIMO VISE
            if ($row['kolicina'] <= 0) {
                echo "<div>Nije dostupno</div>";
            }else {
                echo '<input type="button" value="Dodaj u korpu" class="dugme" onclick="funkcijaObrada()">';
            }   

           
            echo '
                        <div id="output" style="color:limegreen;text-align:center;"></div>
                    </div>
                </div>
            </div>';
        }
    } catch (PDOException $error) {
        echo $error;
    }

    ?>
</div>

<script>
    function promeniKolicinu(unos) {
        var promenjiva = parseInt(document.getElementById("kolicinaTextBox").value) + unos;
        if (promenjiva != 0 && promenjiva <= <?php echo $kolicina; ?>) {
            document.getElementById("kolicinaTextBox").value = promenjiva;
        }
    }

    function promeniSliku(unos) {
        $("#mainSlika").attr("src", "uploadImg/" + unos);
    }

    function funkcijaObrada() {
        //Jquerry ajax
        $("#output").load("includes/dodajUKorpu.inc.php", {
            id: "<?php echo $id; ?>",
            kolicina: document.getElementById("kolicinaTextBox").value,
            csrf: "<?php echo $_SESSION['csrf']; ?>"
        }, function() {
            document.getElementById("kolicinaTextBox").value = 1;
        });

    }
</script>