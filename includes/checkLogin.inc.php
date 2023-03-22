<?php

if (!defined('logincheck')) {
    exit('...');
} else if (empty($_SESSION['userid'])) {
    define("servcheck", true);
    require_once "serverinfo.inc.php";

    try {
        
        $conn = new PDO("mysql:host=$server;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->query("use $dBName");
        $conn->beginTransaction();
        $stmt = $conn->prepare("SELECT * FROM `logintokens` WHERE `userid`=:userid AND `token`=:token AND `ipadress`=:ipadress");
        if (isset($_COOKIE["loginToken"])) {
            $logtoken = explode("|", $_COOKIE["loginToken"]);
            $token = htmlspecialchars(strip_tags($logtoken[0]));
            $ipadress = htmlspecialchars(strip_tags($logtoken[1]));
            $userid = htmlspecialchars(strip_tags($logtoken[2]));
        }
        $ipadress2=$_SERVER['REMOTE_ADDR'];;
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':ipadress', $ipadress2);
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        $conn->commit();
        $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($stmt->fetchAll() as $k => $row) {

            $today = date("Y-m-d");
            $expireAr = explode(" ", $row['DateCreated']);
            $time = $expireAr[0];
            
            $todayar = explode('-',$today);
            $expirear = explode("-",$time);
            
            if ((int)$todayar[1]>((int)$expirear[1]+1)) {
                $conn->beginTransaction();
                $stmt = $conn->prepare("DELETE FROM `logintokens` WHERE `userid`=:userid AND `token`=:token AND `ipadress`=:ipadress");
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':ipadress', $ipadress);
                $stmt->bindParam(':userid', $userid);
                $stmt->execute();
                $conn->commit();
            } else {
                $conn->beginTransaction();
                $stmt = $conn->prepare("SELECT * FROM `korisnik` WHERE `id`=:userid2");
                $stmt->bindParam(':userid2', $userid);
                $stmt->execute();
                $conn->commit();
                $res2 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach ($stmt->fetchAll() as $k2 => $row2) {
                    $_SESSION['userid'] = $row2['id'];
                    $_SESSION['username'] = $row2['username'];
                    $_SESSION['email'] = $row2['email'];
                }
            }
        }
    } catch (PDOException $error) {
        echo "<div style='color:white'>";
        echo $error;
        echo "</div>";
    }
}
