<?php
if (isset($_POST['distance'])) {
    search();
}
function search()
{
    $Account = $_SESSION['Account'];
    $Shop = $_REQUEST["Shop"];
    $Distance = $_REQUEST["distance"];
    $LowerPrice = $_REQUEST["LowerPrice"];
    $UpperPrice = $_REQUEST["UpperPrice"];
    $Meal = $_REQUEST["Meal"];
    $Category = $_REQUEST["category"];
    $Sort = $_REQUEST["sort"];
    $Order = $_REQUEST["sort_order"];

    if ($Sort == "Category") {
        $Sort = 'category';
    } elseif ($Sort == "Distance") {
        $Sort = 'distance';
    } else {
        $Sort = 'shop_name';
    }

    if ($Sort == 'distance') {
        if ($Order == 'Descending') {
            $Order = 'Ascending';
        } else {
            $Order = 'Descending';
        }
    }

    if ($Shop == NULL) {
        $Shop = "";
    }
    if ($Meal == NULL) {
        $Meal = "";
    }
    if ($Category == NULL) {
        $Category = "";
    }
    if ($LowerPrice == NULL) {
        $LowerPrice = 0;
    }
    if ($UpperPrice == NULL) {
        $UpperPrice = 999999999;
    }

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
    if ($Distance == "all") {
        $stmt = $conn->prepare("WITH shops_match_price
                                AS( 
                                    SELECT s.shop_name, s.category, s.location, p.product_name
                                    From shops as s natural join products as p
                                    WHERE p.price>= ? 
                                        and p.price<= ?
                                    )
                                SELECT shop_name, category, (select distance 
                                                            from shop_distance
                                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                                            ) as distance
                                FROM shops_match_price as s
                                WHERE (? = '' or shop_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or product_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or category LIKE CONCAT( '%',?,'%'))
                                GROUP BY shop_name
                                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                                        CASE WHEN 1 = 1 THEN $Sort END");
        $stmt->bind_param('iissssss', $LowerPrice, $UpperPrice, $Shop, $Shop, $Meal, $Meal, $Category, $Category);
        /*$sql = "WITH shops_match_price
                AS( 
                    SELECT s.shop_name, s.category, s.location, p.product_name
                    From shops as s natural join products as p
                    WHERE p.price>=$LowerPrice 
                        and p.price<=$UpperPrice
                    )
                SELECT shop_name, category, (select distance 
                                            from shop_distance
                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                            ) as distance
                FROM shops_match_price as s
                WHERE ('$Shop' = '' or shop_name LIKE '%$Shop%')
                    and ('$Meal' = '' or product_name LIKE '%$Meal%')
                    and ('$Category' = '' or category LIKE '%$Category%')
                GROUP BY shop_name
                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                        CASE WHEN 1 = 1 THEN $Sort END";*/
    } elseif ($Distance == "near") {
        /*$sql = "WITH shops_match_price
                AS( 
                    SELECT s.shop_name, s.category, s.location, p.product_name
                    From shops as s natural join products as p
                    WHERE p.price>=$LowerPrice 
                        and p.price<=$UpperPrice
                    )
                SELECT shop_name, category, (select distance 
                                            from shop_distance
                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                            ) as distance
                FROM shops_match_price as s
                WHERE ('$Shop' = '' or shop_name LIKE '%$Shop%')
                    and ('$Meal' = '' or product_name LIKE '%$Meal%')
                    and ('$Category' = '' or category LIKE '%$Category%')
                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) <= 100000
                GROUP BY shop_name
                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                        CASE WHEN 1 = 1 THEN $Sort END";*/
        $stmt = $conn->prepare("WITH shops_match_price
                                AS( 
                                    SELECT s.shop_name, s.category, s.location, p.product_name
                                    From shops as s natural join products as p
                                    WHERE p.price>= ? 
                                        and p.price<= ?
                                    )
                                SELECT shop_name, category, (select distance 
                                                            from shop_distance
                                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                                            ) as distance
                                FROM shops_match_price as s
                                WHERE (? = '' or shop_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or product_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or category LIKE CONCAT( '%',?,'%'))
                                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) <= 100000
                                GROUP BY shop_name
                                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                                        CASE WHEN '$Order' <> 'Descending' THEN $Sort END");
        $stmt->bind_param('iissssss', $LowerPrice, $UpperPrice, $Shop, $Shop, $Meal, $Meal, $Category, $Category);
    } elseif ($Distance == "medium") {
        /*$sql = "WITH shops_match_price
                AS( 
                    SELECT s.shop_name, s.category, s.location, p.product_name
                    From shops as s natural join products as p
                    WHERE p.price>=$LowerPrice 
                        and p.price<=$UpperPrice
                    )
                SELECT shop_name, category, (select distance 
                                            from shop_distance
                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                            ) as distance
                FROM shops_match_price as s
                WHERE ('$Shop' = '' or shop_name LIKE '%$Shop%')
                    and ('$Meal' = '' or product_name LIKE '%$Meal%')
                    and ('$Category' = '' or category LIKE '%$Category%')
                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) > 100000
                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) <= 200000
                GROUP BY shop_name
                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                        CASE WHEN 1 = 1 THEN $Sort END";*/
        $stmt = $conn->prepare("WITH shops_match_price
                                AS( 
                                    SELECT s.shop_name, s.category, s.location, p.product_name
                                    From shops as s natural join products as p
                                    WHERE p.price>= ? 
                                        and p.price<= ?
                                    )
                                SELECT shop_name, category, (select distance 
                                                            from shop_distance
                                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                                            ) as distance
                                FROM shops_match_price as s
                                WHERE (? = '' or shop_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or product_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or category LIKE CONCAT( '%',?,'%'))
                                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) > 100000
                                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) <= 200000
                                GROUP BY shop_name
                                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                                        CASE WHEN 1 = 1 THEN $Sort END");
        $stmt->bind_param('iissssss', $LowerPrice, $UpperPrice, $Shop, $Shop, $Meal, $Meal, $Category, $Category);
    } elseif ($Distance == "far") {
        /*$sql = "WITH shops_match_price
                AS( 
                    SELECT s.shop_name, s.category, s.location, p.product_name
                    From shops as s natural join products as p
                    WHERE p.price>=$LowerPrice 
                        and p.price<=$UpperPrice
                    )
                SELECT shop_name, category, (select distance 
                                            from shop_distance
                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                            ) as distance
                FROM shops_match_price as s
                WHERE ('$Shop' = '' or shop_name LIKE '%$Shop%')
                    and ('$Meal' = '' or product_name LIKE '%$Meal%')
                    and ('$Category' = '' or category LIKE '%$Category%')
                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) > 200000
                GROUP BY shop_name
                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                        CASE WHEN 1 = 1 THEN $Sort END";*/
        $stmt = $conn->prepare("WITH shops_match_price
                                AS( 
                                    SELECT s.shop_name, s.category, s.location, p.product_name
                                    From shops as s natural join products as p
                                    WHERE p.price>= ? 
                                        and p.price<= ?
                                    )
                                SELECT shop_name, category, (select distance 
                                                            from shop_distance
                                                            where ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) >= lower_bound 
                                                                and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) < upper_bound 
                                                            ) as distance
                                FROM shops_match_price as s
                                WHERE (? = '' or shop_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or product_name LIKE CONCAT( '%',?,'%'))
                                    and (? = '' or category LIKE CONCAT( '%',?,'%'))
                                    and ST_Distance_Sphere((SELECT u.location From users as u WHERE account='$Account'), s.location) > 200000
                                GROUP BY shop_name
                                ORDER BY CASE WHEN '$Order' = 'Descending' THEN $Sort END DESC,
                                        CASE WHEN 1 = 1 THEN $Sort END");
        $stmt->bind_param('iissssss', $LowerPrice, $UpperPrice, $Shop, $Shop, $Meal, $Meal, $Category, $Category);
    }
    $stmt->execute();
    //$result = $conn->query($sql);
    $result = $stmt->get_result();
    $num = 0;
    if ($result->num_rows > 0) {
        $array = array();
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $shop_name = $row["shop_name"];
            $category = $row["category"];
            $distance = $row["distance"];
            array_push($array, $shop_name);
            $shop_name2 = "#" . $shop_name;
            $num_ = $num + 1;
            $content = <<<EOF
            <tbody>
                <tr>
                    <th scope="row">$num_</th>
                    <td>$shop_name</td>
                    <td>$category</td>
                    <td>$distance</td>
                    <td> <button type="button" class="btn btn-info " data-toggle="modal" data-target=$shop_name2>Open
                            menu</button></td>
                </tr>
            </tbody>
            EOF;
            echo $content;
            $num += 1;
        }
    } else {
    }
    $table = <<<EOF
    </table>
    EOF;
    echo $table;

    if ($num > 0) {
        for ($i = 0; $i < $num; $i++) {
            $shop_name = $array[$i];
            $content = <<<EOF
            <div class="modal fade" id=$shop_name data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">menu</h4>
                    </div>
                    <form method="post" action="check_order.php">
                    <div class="modal-body">
                        <div class="row">
                            <div class="  col-xs-12">
                                <table class="table" style=" margin-top: 15px;">
                                    <thead>
                                        <tr>
                                            <th scope="col">Picture</th>
                                            <th scope="col">meal name</th>
                                            <th scope="col">price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            EOF;
            echo $content;

            $sql = "SELECT * 
                    FROM shops as s natural join products as p
                    WHERE shop_name = '$shop_name'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $product_num = 1;
                while ($row = $result->fetch_assoc()) {
                    $image = $row["image"];
                    $img_type = $row["img_type"];
                    $product_name = $row["product_name"];
                    $price = $row["price"];
                    $inventory = $row["inventory"];
                    $content = <<<EOF
                    <tr>
                        <td><img src="data:$img_type;base64, $image" width="100" heigh="60" alt=$product_name></td>
                        <td>$product_name</td>
                        <td>$price</td>
                        <td>$inventory </td>
                        <td><div class="addcount">
                        <input type="hidden" value="$img_type" name="order_img_type[]">
                        <input type="hidden" value="$image" name="order_image[]">
                        <input type="hidden" value="$price" name="order_price[]">
                        <input type="hidden" value="$product_name" name="order_product[]">
                        <a class="J_minus" href="javascript:;">-</a>
                        <input type="text" style="width:25px; height:20px;" class="J_input" value="0" name="order_num[]">
                        <a class="J_add" href="javascript:;">+</a>
                        </td>
                    </tr>
                    EOF;
                    echo $content;
                    $product_num += 1;
                }
            }
            $content = <<<EOF
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <label class="control-label col-sm-1" for="Action">Type</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="sel1" name="order_type">
                                <option>Delivery</option>
                                <option>Pick-up</option>
                            </select>
                        </div>
                        <br></br>
                        <button type="submit" class="btn btn-default" value="$shop_name" name="order_shop">Calculate the price</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
            EOF;
            echo $content;
        }
    }
    $conn->close();
}