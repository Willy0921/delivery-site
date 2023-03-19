<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Hello, world!</title>
</head>
<?php
session_start();
$_SESSION['login_fail'] = 0;
$Account  = $_REQUEST['Account'];
$Phonenumber  = $_REQUEST['Phonenumber'];
$Password  = $_REQUEST['Password'];
$RePassword = $_REQUEST["re-password"];
$Name = $_REQUEST["Name"];
$Longitude = $_REQUEST["Longitude"];
$Latitude = $_REQUEST["Latitude"];
$sign_up_suc = 1;

$_SESSION['alert_mes'] = "";
$form = array(
    'Account' => $Account,
    'Phonenumber' => $Phonenumber,
    'Password' => $Password,
    "re-password" =>  $RePassword,
    "Name" => $Name,
    "Longitude" =>  $Longitude,
    "Latitude" => $Latitude
);
if ($Password != $RePassword) {
    $sign_up_suc = 0;
    $_SESSION["sign_up_suc"] = 0;
    $_SESSION['alert_mes'] = "password!=repassword";
}
if (!is_numeric($Phonenumber) || strlen($Phonenumber) != 10 || !ctype_alnum($Name) || !ctype_alnum($Account) || !ctype_alnum($Password) || ($Latitude) < -90 || ($Latitude) > 90 || ($Longitude) < -180 || ($Longitude) > 180) {
    $sign_up_suc = 0;
    $_SESSION["sign_up_suc"] = 0;
    $_SESSION['alert_mes'] = "wrong format";
}
$Password = hash('sha256', $Password);
//check acount duplication
$_SESSION['Account'] = $Account;
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hw2";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql  = "SELECT account  FROM users  WHERE account = '$Account'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

foreach ($row as $element) {
}
if ($row != NULL) {
    $sign_up_suc = 0;
    $_SESSION["sign_up_suc"] = 0;
    $_SESSION['alert_mes'] = "Account duplication";
}

foreach ($form as $element) //check empty
{

    if ($element == NULL) {
        $sign_up_suc = 0;
        $_SESSION["sign_up_suc"] = 0;
        $_SESSION['alert_mes'] = "empty";

        break;
    }
}
//commit to db
$Latitude = floatval($Latitude);
$Longitude = floatval($Longitude);


$_SESSION["sign_up_suc"] = $sign_up_suc;
if ($sign_up_suc == 1) {
    $sql  = "INSERT INTO users (UID,account,password,name,identity,location,phone_number,wallet_balance) VALUES (NULL,'" . $Account . "', '" . $Password . "','" . $Name . "','user',ST_GeomFromText('POINT($Longitude $Latitude)'),'" . $Phonenumber . "',0)";
    $conn->query($sql);
    $conn->close();
    $_SESSION["sign_up_suc"] = 1;

    header('Location: index.php');
    exit();
} else {
    $_SESSION["sign_up_suc"] = 0;
    header('Location: sign-up.php');
    exit();
}


?>