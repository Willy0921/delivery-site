<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Order').modal('show');
    });
</script>
<?php
if (isset($_POST["order_num"]) && isset($_POST["order_product"]) && isset($_POST["order_type"]) && isset($_POST["order_shop"]) && isset($_POST["order_price"])) {
    session_start();
    $Account = $_SESSION['Account'];
    $order_shop = $_REQUEST["order_shop"];
    $order_img_type = $_REQUEST["order_img_type"];
    $order_image = $_REQUEST["order_image"];
    $order_price = $_REQUEST["order_price"];
    $order_product = $_REQUEST["order_product"];
    $order_num = $_REQUEST["order_num"];
    $order_type = $_REQUEST["order_type"];

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

    $sub_fee = 0;
    $empty_num = 0;

    for ($i = 0; $i < count($order_product); $i++) {

        $sql = "SELECT inventory, price
                FROM products
                WHERE product_name = '$order_product[$i]'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $val = intval($order_num[$i]);
                if (!($val == $order_num[$i] && $order_num[$i] >= 0 && is_numeric($order_num[$i]))) {
                    echo "<script>alert('輸入的訂購數量非正整數');parent.location.href='nav.php'; </script>";
                    break;
                } else if ($order_num[$i] == 0) {
                    $empty_num++;
                } else {
                    $sub_fee += $row["price"] * $order_num[$i];
                }
            }
        }
    }

    if ($empty_num == count($order_product)) {
        echo "<script>alert('訂單為空');parent.location.href='nav.php'; </script>";
    }

    $delivery_fee = 0;
    if ($order_type == "Delivery") {
        $sql = "SELECT (ST_Distance_Sphere((SELECT u.location FROM users as u WHERE account='$Account'), s.location)
                        ) as distance
                FROM shops as s
                WHERE shop_name = '$order_shop'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $delivery_fee = round($row["distance"] / 100);
        if ($delivery_fee < 10) {
            $delivery_fee = 10;
        }
    }

    $total_fee = $sub_fee + $delivery_fee;
}
$content = <<<EOF
<div class="modal fade" id="Order" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" onclick="javascript:location.href='nav.php'">&times;</button>
            <h4 class="modal-title">Order</h4>
        </div>
            <form action="place_order.php" method="post">
            <div class="modal-body">
                <div class="row">
                    <div class="  col-xs-12">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="col">Picture</th>
                                    <th scope="col">meal name</th>
                                    <th scope="col">price</th>
                                    <th scope="col">Order Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
EOF;
echo $content;
for ($i = 0; $i < count($order_product); $i++) {
    if ($order_num[$i] > 0) {
        $content = <<<EOF
        <tr>
            <td><img src="data:$order_img_type[$i];base64, $order_image[$i]" width="100" heigh="60" alt=$order_product[$i]></td>
            <td>$order_product[$i]</td>
            <td>$order_price[$i]</td>
            <td>$order_num[$i]</td>
            <input type="hidden" value="$order_product[$i]" name="exact_order_product[]">
            <input type="hidden" value="$order_price[$i]" name="exact_order_price[]">
            <input type="hidden" value="$order_num[$i]" name="exact_order_num[]">
            <input type="hidden" value="$order_img_type[$i]" name="exact_order_img_type[]">
            <input type="hidden" value="$order_image[$i]" name="exact_order_img[]">
        </tr>
        EOF;
        echo $content;
    }
}
$content = <<<EOF
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p><font size="1">Subtotal:    $$sub_fee</font></p>
                <p>Delivery fee:    $$delivery_fee</p>
                <p><font size="1">Total Price:    $$total_fee</font></p>
                <input type="hidden" value="$total_fee" name="total_fee">
                <input type="hidden" value="$delivery_fee" name="delivery_fee">
                <input type="hidden" value="$order_type" name="exact_order_type">
                <button type="submit" class="btn btn-default" value="$order_shop" name="exact_order_shop">Order</button>
            </div>
            </form>
    </div>
</div>
</div>
EOF;
echo $content;
