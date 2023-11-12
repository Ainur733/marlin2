<?php
session_start();

require_once "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];



$user = get_user_by_email($email);

if (!empty($user)) {
    set_flash_message("danger", "Этот пользователь уже зарегистрирован");
    redirect_to("/page_register.php");
    exit();
}

add_user($email, $password);

set_flash_message("success", "Вы успешно зарегистрировалась");
redirect_to("/page_login.php");
