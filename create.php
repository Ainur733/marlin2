<?php

session_start();


require_once "functions.php";

if (is_not_logged_in()) {
    redirect_to("page_login.php");
}

$email = $_POST['email'];
$password = $_POST['password'];

$name = $_POST['name'];
$work = $_POST['work'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$status = $_POST['status'];
$vk_link = $_POST['vk_link'];
$tg_link = $_POST['tg_link'];
$instagram_link = $_POST['instagram_link'];
$image = $_FILES['image'];





$user = get_user_by_email($email);

if (!empty($user)) {
    set_flash_message("danger", "Такой пользователь уже существует");
    redirect_to("/create_user.php");
    exit();
}

$user_id = add_user($email, $password);


edit_user_info($user_id, $name, $work, $phone, $location);

set_status($user_id, $status);

upload_avatar($user_id, $image);

add_social_links($user_id, $vk_link, $tg_link, $instagram_link);

set_flash_message("success", "Пользователь успешно добавлен");

redirect_to("users.php");













