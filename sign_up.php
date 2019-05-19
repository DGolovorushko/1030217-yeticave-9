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
                    $sql = "SELECT id_user FROM users WHERE email='" . mysqli_real_escape_string($_POST['email']) . "'";
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
                                            . "','" . mysqli_real_escape_string($fields['name'])
                                            . "','" . mysqli_real_escape_string($fields['email'])
                                            . "','" . md5(mysqli_real_escape_string($fields['password']))
                                            . "','" . mysqli_real_escape_string($fields['message'])
                                            . "')";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    toMain($id_item);
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
