<?php

require_once "login_check.php";
include_once "helpers.php";

$categories = [];

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if (!$con) {
    to404();
}

$required_fields = ['email', 'password', 'name', 'message'];
$fields = [
    'email' => '',
    'password' => '',
    'name' => '',
    'message' => ''
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST)) {
        if (count($_POST)) {
            //+валидация полей

            // email
            if (!empty($_POST['email'])) {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $errors['email'] = 'Неправильный формат e-mail';
                } else {
                    $sql = "SELECT id_user FROM users WHERE email='" . mysqli_real_escape_string($con, $_POST['email']) . "'";
                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        $fields['email'] = $_POST['email'];
                    } else {
                        $errors['email'] = 'Такой e-mail уже занят';
                    }
                }
            } else {
                $errors['email'] = 'Введите e-mail';
            }

            // пароль
            if (!empty($_POST['password'])) {
                $fields['password'] = $_POST['password'];
            } else {
                $errors['password'] = 'Введите пароль';
            }

            // имя
            if (!empty($_POST['name'])) {
                $fields['name'] = $_POST['name'];
            } else {
                $errors['name'] = 'Введите имя';
            }

            // контактные данные
            if (!empty($_POST['message'])) {
                $fields['message'] = $_POST['message'];
            } else {
                $errors['message'] = 'Введите контактные данные';
            }

            //-валидация полей

            // добавление пользователя
            if (!count($errors)) {
                $sql = "INSERT INTO users(registration_date, name, email, password, contacts)
                                VALUES ('"
                                            . date('Y-m-d H:i:s')
                                            . "','" . mysqli_real_escape_string($con, $fields['name'])
                                            . "','" . mysqli_real_escape_string($con, $fields['email'])
                                            . "','" . password_hash(($fields['password']), PASSWORD_DEFAULT)
                                            . "','" . mysqli_real_escape_string($con, $fields['message'])
                                            . "')";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    toMain();
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

$data_sign_up = [
    'categories' => $categories,
    'fields' => $fields,
    'errors' => $errors
];

$sign_up = include_template("sign_up.php", $data_sign_up);

$data_layout = [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => "Добавление лота",
    'tmplt_main' => $sign_up
];

$page = include_template("layout.php", $data_layout);

print($page);
