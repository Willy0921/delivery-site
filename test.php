<?php
session_start();
 $Account = $_SESSION['Account'];
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbname = "hw2";
$uid = 45;
 $conn = new mysqli($servername, $username, $password, $dbname);

 $sql = "SELECT status FROM orders WHERE orders.OID = '$uid'";
 $result = $conn->query($sql);
 $row = $result->fetch_all();
 echo $row[0][0];
?>