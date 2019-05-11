<?php

include_once "helpers.php";

$id_item = 0;

if(isset($_GET['id_item']) && $_GET['id_item']!=0){
    $id_item = $_GET['id_item'];
    settype($id_item, 'integer');
}
else{
    to404();
}

$is_auth = rand(0, 1);
$user_name = 'Dmitry'; // укажите здесь ваше имя
$categories = [];
$bets = [];
$item = [];

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if ($con){
    // Получение списка категорий
    $sql = "SELECT * FROM categories;";
    $result = mysqli_query($con, $sql);
    if ($result){
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Получение информации о лоте
    $sql = "SELECT items.*, categories.name AS category, MAX(bets.bet_sum) AS last_price
            FROM items
                LEFT JOIN categories
                    ON items.id_category = categories.id_category
                LEFT JOIN bets
                    ON items.id_item = bets.id_item
            WHERE items.id_item = ".$id_item.";";
    $result = mysqli_query($con, $sql);
    if ($result){
        $item = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
        if($item['id_item']==NULL){
            to404();
        }
        if($item['last_price']==NULL OR $item['last_price']==0){
            $item['last_price'] = $item['price'];
        }
        $format = "Y-m-d H:i:s";
        $dateobj = DateTime::createFromFormat($format, $item['finish_date']);
        $item['finish_date'] = $dateobj;
    }
    else{
        to404();
    }

    // Получение информации о ставках
    $sql = "SELECT bet_date, bet_sum, users.name FROM bets
                LEFT JOIN users
                        ON bets.id_user = users.id_user
            WHERE bets.id_item = ".$id_item."
            ORDER BY bets.bet_date DESC;";
    $result = mysqli_query($con, $sql);
    if ($result){
        $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        to404();
    }
}
else{
    to404();
}

$data_lot = [
    'categories' => $categories,
    'item' => $item,
    'bets' => $bets
];

$lot = include_template("lot.php", $data_lot);

$data_layout = [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => $item['title'],
    'tmplt_main' => $lot
];
$page = include_template("layout.php", $data_layout);

print($page);

?>
