<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Hello, world!</title>
    <script>
        function checkForm(form) {
            if (form.product_name.value == "" || form.price.value == "" || form.inventory.value == "") {
                alert("欄位不得為空");
                return false;
            } else if (form.upfile.value == "") {
                alert("請選擇圖片");
                return false;
            }
            return true;
        }

        function checkForm_edit(form) {
            if (form.price.value == "" || form.inventory.value == "") {
                alert("欄位不得為空");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <?php
    error_reporting(E_ERROR | E_PARSE);
    session_start();
    if (isset($_SESSION["Account"]) == 0) {
        header('Location: index.php');
        exit();
    }
    if ($_SESSION["sign_up_suc"] == 0) {
        if ($_SESSION['alert_mes'] != "") {
            $_SESSION["sign_up_suc"] = 1;
            $mes  = $_SESSION['alert_mes'];
            echo "<script type='text/javascript'>alert('$mes');</script>";
        }
    } else {
        $_SESSION["sign_up_suc"] = 0;
        $messege = "shop register success";
        echo "<script type='text/javascript'>alert('$messege');</script>";
    }
    ?>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand " href="#">Food Delivery Service</a>
            </div>
        </div>
    </nav>
    <div class="container">

        <ul class="nav nav-tabs">
            <li class="active"><a href="#home">Home</a></li>
            <li><a href="#menu1">Shop</a></li>
            <li><a href="#my_order">My Order</a></li>
            <li><a href="#transaction_record">Transaction Record</a></li>
            <li><a href="#shop_order">Shop Order</a></li>
            <li><a href="index.php">Sign out</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>Profile</h3>
                <div class="row">
                    <div class="col-xs-10">
                        <?php
                        include("profile.php");
                        ?>
                    </div>
                </div>
                <h3>Search</h3>
                <div class=" row  col-xs-8">
                    <form class="form-horizontal" action="nav.php" method="post">
                        <div class="form-group">
                            <label class="control-label col-sm-1" for="Shop">Shop</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" placeholder="Enter Shop name" name="Shop">
                            </div>
                            <label class="control-label col-sm-1" for="distance">distance</label>
                            <div class="col-sm-5">


                                <select class="form-control" id="sel1" name="distance">
                                    <option>all</option>
                                    <option>near</option>
                                    <option>medium </option>
                                    <option>far</option>

                                </select>
                            </div>

                        </div>

                        <div class="form-group">

                            <label class="control-label col-sm-1" for="Price">Price</label>
                            <div class="col-sm-2">

                                <input type="text" class="form-control" name="LowerPrice">

                            </div>
                            <label class="control-label col-sm-1" for="~">~</label>
                            <div class="col-sm-2">

                                <input type="text" class="form-control" name="UpperPrice">

                            </div>
                            <label class="control-label col-sm-1" for="Meal">Meal</label>
                            <div class="col-sm-5">
                                <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal" name="Meal">
                                <datalist id="Meals">
                                    <?php
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
                                    $sql = "SELECT product_name FROM products";
                                    $result = $conn->query($sql);
                                    $num = 0;
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $product_name = $row["product_name"];
                                            $content = <<<EOF
                                        <option value="$product_name">
                                        EOF;
                                            echo $content;
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-1" for="category"> category</label>


                            <div class="col-sm-5">
                                <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category" name="category">
                                <datalist id="categorys">
                                    <?php
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
                                    $sql = "SELECT category FROM shops GROUP BY category";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $category = $row["category"];
                                            $content = <<<EOF
                                        <option value="$category">
                                        EOF;
                                            echo $content;
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>

                            <label class="control-label col-sm-1" for="sort">Sort</label>

                            <div class="col-sm-5">
                                <select class="form-control" id="sel1" name="sort">
                                    <option>Shop</option>
                                    <option>Category</option>
                                    <option>Distance</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-1" for="sort_order">Sort Order</label>

                            <div class="col-sm-5">
                                <select class="form-control" id="sel1" name="sort_order">
                                    <option>Ascending</option>
                                    <option>Descending</option>
                                </select>
                            </div>

                            <button type="submit" style="margin-left: 18px;" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
                <script type="text/javascript" src="jquery-1.11.1.min.js"></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.addcount').each(function() {
                            var _this = $(this);
                            var add = $(_this).find(".J_add"); //add number 
                            var reduce = $(_this).find(".J_minus"); //minus number
                            var num = 1; //initial number
                            var num_txt = $(_this).find(".J_input");
                            $(add).click(function() {
                                num = $(num_txt).val();
                                num++;
                                num_txt.val(num);
                            });
                            /*the method of decreasing number*/
                            $(reduce).click(function() {
                                //do minus 1 if num > 0 
                                num = $(num_txt).val();
                                if (num > 0) {
                                    if (num == 1) {
                                        num--;
                                        num_txt.val("0");
                                    } else {
                                        num--;
                                        num_txt.val(num);
                                    }
                                }
                            });
                        })
                    });
                </script>
                <div class="row">
                    <div class="  col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>

                                    <th scope="col">#</th>
                                    <th scope="col">shop name</th>
                                    <th scope="col">shop category</th>
                                    <th scope="col">Distance</th>

                                </tr>

                            </thead>
                            <?php
                            include("search.php");
                            include("order_check.php")
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div id="menu1" class="tab-pane fade">
                <h3> Start a business </h3>

                <?php
                $Account = $_SESSION['Account'];
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "hw2";
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT identity, UID FROM users WHERE account='$Account'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $uid = $row['UID'];
                if ($row['identity'] == "shop_owner") {
                    $sql_shop = "SELECT  shop_name, category, ST_X(location) as longitude,  ST_Y(location) as latitude FROM shops WHERE UID = $uid";
                    $result2 = $conn->query($sql_shop);
                    $row2 = $result2->fetch_assoc();
                    $shop_name = $row2['shop_name'];
                    $category = $row2['category'];
                    $latitude = $row2['latitude'];
                    $longitude = $row2['longitude'];
                    $reg = <<<EOF
                    <form action=shop_register.php method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="ex5">shop name</label>
                                <input class="form-control" id="ex5" placeholder="$shop_name" type="text" name="shop_name" oninput = "checkAccount()" readonly="true">
                                <span id="check"></span>
                            </div>
                            <div class="col-xs-2">
                                <label for="ex5">shop category</label>
                                <input class="form-control" id="ex5" placeholder="$category" type="text" name = "shop_category" readonly="true">
                            </div>
                            <div class="col-xs-2">
                                <label for="ex8">latitude</label>
                                <input class="form-control" id="ex8" placeholder=$latitude type="text" name = "shop_latitude" readonly="true">
                            </div>
                            <div class="col-xs-2">
                                <label for="ex6">longitude</label>
                                <input class="form-control" id="ex6" placeholder=$longitude type="text" name = "shop_longitude" readonly="true">
                            </div>
                        </div>
                    </div>
            

                    <div class=" row" style=" margin-top: 25px;">
                        <div class=" col-xs-3">
                        <input type="submit" value="register" class="btn btn-primary" disabled="disabled">
                        </div>  
                        </form>  
                    </div>
                EOF;
                    echo $reg;
                } else {
                    $reg = <<<EOF
                    <form action=shop_register.php method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="ex5">shop name</label>
                                <input class="form-control" id="ex5" placeholder="macdonald" type="text" name="shop_name" oninput = "checkAccount()">
                                <span id="check"></span>
                            </div>
                            <div class="col-xs-2">
                                <label for="ex5">shop category</label>
                                <input class="form-control" id="ex5" placeholder="fast_food" type="text" name = "shop_category">
                            </div>
                            <div class="col-xs-2">
                                <label for="ex6">latitude</label>
                                <input class="form-control" id="ex6" placeholder="24.78472733371133" type="text" name = "shop_latitude">
                            </div>
                            <div class="col-xs-2">
                                <label for="ex8">longitude</label>
                                <input class="form-control" id="ex8" placeholder="121.00028167648875" type="text" name = "shop_longtitude">
                            </div>
                        </div>
                    </div>
            

                    <div class=" row" style=" margin-top: 25px;">
                        <div class=" col-xs-3">
                        <input type="submit" value="register" class="btn btn-primary">
                        </div>  
                        </form>  
                    </div>
                    EOF;
                    echo $reg;
                }
                ?>
                <hr>
                <h3>ADD</h3>
                <?php

                ?>
                <div class="form-group ">
                    <form action="img-upload.php" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-xs-6">
                                <label for="ex3">meal name</label>
                                <input class="form-control" id="ex3" type="text" name="product_name">
                            </div>
                        </div>
                        <div class="row" style=" margin-top: 15px;">
                            <div class="col-xs-3">
                                <label for="ex7">price</label>
                                <input class="form-control" id="ex7" type="text" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" name="price">
                            </div>
                            <div class="col-xs-3">
                                <label for="ex4">quantity</label>
                                <input class="form-control" id="ex4" type="text" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" name="inventory">
                            </div>
                        </div>


                        <div class="row" style=" margin-top: 25px;">
                            <div class=" col-xs-3">
                                <label for="ex12">上傳圖片</label>
                                <Input Type="File" Name="upfile"><br>
                            </div>
                            <div class=" col-xs-3">
                                <button style=" margin-top: 15px;" type="Submit" class="btn btn-primary" onclick="return checkForm(this.form)">Add</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="  col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <?php
                            $Account = $_SESSION['Account'];
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "hw2";
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql_id = "SELECT UID FROM users WHERE account='$Account'";
                            $result = $conn->query($sql_id);
                            $row = $result->fetch_assoc();
                            $uid = $row['UID'];
                            if (isset($_POST['delete'])) {
                                $N = $_REQUEST['delete'];
                                $sql_delete = "DELETE FROM products WHERE product_name = '$N' AND SID = (SELECT SID FROM shops WHERE UID = '$uid')";
                                $conn->query($sql_delete);
                            } else if (isset($_POST['N'])) {
                                $N = $_REQUEST['N'];
                                $P = $_REQUEST['price'];
                                $I = $_REQUEST['inventory'];
                                $sql_edit = "UPDATE products SET price = '$P', inventory = '$I' WHERE product_name = '$N' AND SID = (SELECT SID FROM shops WHERE UID = '$uid')";
                                $conn->query($sql_edit);
                            }
                            $sql = "SELECT image, img_type, product_name, price, inventory FROM products 
                            WHERE SID=(SELECT SID FROM shops 
                            WHERE UID='$uid')";
                            $result = $conn->query($sql);
                            $conn->close();
                            $count = 1;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $Price = $row['price'];
                                    $Name = $row['product_name'];
                                    $Inventory = $row['inventory'];
                                    $Img = $row['image'];
                                    $Type = $row['img_type'];
                                    $Name2 = "#" . $Name;
                                    $menu = <<<EOF
                                    <tr>
                                        <th scope="row">$count</th>
                                        <td><img src="data:$Type;base64, $Img " width="100" heigh="60"></td>
                                        <td>$Name</td>
                                        <td>$Price</td>
                                        <td>$Inventory</td>
                                        <td><button type="button" class="btn btn-info" data-toggle="modal" data-target=$Name2>
                                                Edit
                                            </button></td>
                                        <!-- Modal -->
                                        <div class="modal fade" id=$Name data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">$Name Edit</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="nav.php#menu1" method="post">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <label for="ex71">price</label>
                                                                    <input class="form-control" id="ex71" type="text" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')"  value=$Price name="price">
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <label for="ex41">inventory</label>
                                                                    <input class="form-control" id="ex41" type="text" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,'')" value=$Inventory name="inventory">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="Submit" value="$Name" class="btn btn-secondary" name="N" onclick="return checkForm_edit(this.form)">Edit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post">
                                            <td><button type="Submit" Value="$Name" class="btn btn-danger" name="delete">Delete</button></td>
                                        </form>
                                    </tr>
                                    EOF;
                                    echo $menu;
                                    $count = $count + 1;
                                }
                            }

                            ?>
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Picture</th>
                                    <th scope="col">meal name</th>

                                    <th scope="col">price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <script type="text/javascript">
                window.onload = function() {
                    var theSelect = document.getElementsByName("transaction_action");
                    var theForm = document.getElementsByName("transaction_action_form");
                    theSelect[0].onchange = function() {
                        theForm[0].submit()
                    }
                }
            </script> -->
            <div id="transaction_record" class="tab-pane fade">
                <br></br>
                <label class="control-label col-sm-1" for="Action">Action</label>
                <div class="col-sm-3">
                    <form method="post" action="nav.php#transaction_record" name="transaction_action_form">
                        <select class="form-control" id="sel1" name="transaction_action">
                            <option>(Please select the action)</option>
                            <option>All</option>
                            <option>Payment</option>
                            <option>Receive</option>
                            <option>Recharge</option>
                        </select>
                    </form>
                </div>
                <div class="row">
                    <div class="  col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="row">Record ID</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Trader</th>
                                    <th scope="col">Amount change</th>
                                </tr>
                            </thead>
                            <?php
                            include("transaction_record.php");
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <script type="text/javascript">
                window.onload = function() {
                    var theSelect = document.getElementsByName("shop_action");
                    var theForm = document.getElementsByName("shop_order_form");
                    theSelect[0].onchange = function() {
                        theForm[0].submit()
                    }
                }
            </script> -->
            <div id="shop_order" class="tab-pane fade">
                <br></br>
                <label class="control-label col-sm-1" for="Status">Status</label>
                <div class="col-sm-3">
                    <form method="post" action="nav.php#shop_order" name="shop_order_form">
                        <select class="form-control" id="sel1" name="shop_action">
                            <option>(Please select the Status)</option>
                            <option>All</option>
                            <option>Finished</option>
                            <option>Not Finish</option>
                            <option>Cancel</option>
                        </select>
                    </form>
                </div>
                <div class="row">
                    <div class="  col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="row">Order ID</th>
                                    <th scope="col">Statues</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">End</th>
                                    <th scope="col">Shop name</th>
                                    <th scope="col">Total price</th>
                                    <th scope="col">Order detail</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <?php

                            include("shop_order.php");

                            ?>
                            <!--/table-->
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                window.onload = function() {
                    var theSelect = document.getElementsByName("my_action");
                    var theForm = document.getElementsByName("my_order_form");
                    theSelect[0].onchange = function() {
                        theForm[0].submit()
                    }
                    var theSelect1 = document.getElementsByName("shop_action");
                    var theForm1 = document.getElementsByName("shop_order_form");
                    theSelect1[0].onchange = function() {
                        theForm1[0].submit()
                    }
                    var theSelect2 = document.getElementsByName("transaction_action");
                    var theForm2 = document.getElementsByName("transaction_action_form");
                    theSelect2[0].onchange = function() {
                        theForm2[0].submit()
                    }
                }
            </script>
            <div id="my_order" class="tab-pane fade">
                <br></br>
                <label class="control-label col-sm-1" for="Action">Status</label>
                <div class="col-sm-3">
                    <form method="post" action="nav.php#my_order" name="my_order_form">
                        <select class="form-control" id="sel1" name="my_action">
                            <option>(Please select the Status)</option>
                            <option>All</option>
                            <option>Finished</option>
                            <option>Not Finish</option>
                            <option>Cancel</option>
                        </select>
                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <table class="table" style=" margin-top: 15px;">
                            <thead>
                                <tr>
                                    <th scope="row">Order ID</th>
                                    <th scope="col">Statues</th>
                                    <th scope="col">Start</th>
                                    <th scope="col">End</th>
                                    <th scope="col">Shop name</th>
                                    <th scope="col">Total price</th>
                                    <th scope="col">Order detail</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <?php


                            include("my_order.php");


                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
    <script type="text/javascript">
        console.log("test");

        function checkAccount() {

            jQuery.ajax({
                url: "check_availability.php",
                data: {
                    'account': $("#ex5").val(),
                    'state': 'shop'
                },

                type: "POST",
                success: function(data) {
                    console.log()
                    document.getElementById("check").innerHTML = data
                },
                error: function() {}
            });
        }
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>