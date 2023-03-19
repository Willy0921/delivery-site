<?php
$content=<<<EOF
<input type="submit" value ="cancel all selected"  style=" margin-left: 5px;" class="btn btn-info" data-toggle="modal" action = "cancel_all_selected.php" form = "cancel_select">

</button>

<input type="submit" value ="done all selected"  style=" margin-left: 5px;" class="btn btn-info" data-toggle="modal" action = "done_all_selected.php" form = "done_select">

</button>

EOF;
echo $content;



session_start();

$Account = $_SESSION['Account'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hw2";
// Create connection
$shop_action="";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * 
        FROM orders
        WHERE shop_name = 
        (SELECT shop_name
        FROM shops , users
        WHERE shops.UID = users.UID and users.account = '$Account')";

if (isset($_POST["shop_action"])) {
    $shop_action = $_POST["shop_action"];
    if ($shop_action == "All") {
        $sql = "SELECT * 
                FROM orders
                WHERE shop_name = 
                (SELECT shop_name
                FROM shops , users
                WHERE shops.UID = users.UID and users.account = '$Account')";
    } else {
        $sql = "SELECT * 
                FROM orders
                WHERE status = '$shop_action' and shop_name = 
                (SELECT shop_name
                FROM shops , users
                WHERE shops.UID = users.UID and users.account = '$Account')";
    }
}

header("Refresh:2");
$num = 0;
$result = $conn->query($sql);
// echo $result->num_rows ;
if ($result->num_rows > 0) {
    $array = array();
    while ($row = $result->fetch_assoc()) {
        $OID = $row["OID"];
        $OID2 = "#" . $OID;
        $status = $row["status"];
        $start = $row["start"];
        $end = $row["end"];
        $shop_name = $row["shop_name"];
        $total = $row["total_price"];
        array_push($array, $OID);
        if($status=="Not Finish")
        {
            $content = <<<EOF
            <tbody>
                <tr>
                    <th scope="row">$OID</th>
                    <td>$status</td>
                    <td>$start</td>
                    <td>$end</td>
                    <td>$shop_name</td>
                    <td>$total</td>
                    <td>
                    <button type="button" style=" margin-left: 5px;" class="btn btn-info" data-toggle="modal" data-target=$OID2>
                        order detail    
                    </button>
                    </td>
                    <td>
                    <form method="post">
                        <input type="submit" name="button2" class="btn btn-success" value="Done" />
                        <input type='hidden' name='OID' value='$OID' > 
                    </form>
                    </td>
                    <td>
                    <form action="cancel_all_selected.php" method="post" id="cancel_select"></form>
                    <form action="done_all_selected.php" method="post" id="done_select"></form>
                    <form method="post" >
                        <input type="submit" name="button1" class="btn btn-danger" value="Cancel" > 
                        <input type='hidden' name='OID' value='$OID'> 
                    </form>
                    </td>
                    <td>
                    <input type="checkbox" name="checkbox_cancel[]" value="$OID" form="cancel_select">
                    <p>cancel</p>
                    <input type="checkbox" name="checkbox_done[]" value="$OID" form="done_select">
                    <p>done</p>
                    </td>
                </tr>
            </tbody>
            EOF;
            echo $content;
        }
        else
        {
            $content = <<<EOF
            <tbody>
                <tr>
                    <th scope="row">$OID</th>
                    <td>$status</td>
                    <td>$start</td>
                    <td>$end</td>
                    <td>$shop_name</td>
                    <td>$total</td>
                    <td>
                    <button type="button" style=" margin-left: 5px;" class="btn btn-info" data-toggle="modal" data-target=$OID2>
                        order detail    
                    </button>
                    </td>
                   
                </tr>
            </tbody>
            EOF;
            echo $content;
        }
        $num += 1;
    }
}
$table = <<<EOF
</table>
EOF;
echo $table;
if ($num > 0) {
    for ($i = 0; $i < $num; $i++) {
        $OID = $array[$i];
        $content = <<<EOF
                    <div class="modal fade" id=$OID data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Order Detail</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="  col-xs-12">
                                            <table class="table" style=" margin-top: 15px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Picture</th>
                                                        <th scope="col">Meal name</th>
                                                        <th scope="col">Price</th>
                                                        <th scope="col">Order quantity</th>
                                                    </tr>
                                                </thead>            
        EOF;
        echo $content;
        $sql = "SELECT * 
                FROM order_product
                WHERE OID = '$OID'";
        $result2 = $conn->query($sql);
        
        $sql_delivery = "SELECT delivery_fee
                        FROM orders
                        WHERE OID = '$OID'";
        $delivery_result = $conn->query($sql_delivery);
        $row3 = $delivery_result->fetch_assoc();
        $delivery = $row3["delivery_fee"];

        if ($result2->num_rows > 0) {
            $subtotal = 0;
            while ($row2 = $result2->fetch_assoc()) {
                $image = $row2["image"];
                $img_type = $row2["img_type"];
                $product_name = $row2["product_name"];
                $price = $row2["product_price"];
                $quantity = $row2["amount"];
                $subtotal += $price * $quantity;
                $content = <<<EOF
                        <tr>
                            <td><img src="data:$img_type;base64, $image" width="100" heigh="60" alt=$product_name></td>
                            <td>$product_name</td>
                            <td>$price</td>
                            <td>$quantity </td>
                        </tr>
                EOF;
                echo $content;
            }
        }
        $total_price = $subtotal + $delivery;
        $content = <<<EOF
                                </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <td>Subtotal: $subtotal</td><br>
                        <td>Delivery fee: $delivery</td><br>
                        <td><b>Total price: $total_price</b></td>
                        </div>
                    </div>
                </div>
            </div>
        EOF;
        echo $content;
    }
}

if(array_key_exists('button1', $_POST)) {
    $uid = $_POST["OID"];
    button1($uid);
}
else if(array_key_exists('button2', $_POST)) {
    $uid = $_POST["OID"];
    button2($uid);
}
function button1($oid) {
    $Account = $_SESSION['Account'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT status FROM orders WHERE orders.OID = '$oid'";
    $result = $conn->query($sql);
    $row = $result->fetch_all();
    $status = $row[0][0];
    if($status!="Not Finish")
    {
        $messege = "cancel fail";
        echo "<script>alert('$messege') ; </script>";
        
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
    echo "<script>document.location.href='nav.php';</script>";
}
function button2($uid) {
    
    $Account = $_SESSION['Account'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hw2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT status FROM orders WHERE orders.OID = '$uid'";
    $result = $conn->query($sql);
    $row = $result->fetch_all();
    $status = $row[0][0];
    // echo "<script>alert('$status') ; </script>";
    
    if($status!="Not Finish")
    {
        $messege = "done fail";
        echo "<script>alert('$messege') ; </script>";
        
    }
    else
    {
        $meg = "Finished";
        date_default_timezone_set('Asia/Taipei');
        $time = date('Y-m-d H:i:s', time());
        $sql = "UPDATE orders SET status = '$meg',end = '$time' WHERE orders.OID ='$uid'";
        $conn->query($sql);

    }
    echo "<script>document.location.href='nav.php';</script>";
}

$conn->close();
