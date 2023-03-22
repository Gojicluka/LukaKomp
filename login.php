<?php 
define("navcheck",true);
require "nav.php"
 ?>

<link rel="stylesheet" href="css/login.css">

<body>
    <div style="position:relative;height:50vh;" >
        <form action="includes/login.inc.php" method="POST" class="forma">
            <!-- csrf token-->
            <input type="hidden" name="csrf" value="<?php echo $csrf ?>">
            
            <div class="naslov">Login</div>
            <?php
                define("errorcheck", true);
                define("login", true);
                require_once "includes/errors.php";
            ?>
            <input type="text" class="inputtext" name="uid" placeholder="Username">
            <input type="password"  class="inputtext" name="pwd" placeholder="Password">
            <input type="submit" class="dugme" value="Login" name="login-submit">
            <br>
            <input type="button" class="dugme" value="Register" onclick="location.href='register.php'">
        </form>
    </div>
</body>