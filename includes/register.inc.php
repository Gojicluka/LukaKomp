<?php

//da
session_start();
//$conn->query("use teambuilder");
if (isset($_POST['singup-submit'])) {
    if (hash_equals($_SESSION['csrf'], htmlspecialchars(strip_tags($_POST['csrf'])))) {
        define("servcheck", true);
        require "serverinfo.inc.php";
        try {
            //uspostavljanje konekcije
            $conn = new PDO("mysql:host=$server;", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //promenjive
            $greska = "";
            $username = htmlspecialchars(strip_tags($_POST['uid']));
            $email = htmlspecialchars(strip_tags($_POST['mail']));
            $pwd = htmlspecialchars(strip_tags($_POST['pwd']));
            $passwordrepeat = htmlspecialchars(strip_tags($_POST['pwd-repeat']));
            //Provera errora
            if (empty($username) || empty($email) || empty($pwd) || empty($passwordrepeat)) {
                header("Location: ../register.php?error=EmptyFields");
                exit();
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                header("Location: ../register.php?error=invalidmailoruser");
                exit();
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../register.php?error=invailidemail");
                exit();
            } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                header("Location: ../register.php?error=invaliduser");
                exit();
            } else if ($pwd !== $passwordrepeat) {
                header("Location: ../register.php?error=passworddoesnotmatch");
                exit();
            } else {
                //provera da li korisnik postoji ili da li mejl je u koriscenju trenutno
                $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
                $conn->query("use $dBName");
                $conn->beginTransaction();
                $stmt = $conn->prepare("call selectUsersBy(:username,:email)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $conn->commit();
                $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $brojac = 0;
                foreach ($stmt->fetchAll() as $k => $row) {
                    $brojac++;
                }
                if ($brojac > 0) {
                    header("Location: ../register.php?error=nameOrEmailAlreadyExists");
                } else {
                    //INSERT INTO users(uidUsers,emailUsers,pwdUsers,tipnaloga) VALUES (?,?,?,?)
                    $stmt = $conn->prepare("call registerUserProc(:username,:email2,:pwd)");
                    $stmt->bindParam(':username', $username);
                    $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
                    $stmt->bindParam(':email2', $email);
                    $stmt->bindParam(':pwd', $hashedpwd);
                    $stmt->execute();
                    $conn->commit();

                    //headers
                    header("Location: ../login.php?success=registration");
                    exit();
                }
            }
        } catch (PDOException $error) {
            echo $error;
            //header("Location: ../register.php?error=sqlerror");
            //exit();
        }
    } else {
        echo 'bad csrf token';
    }
} else {
    header("Location: ../register.php");
    exit();
}
