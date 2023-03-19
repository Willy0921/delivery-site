<?php
session_start();
if ($_SESSION['Account'] != "") {
    $Account = $_SESSION['Account'];
} else {
    $Account = $_REQUEST['Account'];
}
$Account = $_SESSION['Account'];
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
$sql = "SELECT name, UID, account, identity, phone_number, wallet_balance, ST_X(location) as longitude, ST_Y(location) as latitude FROM users WHERE account = '$Account'";
$result = $conn->query($sql);
// output data
$row = $result->fetch_assoc();
$_SESSION["UID"] = $row["UID"]; //new
$_SESSION["phone_number"] = $row["phone_number"];
echo "Name: " . $row["name"] . ", Account: " . $row["account"] . ", " . $row["identity"] . ", PhoneNumber:" . $row["phone_number"] . ", location: " . $row["latitude"] . ", " . $row["longitude"];
$content = <<<EOF
<button type=" button " style=" margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Edit_location">Edit location</button>
<div class="modal fade" id="Edit_location" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit location</h4>
            </div>
            <form action="profile.php" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control" id="edit_latitude" placeholder="enter new latitude" name="new_latitude">
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="edit_longitude" placeholder="enter new longitude" name="new_longitude">
                </div>
                <div class="modal-footer">
                    <button type="Submit" class="btn btn-default">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
EOF;
echo $content;
echo "walletbalance: " . $row["wallet_balance"];
$content = <<< EOF
<!-- Modal -->
<button type=" button " style=" margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Recharge">Recharge</button>
<div class="modal fade" id="Recharge" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recharge</h4>
            </div>
            <form action="nav.php" method="post">
                <div class="modal-body">
                    <input type="text" class="form-control" id="Recharge" placeholder="enter recharge value" name="recharge_value">
                </div>
                <div class="modal-footer">
                    <button type="Submit" class="btn btn-default">Recharge</button>
                </div>
            </form>
        </div>
    </div>
</div>
EOF;
echo $content;
if (isset($_POST["recharge_value"])) {
    $recharge_value = $_REQUEST["recharge_value"];
    $val = intval($recharge_value);
    if ($val == $recharge_value && $recharge_value > 0) {
        $recharge_value = (int)$recharge_value;
        $stmt = $conn->prepare("UPDATE users 
                                SET wallet_balance = wallet_balance + ? 
                                WHERE account = '$Account'");
        $stmt->bind_param('i', $recharge_value);
        $stmt->execute();
        $amount_change = "+" . $recharge_value;
        date_default_timezone_set('Asia/Taipei');
        $time = date('Y-m-d H:i:s', time());
        $sql  = "SELECT UID
                FROM users
                WHERE account = '$Account'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $user_id = $row["UID"];
        $sql  = "INSERT INTO transaction_record (TID, UID, action, trader, time, amount_change)
                VALUES (NULL, $user_id, 'Recharge', '$Account', '$time', '$amount_change')";
        $conn->query($sql);
        echo "<script>alert('加值成功');parent.location.href='nav.php'; </script>";
    } else {
        echo "<script>alert('加值失敗，加值數值應為正整數');parent.location.href='nav.php'; </script>";
    }
}
if (isset($_POST["new_latitude"]) || isset($_POST["new_longitude"])) {
    $new_latitude = $_REQUEST["new_latitude"];
    $new_longitude = $_REQUEST["new_longitude"];
    if ($new_latitude <= 90 && $new_latitude >= -90 && is_numeric($new_latitude) && $new_longitude <= 180 &&  $new_longitude >= -180 && is_numeric($new_longitude)) {
        $sql  = "UPDATE users SET location = ST_GeomFromText('POINT($new_longitude $new_latitude)') WHERE account = '$Account'";
        $conn->query($sql);
        echo "<script>alert('更新位置成功');parent.location.href='nav.php'; </script>";
    } else {
        echo "<script>alert('更新位置失敗，格式錯誤或著欄位為空');parent.location.href='nav.php'; </script>";
    }
}
$conn->close();
