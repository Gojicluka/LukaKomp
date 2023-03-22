<?php
/*
define("servcheck", true);
require_once "includes/serverinfo.inc.php";

$conn = new PDO("mysql:host=$server;", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
$conn->query("use $dBName");
$conn->beginTransaction();
$stmt = $conn->prepare("select * from racun_medjutabela where `tip`='graficke' AND `idracun`=1");
$stmt->execute();
$conn->commit();
$res = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$brojac = 0;
foreach ($stmt->fetchAll() as $k => $row) {
    $conn->beginTransaction();
    $stmt = $conn->prepare("select (racun_medjutabela.kolicina*graficke.cena) as cena1, graficke.Ime as ime from racun_medjutabela
    inner join graficke on graficke.id = idDrugeTabele
    where `tip`='graficke' AND `idracun`=1");
    $stmt->execute();
    $conn->commit();
    $res2 = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $niz2= $stmt->fetchAll();
    echo $niz2[1]['ime'];
    echo $niz2[1]['cena1'];
}


$conn = new PDO("mysql:host=$server;", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
$conn->query("use $dBName");
$conn->beginTransaction();
//$stmt = $conn->prepare("insert into racun (cena,korisnik_id)values(321312312,1)");
$stmt->execute();
echo $conn->lastInsertId();
$conn->commit();
echo "<br>";
$array  = [];
$array['tip'] = [];
//echo $array['tip'];
//array['tip'] = [];
array_push($array,array());
array_push($array['tip'],"vrednost");
var_dump($array);
*/

/*
session_start();
define("servcheck", true);
require_once "includes/serverinfo.inc.php";
try {
    //uspostavljanje konekcije
    $conn = new PDO("mysql:host=$server;", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    $conn->query("use $dBName");
    $conn->beginTransaction();

    $token = md5(time() . rand() . $_SESSION['username']);
    $ipadress = $_SERVER['REMOTE_ADDR'];
    $useridtoken = $_SESSION['userid'];
    setcookie("loginToken", $token . "|" . $ipadress . "|" . $useridtoken, time() + (86400 * 30), "/");
    $stmt = $conn->prepare("INSERT INTO `logintokens`(`userid`,`token`,`ipadress`) VALUES(:userid,:token,:ipadress)");

    $stmt->bindParam(':userid', $_SESSION['userid']);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':ipadress', $ipadress);
    $stmt->execute();
    $conn->commit();
} catch (Exception $ex) {
    echo $ex;
}
*/


