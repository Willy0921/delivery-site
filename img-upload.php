<?php
  //開啟圖片檔
  $file = fopen($_FILES["upfile"]["tmp_name"], "rb");
  // 讀入圖片檔資料
  $fileContents = fread($file, filesize($_FILES["upfile"]["tmp_name"])); 
  //關閉圖片檔
  fclose($file);
  //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
   $fileContents = base64_encode($fileContents);
  
  //連結MySQL Server
    session_start();
    $Account = $_SESSION['Account'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";
    $product_name = $_REQUEST["product_name"];
    $price = $_REQUEST["price"];
    $inventory = $_REQUEST["inventory"];
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql_sid="SELECT SID 
              FROM shops as s
              WHERE s.UID=(
                SELECT u.UID 
                FROM users as u
                WHERE account='$Account')";
    $result = $conn->query($sql_sid);
    $row = $result->fetch_assoc();
    $sid = $row['SID'];
  //組合查詢字串
    $img_type=$_FILES["upfile"]["type"];
    $stmt = $conn->prepare("INSERT INTO products (SID, product_name, price, inventory, image, img_type) VALUES (?, ?, ?, ?, '$fileContents', ?)");
    $stmt->bind_param('isiii', $sid, $product_name, $price, $inventory, $img_type);
    $stmt->execute();
    $stmt->get_result();
    echo"<script>alert('商品添加成功');parent.location.href='nav.php'; </script>";
    $conn->close();
