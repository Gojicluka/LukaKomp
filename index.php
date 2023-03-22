<?php
define("navcheck", true);
require "nav.php"; ?>

<link rel="stylesheet" href="css/index.css">

<style>
    .pushdown {
        padding-top: 0;
    }
    body {
        overflow: hidden;
    }
    .nav-area ul li a {
        color: white;
    }
    .korpa {
        filter: invert(1);
    }
</style>

<body>
    <div class="video-container">
        <video class="video" autoplay muted loop style="width:100%;height:100vh;object-fit: fill;">
            <source src="video/video.mp4" type="video/mp4">
        </video>
        <div class="naslov">LukaKomp</div>
    </div>


</body>

</html>