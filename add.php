<?php

include_once "helpers.php";

$is_auth = rand(0, 1);
$user_name = 'Dmitry'; // укажите здесь ваше имя
$categories = [];
$file_url = '';

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if (!$con) {
    to404();
}
$required_fields = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date'];
$fields = [
    'lot-name' => '',
    'category' => 'Выберите категорию',
    'message' => '',  //opisanie
    'lot-rate' => '', //nachalnaya tsena
    'lot-step' => '', //shag stavki
    'lot-date' => '' //data zaversheniya torgov
];
$errors = [];


print_r($_FILES);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['lot-img'])) {
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = $file_path . $file_name;

        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
    } else {
        $errors['lot-img'] = 'Выберите файл';
    }

    if (isset($_POST)) {
        if (count($_POST)) {
            //+валидация полей

            // наименование лота
            if (!empty($_POST['lot-name'])) {
                $fields['lot-name'] = $_POST['lot-name'];
            } else {
                $errors['lot-name'] = 'Введите наименование лота';
            }

            // категория
            if (!empty($_POST['category'])) {
                $fields['category'] = $_POST['category'];
                if ($fields['category'] == 'Выберите категорию') {
                    $errors['category'] = 'Выберите категорию';
                }
            } else {
                $errors['category'] = 'Выберите категорию';
            }

            // описание
            if (!empty($_POST['message'])) {
                $fields['message'] = $_POST['message'];
            } else {
                $errors['message'] = 'Напишите описание лота';
            }

            // начальная цена
            if (!empty($_POST['lot-rate'])) {
                $fields['lot-rate'] = $_POST['lot-rate'];
                if (filter_var($fields['lot-rate'], FILTER_VALIDATE_INT) == FALSE) {
                    $errors['lot-rate'] = 'Начальная цена должна быть числом';
                }
                else{
                    if ($fields['lot-rate'] == 0){
                        $errors['lot-rate'] = 'Начальная цена должна быть больше нуля';
                    }
                }
            } else {
                $errors['lot-rate'] = 'Введите начальную цену';
            }

            // шаг ставки
            if (!empty($_POST['lot-step'])) {
                $fields['lot-step'] = $_POST['lot-step'];
                if (filter_var($fields['lot-step'], FILTER_VALIDATE_INT) == FALSE) {
                    $errors['lot-step'] = 'Шаг ставки должен быть числом';
                }
                else{
                    if ($fields['lot-step'] == 0){
                        $errors['lot-step'] = 'Шаг ставки должен быть больше нуля';
                    }
                }
            } else {
                $errors['lot-step'] = 'Введите шаг ставки';
            }

            // дата завершения торгов
            if (!empty($_POST['lot-date'])) {
                $fields['lot-date'] = $_POST['lot-date'];
                $date = date_create($fields['lot-date']);
                if ($date == NULL){
                    $errors['lot-date'] = 'Неправильный формат даты';
                }
            } else {
                $errors['lot-date'] = 'Введите дату завершения торгов';
            }

            //-валидация полей

            // добавление лота
            if (!count($errors) && !empty($file_url)) {
                $sql = "INSERT INTO items(start_date, description, image, price, finish_date, step, id_author, id_category, title)
                                VALUES (".$fields['lot-date'].",".$fields['message'].",".$file_url.",".$fields['lot-rate'].",".$fields['lot-date'].",".$fields['lot-step'].",1,".id_category.",".$fields['lot-name'].")";
                $result = mysqli_query($con, $sql);
                if ($result){
                    $result = mysqli_insert_id($result);
                    toItem($result);
                }
            }
        }
    }
}

// Получение списка категорий
$sql = "SELECT * FROM categories;";
$result = mysqli_query($con, $sql);
if ($result){
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$data_add = [
    'categories' => $categories,
    'fields' => $fields,
    'errors' => $errors
];

$add = include_template("add.php", $data_add);

$data_layout = [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => "Добавление лота",
    'tmplt_main' => $add
];

$page = include_template("layout.php", $data_layout);

print($page);

?>