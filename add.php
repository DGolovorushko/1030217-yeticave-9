<?php

require_once "login_check.php";
include_once "helpers.php";

if (isset($_SESSION["is_auth"])) {
    $is_auth = $_SESSION["is_auth"];
    $user_name = $_SESSION["user_name"];
}

if (!$is_auth) {
    header("HTTP/1.1 403 Доступно только зарегистрированным пользователям");
    exit();
}

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
    'message' => '',
    'lot-rate' => '',
    'lot-step' => '',
    'lot-date' => ''
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['lot-img'])) {

        $file_type = "";

        if ($_FILES['lot-img']['type'] == "image/jpeg") {
            $file_type = ".jpg";
        } elseif ($_FILES['lot-img']['type'] == "image/png") {
            $file_type = ".png";
        } else {
            $errors['lot-img'] = 'Выберите файл png или jpg';
        }

        if ($file_type != "") {
            $file_name = uniqid();
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name . $file_type;
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name . $file_type);
        }

    } else {
        $errors['lot-img'] = 'Выберите файл';
    }

    if (isset($_POST)) {
        if (count($_POST)) {
            //+валидация полей

            // наименование лота
            if (!empty($_POST['lot-name'])) {
                $fields['lot-name'] = mysqli_real_escape_string($con, $_POST['lot-name']);
            } else {
                $errors['lot-name'] = 'Введите наименование лота';
            }

            // категория
            $id_category = 0;
            if (!empty($_POST['category'])) {
                $fields['category'] = mysqli_real_escape_string($con, $_POST['category']);
                if ($fields['category'] == 'Выберите категорию') {
                    $errors['category'] = 'Выберите категорию';
                } else {
                    $sql = "SELECT * FROM categories WHERE name='".mysqli_real_escape_string($con, $fields['category'])."';";
                    $result = mysqli_query($con, $sql);
                    if ($result){
                        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        if (count($categories)) {
                            $id_category = $categories[0]['id_category'];
                        } else {
                            $errors['category'] = 'Выберите категорию из списка';
                        }
                    } else {
                        $errors['category'] = 'Выберите категорию из списка';
                    }
                }
            } else {
                $errors['category'] = 'Выберите категорию';
            }

            // описание
            if (!empty($_POST['message'])) {
                $fields['message'] = mysqli_real_escape_string($con, $fields['message']);
            } else {
                $errors['message'] = 'Напишите описание лота';
            }

            // начальная цена
            if (!empty($_POST['lot-rate'])) {
                $fields['lot-rate'] = mysqli_real_escape_string($con, $fields['lot-rate']);
                if (filter_var($fields['lot-rate'], FILTER_VALIDATE_INT) == FALSE) {
                    $errors['lot-rate'] = 'Начальная цена должна быть числом';
                } else {
                    if ($fields['lot-rate'] == 0){
                        $errors['lot-rate'] = 'Начальная цена должна быть больше нуля';
                    }
                }
            } else {
                $errors['lot-rate'] = 'Введите начальную цену';
            }

            // шаг ставки
            if (!empty($_POST['lot-step'])) {
                $fields['lot-step'] = mysqli_real_escape_string($con, $fields['lot-step']);
                if (filter_var($fields['lot-step'], FILTER_VALIDATE_INT) == FALSE) {
                    $errors['lot-step'] = 'Шаг ставки должен быть числом';
                } else {
                    if ($fields['lot-step'] == 0){
                        $errors['lot-step'] = 'Шаг ставки должен быть больше нуля';
                    }
                }
            } else {
                $errors['lot-step'] = 'Введите шаг ставки';
            }

            // дата завершения торгов
            if (!empty($_POST['lot-date'])) {
                $fields['lot-date'] = mysqli_real_escape_string($con, $fields['lot-date']);
                $date = date_create($fields['lot-date']);
                if ($date == NULL){
                    $errors['lot-date'] = 'Неправильный формат даты';
                } else {
                    $tomorrow = date_create("tomorrow");
                    if($date < $tomorrow){
                        $errors['lot-date'] = 'Дата не может быть меньше завтрашнего дня';
                    }
                }
            } else {
                $errors['lot-date'] = 'Введите дату завершения торгов';
            }

            //-валидация полей

            // добавление лота
            if (!count($errors) && !empty($file_url)) {

                $sql = "INSERT INTO items(start_date, description, image, price, finish_date, step, id_author, id_category, title)
                                VALUES (".$fields['lot-date'].",'".$fields['message']."','".$file_url."','".$fields['lot-rate']."','".$fields['lot-date']."','".$fields['lot-step']."',1,".$id_category.",'".$fields['lot-name']."')";
                $result = mysqli_query($con, $sql);
                if ($result){

                    $id_item = mysqli_insert_id($con);
                    toItem($id_item);

                } else {
                    unlink($file_path . $file_name);
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