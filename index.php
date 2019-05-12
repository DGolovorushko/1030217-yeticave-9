<?php

include_once "helpers.php";

$is_auth = rand(0, 1);
$user_name = 'Dmitry'; // укажите здесь ваше имя
$categories = [];
$announcements = [];
$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if ($con){
    // Получение списка категорий
    $sql = "SELECT * FROM categories;";
    $result = mysqli_query($con, $sql);
    if ($result){
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Получение списка лотов
    $sql = "SELECT items.id_item, items.title AS name, items.price AS first_price, items.image AS url, categories.name AS category, items.finish_date, MAX(bets.bet_sum) AS price
            FROM items
                     LEFT JOIN categories
                               ON items.id_category = categories.id_category
                     LEFT JOIN bets
                               ON items.id_item = bets.id_item
            WHERE items.id_winner IS NULL
            GROUP BY items.id_item
            ORDER BY items.start_date DESC;";
    $result = mysqli_query($con, $sql);
    if ($result){
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($items as &$item){
            $format = "Y-m-d H:i:s";
            $dateobj = DateTime::createFromFormat($format, $item['finish_date']);
            $item['finish_date'] = $dateobj;
            if ($item['price']==0){
                $item['price'] = $item['first_price'];
            }
        }
    }
}

$data_index = [
    'categories' => $categories,
    'items' => $items
];

$index = include_template("index.php", $data_index);

$data_layout = [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => "Главная",
    'tmplt_main' => $index
];

$page = include_template("layout.php", $data_layout);

print($page);

?>
