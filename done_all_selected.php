<?php
    session_start();
    $Account = $_SESSION['Account'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";
    $error = 0;

    $conn = new mysqli($servername, $username, $password, $dbname);
    $selected = $_POST["checkbox_done"];
    foreach($selected as $uid)
    {
        $sql = "SELECT status FROM orders WHERE orders.OID = '$uid'";
        $result = $conn->query($sql);
        $row = $result->fetch_all();
        $status = $row[0][0];
        // echo "<script>alert('$status') ; </script>";
        
        if($status!="Not Finish")
        {
            $error=1;
            continue;
            
        }
        else
        {
            $meg = "Finished";
            date_default_timezone_set('Asia/Taipei');
            $time = date('Y-m-d H:i:s', time());
            $sql = "UPDATE orders SET status = '$meg',end = '$time' WHERE orders.OID ='$uid'";
            $conn->query($sql);

        }
    }
    if($error==1)
    {
        $messege = "some done fail";
            echo "<script>alert('$messege') ; </script>";
          
    }
    echo "<script>document.location.href='nav.php';</script>";
    $conn->close();
?>