<?php
session_start();

require_once "functions.php";

$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];

update_credentials($id, $email, $password);

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/security.php/?id=$id");
exit();