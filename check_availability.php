<?php
# create database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hw2";
$connect = new mysqli($servername, $username, $password, $dbname);

if (!empty($_POST["account"])) {
  if ($_POST["state"] == "user") {
    if (!ctype_alnum($_POST["account"])) {
      echo "<span style='color:red'> Wrong format .</span>";
      echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {

      $query = "SELECT * FROM users WHERE account='" . $_POST["account"] . "'";
      $result = mysqli_query($connect, $query);
      $count = mysqli_num_rows($result);
      if ($count > 0) {
        echo "<span style='color:red'> Sorry User already exists .</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
      } else {
        echo "<span style='color:green'> User available for Registration .</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
      }
    }
  } else {
    $stmt  = $connect->prepare("SELECT * FROM shops WHERE shop_name=?");
    $stmt->bind_param('s', $_POST["account"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = mysqli_num_rows($result);

    if ($count > 0) {
      echo "<span style='color:red'> Sorry User already exists .</span>";
      echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
      echo "<span style='color:green'> User available for Registration .</span>";
      echo "<script>$('#submit').prop('disabled',false);</script>";
    }
  }
}
