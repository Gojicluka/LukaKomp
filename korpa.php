<?php 
define("korpa", true);
define("navcheck",true);
require "nav.php"; ?>
<link rel="stylesheet" href="css/korpa.css">

<div class="main">
    <div style="font-size:60px;text-align:center">Korpa</div>
    <?php
        require "includes/korpaIspis.inc.php";
    ?>

</div>
<script>
    function returnKolicinaNiz()
    {
        kolicinaNiz = [];
        for(var i=0;i<idNiz.length;i++)
        {
            kolicinaNiz.push(parseInt(document.getElementById("kolicinaTextBox|"+idNiz[i]).value))
        }
        return kolicinaNiz;
    }
    function obrisi(id) {
        kolicinaNiz = returnKolicinaNiz();

        $("#mainContainer").load("includes/korpaIspis.inc.php", {
            idZaBrisanje: id,
            kolicinaNiz: kolicinaNiz,
            brisiMode: true
        });
    }

    function promeniKolicinu(unos, id,maxKolicina) {
        var promenjiva = parseInt(document.getElementById("kolicinaTextBox|" + id).value) + unos;

        if (promenjiva != 0 && promenjiva <=maxKolicina) 
        {
            document.getElementById("kolicinaTextBox|" + id).value = promenjiva;
            document.getElementById("ukupnaCena").innerHTML = 
            parseInt(parseInt(document.getElementById("ukupnaCena").innerHTML) +unos*document.getElementById('cena|'+id).innerHTML)
        }
           
    }

    function funkcijaObrada() {
        kolicinaNiz = returnKolicinaNiz();

        $("#mainContainer").load("includes/kupi.inc.php", {
            idNiz: idNiz,
            kolicinaNiz: kolicinaNiz,
            csrf: "<?php echo $_SESSION['csrf'];?>"
        }, function() {});

    }
</script>