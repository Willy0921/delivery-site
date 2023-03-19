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

$shop_name  = $_REQUEST['shop_name'];
$shop_category  = $_REQUEST['shop_category'];
$shop_latitude = $_REQUEST['shop_latitude'];
$shop_longtitude = $_REQUEST['shop_longtitude'];
$sign_up_suc = 1;
session_start();

//check empty
$_SESSION["sign_up_suc"] = 1;
$form = array(
    'shop_name' => $shop_name,
    'shop_category' => $shop_category,
    'shop_latitude' => $shop_latitude,
    "shop_longtitude" =>  $shop_category
);

foreach ($form as $element) {
    if ($element == NULL) {
        $sign_up_suc = 0;

        $_SESSION['alert_mes'] = "empty";
        break;
    }
}
// if(!is_numeric($shop_latitude)||!is_numeric($shop_longtitude))
// {
//     $sign_up_suc = 0;
//     $_SESSION["sign_up_suc"]=0;
//     $_SESSION['alert_mes'] = "wrong format";
// }
if ($shop_latitude < -90 || $shop_latitude > 90 || $shop_longtitude < -180 || $shop_longtitude > 180) {
    if ($sign_up_suc == 1) {
        $sign_up_suc = 0;

        $_SESSION['alert_mes'] = "wrong format";
    }
}
$Account = $_SESSION['Account'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hw2";
$conn = new mysqli($servername, $username, $password, $dbname);
$stmt  = $conn->prepare("SELECT shop_name  FROM shops  WHERE shop_name =?");
$stmt->bind_param('s', $shop_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row != NULL && $sign_up_suc == 1) {
    $sign_up_suc = 0;

    $_SESSION['alert_mes'] = "shop name duplication";
}

if ($sign_up_suc == 1) {
    $stmt  = $conn->prepare("INSERT INTO shops (SID,UID,shop_name,location,phone_number,category) VALUES (NULL,?,?,ST_GeomFromText('POINT($shop_longtitude $shop_latitude)'),?,?)");
    // $stmt->bind_param('isis',$_SESSION['Account'], $shop_name,$_SESSION['phone_number'],$shop_category);
    $stmt->bind_param('isss', $_SESSION['UID'], $shop_name, $_SESSION['phone_number'], $shop_category);
    $stmt->execute();
    $row = $result->fetch_assoc();
    $sql = "UPDATE users SET identity = 'shop_owner'
        WHERE account = '$Account'";
    $result = $conn->query($sql);
}
$_SESSION["sign_up_suc"] = $sign_up_suc;
$conn->close();
header('Location: nav.php#menu1');
exit();
?>