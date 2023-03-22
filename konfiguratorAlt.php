<?php
define("navcheck", true);
require "nav.php";
?>
<link rel="stylesheet" href="css/konfigurator.css">

<div class="main">
    <div style="text-align:center;font-size:50px;font-weight:bold;">Konfigurator</div>


    <div id="procesorContainer"></div>
    <div id="ostaliTipovi">

    </div>

    <div id="cena"></div>
    <div id="kupiDugme" style="text-align:center;margin-top:10px;">
        
    </div>
    <div id="kupiError" style="text-align:center;margin-top:10px;">
</div>
<script>
    var selektovanoNiz = {};
    var nizCena = {};

    var tipovi = ['procesori', 'maticne', 'graficke', 'kuciste', 'disk', 'napajanje', 'ram'];

    $("#procesorContainer").load("includes/ucitajTipKonfigurator.inc.php", {
        tip: tipovi[0],
        filter: ""
    }, function() {});

    function getArraySum(a) {
        var total = 0;
        for (var i in a) {
            total += a[i];
        }
        return total;
    }

    function selektovano(id, tip, filter, cena) {
        if (tip == "procesori") selektovanoNiz["maticne"] = "";

        $("#main" + selektovanoNiz[tip]).css("border", "1px solid black");
        $("#main" + id).css("border", "2px solid red");

        selektovanoNiz[tip] = id;
        nizCena[tip] = cena;
        if (tipovi.indexOf(tip) != (tipovi.length - 1)) {


            for (i = (tipovi.indexOf(tip) + 1); i < tipovi.length; i++) {
                console.log(tipovi[i]);
                if (document.getElementById(tipovi[i] + "Container")) {
                    document.getElementById(tipovi[i] + "Container").remove();
                    nizCena[tipovi[i]] = 0;
                    selektovanoNiz[tipovi[i]] = "";
                }
            }
            document.getElementById("ostaliTipovi").innerHTML += "<div id='" + tipovi[tipovi.indexOf(tip) + 1] + "Container'></div>";
            $("#" + tipovi[tipovi.indexOf(tip) + 1] + "Container").load("includes/ucitajTipKonfigurator.inc.php", {
                tip: tipovi[tipovi.indexOf(tip) + 1],
                filter: filter
            }, function() {});

            document.getElementById("kupiDugme").innerHTML = "";
        } else {
            document.getElementById("kupiDugme").innerHTML = '<input onclick="dodajUKorpu()" type="button" value="Dodaj u korpu" class="dugme">';
        }
        document.getElementById("cena").innerHTML = getArraySum(nizCena);
    }

    function dodajUKorpu() {
        $("#kupiError").load("includes/dodajUKorpuKonfigurator.inc.php", {
            niz: selektovanoNiz,
            csrf: "<?php echo $_SESSION['csrf']; ?>"
        }, function() {});
    }
</script>