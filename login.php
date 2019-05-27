<?php

require_once "login_check.php";
include_once "helpers.php";

$categories = [];

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if (!$con) {
    to404();
}

$required_fields = ['email', 'password'];
$fields = [
    'email' => '',
    'password' => ''
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
                    $fields['email'] = $_POST['email'];
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

            // проверка существования пользователя с таким email
            if (!count($errors)) {
                $sql = "SELECT name, password FROM users
                        WHERE email = '" . mysqli_real_escape_string($con, $fields['email']) . "';";
                $result = mysqli_query($con, $sql);

                if ($result) {
                    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    if (count($user)) {
                        if (password_verify($fields['password'], $user[0]['password'])) {
                            // вход
                            $_SESSION["is_auth"] = 1;
                            $_SESSION["user_name"] = $user[0]['name'];
                            toMain();
                        } else {
                            $errors['login'] = 'Неверный логин или пароль';
                        }
                    } else {
                        $errors['login'] = 'Неверный логин или пароль';
                    }
                }
            }
            //-валидация полей
        }
    }
}

// Получение списка категорий
$sql = "SELECT * FROM categories;";
$result = mysqli_query($con, $sql);
if ($result){
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$data_login = [
    'categories' => $categories,
    'fields' => $fields,
    'errors' => $errors
];

$login = include_template("login.php", $data_login);

$data_layout = [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'title' => "Добавление лота",
    'tmplt_main' => $login
];

$page = include_template("layout.php", $data_layout);

print($page);
