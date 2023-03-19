<?php
if (isset($_POST["exact_order_shop"]) && isset($_POST["exact_order_price"]) && isset($_POST["exact_order_product"]) && isset($_POST["exact_order_num"]) && isset($_POST["total_fee"]) && isset($_POST["delivery_fee"]) && isset($_POST["exact_order_type"])) {

    session_start();
    $Account = $_SESSION['Account'];
    $shop = $_REQUEST["exact_order_shop"];
    $product = $_REQUEST["exact_order_product"];
    $num = $_REQUEST["exact_order_num"];
    $total_fee = $_REQUEST["total_fee"];
    $type = $_REQUEST["exact_order_type"];
    $price = $_REQUEST["exact_order_price"];
    $img_type = $_REQUEST["exact_order_img_type"];
    $image = $_REQUEST["exact_order_img"];
    $delivery_fee = $_REQUEST["delivery_fee"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $fail_product_list = array();
    $suceess_order = 1;
    for ($i = 0; $i < count($product); $i++) {

        $sql = "SELECT inventory, price
            FROM products
            WHERE product_name = '$product[$i]'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                if ($row["inventory"] < $num[$i]) {
                    array_push($fail_product_list, $product[$i]);
                    $suceess_order = 0;
                }
            }
        } else {
            echo "<script>alert('訂購失敗，餐點不存在');parent.location.href='nav.php'; </script>";
            $suceess_order = 0;
            break;
        }
    }

    if (count($fail_product_list) > 0) {
        $content = <<<EOF
        <script>alert('訂購失敗，以下商品訂購數量 > 店家庫存: 
        EOF;
        echo $content;
        echo '\r';
        for ($i = 0; $i < count($fail_product_list); $i++) {
            echo $fail_product_list[$i];
            echo '\r';
        }
        $content = <<<EOF
        ');parent.location.href='nav.php'; </script>";
        EOF;
        echo $content;
    }
    if ($suceess_order) {
        $sql = "SELECT wallet_balance
            FROM users
            WHERE account='$Account'";

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $wallet_balance = $row["wallet_balance"];
        if ($wallet_balance < $total_fee) {
            echo "<script>alert('訂購失敗，錢包餘額不足');parent.location.href='nav.php'; </script>";
        } else {

            $time = date('Y-m-d H:i:s', time());
            $sql  = "SELECT UID
                    FROM users
                    WHERE account = '$Account'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $UID = $row["UID"];

            $sql = "INSERT INTO orders (OID, UID, status, start, end, shop_name, total_price, type, delivery_fee)
                    VALUES (NULL, $UID, 'Not Finish', '$time', NULL, '$shop', $total_fee, '$type', $delivery_fee)";
            $conn->query($sql);

            $sql  = "SELECT OID
                FROM orders
                WHERE UID = $UID
                    and start = '$time'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $OID = $row["OID"];

            for ($i = 0; $i < count($product); $i++) {

                $sql = "SELECT PID
                    FROM products
                    WHERE product_name = '$product[$i]'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $PID = $row["PID"];

                $sql = "UPDATE products
                    SET inventory = inventory - $num[$i] 
                    WHERE PID = $PID";
                $conn->query($sql);

                $sql = "INSERT INTO order_product (OID, PID, amount, product_price, product_name, image, img_type)
                        VALUES ($OID, $PID, $num[$i], $price[$i], '$product[$i]', '$image[$i]', '$img_type[$i]')";
                $conn->query($sql);
            }

            $amount_change = "-" . $total_fee;
            $sql  = "INSERT INTO transaction_record (TID, UID, action, trader, time, amount_change)
                VALUES (NULL, $UID, 'Payment', '$shop', '$time', '$amount_change')";
            $conn->query($sql);

            $sql  = "SELECT UID
                FROM shops
                WHERE shop_name = '$shop'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $shop_owner_id = $row["UID"];

            $amount_change = "+" . $total_fee;
            $sql  = "INSERT INTO transaction_record (TID, UID, action, trader, time, amount_change)
                VALUES (NULL, $shop_owner_id, 'Receive', '$Account', '$time', '$amount_change')";
            $conn->query($sql);

            $sql = "UPDATE users
                SET wallet_balance = wallet_balance - $total_fee
                WHERE account = '$Account'";
            $conn->query($sql);

            $sql = "UPDATE users
            SET wallet_balance = wallet_balance + $total_fee
            WHERE UID = (SELECT UID FROM shops
            WHERE shop_name = '$shop')";
            $conn->query($sql);
            echo "<script>alert('訂購成功');parent.location.href='nav.php'; </script>";
        }
    }
}
