<?php
    session_start();
    $Account = $_SESSION['Account'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";
    $error = 0;
    $conn = new mysqli($servername, $username, $password, $dbname);
    $selected = $_POST["checkbox_cancel"];
    foreach($selected as $oid)
    {
        $sql = "SELECT status FROM orders WHERE orders.OID = '$oid'";
        $result = $conn->query($sql);
        $row = $result->fetch_all();
        $status = $row[0][0];
        if($status!="Not Finish")
        {
            $error=1;
            continue;
            
            
        }
        else
        {
            $meg = "Cancel";
            date_default_timezone_set('Asia/Taipei');
            $time = date('Y-m-d H:i:s', time());

            $sql = "UPDATE orders SET status = '$meg', end = '$time' WHERE orders.OID = '$oid'";
            $conn->query($sql);
            

            $sql = "UPDATE users SET wallet_balance = wallet_balance+(
                    SELECT total_price FROM orders as o 
                    WHERE o.OID = '$oid')
                    WHERE UID = (
                    SELECT UID FROM orders 
                    WHERE OID = '$oid')";
            $conn->query($sql);
            $sql = "UPDATE users SET wallet_balance = wallet_balance - (
                        SELECT total_price FROM orders as o
                        WHERE o.OID = '$oid')
                        WHERE UID = (
                            SELECT UID FROM shops
                            WHERE shop_name = (
                                SELECT shop_name FROM orders
                                WHERE OID = '$oid'))";
            $conn->query($sql);
            
            $sql = "SELECT * FROM orders WHERE OID = '$oid'";
            $cancel_result = $conn->query($sql);
            $cancel_row = $cancel_result->fetch_assoc();
            
            $total_price = $cancel_row["total_price"];
            $shop = $cancel_row["shop_name"];
            $UID = $cancel_row["UID"];
            
            $amount_change = "+" . $total_price;
            $sql = "INSERT INTO transaction_record (TID, UID, action, trader, time, amount_change)
                    VALUES (NULL, $UID, 'Receive', '$shop', '$time', '$amount_change')";
            $conn->query($sql);

            $sql = "SELECT UID FROM shops
                    WHERE shop_name = '$shop'";
            $shop_result = $conn->query($sql);
            $shop_row = $shop_result->fetch_assoc();
            $shop_owner_id = $shop_row["UID"];

            $amount_change = "-" . $total_price;
            $sql  = "INSERT INTO transaction_record (TID, UID, action, trader, time, amount_change)
                    VALUES (NULL, $shop_owner_id, 'Payment', '$Account', '$time', '$amount_change')";
            $conn->query($sql);
            
            $sql = "SELECT PID FROM order_product WHERE OID  =  '$oid'";
            $result = $conn->query($sql);
            $sql = "UPDATE orders SET status = '$meg', end = '$time' WHERE orders.OID = '$oid'";
            //$conn->query($sql);
            // echo "<script>alert('$result->num_rows')</script>";
            // sleep(5);
            if ($result->num_rows > 0) 
            {
                while ($rowtest = $result->fetch_assoc()) 
                {
                    $sub = $rowtest["PID"];
                    $sql = "UPDATE products as p SET p.inventory = p.inventory + (
                        SELECT amount FROM order_product 
                        WHERE OID = '$oid' and PID = p.PID)
                        WHERE p.PID = '$sub'";
                    $conn->query($sql);
                }
                
            }

        }
        
    }
    if($error==1)
    {
        $messege = "some cancel fail";
        echo "<script>alert('$messege') ; </script>";
    }
    echo "<script>document.location.href='nav.php';</script>";
    $conn->close();
?>