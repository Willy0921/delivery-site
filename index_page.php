
 <?php
    error_reporting(E_ERROR | E_PARSE);
    // // take data from previous page
    session_start();

    $Account  = $_REQUEST['Account'];
    $Password  = $_REQUEST['Password'];
    $Password = hash('sha256', $Password);

    if (!ctype_alnum($Account) || !ctype_alnum($Password)) {
        $messege = "login fail";

        echo "<script>alert('$messege') ; window.location.href = 'index.php'</script>";
    } else {
        //echo "<script type='text/javascript'>alert('$Password');</script>";
        // take data from database
        //connect
        $_SESSION['Account'] = $Account;
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hw2";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "SELECT account FROM users  WHERE account = '$Account' AND password = '$Password'";
        $result = $conn->query($sql);
        $row = $result->fetch_all();

        //href or not
        $conn->close();
        if ($row == NULL) {
            $messege = "login fail";
            unset($_SESSION["Account"]);
            // echo "<script type='text/javascript'>alert('$messege');</script>";
            echo "<script>alert('$messege') ; window.location.href = 'index.php'</script>";
            // echo "null";
            // exit();
        } else {
            // echo "got it";
            header('Location: nav.php');
            // exit();
        }
    }
    ?>