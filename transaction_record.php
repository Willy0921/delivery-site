<?php
session_start();
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
$sql = "SELECT * 
        FROM users as u natural join transaction_record as t
        WHERE u.account = '$Account'";
if (isset($_POST["transaction_action"])) {
    $action = $_POST["transaction_action"];
    if ($action == "All") {
        $sql = "SELECT * 
            FROM users as u natural join transaction_record as t
            WHERE u.account = '$Account'";
    } else {
        $sql = "SELECT * 
            FROM users as u natural join transaction_record as t
            WHERE u.account = '$Account'
                and t.action = '$action'";
    }
}
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $record_id = $row["TID"];
        $action = $row["action"];
        $time = $row["time"];
        $trader = $row["trader"];
        $amount_change = $row["amount_change"];
        $content = <<<EOF
            <tbody>
                <tr>
                    <th scope="row">$record_id</th>
                    <td>$action</td>
                    <td>$time</td>
                    <td>$trader</td>
                    <td>$amount_change</td>
                </tr>
            </tbody>
            EOF;
        echo $content;
    }
}
$conn->close();
