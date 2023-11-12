<?php
session_start();
require_once "functions.php";

$id = $_POST['id'];
$name = $_POST['name'];
$work = $_POST['work'];
$phone = $_POST['phone'];
$location = $_POST['location'];

 edit_user_info($id, $name, $work, $phone, $location);
set_flash_message("success", "Профиль успешно обновлен");
 redirect_to("/page_profile.php/?id=$id");
 exit();