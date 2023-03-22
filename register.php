<?php 
define("navcheck",true);
require "nav.php" 
?>

<link rel="stylesheet" href="css/login.css">

<body>
    <div style="position:relative;height:30vh;" >
        <form action="includes/register.inc.php" method="POST" class="forma">
            <!-- csrf token-->
            <input type="hidden" name="csrf" value="<?php echo $csrf ?>">

            <div class="naslov">Register</div>
            <?php
                define("errorcheck", true);
                define("registration", true);
                require_once "includes/errors.php";
            ?>
            <input type="text" class="inputtext" name="uid" placeholder="Username">
            <input type="text" class="inputtext" name="mail" placeholder="E-mail">
            <input type="password" class="inputtext" name="pwd" placeholder="Password">
            <input type="password" class="inputtext" name="pwd-repeat" placeholder="Ponovite passwrod">
            <input type="submit" class="dugme" value="Register" name="singup-submit">
            <br>
            <input type="button" class="dugme" value="Login" onclick="location.href='login.php'">
        </form>
    </div>
</body>