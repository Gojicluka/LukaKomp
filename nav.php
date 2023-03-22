<!DOCTYPE html>
<html lang="en">
<?php 
if(!defined("navcheck")){exit("...");}
session_start();
define("logincheck",true);
require "includes/checkLogin.inc.php";

$key = bin2hex(random_bytes(32));
$csrf = hash_hmac("sha256", 'random string bura http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '', $key);
$_SESSION['csrf'] = $csrf;


if(empty($_SESSION['tipovi']))
{
    require "includes/getTipovi.inc.php";
}
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/nav.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<div style="position:relative;">
    <div style="z-index:20;"> <!-- -->
        <nav class="nav-area" style="position:fixed;right:0;z-index:20;">
            <!--<img src="img/logo.png" alt="" class="logo" style="float:left;">-->
            <ul style="">
                <li><a href="korpa.php"><img src="img/cart.png" class="korpa" style="height:18px;width:18px;"></a></li>
                <li>
                    <?php 
                    if(isset($_SESSION['username']))
                    {
                        ?>
                        <a href="" class="loginButton" style="color:white;"><?php echo $_SESSION['username'];?></a>
                        <ul>
                            <li><a href="profil.php" class="profil">profil</a></li>
                            <li class="logoutli"><a href="includes/logout.inc.php" class="logout" style="background-color:red;">logout</a></li>
                        </ul>
                    <?php
                    }else{?>
                        <a href="login.php" class="loginButton" style="color:white;">Login</a>
                    <?php
                    }
                    ?>
                    
                </li>
                <li><a href="">O nama</a></li>
                <li><a href="">Kontakt</a></li>
                <li><a href="konfiguracije.php">konfiguracije</a></li>
                <li><a href="konfigurator.php">Konfigurator</a></li>
                <li><a href="">Komponente</a>
                    <ul>
                        <li><a href="komponente.php?tip=procesori">
                                <div style="text-align:left;float:left;">
                                    < </div>
                                        <div style="text-align:right;"> Procesori</div>
                            </a>
                            <ul>
                                <li><a href="komponente.php?tip=procesori&proizvodjac=1">AMD</a></li>
                                <li><a href="komponente.php?tip=procesori&proizvodjac=3">INTEL</a></li>
                            </ul>
                        </li>
                        <li><a href="komponente.php?tip=graficke">
                                <div style="text-align:left;float:left;">
                                    < </div> Graficke
                            </a>
                            <ul>
                                <li><a href="komponente.php?tip=graficke&proizvodjac=1">AMD</a></li>
                                <li><a href="komponente.php?tip=graficke&proizvodjac=2">NVIDiA</a></li>
                            </ul>
                        </li>
                        <li><a href="komponente.php?tip=maticne">Maticne</a></li>
                        <li><a href="komponente.php?tip=ram">RAM</a></li>
                        <li><a href="komponente.php?tip=disk">Diskovi</a></li>
                        <li><a href="komponente.php?tip=napajanje">Napjanja</a></li>
                        <li><a href="komponente.php?tip=kuciste">Kucista</a></li>
                    </ul>
                    
                </li>

                <li><a href="index.php">Pocetna</a></li>
            </ul>
            
        </nav>
    </div>
</div>

<script>
    
</script>
<div class="pushdown"></div>