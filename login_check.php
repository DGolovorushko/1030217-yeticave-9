<?php
session_start();

$is_auth = 0;
$user_name = '';

if (isset($_SESSION["is_auth"])) {
    $is_auth = $_SESSION["is_auth"];
    $user_name = $_SESSION["user_name"];
}