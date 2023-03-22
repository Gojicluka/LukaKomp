<?php
/*Promeni kod kada napravis databazu*/

session_start();
//$conn->query("use teambuilder");
if (isset($_POST['login-submit'])) {
    if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
        define("servcheck", true);
        require "serverinfo.inc.php";
        try {
            //uspostavljanje konekcije
            $conn = new PDO("mysql:host=$server;", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "konekcija uspesno uspostavljena";
            //promenjive
            $mailuid = htmlspecialchars(strip_tags($_POST['uid']));
            $pwd = htmlspecialchars(strip_tags($_POST['pwd']));
            //Provera errora
            if (isset($_SESSION['username'])) {
                header("Location: ../login.php?error=alreadyLoggedIn");
                exit();
            } else if (empty($mailuid) || empty($pwd)) {
                header("Location: ../login.php?error=EmptyFields");
                exit();
            } else {
                //provera da li korisnik postoji
                $conn->query("use $dBName");
                $mailuid = htmlspecialchars(strip_tags($mailuid));
                $pwd = htmlspecialchars(strip_tags($pwd));
                $conn->beginTransaction();
                $stmt = $conn->prepare("call loginProc(:username)");
                $stmt->bindParam(':username', $mailuid);
                $stmt->execute();
                $conn->commit();
                $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $brojac = 0;
                foreach ($stmt->fetchAll() as $k => $row) {
                    $pwdcheck = password_verify($pwd, $row['sifra']);
                    if ($pwdcheck == true) {
                        $_SESSION['userid'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['email'] = $row['email'];
                        

                        $token = md5(time() . rand() . $_SESSION['username']);
                        $ipadress = $_SERVER['REMOTE_ADDR'];
                        $useridtoken = $row['id'];
                        setcookie("loginToken", $token . "|" . $ipadress . "|" . $useridtoken, time() + (86400 * 30), "/");
                        $stmt = $conn->prepare("INSERT INTO logintokens(`userid`,`token`,`ipadress`) VALUES(:userid,:token,:ipadress)");
                        $stmt->bindParam(':userid', $useridtoken);
                        $stmt->bindParam(':token', $token);
                        $stmt->bindParam(':ipadress', $ipadress);
                        $stmt->execute();
                        echo "alooo";
                        $conn->commit();
                        //exiting
                        header("Location: ../index.php");
                        exit();
                    } else {
                        header("Location: ../login.php?error=pwdWrong");
                        exit();
                    }
                }
                
                header("Location: ../login.php?error=badUsername");
                exit();
            }
        } catch (PDOException $error) {
            echo $error;
            /*
            header("Location: ../login.php?error=databaseconnectionerror");
            exit();*/
        }
    } else {
        echo "bad csrf token";
    }
} else {
    header("Location: ../login.php");
    exit();
}
